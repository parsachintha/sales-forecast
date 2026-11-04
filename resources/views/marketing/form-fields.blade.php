@php
    $isEdit = isset($plan) && $plan->exists;
@endphp

<!-- Document Header & Entrepreneur Details -->
<div class="card">
    <div class="section-header">
        <h2 class="section-title">
            <span class="sec-num">SEDD</span>
            Small Enterprise Development Division - MSME Practical Marketing Plan
        </h2>
        <span style="font-size:12px; color:var(--mute);">Form Details</span>
    </div>

    <div class="form-grid">
        <div class="form-group">
            <label>Entrepreneur Name *</label>
            <input type="text" name="entrepreneur_name" value="{{ old('entrepreneur_name', $plan->entrepreneur_name) }}" required placeholder="e.g. K.A. Sunimal Perera">
        </div>
        <div class="form-group">
            <label>Address / Pre.Lt. (DS) Division *</label>
            <input type="text" name="address_division" value="{{ old('address_division', $plan->address_division) }}" required placeholder="e.g. 142/A, Kandy Road, Gampaha DS Division">
        </div>
        <div class="form-group">
            <label>Telephone Number</label>
            <input type="text" name="telephone" value="{{ old('telephone', $plan->telephone) }}" placeholder="e.g. 0771234567 / 0332211222">
        </div>
        <div class="form-group">
            <label>E-mail Address</label>
            <input type="email" name="email" value="{{ old('email', $plan->email) }}" placeholder="e.g. info@business.lk">
        </div>
        <div class="form-group">
            <label>Business Name *</label>
            <input type="text" name="business_name" value="{{ old('business_name', $plan->business_name) }}" required placeholder="e.g. Apex Agri Products">
        </div>
        <div class="form-group">
            <label>Registered Number (BR No.)</label>
            <input type="text" name="registered_number" value="{{ old('registered_number', $plan->registered_number) }}" placeholder="e.g. W/GM/14582">
        </div>
        <div class="form-group col-span-2">
            <label>Main Production and Service</label>
            <input type="text" name="main_product_service_summary" value="{{ old('main_product_service_summary', $plan->main_product_service_summary) }}" placeholder="e.g. Organic Dehydrated Fruits and Herbal Teas">
        </div>
        <div class="form-group">
            <label>Plan Period: From (Date)</label>
            <input type="date" name="plan_period_from" value="{{ old('plan_period_from', optional($plan->plan_period_from)->format('Y-m-d')) }}">
        </div>
        <div class="form-group">
            <label>Plan Period: To (12 Months)</label>
            <input type="date" name="plan_period_to" value="{{ old('plan_period_to', optional($plan->plan_period_to)->format('Y-m-d')) }}">
        </div>
    </div>
</div>

<!-- 1. Purpose & Executive Summary -->
<div class="card">
    <div class="section-header">
        <h2 class="section-title"><span class="sec-num">1</span> Purpose & Executive Summary of the Marketing Plan</h2>
    </div>
    <div class="form-group">
        <label>Provide an executive summary detailing market goals, vision, and operational purpose</label>
        <textarea name="purpose_executive_summary" rows="4" placeholder="Describe the background, primary market objective, and executive roadmap for this 12-month period...">{{ old('purpose_executive_summary', $plan->purpose_executive_summary) }}</textarea>
    </div>
</div>

<!-- 2. Product/Service Description -->
<div class="card">
    <div class="section-header">
        <h2 class="section-title"><span class="sec-num">2</span> Product / Service Description</h2>
    </div>
    <div class="form-grid">
        <div class="form-group col-span-2">
            <label>2.1 Main Product / Service</label>
            <textarea name="main_products_services" rows="3" placeholder="Describe current core products/services, quality attributes, and unique selling points...">{{ old('main_products_services', $plan->main_products_services) }}</textarea>
        </div>
        <div class="form-group col-span-2">
            <label>2.2 Proposed Future Products / Services</label>
            <textarea name="future_products_services" rows="3" placeholder="Describe new product lines, value additions, or service innovations planned...">{{ old('future_products_services', $plan->future_products_services) }}</textarea>
        </div>
    </div>
</div>

