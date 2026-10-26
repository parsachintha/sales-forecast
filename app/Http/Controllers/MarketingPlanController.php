<?php

namespace App\Http\Controllers;

use App\Models\MarketingPlan;
use Illuminate\Http\Request;

class MarketingPlanController extends Controller
{
    public function index()
    {
        $plans = MarketingPlan::latest()->get();
        return view('marketing.index', compact('plans'));
    }

    public function create()
    {
        $plan = new MarketingPlan([
            'competitive_7ps' => MarketingPlan::default7Ps(),
            'marketing_expense_budget' => MarketingPlan::defaultExpenseBudget(),
            'sales_forecast_monthly' => MarketingPlan::defaultMonthlyForecast(),
            'sales_forecast_longterm' => MarketingPlan::defaultLongtermForecast(),
            'target_customer_profiles' => [
                ['product_service' => '', 'targeted_area' => '', 'goals_customers' => '']
            ],
            'competitors' => [
                ['name' => '', 'active_area' => '', 'key_strengths' => '', 'weaknesses_opportunities' => '']
            ],
            'supply_capacities' => [
                ['product_service' => '', 'demand_nature' => 'Medium', 'monthly_capacity' => '', 'annual_capacity' => '']
            ],
            'strategies_4ps' => [
                ['product' => '', 'price' => '', 'place' => '', 'promotion' => '']
            ],
            'action_plans' => [
                ['task' => '', 'responsible_person' => '', 'time_frame' => '', 'status' => 'Pending']
            ],
        ]);

        return view('marketing.create', compact('plan'));
    }

    public function store(Request $request)
    {
        $data = $this->extractPlanData($request);
        $plan = MarketingPlan::create($data);

        return redirect()->route('marketing-plan.show', $plan)->with('status', 'SEDD Marketing Plan created successfully!');
    }

    public function show(MarketingPlan $marketing_plan)
    {
        $plan = $marketing_plan;
        return view('marketing.show', compact('plan'));
    }

    public function edit(MarketingPlan $marketing_plan)
    {
        $plan = $marketing_plan;
        return view('marketing.edit', compact('plan'));
    }

    public function update(Request $request, MarketingPlan $marketing_plan)
    {
        $data = $this->extractPlanData($request);
        $marketing_plan->update($data);

        return redirect()->route('marketing-plan.show', $marketing_plan)->with('status', 'Marketing Plan updated successfully!');
    }

    public function destroy(MarketingPlan $marketing_plan)
    {
        $marketing_plan->delete();
        return redirect()->route('marketing-plan.index')->with('status', 'Marketing Plan deleted successfully.');
    }

    protected function extractPlanData(Request $request): array
    {
        $input = $request->except(['_token', '_method']);

        // Process array collections and sanitize
        $arrays = [
            'target_customer_profiles',
            'competitors',
            'supply_capacities',
            'competitive_7ps',
            'strategies_4ps',
            'marketing_expense_budget',
            'sales_forecast_monthly',
            'sales_forecast_longterm',
            'action_plans',
        ];

        foreach ($arrays as $key) {
            if (isset($input[$key]) && is_array($input[$key])) {
                // Reindex values
                $input[$key] = array_values($input[$key]);
            } else {
                $input[$key] = [];
            }
        }

        return $input;
    }
}
