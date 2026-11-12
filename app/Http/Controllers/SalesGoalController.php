<?php

namespace App\Http\Controllers;

use App\Models\CompanyGoal;
use App\Models\MarketingPlan;
use App\Models\Rep;
use App\Models\SalesGoal;
use App\Services\ForecastService;
use App\Support\TargetMath;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SalesGoalController extends Controller
{
    public function __construct(private ForecastService $forecast)
    {
    }

    /** List of all goals the manager has assigned. */
    public function index()
    {
        $goals = SalesGoal::with('rep')->orderByDesc('year')->get()->each(function (SalesGoal $goal) {
            $goal->achieved = $this->forecast->yearAchieved($goal);
        });

        $companyGoals = CompanyGoal::orderByDesc('year')->get();

        return Inertia::render('Forecast/Index', [
            'goals' => $goals,
            'companyGoals' => $companyGoals,
        ]);
    }

    /** Form: assign a new goal (Company cascade mode or single rep mode). */
    public function create(Request $request)
    {
        $reps = Rep::orderBy('name')->get();
        $marketingPlans = MarketingPlan::latest()->get();

        // Optional pre-selected marketing plan
        $selectedPlan = null;
        if ($request->filled('plan_id')) {
            $selectedPlan = MarketingPlan::find($request->input('plan_id'));
        }

        return Inertia::render('Forecast/Create', [
            'reps' => $reps,
            'marketingPlans' => $marketingPlans,
            'selectedPlan' => $selectedPlan,
        ]);
    }

    /** Save either a company-wide cascading goal or a single rep goal. */
    public function store(Request $request)
    {
        // Check if this is the Company Cascading Goal flow
        if ($request->input('goal_mode') === 'company') {
            return $this->storeCompanyCascade($request);
        }

        // Standard Single Rep Goal flow
        $data = $request->validate([
            'rep_id'          => ['required', 'exists:reps,id'],
            'title'           => ['required', 'string', 'max:120'],
            'year'            => [
                'required', 'integer', 'min:2020', 'max:2100',
                Rule::unique('sales_goals', 'year')->where(fn ($q) => $q->where('rep_id', $request->input('rep_id'))),
            ],
            'baseline_amount' => ['nullable', 'integer', 'min:0'],
            'target_amount'   => ['required', 'integer', 'min:1'],
        ], [
            'year.unique' => 'This rep already has a goal for that year.',
        ]);

        $data['baseline_amount'] = $data['baseline_amount'] ?? 0;

        $goal = DB::transaction(function () use ($data) {
            $goal = SalesGoal::create($data);

            foreach (TargetMath::evenSplit((int) $goal->target_amount, 12) as $i => $amount) {
                $goal->months()->create([
                    'month'         => $i + 1,
                    'target_amount' => $amount,
                    'is_fixed'      => false,
                ]);
            }

            return $goal;
        });

        return redirect()->route('forecast.show', $goal)->with('status', 'Goal created with 12 equal monthly targets.');
    }

    /** Process cascading company product-wise goals to all selected sales reps. */
    protected function storeCompanyCascade(Request $request)
    {
        $request->validate([
            'company_title' => ['required', 'string', 'max:150'],
            'year'          => ['required', 'integer', 'min:2020', 'max:2100'],
            'rep_ids'       => ['nullable', 'array'],
            'rep_ids.*'     => ['exists:reps,id'],
            'products'      => ['required', 'array', 'min:1'],
            'products.*.name' => ['required', 'string'],
            'products.*.annual_target' => ['required', 'numeric', 'min:0'],
        ]);

        $repIds = array_map('intval', $request->input('rep_ids', []));
        $repCount = count($repIds);
        $productsInput = $request->input('products', []);
        $year = (int) $request->input('year');
        $companyTitle = $request->input('company_title');
        $marketingPlanId = $request->input('marketing_plan_id') ?: null;

        $totalCompanyTarget = 0;
        $monthlyCompanyTotals = array_fill(1, 12, 0); // Month 1 to 12
        $processedProducts = [];

        foreach ($productsInput as $p) {
            $name = trim($p['name'] ?? '');
            $annual = (float) ($p['annual_target'] ?? 0);
            $totalCompanyTarget += $annual;

            // Monthly distribution for this product (1..12)
            $pMonths = [];
            for ($m = 1; $m <= 12; $m++) {
                $mVal = isset($p['months'][$m]) ? (float) $p['months'][$m] : 0;
                $pMonths[$m] = $mVal;
                $monthlyCompanyTotals[$m] += $mVal;
            }

            $processedProducts[] = [
                'name' => $name,
                'annual_target' => $annual,
                'months' => $pMonths,
            ];
        }

        // Divide the monthly company totals across reps IF reps are selected
        $repMonthlyShare = [];
        if ($repCount > 0) {
            for ($m = 1; $m <= 12; $m++) {
                $monthTotal = $monthlyCompanyTotals[$m];
                $split = TargetMath::evenSplit((int) round($monthTotal), $repCount);
                $repMonthlyShare[$m] = $split;
            }
        }

        DB::transaction(function () use ($repIds, $repCount, $year, $companyTitle, $totalCompanyTarget, $processedProducts, $repMonthlyShare, $marketingPlanId) {
            // 1. Create or update CompanyGoal record
            CompanyGoal::create([
                'marketing_plan_id' => $marketingPlanId,
                'title' => $companyTitle,
                'year' => $year,
                'total_annual_target' => (int) round($totalCompanyTarget),
                'products' => $processedProducts,
                'assigned_rep_ids' => $repIds,
            ]);

            // 2. Assign / Update SalesGoal for each selected rep (if any selected)
            if ($repCount > 0) {
                foreach ($repIds as $idx => $repId) {
                    $thisRepAnnual = 0;
                    $thisRepMonths = [];

                    for ($m = 1; $m <= 12; $m++) {
                        $amount = $repMonthlyShare[$m][$idx] ?? 0;
                        $thisRepMonths[$m] = $amount;
                        $thisRepAnnual += $amount;
                    }

                    $salesGoal = SalesGoal::updateOrCreate(
                        ['rep_id' => $repId, 'year' => $year],
                        [
                            'title' => $companyTitle . ' (Rep Target)',
                            'target_amount' => $thisRepAnnual,
                            'baseline_amount' => 0,
                        ]
                    );

                    foreach ($thisRepMonths as $month => $amount) {
                        $salesGoal->months()->updateOrCreate(
                            ['month' => $month],
                            ['target_amount' => $amount, 'is_fixed' => true]
                        );
                    }
                }
            }
        });

        $msg = $repCount > 0
            ? "Company goal successfully established! Targets cascaded across {$repCount} sales reps."
            : "Company Master Goal successfully saved! (No individual reps assigned).";

        return redirect()->route('forecast.index')->with('status', $msg);
    }

    /** The 12-month forecast grid for one goal. */
    public function show(Request $request, SalesGoal $goal)
    {
        $request->validate(['as_of' => ['nullable', 'date']]);

        $asOf = $request->filled('as_of') ? Carbon::parse($request->input('as_of')) : Carbon::today();

        $goal->load('rep');
        $s = $this->forecast->grid($goal, $asOf);

        return Inertia::render('Forecast/Show', [
            'goal' => $goal,
            's' => $s,
            'asOf' => $asOf->toDateString(),
        ]);
    }

    /** Manager modified individual month targets; rebalance if needed. */
    public function update(Request $request, SalesGoal $goal)
    {
        $request->validate([
            'targets' => ['required', 'array', 'size:12'],
            'targets.*' => ['required', 'integer', 'min:0'],
            'fixed' => ['nullable', 'array'],
            'fixed.*' => ['nullable', 'boolean'],
            'as_of' => ['nullable', 'date'],
        ]);

        $rawTargets = array_map('intval', $request->input('targets'));
        $fixedMap = [];
        foreach ($request->input('fixed', []) as $m => $v) {
            $fixedMap[(int) $m] = (bool) $v;
        }

        $res = TargetMath::rebalance((int) $goal->target_amount, $rawTargets, $fixedMap);

        if (! $res['ok']) {
            return back()->withInput()->withErrors(['rebalance' => $res['message']]);
        }

        DB::transaction(function () use ($goal, $res, $fixedMap) {
            foreach ($res['targets'] as $m => $amount) {
                $goal->months()->updateOrCreate(
                    ['month' => $m],
                    [
                        'target_amount' => $amount,
                        'is_fixed' => ! empty($fixedMap[$m]),
                    ]
                );
            }
        });

        $asOfParam = $request->filled('as_of') ? ['as_of' => $request->input('as_of')] : [];

        return redirect()->route('forecast.show', array_merge(['goal' => $goal], $asOfParam))
            ->with('status', $res['message']);
    }
}