<!-- 3. Target Market & Customers Analysis -->
<div class="card">
    <div class="section-header">
        <h2 class="section-title"><span class="sec-num">3</span> Target Market & Customers Analysis</h2>
    </div>
    
    <div class="form-grid">
        <div class="form-group">
            <label>3.1 Areas Currently Covered</label>
            <textarea name="market_areas_current" rows="2" placeholder="e.g. Gampaha Municipal area, retail supermarkets in Colombo">{{ old('market_areas_current', $plan->market_areas_current) }}</textarea>
        </div>
        <div class="form-group">
            <label>3.1 Areas Expected to be Covered in the Future</label>
            <textarea name="market_areas_future" rows="2" placeholder="e.g. Export markets (Maldives/Dubai), Southern province hotels">{{ old('market_areas_future', $plan->market_areas_future) }}</textarea>
        </div>
    </div>

    <div style="margin-top:16px;">
        <label style="font-weight:600; font-size:13px; color:#374151;">3.2 Target Customer Profile</label>
        <table class="sedd-table" id="target-customers-table">
            <thead>
                <tr>
                    <th style="width:25%;">Product / Service</th>
                    <th style="width:30%;">Targeted Area</th>
                    <th style="width:40%;">Goals / Customers (age, income, gender, institutions)</th>
                    <th style="width:5%;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach(old('target_customer_profiles', $plan->target_customer_profiles ?? [['product_service'=>'','targeted_area'=>'','goals_customers'=>'']]) as $idx => $tcp)
                    <tr>
                        <td><input type="text" name="target_customer_profiles[{{ $idx }}][product_service]" value="{{ $tcp['product_service'] ?? '' }}" placeholder="e.g. Organic Jam"></td>
                        <td><input type="text" name="target_customer_profiles[{{ $idx }}][targeted_area]" value="{{ $tcp['targeted_area'] ?? '' }}" placeholder="e.g. Urban retail stores"></td>
                        <td><input type="text" name="target_customer_profiles[{{ $idx }}][goals_customers]" value="{{ $tcp['goals_customers'] ?? '' }}" placeholder="e.g. Health conscious middle/upper income 25-50 years"></td>
                        <td style="text-align:center;"><button type="button" class="btn danger remove-row-btn">&times;</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="button" class="btn add-row" onclick="addRow('target-customers-table', 'target_customer_profiles', ['product_service', 'targeted_area', 'goals_customers'])">+ Add Customer Profile Row</button>
    </div>
</div>

