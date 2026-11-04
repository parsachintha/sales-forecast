<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingPlan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'plan_period_from' => 'date',
        'plan_period_to' => 'date',
        'target_customer_profiles' => 'array',
        'competitors' => 'array',
        'supply_capacities' => 'array',
        'competitive_7ps' => 'array',
        'strategies_4ps' => 'array',
        'marketing_expense_budget' => 'array',
        'sales_forecast_monthly' => 'array',
        'sales_forecast_longterm' => 'array',
        'action_plans' => 'array',
    ];

    public static function default7Ps(): array
    {
        return [
            ['factor' => '1. Product', 'our_business' => '', 'competitor_01' => '', 'competitor_02' => ''],
            ['factor' => '2. Price', 'our_business' => '', 'competitor_01' => '', 'competitor_02' => ''],
            ['factor' => '3. Place / Delivery (Place)', 'our_business' => '', 'competitor_01' => '', 'competitor_02' => ''],
            ['factor' => '4. Promotion', 'our_business' => '', 'competitor_01' => '', 'competitor_02' => ''],
            ['factor' => '5. People', 'our_business' => '', 'competitor_01' => '', 'competitor_02' => ''],
            ['factor' => '6. Physical Evidence', 'our_business' => '', 'competitor_01' => '', 'competitor_02' => ''],
            ['factor' => '7. Process', 'our_business' => '', 'competitor_01' => '', 'competitor_02' => ''],
        ];
    }

    public static function defaultExpenseBudget(): array
    {
        return [
            ['item' => 'Digital Marketing (Social Media Ads)', 'existing_year' => 0, 'proposed_year1' => 0, 'proposed_year2' => 0, 'proposed_year3' => 0],
            ['item' => 'Liquid and advertising materials (Flyers/Banners)', 'existing_year' => 0, 'proposed_year1' => 0, 'proposed_year2' => 0, 'proposed_year3' => 0],
            ['item' => 'Growth Commissions and discounts', 'existing_year' => 0, 'proposed_year1' => 0, 'proposed_year2' => 0, 'proposed_year3' => 0],
            ['item' => 'Packaging and label costs', 'existing_year' => 0, 'proposed_year1' => 0, 'proposed_year2' => 0, 'proposed_year3' => 0],
            ['item' => 'Delivery and travel expenses', 'existing_year' => 0, 'proposed_year1' => 0, 'proposed_year2' => 0, 'proposed_year3' => 0],
        ];
    }

    public static function defaultMonthlyForecast(): array
    {
        return [
            ['product_service' => 'Production A', 'm1' => 0, 'm2' => 0, 'm3' => 0, 'm4' => 0, 'm5' => 0, 'm6' => 0, 'm7' => 0, 'm8' => 0, 'm9' => 0, 'm10' => 0, 'm11' => 0, 'm12' => 0, 'total' => 0],
            ['product_service' => 'Production B', 'm1' => 0, 'm2' => 0, 'm3' => 0, 'm4' => 0, 'm5' => 0, 'm6' => 0, 'm7' => 0, 'm8' => 0, 'm9' => 0, 'm10' => 0, 'm11' => 0, 'm12' => 0, 'total' => 0],
        ];
    }

    public static function defaultLongtermForecast(): array
    {
        return [
            ['product_service' => 'Production 01', 'base_year' => 0, 'proposed_year1' => 0, 'proposed_year2' => 0, 'proposed_year3' => 0],
            ['product_service' => 'Production 02', 'base_year' => 0, 'proposed_year1' => 0, 'proposed_year2' => 0, 'proposed_year3' => 0],
        ];
    }
}
