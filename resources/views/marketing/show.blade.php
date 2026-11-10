@extends('marketing.layout')

@section('title', 'SEDD - ' . ($plan->business_name ?? 'Marketing Plan'))

@section('content')
<div class="no-print" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
    <a href="{{ route('marketing-plan.index') }}" style="color:var(--mute); text-decoration:none; font-weight:600;">&larr; Back to all plans</a>
    <div style="display:flex; gap:10px;">
        @auth
            @if(Auth::user()->isCompany())
                <a href="{{ route('forecast.create', ['plan_id' => $plan->id]) }}" class="btn" style="background:#059669; border-color:#047857;">
                    🎯 Allocate & Cascade Goals to Reps
                </a>
            @endif
            @if(Auth::user()->isOfficer())
                <a href="{{ route('marketing-plan.edit', $plan) }}" class="btn">✏️ Edit Plan (Officer)</a>
            @endif
        @endauth
        <button onclick="window.print()" class="btn ghost">🖨️ Print / Save as PDF</button>
    </div>
</div>

<div class="card" style="padding: 36px 42px; border: 1px solid #cbd5e1; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    <!-- Header Block -->
    <div style="text-align:center; border-bottom: 3px double #1a56db; padding-bottom: 20px; margin-bottom: 26px;">
        <div style="font-size:14px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#475569;">Government of Sri Lanka</div>
        <h1 style="font-size:24px; color:#1e3a8a; margin:6px 0 2px;">Small Enterprise Development Division (SEDD)</h1>
        <h2 style="font-size:18px; color:#1d4ed8; font-weight:600; margin:0 0 10px;">MSME Practical Marketing Plan</h2>
        <div style="font-size:13px; color:#64748b; font-style:italic;">12-Month Operational & Strategic Marketing Document</div>
    </div>

    <!-- Entrepreneur & Business Profile Table -->
    <div style="margin-bottom: 30px;">
        <table class="sedd-table" style="margin-top:0;">
            <tr>
                <th style="width:25%; background:#f8fafc;">Entrepreneur Name</th>
                <td style="width:25%;"><strong>{{ $plan->entrepreneur_name ?? '-' }}</strong></td>
                <th style="width:25%; background:#f8fafc;">Address / Pre.Lt. (DS) Division</th>
                <td style="width:25%;">{{ $plan->address_division ?? '-' }}</td>
            </tr>
            <tr>
                <th>Telephone / Mobile</th>
                <td>{{ $plan->telephone ?? '-' }}</td>
                <th>E-mail</th>
                <td>{{ $plan->email ?? '-' }}</td>
            </tr>
            <tr>
                <th>Business Name</th>
                <td><strong>{{ $plan->business_name ?? '-' }}</strong></td>
                <th>Registered Number</th>
                <td>{{ $plan->registered_number ?? '-' }}</td>
            </tr>
            <tr>
                <th>Main Production & Service</th>
                <td colspan="3">{{ $plan->main_product_service_summary ?? '-' }}</td>
            </tr>
            <tr>
                <th>Plan Period (12 Months)</th>
                <td colspan="3">
                    <strong>From:</strong> {{ $plan->plan_period_from ? \Carbon\Carbon::parse($plan->plan_period_from)->format('Y/m/d') : '20...../..../....' }}
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <strong>To:</strong> {{ $plan->plan_period_to ? \Carbon\Carbon::parse($plan->plan_period_to)->format('Y/m/d') : '20...../..../....' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- 1. Purpose & Executive Summary -->
    <div style="margin-bottom: 30px;">
        <h3 style="font-size:16px; color:#1e3a8a; border-bottom:1px solid #cbd5e1; padding-bottom:6px; margin-bottom:12px;">
            1. Purpose & Executive Summary of the Marketing Plan
        </h3>
        <div style="white-space:pre-line; color:#334155; line-height:1.6; padding: 4px 6px;">{{ $plan->purpose_executive_summary ?: 'No summary provided.' }}</div>
    </div>

    <!-- 2. Product/Service Description -->
    <div style="margin-bottom: 30px;">
        <h3 style="font-size:16px; color:#1e3a8a; border-bottom:1px solid #cbd5e1; padding-bottom:6px; margin-bottom:12px;">
            2. Product/Service Description
        </h3>
        <div style="margin-bottom:14px;">
            <strong style="color:#0f172a; font-size:14px;">2.1 Main product/service:</strong>
            <div style="white-space:pre-line; color:#334155; margin-top:4px; padding: 0 6px;">{{ $plan->main_products_services ?: 'None recorded.' }}</div>
        </div>
        <div>
            <strong style="color:#0f172a; font-size:14px;">2.2 Proposed future products/services:</strong>
            <div style="white-space:pre-line; color:#334155; margin-top:4px; padding: 0 6px;">{{ $plan->future_products_services ?: 'None recorded.' }}</div>
        </div>
    </div>

    <!-- 3. Target Market & Customers Analysis -->
    <div style="margin-bottom: 30px;">
        <h3 style="font-size:16px; color:#1e3a8a; border-bottom:1px solid #cbd5e1; padding-bottom:6px; margin-bottom:12px;">
            3. Target Market & Customers Analysis
        </h3>
        <div style="margin-bottom:14px;">
            <strong style="color:#0f172a; font-size:14px;">3.1 Target market areas:</strong>
            <div style="margin: 6px 0 0 16px;">
                <div><strong>• Areas currently covered:</strong> {{ $plan->market_areas_current ?: 'None' }}</div>
                <div style="margin-top:4px;"><strong>• Areas expected to be covered in the future:</strong> {{ $plan->market_areas_future ?: 'None' }}</div>
            </div>
        </div>
        <div>
            <strong style="color:#0f172a; font-size:14px;">3.2 Target Customer Profile:</strong>
            <table class="sedd-table" style="margin-top:8px;">
                <thead>
                    <tr>
                        <th style="width:25%;">Product / Service</th>
                        <th style="width:30%;">Targeted Area</th>
                        <th style="width:45%;">Goals / Customers (age, income, gender, institutions)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plan->target_customer_profiles ?? [] as $tcp)
                        <tr>
                            <td>{{ $tcp['product_service'] ?? '-' }}</td>
                            <td>{{ $tcp['targeted_area'] ?? '-' }}</td>
                            <td>{{ $tcp['goals_customers'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align:center; color:#94a3b8;">No customer profiles specified.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. Market and Competitor Analysis -->
    <div style="margin-bottom: 30px;">
        <h3 style="font-size:16px; color:#1e3a8a; border-bottom:1px solid #cbd5e1; padding-bottom:6px; margin-bottom:12px;">
            4. Market and Competitor Analysis (Practical Market & Competitor Analysis)
        </h3>
        <div style="margin-bottom:18px;">
            <strong style="color:#0f172a; font-size:14px;">4.1 Identifying the main competitors in the area (Competitor Assessment):</strong>
            <table class="sedd-table" style="margin-top:8px;">
                <thead>
                    <tr>
                        <th style="width:25%;">Competitor's Name / Institution</th>
                        <th style="width:25%;">Active Area</th>
                        <th style="width:25%;">Their Key Strength</th>
                        <th style="width:25%;">Their Weaknesses / Opportunities we have</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plan->competitors ?? [] as $c)
                        <tr>
                            <td><strong>{{ $c['name'] ?? '-' }}</strong></td>
                            <td>{{ $c['active_area'] ?? '-' }}</td>
                            <td>{{ $c['key_strengths'] ?? '-' }}</td>
                            <td>{{ $c['weaknesses_opportunities'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; color:#94a3b8;">No competitors identified.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            <strong style="color:#0f172a; font-size:14px;">4.2 Proposed Supply Capacity of the business:</strong>
            <table class="sedd-table" style="margin-top:8px;">
                <thead>
                    <tr>
                        <th style="width:30%;">Product / Service</th>
                        <th style="width:20%;">In Demand Nature (High / Medium / Low)</th>
                        <th style="width:25%;">Monthly Production Capacity</th>
                        <th style="width:25%;">Annual Production Capacity (units / Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plan->supply_capacities ?? [] as $sc)
                        <tr>
                            <td><strong>{{ $sc['product_service'] ?? '-' }}</strong></td>
                            <td>{{ $sc['demand_nature'] ?? '-' }}</td>
                            <td>{{ $sc['monthly_capacity'] ?? '-' }}</td>
                            <td>{{ $sc['annual_capacity'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; color:#94a3b8;">No supply capacities recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 5. Competitive Analysis (7 Ps) -->
    <div style="margin-bottom: 30px;">
        <h3 style="font-size:16px; color:#1e3a8a; border-bottom:1px solid #cbd5e1; padding-bottom:6px; margin-bottom:12px;">
            5. Competitive Analysis (Competitor Comparison - 7 Ps)
        </h3>
        <table class="sedd-table">
            <thead>
                <tr>
                    <th style="width:22%;">7 Ps Factors</th>
                    <th style="width:26%; background:#eff6ff;">Our Business</th>
                    <th style="width:26%;">Competitor 01</th>
                    <th style="width:26%;">Competitor 02</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plan->competitive_7ps ?? [] as $p)
                    <tr>
                        <td><strong>{{ $p['factor'] ?? '' }}</strong></td>
                        <td style="background:#f8faff;">{{ $p['our_business'] ?? '-' }}</td>
                        <td>{{ $p['competitor_01'] ?? '-' }}</td>
                        <td>{{ $p['competitor_02'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- 6. SWOT Analysis -->
    <div style="margin-bottom: 30px;">
        <h3 style="font-size:16px; color:#1e3a8a; border-bottom:1px solid #cbd5e1; padding-bottom:6px; margin-bottom:12px;">
            6. SWOT Analysis (Strengths, Weaknesses, Opportunities, and Threats)
        </h3>
        <table class="sedd-table" style="table-layout: fixed;">
            <thead>
                <tr>
                    <th style="width:50%; background:#ecfdf5; color:#065f46;">Strengths (Internal)</th>
                    <th style="width:50%; background:#fef2f2; color:#991b1b;">Weaknesses (Internal)</th>
                </tr>
            </thead>
            <tbody>
                <tr style="vertical-align: top;">
                    <td style="white-space: pre-line; padding: 12px; height: 100px;">{{ $plan->swot_strengths ?: 'None specified' }}</td>
                    <td style="white-space: pre-line; padding: 12px;">{{ $plan->swot_weaknesses ?: 'None specified' }}</td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <th style="background:#eff6ff; color:#1e40af;">Opportunities (External)</th>
                    <th style="background:#fffbeb; color:#92400e;">Threats (External)</th>
                </tr>
            </thead>
            <tbody>
                <tr style="vertical-align: top;">
                    <td style="white-space: pre-line; padding: 12px; height: 100px;">{{ $plan->swot_opportunities ?: 'None specified' }}</td>
                    <td style="white-space: pre-line; padding: 12px;">{{ $plan->swot_threats ?: 'None specified' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 7. Suggested Marketing Strategies (4 Ps) -->
    <div style="margin-bottom: 30px;">
        <h3 style="font-size:16px; color:#1e3a8a; border-bottom:1px solid #cbd5e1; padding-bottom:6px; margin-bottom:12px;">
            7. Suggested Marketing Strategies (4 Ps)
        </h3>
        <table class="sedd-table">
            <thead>
                <tr>
                    <th style="width:25%;">Goods and Services (Product)</th>
                    <th style="width:25%;">Price</th>
                    <th style="width:25%;">Place</th>
                    <th style="width:25%;">Promotion</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plan->strategies_4ps ?? [] as $strat)
                    <tr>
                        <td style="white-space: pre-line;">{{ $strat['product'] ?? '-' }}</td>
                        <td style="white-space: pre-line;">{{ $strat['price'] ?? '-' }}</td>
                        <td style="white-space: pre-line;">{{ $strat['place'] ?? '-' }}</td>
                        <td style="white-space: pre-line;">{{ $strat['promotion'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center; color:#94a3b8;">No strategies recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 8. Marketing Expense Budget -->
    <div style="margin-bottom: 30px;">
        <h3 style="font-size:16px; color:#1e3a8a; border-bottom:1px solid #cbd5e1; padding-bottom:6px; margin-bottom:12px;">
            8. Marketing Expense Budget
        </h3>
        <table class="sedd-table">
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
                @php
                    $tot_ex = 0; $tot_p1 = 0; $tot_p2 = 0; $tot_p3 = 0;
                @endphp
                @foreach($plan->marketing_expense_budget ?? [] as $b)
                    @php
                        $ex = (float)($b['existing_year'] ?? 0);
                        $p1 = (float)($b['proposed_year1'] ?? 0);
                        $p2 = (float)($b['proposed_year2'] ?? 0);
                        $p3 = (float)($b['proposed_year3'] ?? 0);
                        $tot_ex += $ex; $tot_p1 += $p1; $tot_p2 += $p2; $tot_p3 += $p3;
                    @endphp
                    <tr>
                        <td>{{ $b['item'] ?? '-' }}</td>
                        <td class="num">{{ number_format($ex, 2) }}</td>
                        <td class="num">{{ number_format($p1, 2) }}</td>
                        <td class="num">{{ number_format($p2, 2) }}</td>
                        <td class="num">{{ number_format($p3, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td>Collection (Total)</td>
                    <td class="num">{{ number_format($tot_ex, 2) }}</td>
                    <td class="num">{{ number_format($tot_p1, 2) }}</td>
                    <td class="num">{{ number_format($tot_p2, 2) }}</td>
                    <td class="num">{{ number_format($tot_p3, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 9. Sales Income Forecast -->
    <div style="margin-bottom: 30px;">
        <h3 style="font-size:16px; color:#1e3a8a; border-bottom:1px solid #cbd5e1; padding-bottom:6px; margin-bottom:12px;">
            9. Sales Income Forecast
        </h3>
        
        <!-- 9.1 Monthly -->
        <div style="margin-bottom: 22px;">
            <strong style="color:#0f172a; font-size:14px;">9.1 Estimated monthly sales revenue for the proposed 1st year (Rs.):</strong>
            <div style="overflow-x:auto;">
                <table class="sedd-table" style="margin-top:8px; min-width:1150px;">
                    <thead>
                        <tr>
                            <th style="min-width:150px;">Product / Service</th>
                            <th class="num" style="min-width:65px;">1 M</th>
                            <th class="num" style="min-width:65px;">2 M</th>
                            <th class="num" style="min-width:65px;">3 M</th>
                            <th class="num" style="min-width:65px;">4 M</th>
                            <th class="num" style="min-width:65px;">5 M</th>
                            <th class="num" style="min-width:65px;">6 M</th>
                            <th class="num" style="min-width:65px;">7 M</th>
                            <th class="num" style="min-width:65px;">8 M</th>
                            <th class="num" style="min-width:65px;">9 M</th>
                            <th class="num" style="min-width:65px;">10 M</th>
                            <th class="num" style="min-width:65px;">11 M</th>
                            <th class="num" style="min-width:65px;">12 M</th>
                            <th class="num" style="background:#e0e7ff; min-width:110px;">1 Year Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $m_cols = array_fill(1, 12, 0);
                            $grand_total = 0;
                        @endphp
                        @foreach($plan->sales_forecast_monthly ?? [] as $row)
                            @php
                                $legacyHalf = isset($row['m7_12']) ? (float)$row['m7_12'] / 6 : 0;
                                $row_sum = 0;
                            @endphp
                            <tr>
                                <td><strong>{{ $row['product_service'] ?? '-' }}</strong></td>
                                @for($m = 1; $m <= 12; $m++)
                                    @php
                                        $val = isset($row['m'.$m]) ? (float)$row['m'.$m] : ($m >= 7 ? $legacyHalf : 0);
                                        $row_sum += $val;
                                        $m_cols[$m] += $val;
                                    @endphp
                                    <td class="num">{{ number_format($val, 2) }}</td>
                                @endfor
                                @php $grand_total += $row_sum; @endphp
                                <td class="num" style="font-weight:700; background:#f5f7ff;">{{ number_format($row_sum, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td>Total Income (Rs.)</td>
                            @for($m = 1; $m <= 12; $m++)
                                <td class="num">{{ number_format($m_cols[$m], 2) }}</td>
                            @endfor
                            <td class="num" style="background:#e0e7ff;">{{ number_format($grand_total, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 9.2 Long-term -->
        <div>
            <strong style="color:#0f172a; font-size:14px;">9.2 Long-term (3-year) sales revenue estimate:</strong>
            <table class="sedd-table" style="margin-top:8px;">
                <thead>
                    <tr>
                        <th style="width:36%;">Product / Service</th>
                        <th class="num" style="width:16%;">Foundations Year (Rs.)</th>
                        <th class="num" style="width:16%;">Proposed 1 Year (Rs.)</th>
                        <th class="num" style="width:16%;">Proposed 2 Years (Rs.)</th>
                        <th class="num" style="width:16%;">Proposed 3 Years (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $lt_base = 0; $lt_p1 = 0; $lt_p2 = 0; $lt_p3 = 0;
                    @endphp
                    @foreach($plan->sales_forecast_longterm ?? [] as $lt)
                        @php
                            $b_base = (float)($lt['base_year'] ?? 0);
                            $b_p1 = (float)($lt['proposed_year1'] ?? 0);
                            $b_p2 = (float)($lt['proposed_year2'] ?? 0);
                            $b_p3 = (float)($lt['proposed_year3'] ?? 0);
                            $lt_base += $b_base; $lt_p1 += $b_p1; $lt_p2 += $b_p2; $lt_p3 += $b_p3;
                        @endphp
                        <tr>
                            <td><strong>{{ $lt['product_service'] ?? '-' }}</strong></td>
                            <td class="num">{{ number_format($b_base, 2) }}</td>
                            <td class="num">{{ number_format($b_p1, 2) }}</td>
                            <td class="num">{{ number_format($b_p2, 2) }}</td>
                            <td class="num">{{ number_format($b_p3, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>Collection (Total Sales)</td>
                        <td class="num">{{ number_format($lt_base, 2) }}</td>
                        <td class="num">{{ number_format($lt_p1, 2) }}</td>
                        <td class="num">{{ number_format($lt_p2, 2) }}</td>
                        <td class="num">{{ number_format($lt_p3, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 10. Action Plan & Monitoring -->
    <div style="margin-bottom: 20px;">
        <h3 style="font-size:16px; color:#1e3a8a; border-bottom:1px solid #cbd5e1; padding-bottom:6px; margin-bottom:12px;">
            10. Action Plan & Monitoring
        </h3>
        <table class="sedd-table">
            <thead>
                <tr>
                    <th style="width:40%;">Task / Activity</th>
                    <th style="width:25%;">Responsible Person</th>
                    <th style="width:20%;">Time Frame</th>
                    <th style="width:15%;">Progress / Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plan->action_plans ?? [] as $act)
                    <tr>
                        <td>{{ $act['task'] ?? '-' }}</td>
                        <td>{{ $act['responsible_person'] ?? '-' }}</td>
                        <td>{{ $act['time_frame'] ?? '-' }}</td>
                        <td>
                            <span style="display:inline-block; padding: 2px 8px; border-radius: 4px; font-size:12px; font-weight:600; background: #e0f2fe; color: #0369a1;">
                                {{ $act['status'] ?? 'Pending' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center; color:#94a3b8;">No action plans recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Signature Sign-off footer for official documents -->
    <div style="margin-top: 50px; padding-top: 30px; border-top: 1px dashed #cbd5e1; display:flex; justify-content:space-between;">
        <div style="text-align:center; width:220px;">
            <div style="border-bottom: 1px solid #94a3b8; height: 40px; margin-bottom: 6px;"></div>
            <div style="font-size:12px; font-weight:600;">Entrepreneur's Signature</div>
            <div style="font-size:11px; color:#64748b;">Date: ........................</div>
        </div>
        <div style="text-align:center; width:220px;">
            <div style="border-bottom: 1px solid #94a3b8; height: 40px; margin-bottom: 6px;"></div>
            <div style="font-size:12px; font-weight:600;">SEDD Enterprise Development Officer</div>
            <div style="font-size:11px; color:#64748b;">Signature & Official Stamp</div>
        </div>
    </div>
</div>
@endsection
