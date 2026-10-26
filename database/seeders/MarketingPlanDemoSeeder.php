<?php

namespace Database\Seeders;

use App\Models\MarketingPlan;
use Illuminate\Database\Seeder;

class MarketingPlanDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (MarketingPlan::count() > 0) {
            return;
        }

        MarketingPlan::create([
            'entrepreneur_name' => 'K.A. Sunimal Perera',
            'address_division' => 'No. 45/2, Temple Road, Gampaha DS Division',
            'telephone' => '077-1234567 / 033-2289450',
            'email' => 'sunimal.organics@gmail.com',
            'business_name' => 'Ceylon Pure Agro Foods',
            'registered_number' => 'PV 00249812',
            'main_product_service_summary' => 'Manufacturing and distribution of organic dehydrated fruits, herbal teas, and natural spices.',
            'plan_period_from' => '2026-01-01',
            'plan_period_to' => '2026-12-31',

            // 1. Purpose & Executive Summary
            'purpose_executive_summary' => "The primary purpose of this 12-month Marketing Plan is to expand the market footprint of Ceylon Pure Agro Foods across the Western and Central provinces. By strengthening direct retail placement and expanding online e-commerce channels, the business aims to achieve a 35% growth in sales revenue while establishing high brand recall for healthy, preservative-free organic foods.",

            // 2. Product/Service Description
            'main_products_services' => "2.1 Main Products: Dehydrated mango, papaya and pineapple packets (100g, 250g), ginger tea and cinnamon spiced herbal tea bags (25 teabags per pack). High standards of hygiene, eco-friendly vacuum packaging.",
            'future_products_services' => "2.2 Proposed Future Products: Organic fruit energy bars, cold-pressed virgin coconut oil infused with spices, and export-grade single-origin spice gift boxes.",

            // 3. Target Market & Customers Analysis
            'market_areas_current' => "Gampaha, Negombo, and Colombo suburbs (local groceries and direct consumer deliveries).",
            'market_areas_future' => "High-end supermarkets across Western Province (Keells, Cargills, Glomark) and selected tourist gift boutiques in Kandy and Galle.",
            'target_customer_profiles' => [
                [
                    'product_service' => 'Organic Dehydrated Fruits',
                    'targeted_area' => 'Colombo & Gampaha Urban Centers',
                    'goals_customers' => 'Health-conscious professionals, parents seeking healthy snacks for children, age 22-55, middle to high income.'
                ],
                [
                    'product_service' => 'Herbal Spiced Teas',
                    'targeted_area' => 'Island-wide & Tourist Resorts',
                    'goals_customers' => 'Wellness seekers, Ayurveda practitioners, domestic gift buyers and foreign tourists.'
                ],
            ],

            // 4. Market and Competitor Analysis
            'competitors' => [
                [
                    'name' => 'Lanka Organic Treats Ltd.',
                    'active_area' => 'Colombo District',
                    'key_strengths' => 'Strong supermarket shelf presence, established brand name.',
                    'weaknesses_opportunities' => 'High consumer pricing, artificial flavor additives in some items; our opportunity is 100% certified raw purity at 15% lower price point.'
                ],
                [
                    'name' => 'GreenLife Agro Products',
                    'active_area' => 'Kandy & Central Province',
                    'key_strengths' => 'Attractive packaging and direct farm procurement.',
                    'weaknesses_opportunities' => 'Weak digital marketing and poor retail distribution in Western Province.'
                ]
            ],
            'supply_capacities' => [
                [
                    'product_service' => 'Dehydrated Fruit Packs (100g)',
                    'demand_nature' => 'High',
                    'monthly_capacity' => '3,500 packs',
                    'annual_capacity' => '42,000 packs / Rs. 16,800,000'
                ],
                [
                    'product_service' => 'Herbal Teabags (Box of 25)',
                    'demand_nature' => 'Medium',
                    'monthly_capacity' => '2,000 boxes',
                    'annual_capacity' => '24,000 boxes / Rs. 7,200,000'
                ]
            ],

            // 5. Competitive Analysis (7 Ps)
            'competitive_7ps' => [
                [
                    'factor' => '1. Product',
                    'our_business' => '100% natural, zero sulfur preservatives, eco-friendly ziplock pouches.',
                    'competitor_01' => 'Standard packaging, mild sulfur preservatives used.',
                    'competitor_02' => 'Attractive craft boxes, but shorter shelf-life.'
                ],
                [
                    'factor' => '2. Price',
                    'our_business' => 'Affordable premium (Rs. 400 - 450 per pack).',
                    'competitor_01' => 'Higher end (Rs. 550 - 600 per pack).',
                    'competitor_02' => 'Mid-tier (Rs. 420 per pack).'
                ],
                [
                    'factor' => '3. Place / Delivery (Place)',
                    'our_business' => 'Direct to retail stores, island-wide courier, social media orders.',
                    'competitor_01' => 'Major supermarket chains only.',
                    'competitor_02' => 'Regional souvenir shops and tea centers.'
                ],
                [
                    'factor' => '4. Promotion',
                    'our_business' => 'Social media video ads, influencer tastings, SEDD fairs.',
                    'competitor_01' => 'Print ads, supermarket end-cap displays.',
                    'competitor_02' => 'Word of mouth and hotel brochures.'
                ],
                [
                    'factor' => '5. People',
                    'our_business' => 'Well-trained local female workforce from Gampaha DS division.',
                    'competitor_01' => 'Standard factory crew.',
                    'competitor_02' => 'Family-operated team.'
                ],
                [
                    'factor' => '6. Physical Evidence',
                    'our_business' => 'Clean processing plant, SLS/ISO test certifications, elegant branding.',
                    'competitor_01' => 'SLS certified factory.',
                    'competitor_02' => 'Cottage industry setup.'
                ],
                [
                    'factor' => '7. Process',
                    'our_business' => 'Hygienic solar-electric dehydrators, computerized moisture monitoring.',
                    'competitor_01' => 'Commercial tunnel dryers.',
                    'competitor_02' => 'Traditional hot air ovens.'
                ],
            ],

            // 6. SWOT Analysis
            'swot_strengths' => "• Continuous supply of fresh tropical fruits directly from Gampaha contract farmers.\n• Low production overheads with high hygienic processing standards.\n• Dynamic, certified training received from SEDD entrepreneurship programs.",
            'swot_weaknesses' => "• Limited automated sealing and bagging machinery.\n• Restricted initial working capital for large credit periods demanded by tier-1 supermarkets.",
            'swot_opportunities' => "• Surging national interest in chemical-free immunity-boosting organic food.\n• Government export promotion support and SEDD market link programs.\n• Untapped corporate gift hampers market for festive seasons.",
            'swot_threats' => "• Seasonal fluctuations in raw fruit yields and unseasonal heavy rains.\n• Inflation in imported food-grade packaging materials.",

            // 7. Suggested Marketing Strategies (4 Ps)
            'strategies_4ps' => [
                [
                    'product' => 'Launch 50g trial pouches and bundle packs (tea + dried fruit combos) with QR codes displaying origin story.',
                    'price' => 'Competitive introductory pricing with 10% retailer margins and bulk discounts for corporate orders.',
                    'place' => 'Partner with 40 selected franchise pharmacies and organic grocery outlets in Colombo & Gampaha.',
                    'promotion' => 'Run targeted Meta ads (Facebook/Instagram), sponsor local fitness and marathon events, participate in SEDD trade expos.'
                ]
            ],

            // 8. Marketing Expense Budget
            'marketing_expense_budget' => [
                ['item' => 'Digital Marketing (Social Media Ads)', 'existing_year' => 30000, 'proposed_year1' => 80000, 'proposed_year2' => 120000, 'proposed_year3' => 150000],
                ['item' => 'Liquid and advertising materials (Flyers/Banners)', 'existing_year' => 20000, 'proposed_year1' => 45000, 'proposed_year2' => 60000, 'proposed_year3' => 75000],
                ['item' => 'Growth Commissions and discounts', 'existing_year' => 50000, 'proposed_year1' => 120000, 'proposed_year2' => 180000, 'proposed_year3' => 240000],
                ['item' => 'Packaging and label costs', 'existing_year' => 60000, 'proposed_year1' => 150000, 'proposed_year2' => 220000, 'proposed_year3' => 280000],
                ['item' => 'Delivery and travel expenses', 'existing_year' => 40000, 'proposed_year1' => 90000, 'proposed_year2' => 130000, 'proposed_year3' => 160000],
            ],

            // 9. Sales Income Forecast
            // 9.1 Monthly sales revenue proposed 1st year (Rs.)
            'sales_forecast_monthly' => [
                [
                    'product_service' => 'Production A (Dehydrated Fruits)',
                    'm1' => 120000,
                    'm2' => 140000,
                    'm3' => 160000,
                    'm4' => 180000,
                    'm5' => 200000,
                    'm6' => 220000,
                    'm7_12' => 1500000,
                ],
                [
                    'product_service' => 'Production B (Herbal Teas)',
                    'm1' => 60000,
                    'm2' => 70000,
                    'm3' => 85000,
                    'm4' => 95000,
                    'm5' => 110000,
                    'm6' => 120000,
                    'm7_12' => 850000,
                ],
            ],

            // 9.2 Long-term 3-year sales revenue estimate
            'sales_forecast_longterm' => [
                [
                    'product_service' => 'Production 01 (Dehydrated Fruits)',
                    'base_year' => 1200000,
                    'proposed_year1' => 2520000,
                    'proposed_year2' => 3600000,
                    'proposed_year3' => 4800000,
                ],
                [
                    'product_service' => 'Production 02 (Herbal Teas)',
                    'base_year' => 650000,
                    'proposed_year1' => 1390000,
                    'proposed_year2' => 2100000,
                    'proposed_year3' => 2900000,
                ],
            ],

            // 10. Action Plan & Monitoring
            'action_plans' => [
                ['task' => 'Design and print revised eco-packaging and nutrition labels', 'responsible_person' => 'Sunimal (Owner)', 'time_frame' => 'Month 1 - 2', 'status' => 'Completed'],
                ['task' => 'Launch Meta ad campaign targeting Western Province health enthusiasts', 'responsible_person' => 'Marketing Officer', 'time_frame' => 'Month 2 - 4', 'status' => 'In Progress'],
                ['task' => 'Sign agreements with 30 retail partners in Colombo & Gampaha', 'responsible_person' => 'Sales Lead', 'time_frame' => 'Month 3 - 6', 'status' => 'In Progress'],
                ['task' => 'SEDD progress review & evaluation with Enterprise Development Officer', 'responsible_person' => 'Sunimal & SEDD Officer', 'time_frame' => 'Month 6', 'status' => 'Pending'],
            ],
        ]);
    }
}