<!-- 4. Market and Competitor Analysis -->
<div class="card">
    <div class="section-header">
        <h2 class="section-title"><span class="sec-num">4</span> Practical Market & Competitor Analysis</h2>
    </div>

    <div style="margin-bottom:20px;">
        <label style="font-weight:600; font-size:13px; color:#374151;">4.1 Identifying the Main Competitors in the Area (Competitor Assessment)</label>
        <table class="sedd-table" id="competitors-table">
            <thead>
                <tr>
                    <th style="width:24%;">Competitor's Name / Institution</th>
                    <th style="width:22%;">Active Area</th>
                    <th style="width:24%;">Their Key Strength</th>
                    <th style="width:25%;">Their Weaknesses / Opportunities We Have</th>
                    <th style="width:5%;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach(old('competitors', $plan->competitors ?? [['name'=>'','active_area'=>'','key_strengths'=>'','weaknesses_opportunities'=>'']]) as $idx => $c)
                    <tr>
                        <td><input type="text" name="competitors[{{ $idx }}][name]" value="{{ $c['name'] ?? '' }}" placeholder="Competitor name"></td>
                        <td><input type="text" name="competitors[{{ $idx }}][active_area]" value="{{ $c['active_area'] ?? '' }}" placeholder="Regional coverage"></td>
                        <td><input type="text" name="competitors[{{ $idx }}][key_strengths]" value="{{ $c['key_strengths'] ?? '' }}" placeholder="e.g. Established brand name"></td>
                        <td><input type="text" name="competitors[{{ $idx }}][weaknesses_opportunities]" value="{{ $c['weaknesses_opportunities'] ?? '' }}" placeholder="e.g. High pricing, poor service"></td>
                        <td style="text-align:center;"><button type="button" class="btn danger remove-row-btn">&times;</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="button" class="btn add-row" onclick="addRow('competitors-table', 'competitors', ['name', 'active_area', 'key_strengths', 'weaknesses_opportunities'])">+ Add Competitor Row</button>
    </div>

    <div>
        <label style="font-weight:600; font-size:13px; color:#374151;">4.2 Proposed Supply Capacity of the Business</label>
        <table class="sedd-table" id="supply-capacity-table">
            <thead>
                <tr>
                    <th style="width:30%;">Product / Service</th>
                    <th style="width:20%;">In Demand Nature (High/Medium/Low)</th>
                    <th style="width:22%;">Monthly Production Capacity</th>
                    <th style="width:23%;">Annual Production Capacity (units / Rs.)</th>
                    <th style="width:5%;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach(old('supply_capacities', $plan->supply_capacities ?? [['product_service'=>'','demand_nature'=>'Medium','monthly_capacity'=>'','annual_capacity'=>'']]) as $idx => $sc)
                    <tr>
                        <td><input type="text" name="supply_capacities[{{ $idx }}][product_service]" value="{{ $sc['product_service'] ?? '' }}" placeholder="e.g. Herbal tea pack 100g"></td>
                        <td>
                            <select name="supply_capacities[{{ $idx }}][demand_nature]">
                                <option value="High" {{ ($sc['demand_nature'] ?? '') == 'High' ? 'selected' : '' }}>High</option>
                                <option value="Medium" {{ ($sc['demand_nature'] ?? '') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="Low" {{ ($sc['demand_nature'] ?? '') == 'Low' ? 'selected' : '' }}>Low</option>
                            </select>
                        </td>
                        <td><input type="text" name="supply_capacities[{{ $idx }}][monthly_capacity]" value="{{ $sc['monthly_capacity'] ?? '' }}" placeholder="e.g. 5,000 units"></td>
                        <td><input type="text" name="supply_capacities[{{ $idx }}][annual_capacity]" value="{{ $sc['annual_capacity'] ?? '' }}" placeholder="e.g. 60,000 units / Rs. 3.6M"></td>
                        <td style="text-align:center;"><button type="button" class="btn danger remove-row-btn">&times;</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="button" class="btn add-row" onclick="addRow('supply-capacity-table', 'supply_capacities', ['product_service', 'demand_nature', 'monthly_capacity', 'annual_capacity'], {demand_nature: ['select', ['High', 'Medium', 'Low']]})">+ Add Supply Capacity Row</button>
    </div>
</div>

<!-- 5. Competitive Analysis (7 Ps Comparison) -->
<div class="card">
    <div class="section-header">
        <h2 class="section-title"><span class="sec-num">5</span> Competitive Analysis (Competitor Comparison - 7 Ps)</h2>
    </div>
    <p style="font-size:13px; color:var(--mute); margin-top:0;">Evaluate your business against the top 2 competitors across the 7 Ps of Marketing.</p>
    <table class="sedd-table">
        <thead>
            <tr>
                <th style="width:20%;">7 Ps Factors</th>
                <th style="width:27%; background:#eff6ff;">Our Business</th>
                <th style="width:26%;">Competitor 01</th>
                <th style="width:27%;">Competitor 02</th>
            </tr>
        </thead>
        <tbody>
            @foreach(old('competitive_7ps', $plan->competitive_7ps ?? \App\Models\MarketingPlan::default7Ps()) as $idx => $item)
                <tr>
                    <td>
                        <strong>{{ $item['factor'] ?? '' }}</strong>
                        <input type="hidden" name="competitive_7ps[{{ $idx }}][factor]" value="{{ $item['factor'] ?? '' }}">
                    </td>
                    <td style="background:#f9fafb;">
                        <textarea rows="2" name="competitive_7ps[{{ $idx }}][our_business]" placeholder="Our feature/approach...">{{ $item['our_business'] ?? '' }}</textarea>
                    </td>
                    <td>
                        <textarea rows="2" name="competitive_7ps[{{ $idx }}][competitor_01]" placeholder="Competitor 1 approach...">{{ $item['competitor_01'] ?? '' }}</textarea>
                    </td>
                    <td>
                        <textarea rows="2" name="competitive_7ps[{{ $idx }}][competitor_02]" placeholder="Competitor 2 approach...">{{ $item['competitor_02'] ?? '' }}</textarea>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- 6. SWOT Analysis -->
<div class="card">
    <div class="section-header">
        <h2 class="section-title"><span class="sec-num">6</span> SWOT Analysis</h2>
    </div>
    <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
        <!-- Strengths -->
        <div style="background:#f0fdf4; padding:14px; border-radius:8px; border:1px solid #bbf7d0;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <label style="color:#166534; font-weight:700; font-size:13.5px;">Strengths (Strengths) - Internal</label>
                <button type="button" class="btn" style="padding:2px 8px; font-size:12px; background:#16a34a; border-color:#15803d;" onclick="addSwotPoint('strengths')" title="Add point">+</button>
            </div>
            <div id="swot-strengths-container" style="display:flex; flex-direction:column; gap:6px;">
                @php
                    $strengthsPoints = old('swot_strengths_list', isset($plan->swot_strengths) ? array_filter(array_map('trim', explode("\n", $plan->swot_strengths))) : ['Experienced team', 'Certified organic quality', 'Low cost local production']);
                    if (empty($strengthsPoints)) $strengthsPoints = [''];
                @endphp
                @foreach($strengthsPoints as $point)
                    <div class="swot-row" style="display:flex; gap:6px; align-items:center;">
                        <span style="color:#16a34a; font-weight:700;">•</span>
                        <input type="text" name="swot_strengths_list[]" value="{{ ltrim($point, '• ') }}" placeholder="Enter strength..." style="flex:1;">
                        <button type="button" class="btn danger remove-swot-btn" style="padding:2px 6px; font-size:11px;">&times;</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn add-row" style="margin-top:8px; border-color:#16a34a; color:#16a34a !important;" onclick="addSwotPoint('strengths')">+ Add Strength Point</button>
        </div>

        <!-- Weaknesses -->
        <div style="background:#fef2f2; padding:14px; border-radius:8px; border:1px solid #fecaca;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <label style="color:#991b1b; font-weight:700; font-size:13.5px;">Weaknesses (Weaknesses) - Internal</label>
                <button type="button" class="btn" style="padding:2px 8px; font-size:12px; background:#dc2626; border-color:#b91c1c;" onclick="addSwotPoint('weaknesses')" title="Add point">+</button>
            </div>
            <div id="swot-weaknesses-container" style="display:flex; flex-direction:column; gap:6px;">
                @php
                    $weaknessesPoints = old('swot_weaknesses_list', isset($plan->swot_weaknesses) ? array_filter(array_map('trim', explode("\n", $plan->swot_weaknesses))) : ['Limited automated machinery', 'Restricted initial marketing budget']);
                    if (empty($weaknessesPoints)) $weaknessesPoints = [''];
                @endphp
                @foreach($weaknessesPoints as $point)
                    <div class="swot-row" style="display:flex; gap:6px; align-items:center;">
                        <span style="color:#dc2626; font-weight:700;">•</span>
                        <input type="text" name="swot_weaknesses_list[]" value="{{ ltrim($point, '• ') }}" placeholder="Enter weakness..." style="flex:1;">
                        <button type="button" class="btn danger remove-swot-btn" style="padding:2px 6px; font-size:11px;">&times;</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn add-row" style="margin-top:8px; border-color:#dc2626; color:#dc2626 !important;" onclick="addSwotPoint('weaknesses')">+ Add Weakness Point</button>
        </div>

        <!-- Opportunities -->
        <div style="background:#eff6ff; padding:14px; border-radius:8px; border:1px solid #bfdbfe;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <label style="color:#1e40af; font-weight:700; font-size:13.5px;">Opportunities (Opportunities) - External</label>
                <button type="button" class="btn" style="padding:2px 8px; font-size:12px; background:#2563eb; border-color:#1d4ed8;" onclick="addSwotPoint('opportunities')" title="Add point">+</button>
            </div>
            <div id="swot-opportunities-container" style="display:flex; flex-direction:column; gap:6px;">
                @php
                    $opportunitiesPoints = old('swot_opportunities_list', isset($plan->swot_opportunities) ? array_filter(array_map('trim', explode("\n", $plan->swot_opportunities))) : ['Growing tourist demand', 'Government export subsidies via SEDD']);
                    if (empty($opportunitiesPoints)) $opportunitiesPoints = [''];
                @endphp
                @foreach($opportunitiesPoints as $point)
                    <div class="swot-row" style="display:flex; gap:6px; align-items:center;">
                        <span style="color:#2563eb; font-weight:700;">•</span>
                        <input type="text" name="swot_opportunities_list[]" value="{{ ltrim($point, '• ') }}" placeholder="Enter opportunity..." style="flex:1;">
                        <button type="button" class="btn danger remove-swot-btn" style="padding:2px 6px; font-size:11px;">&times;</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn add-row" style="margin-top:8px; border-color:#2563eb; color:#2563eb !important;" onclick="addSwotPoint('opportunities')">+ Add Opportunity Point</button>
        </div>

        <!-- Threats -->
        <div style="background:#fffbeb; padding:14px; border-radius:8px; border:1px solid #fde68a;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <label style="color:#92400e; font-weight:700; font-size:13.5px;">Threats (Threats) - External</label>
                <button type="button" class="btn" style="padding:2px 8px; font-size:12px; background:#d97706; border-color:#b45309;" onclick="addSwotPoint('threats')" title="Add point">+</button>
            </div>
            <div id="swot-threats-container" style="display:flex; flex-direction:column; gap:6px;">
                @php
                    $threatsPoints = old('swot_threats_list', isset($plan->swot_threats) ? array_filter(array_map('trim', explode("\n", $plan->swot_threats))) : ['Raw material price inflation', 'Cheap imported substitutes']);
                    if (empty($threatsPoints)) $threatsPoints = [''];
                @endphp
                @foreach($threatsPoints as $point)
                    <div class="swot-row" style="display:flex; gap:6px; align-items:center;">
                        <span style="color:#d97706; font-weight:700;">•</span>
                        <input type="text" name="swot_threats_list[]" value="{{ ltrim($point, '• ') }}" placeholder="Enter threat..." style="flex:1;">
                        <button type="button" class="btn danger remove-swot-btn" style="padding:2px 6px; font-size:11px;">&times;</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn add-row" style="margin-top:8px; border-color:#d97706; color:#d97706 !important;" onclick="addSwotPoint('threats')">+ Add Threat Point</button>
        </div>
    </div>
</div>

<!-- 7. Suggested Marketing Strategies (4 Ps) -->
<div class="card">
    <div class="section-header">
        <h2 class="section-title"><span class="sec-num">7</span> Suggested Marketing Strategies (4 Ps)</h2>
    </div>
    <table class="sedd-table" id="strategies-4ps-table">
        <thead>
            <tr>
                <th style="width:25%;">Goods and Services (Product)</th>
                <th style="width:24%;">Price</th>
                <th style="width:24%;">Place</th>
                <th style="width:24%;">Promotion</th>
                <th style="width:3%;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach(old('strategies_4ps', $plan->strategies_4ps ?? [['product'=>'','price'=>'','place'=>'','promotion'=>'']]) as $idx => $s4)
                <tr>
                    <td><textarea rows="3" name="strategies_4ps[{{ $idx }}][product]" placeholder="Product strategies, packaging & branding">{{ $s4['product'] ?? '' }}</textarea></td>
                    <td><textarea rows="3" name="strategies_4ps[{{ $idx }}][price]" placeholder="Pricing strategy, discounts & payment terms">{{ $s4['price'] ?? '' }}</textarea></td>
                    <td><textarea rows="3" name="strategies_4ps[{{ $idx }}][place]" placeholder="Distribution channels, delivery & retail points">{{ $s4['place'] ?? '' }}</textarea></td>
                    <td><textarea rows="3" name="strategies_4ps[{{ $idx }}][promotion]" placeholder="Advertising, direct sales, social media, exhibitions">{{ $s4['promotion'] ?? '' }}</textarea></td>
                    <td style="text-align:center;"><button type="button" class="btn danger remove-row-btn">&times;</button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button type="button" class="btn add-row" onclick="addRow('strategies-4ps-table', 'strategies_4ps', ['product', 'price', 'place', 'promotion'], {}, true)">+ Add Marketing Strategy Row</button>
</div>

<!-- 8. Marketing Expense Budget -->
<div class="card">
    <div class="section-header">
        <h2 class="section-title"><span class="sec-num">8</span> Marketing Expense Budget</h2>
    </div>
    <table class="sedd-table" id="budget-table">
        <thead>
            <tr>
                <th style="width:36%;">Expenses Item</th>
                <th class="num" style="width:16%;">Existing Year (Rs.)</th>
                <th class="num" style="width:16%;">Proposed 1 Year (Rs.)</th>
                <th class="num" style="width:16%;">Proposed 2 Years (Rs.)</th>
                <th class="num" style="width:16%;">Proposed 3 Years (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach(old('marketing_expense_budget', $plan->marketing_expense_budget ?? \App\Models\MarketingPlan::defaultExpenseBudget()) as $idx => $b)
                <tr>
                    <td>
                        <input type="text" name="marketing_expense_budget[{{ $idx }}][item]" value="{{ $b['item'] ?? '' }}" placeholder="Expense item">
                    </td>
                    <td><input type="number" step="0.01" class="budget-input bg-ex num" name="marketing_expense_budget[{{ $idx }}][existing_year]" value="{{ $b['existing_year'] ?? 0 }}"></td>
                    <td><input type="number" step="0.01" class="budget-input bg-p1 num" name="marketing_expense_budget[{{ $idx }}][proposed_year1]" value="{{ $b['proposed_year1'] ?? 0 }}"></td>
                    <td><input type="number" step="0.01" class="budget-input bg-p2 num" name="marketing_expense_budget[{{ $idx }}][proposed_year2]" value="{{ $b['proposed_year2'] ?? 0 }}"></td>
                    <td><input type="number" step="0.01" class="budget-input bg-p3 num" name="marketing_expense_budget[{{ $idx }}][proposed_year3]" value="{{ $b['proposed_year3'] ?? 0 }}"></td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>Collection (Total)</td>
                <td class="num" id="total-bg-ex">0.00</td>
                <td class="num" id="total-bg-p1">0.00</td>
                <td class="num" id="total-bg-p2">0.00</td>
                <td class="num" id="total-bg-p3">0.00</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- 9. Sales Income Forecast -->
<div class="card">
    <div class="section-header">
        <h2 class="section-title"><span class="sec-num">9</span> Sales Income Forecast</h2>
    </div>

    <!-- 9.1 Monthly Forecast for 1st Year -->
    <div style="margin-bottom: 24px;">
        <label style="font-weight:700; font-size:14px; color:#1e3a8a;">9.1 Estimated monthly sales revenue for the proposed 1st year (Rs.)</label>
        <p style="font-size:12px; color:var(--mute); margin:2px 0 8px;">Breakdown of all 12 individual months (Month 1 through Month 12) with full annual total.</p>

        <div style="overflow-x:auto;">
            <table class="sedd-table" id="forecast-monthly-table" style="min-width:1150px;">
                <thead>
                    <tr>
                        <th style="min-width:150px;">Product / Service</th>
                        <th class="num" style="min-width:70px;">1 M</th>
                        <th class="num" style="min-width:70px;">2 M</th>
                        <th class="num" style="min-width:70px;">3 M</th>
                        <th class="num" style="min-width:70px;">4 M</th>
                        <th class="num" style="min-width:70px;">5 M</th>
                        <th class="num" style="min-width:70px;">6 M</th>
                        <th class="num" style="min-width:70px;">7 M</th>
                        <th class="num" style="min-width:70px;">8 M</th>
                        <th class="num" style="min-width:70px;">9 M</th>
                        <th class="num" style="min-width:70px;">10 M</th>
                        <th class="num" style="min-width:70px;">11 M</th>
                        <th class="num" style="min-width:70px;">12 M</th>
                        <th class="num" style="background:#e0e7ff; min-width:110px;">1 Year Total</th>
                        <th style="width:30px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(old('sales_forecast_monthly', $plan->sales_forecast_monthly ?? \App\Models\MarketingPlan::defaultMonthlyForecast()) as $idx => $m)
                        @php
                            // Handle legacy m7_12 data gracefully if present
                            $legacyHalf = isset($m['m7_12']) ? (float)$m['m7_12'] / 6 : 0;
                            $m7 = isset($m['m7']) ? (float)$m['m7'] : $legacyHalf;
                            $m8 = isset($m['m8']) ? (float)$m['m8'] : $legacyHalf;
                            $m9 = isset($m['m9']) ? (float)$m['m9'] : $legacyHalf;
                            $m10 = isset($m['m10']) ? (float)$m['m10'] : $legacyHalf;
                            $m11 = isset($m['m11']) ? (float)$m['m11'] : $legacyHalf;
                            $m12 = isset($m['m12']) ? (float)$m['m12'] : $legacyHalf;
                        @endphp
                        <tr class="monthly-row">
                            <td><input type="text" name="sales_forecast_monthly[{{ $idx }}][product_service]" value="{{ $m['product_service'] ?? '' }}" placeholder="e.g. Production A"></td>
                            <td><input type="number" step="0.01" class="m-input m1 num" name="sales_forecast_monthly[{{ $idx }}][m1]" value="{{ $m['m1'] ?? 0 }}"></td>
                            <td><input type="number" step="0.01" class="m-input m2 num" name="sales_forecast_monthly[{{ $idx }}][m2]" value="{{ $m['m2'] ?? 0 }}"></td>
                            <td><input type="number" step="0.01" class="m-input m3 num" name="sales_forecast_monthly[{{ $idx }}][m3]" value="{{ $m['m3'] ?? 0 }}"></td>
                            <td><input type="number" step="0.01" class="m-input m4 num" name="sales_forecast_monthly[{{ $idx }}][m4]" value="{{ $m['m4'] ?? 0 }}"></td>
                            <td><input type="number" step="0.01" class="m-input m5 num" name="sales_forecast_monthly[{{ $idx }}][m5]" value="{{ $m['m5'] ?? 0 }}"></td>
                            <td><input type="number" step="0.01" class="m-input m6 num" name="sales_forecast_monthly[{{ $idx }}][m6]" value="{{ $m['m6'] ?? 0 }}"></td>
                            <td><input type="number" step="0.01" class="m-input m7 num" name="sales_forecast_monthly[{{ $idx }}][m7]" value="{{ $m7 }}"></td>
                            <td><input type="number" step="0.01" class="m-input m8 num" name="sales_forecast_monthly[{{ $idx }}][m8]" value="{{ $m8 }}"></td>
                            <td><input type="number" step="0.01" class="m-input m9 num" name="sales_forecast_monthly[{{ $idx }}][m9]" value="{{ $m9 }}"></td>
                            <td><input type="number" step="0.01" class="m-input m10 num" name="sales_forecast_monthly[{{ $idx }}][m10]" value="{{ $m10 }}"></td>
                            <td><input type="number" step="0.01" class="m-input m11 num" name="sales_forecast_monthly[{{ $idx }}][m11]" value="{{ $m11 }}"></td>
                            <td><input type="number" step="0.01" class="m-input m12 num" name="sales_forecast_monthly[{{ $idx }}][m12]" value="{{ $m12 }}"></td>
                            <td class="num row-year-total" style="font-weight:700; background:#f4f6ff;">0.00</td>
                            <td style="text-align:center;"><button type="button" class="btn danger remove-row-btn">&times;</button></td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>Total Income (Rs.)</td>
                        <td class="num" id="tot-m1">0.00</td>
                        <td class="num" id="tot-m2">0.00</td>
                        <td class="num" id="tot-m3">0.00</td>
                        <td class="num" id="tot-m4">0.00</td>
                        <td class="num" id="tot-m5">0.00</td>
                        <td class="num" id="tot-m6">0.00</td>
                        <td class="num" id="tot-m7">0.00</td>
                        <td class="num" id="tot-m8">0.00</td>
                        <td class="num" id="tot-m9">0.00</td>
                        <td class="num" id="tot-m10">0.00</td>
                        <td class="num" id="tot-m11">0.00</td>
                        <td class="num" id="tot-m12">0.00</td>
                        <td class="num" id="tot-year" style="background:#e0e7ff;">0.00</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button type="button" class="btn add-row" onclick="addMonthlyRow()">+ Add Product / Service Row</button>
    </div>

    <!-- 9.2 Long-term (3-year) sales revenue estimate -->
    <div>
        <label style="font-weight:700; font-size:14px; color:#1e3a8a;">9.2 Long-term (3-year) sales revenue estimate</label>
        <table class="sedd-table" id="forecast-longterm-table">
            <thead>
                <tr>
                    <th style="width:36%;">Product / Service</th>
                    <th class="num" style="width:16%;">Foundations Year (Rs.)</th>
                    <th class="num" style="width:16%;">Proposed 1 Year (Rs.)</th>
                    <th class="num" style="width:16%;">Proposed 2 Years (Rs.)</th>
                    <th class="num" style="width:16%;">Proposed 3 Years (Rs.)</th>
                    <th style="width:40px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach(old('sales_forecast_longterm', $plan->sales_forecast_longterm ?? \App\Models\MarketingPlan::defaultLongtermForecast()) as $idx => $lt)
                    <tr class="lt-row">
                        <td><input type="text" name="sales_forecast_longterm[{{ $idx }}][product_service]" value="{{ $lt['product_service'] ?? '' }}" placeholder="e.g. Production 01"></td>
                        <td><input type="number" step="0.01" class="lt-input lt-base num" name="sales_forecast_longterm[{{ $idx }}][base_year]" value="{{ $lt['base_year'] ?? 0 }}"></td>
                        <td><input type="number" step="0.01" class="lt-input lt-p1 num" name="sales_forecast_longterm[{{ $idx }}][proposed_year1]" value="{{ $lt['proposed_year1'] ?? 0 }}"></td>
                        <td><input type="number" step="0.01" class="lt-input lt-p2 num" name="sales_forecast_longterm[{{ $idx }}][proposed_year2]" value="{{ $lt['proposed_year2'] ?? 0 }}"></td>
                        <td><input type="number" step="0.01" class="lt-input lt-p3 num" name="sales_forecast_longterm[{{ $idx }}][proposed_year3]" value="{{ $lt['proposed_year3'] ?? 0 }}"></td>
                        <td style="text-align:center;"><button type="button" class="btn danger remove-row-btn">&times;</button></td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td>Collection (Total Sales)</td>
                    <td class="num" id="tot-lt-base">0.00</td>
                    <td class="num" id="tot-lt-p1">0.00</td>
                    <td class="num" id="tot-lt-p2">0.00</td>
                    <td class="num" id="tot-lt-p3">0.00</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn add-row" onclick="addLongtermRow()">+ Add Long-Term Product Row</button>
    </div>
</div>

<!-- 10. Action Plan & Monitoring -->
<div class="card">
    <div class="section-header">
        <h2 class="section-title"><span class="sec-num">10</span> Action Plan & Monitoring</h2>
    </div>
    <table class="sedd-table" id="action-plans-table">
        <thead>
            <tr>
                <th style="width:40%;">Task / Activity</th>
                <th style="width:25%;">Responsible Person</th>
                <th style="width:20%;">Time Frame</th>
                <th style="width:12%;">Progress / Status</th>
                <th style="width:3%;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach(old('action_plans', $plan->action_plans ?? [['task'=>'','responsible_person'=>'','time_frame'=>'','status'=>'Pending']]) as $idx => $act)
                <tr>
                    <td><input type="text" name="action_plans[{{ $idx }}][task]" value="{{ $act['task'] ?? '' }}" placeholder="Action step or milestone"></td>
                    <td><input type="text" name="action_plans[{{ $idx }}][responsible_person]" value="{{ $act['responsible_person'] ?? '' }}" placeholder="Name / Role"></td>
                    <td><input type="text" name="action_plans[{{ $idx }}][time_frame]" value="{{ $act['time_frame'] ?? '' }}" placeholder="e.g. Month 1 - 3"></td>
                    <td>
                        <select name="action_plans[{{ $idx }}][status]">
                            <option value="Pending" {{ ($act['status'] ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ ($act['status'] ?? '') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ ($act['status'] ?? '') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </td>
                    <td style="text-align:center;"><button type="button" class="btn danger remove-row-btn">&times;</button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button type="button" class="btn add-row" onclick="addRow('action-plans-table', 'action_plans', ['task', 'responsible_person', 'time_frame', 'status'], {status: ['select', ['Pending', 'In Progress', 'Completed']]})">+ Add Action Plan Row</button>
</div>

<div style="display:flex; justify-content:space-between; align-items:center; margin-top:30px;">
    <a href="{{ route('marketing-plan.index') }}" style="color:var(--mute); text-decoration:none;">Cancel</a>
    <button type="submit" class="btn success" style="padding:10px 24px; font-size:15px;">💾 {{ $isEdit ? 'Update SEDD Marketing Plan' : 'Save Marketing Plan' }}</button>
</div>
