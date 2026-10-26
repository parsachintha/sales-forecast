<?php

namespace App\Http\Controllers;

use App\Models\Rep;
use App\Models\SalesGoal;
use App\Services\ForecastService;
use App\Support\TargetMath;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

        return view('forecast.index', compact('goals'));
    }

    /** Form: assign a new goal to a rep. */
    public function create()
    {
        return view('forecast.create', ['reps' => Rep::orderBy('name')->get()]);
    }

    /** Save the goal and pre-fill 12 equal monthly targets. */
    public function store(Request $request)
    {
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

    /** The 12-month forecast grid for one goal. */
    public function show(Request $request, SalesGoal $goal)
    {
        $request->validate(['as_of' => ['nullable', 'date']]);

        $asOf = $request->filled('as_of') ? Carbon::parse($request->input('as_of')) : Carbon::today();

        $goal->load('rep');
        $s = $this->forecast->grid($goal, $asOf);

        return view('forecast.show', compact('goal', 's', 'asOf'));
    }

    /** Save edited monthly targets (optionally rebalancing the unfixed months). */
    public function update(Request $request, SalesGoal $goal)
    {
        $request->validate([
            'targets'   => ['required', 'array', 'size:12'],
            'targets.*' => ['required', 'integer', 'min:0'],
            'fixed'     => ['nullable', 'array'],
            'as_of'     => ['nullable', 'date'],
        ]);

        $targets = [];
        $fixed   = [];
        for ($m = 1; $m <= 12; $m++) {
            $targets[$m] = (int) $request->input("targets.$m");
            $fixed[$m]   = (bool) $request->input("fixed.$m");
        }

        $message = 'Targets saved.';

        if ($request->input('action') === 'rebalance') {
            $result = TargetMath::rebalance((int) $goal->target_amount, $targets, $fixed);

            if ($result['ok']) {
                $targets = $result['targets'];
                $message = 'Targets saved. Other months were rebalanced so the total still equals the annual goal.';
            } else {
                $message = 'Targets saved, but not rebalanced: ' . $result['message'];
            }
        }

        DB::transaction(function () use ($goal, $targets, $fixed) {
            foreach ($targets as $month => $amount) {
                $goal->months()->updateOrCreate(
                    ['month' => $month],
                    ['target_amount' => $amount, 'is_fixed' => $fixed[$month]]
                );
            }
        });

        return redirect()
            ->route('forecast.show', ['goal' => $goal, 'as_of' => $request->input('as_of')])
            ->with('status', $message);
    }
}
