<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('marketing_plans', function (Blueprint $table) {
            $table->id();
            
            // Header / Entrepreneur info
            $table->string('entrepreneur_name')->nullable();
            $table->string('address_division')->nullable(); // Address / Pre.Lt. (DS) Division
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('business_name')->nullable();
            $table->string('registered_number')->nullable();
            $table->string('main_product_service_summary')->nullable();
            $table->date('plan_period_from')->nullable();
            $table->date('plan_period_to')->nullable();

            // 1. Purpose & Executive Summary
            $table->text('purpose_executive_summary')->nullable();

            // 2. Product/Service Description
            $table->text('main_products_services')->nullable(); // 2.1
            $table->text('future_products_services')->nullable(); // 2.2

            // 3. Target Market & Customers Analysis
            $table->text('market_areas_current')->nullable(); // 3.1
            $table->text('market_areas_future')->nullable(); // 3.1
            $table->json('target_customer_profiles')->nullable(); // 3.2 [{product_service, targeted_area, goals_customers}]

            // 4. Market and Competitor Analysis
            $table->json('competitors')->nullable(); // 4.1 [{name, active_area, key_strengths, weaknesses_opportunities}]
            $table->json('supply_capacities')->nullable(); // 4.2 [{product_service, demand_nature, monthly_capacity, annual_capacity}]

            // 5. Competitive Analysis (7 Ps Comparison)
            // [{factor: 'Product', our_business: '', competitor_01: '', competitor_02: ''}, ...]
            $table->json('competitive_7ps')->nullable();

            // 6. SWOT Analysis
            $table->text('swot_strengths')->nullable();
            $table->text('swot_weaknesses')->nullable();
            $table->text('swot_opportunities')->nullable();
            $table->text('swot_threats')->nullable();

            // 7. Suggested Marketing Strategies (4 Ps)
            // [{product: '', price: '', place: '', promotion: ''}]
            $table->json('strategies_4ps')->nullable();

            // 8. Marketing Expense Budget
            // [{item: '', existing_year: 0, proposed_year1: 0, proposed_year2: 0, proposed_year3: 0}]
            $table->json('marketing_expense_budget')->nullable();

            // 9. Sales Income Forecast
            // 9.1 Monthly sales revenue proposed 1st year
            // [{product_service: '', m1: 0, m2: 0, m3: 0, m4: 0, m5: 0, m6: 0, m7_12: 0, total: 0}]
            $table->json('sales_forecast_monthly')->nullable();

            // 9.2 Long-term 3-year sales revenue estimate
            // [{product_service: '', base_year: 0, proposed_year1: 0, proposed_year2: 0, proposed_year3: 0}]
            $table->json('sales_forecast_longterm')->nullable();

            // 10. Action Plan & Monitoring
            // [{task: '', responsible_person: '', time_frame: '', status: ''}]
            $table->json('action_plans')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_plans');
    }
};
