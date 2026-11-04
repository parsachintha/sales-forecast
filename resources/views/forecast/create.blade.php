@extends('forecast.layout')

@section('title', 'Set Sales Goals')

@section('content')
<p class="crumb"><a href="{{ route('forecast.index') }}">&larr; Back to all goals</a></p>

<div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom: 20px;">
    <div>
        <h1 style="font-size:24px; margin:0 0 6px;">Set Sales Income Goal</h1>
        <div style="color:var(--mute); font-size:14.5px;">Establish company goals product-wise and cascade them to sales reps.</div>
    </div>
    <div style="display:flex; gap:8px; background:#e2e8f0; padding:4px; border-radius:8px;">
        <button type="button" id="tabCompanyBtn" class="btn" style="padding:6px 14px; font-size:13px;" onclick="switchMode('company')">🏢 Company Goal (Cascade to Reps)</button>
        <button type="button" id="tabSingleBtn" class="btn ghost" style="padding:6px 14px; font-size:13px;" onclick="switchMode('single')">👤 Single Rep Goal</button>
    </div>
</div>

@if($errors->any())
    <div class="note bad">{{ $errors->first() }}</div>
@endif

<!-- MODE 1: COMPANY GOAL & PRODUCT-WISE CASCADE -->
<div id="companyGoalSection">
    <form method="POST" action="{{ route('forecast.store') }}" id="companyForm">
        @csrf
        <input type="hidden" name="goal_mode" value="company">

        <!-- Top Overview Card -->
        <div class="card" style="margin-bottom:20px;">
            <h3 style="margin:0 0 14px; font-size:16px; color:#1e3a8a;">1. Company Target Overview & Rep Assignment</h3>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:16px;">
                <div>
                    <label style="font-weight:600; font-size:13px; display:block; margin-bottom:5px;">Goal Title *</label>
                    <input type="text" name="company_title" value="{{ old('company_title', '2027 Company Annual Sales Goal') }}" required>
                </div>
                <div>
                    <label style="font-weight:600; font-size:13px; display:block; margin-bottom:5px;">Target Year *</label>
                    <input type="number" name="year" id="companyYear" value="{{ old('year', 2027) }}" min="2020" max="2100" required>
                </div>
                <div>
                    <label style="font-weight:700; font-size:13px; display:block; margin-bottom:5px; color:#1e40af;">
                        🏢 Total Company Annual Goal (LKR)
                    </label>
                    <div style="display:flex; gap:6px;">
                        <input type="number" id="companyTotalAnnualInput" value="400000" min="0" step="1" style="font-weight:700; font-size:15px; border-color:#2563eb; background:#f0f7ff;" placeholder="e.g. 400000">
                        <button type="button" class="btn" style="padding:6px 12px; font-size:12px; white-space:nowrap;" onclick="applyTotalCompanyGoal()" title="Distribute this total equally across products">Split Across Products</button>
                    </div>
                    <span style="font-size:11px; color:var(--mute);">You can enter company target here or set it product-wise below.</span>
                </div>
                <div>
                    <label style="font-weight:600; font-size:13px; display:block; margin-bottom:5px;">Import from SEDD Marketing Plan</label>
                    <select name="marketing_plan_id" id="planSelect" onchange="loadPlanData(this.value)">
                        <option value="">-- Manual Entry or Select Plan --</option>
                        @foreach($marketingPlans as $mp)
                            <option value="{{ $mp->id }}" @selected(optional($selectedPlan)->id == $mp->id)>
                                {{ $mp->business_name }} ({{ $mp->plan_period_from ? \Carbon\Carbon::parse($mp->plan_period_from)->format('Y') : 'Plan' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Rep Selection -->
            <div style="margin-top:16px; border-top:1px solid #e2e8f0; padding-top:14px;">
                <label style="font-weight:600; font-size:13px; display:block; margin-bottom:8px;">
                    Select Sales Reps to divide the targets among:
                    <span style="font-weight:normal; color:var(--mute); font-size:12px;">(<span id="selectedRepCount">{{ count($reps) }}</span> selected)</span>
                </label>
                <div style="display:flex; flex-wrap:wrap; gap:12px;">
                    @foreach($reps as $rep)
                        <label style="display:inline-flex; align-items:center; gap:6px; background:#f8fafc; border:1px solid #cbd5e1; padding:6px 12px; border-radius:6px; cursor:pointer; font-size:13px;">
                            <input type="checkbox" name="rep_ids[]" value="{{ $rep->id }}" class="rep-checkbox" checked onchange="updateCalculations()">
                            <strong>{{ $rep->name }}</strong>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product-wise Targets Card -->
        <div class="card" style="margin-bottom:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <div>
                    <h3 style="margin:0 0 4px; font-size:16px; color:#1e3a8a;">2. Product/Service Annual & Monthly Breakdown (Section 9.1 Reference)</h3>
                    <div style="color:var(--mute); font-size:13px;">
                        Set annual target for each product. When you allocate amounts to specific months, the remaining unallocated balance is shown in real-time.
                    </div>
                </div>
                <button type="button" class="btn add-row" onclick="addProductBlock()">+ Add Another Product</button>
            </div>

            <div id="productBlocksContainer">
                <!-- Dynamically populated product cards -->
            </div>

            <!-- Overall Summary Banner -->
            <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:16px; margin-top:20px;">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;">
                    <div>
                        <div style="font-size:12px; text-transform:uppercase; font-weight:700; color:#1e40af;">Total Company Annual Goal</div>
                        <div style="font-size:24px; font-weight:800; color:#1e3a8a;" id="summaryTotalAnnual">Rs. 0.00</div>
                    </div>
                    <div>
                        <div style="font-size:12px; text-transform:uppercase; font-weight:700; color:#1e40af;">Monthly Company Target</div>
                        <div style="font-size:20px; font-weight:700; color:#1e3a8a;" id="summaryMonthlyAvg">Rs. 0.00 / mo</div>
                    </div>
                    <div>
                        <div style="font-size:12px; text-transform:uppercase; font-weight:700; color:#047857;">Per Sales Rep (Across <span id="summaryRepCountLabel">3</span> Reps)</div>
                        <div style="font-size:20px; font-weight:700; color:#047857;" id="summaryPerRepMonth">Rs. 0.00 / rep / mo</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rep Share Preview Card -->
        <div class="card" style="margin-bottom:24px;">
            <h3 style="margin:0 0 10px; font-size:16px; color:#1e3a8a;">3. Preview of Rep Monthly Targets (Cascaded)</h3>
            <p style="font-size:13px; color:var(--mute); margin:0 0 12px;">This table shows the exact monthly target that will be assigned to each sales rep.</p>
            <div style="overflow-x:auto;">
                <table class="sedd-table" id="repPreviewTable">
                    <thead>
                        <tr>
                            <th>Sales Rep</th>
                            <th class="num">M1</th>
                            <th class="num">M2</th>
                            <th class="num">M3</th>
                            <th class="num">M4</th>
                            <th class="num">M5</th>
                            <th class="num">M6</th>
                            <th class="num">M7</th>
                            <th class="num">M8</th>
                            <th class="num">M9</th>
                            <th class="num">M10</th>
                            <th class="num">M11</th>
                            <th class="num">M12</th>
                            <th class="num" style="background:#e0e7ff; font-weight:700;">Annual Total</th>
                        </tr>
                    </thead>
                    <tbody id="repPreviewTbody">
                        <!-- Filled by JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:12px;">
            <a href="{{ route('forecast.index') }}" class="btn ghost">Cancel</a>
            <button type="submit" class="btn success" style="padding:10px 24px; font-size:15px;" id="submitCompanyBtn">
                🚀 Create & Cascade Goals to Reps
            </button>
        </div>
    </form>
</div>

<!-- MODE 2: SINGLE REP GOAL (ORIGINAL) -->
<div id="singleRepSection" style="display:none;">
    <form method="POST" action="{{ route('forecast.store') }}" class="form card">
        @csrf
        <input type="hidden" name="goal_mode" value="single">

        <label>Sales Rep
            <select name="rep_id">
                <option value="">Choose a rep...</option>
                @foreach($reps as $rep)
                    <option value="{{ $rep->id }}" @selected(old('rep_id') == $rep->id)>{{ $rep->name }}</option>
                @endforeach
            </select>
        </label>

        <label>Goal Title
            <input type="text" name="title" value="{{ old('title', 'Annual Sales Income Goal') }}">
        </label>

        <label>Goal Year
            <input type="number" name="year" value="{{ old('year', 2027) }}" min="2020" max="2100">
        </label>

        <label>Last Year's Income (LKR)
            <input type="number" name="baseline_amount" value="{{ old('baseline_amount', 100000) }}" min="0">
            <span class="hint">For context only - shows historical benchmark.</span>
        </label>

        <label>Annual Target (LKR)
            <input type="number" name="target_amount" value="{{ old('target_amount', 360000) }}" min="1">
            <span class="hint">E.g., 360,000 becomes 30,000 per month.</span>
        </label>

        <div><button class="btn" type="submit">Create Single Goal</button></div>
    </form>
</div>
@endsection

@section('scripts')
<script>
// JSON representation of plans for quick loading
const marketingPlansData = {
    @foreach($marketingPlans as $mp)
        "{{ $mp->id }}": @json($mp->sales_forecast_monthly ?? []),
    @endforeach
};

let products = [
    { name: "Product A", annual_target: 160000, months: Array(13).fill(0) },
    { name: "Product B", annual_target: 240000, months: Array(13).fill(0) }
];

function switchMode(mode) {
    if (mode === 'company') {
        document.getElementById('companyGoalSection').style.display = 'block';
        document.getElementById('singleRepSection').style.display = 'none';
        document.getElementById('tabCompanyBtn').className = 'btn';
        document.getElementById('tabSingleBtn').className = 'btn ghost';
    } else {
        document.getElementById('companyGoalSection').style.display = 'none';
        document.getElementById('singleRepSection').style.display = 'block';
        document.getElementById('tabCompanyBtn').className = 'btn ghost';
        document.getElementById('tabSingleBtn').className = 'btn';
    }
}

function formatNum(val) {
    return Number(val).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function loadPlanData(planId) {
    if (!planId || !marketingPlansData[planId]) return;
    const items = marketingPlansData[planId];
    if (items && items.length > 0) {
        products = [];
        items.forEach(item => {
            let mArr = [0];
            let annual = 0;
            let legacyHalf = item.m7_12 ? (parseFloat(item.m7_12) / 6) : 0;

            for (let m = 1; m <= 12; m++) {
                let val = item['m' + m] !== undefined ? parseFloat(item['m' + m] || 0) : (m >= 7 ? legacyHalf : 0);
                mArr.push(val);
                annual += val;
            }

            products.push({
                name: item.product_service || 'Product',
                annual_target: annual,
                months: mArr
            });
        });
        renderProductBlocks();
    }
}

function addProductBlock() {
    products.push({
        name: `Product ${String.fromCharCode(65 + products.length)}`,
        annual_target: 120000,
        months: Array(13).fill(0)
    });
    renderProductBlocks();
}

function removeProduct(idx) {
    if (products.length <= 1) {
        alert("At least one product is required.");
        return;
    }
    products.splice(idx, 1);
    renderProductBlocks();
}

function divideProductEvenly(pIdx) {
    const p = products[pIdx];
    const annual = parseFloat(p.annual_target) || 0;
    const monthly = Math.floor(annual / 12);
    const remainder = annual - (monthly * 12);

    for (let m = 1; m <= 12; m++) {
        p.months[m] = monthly + (m <= remainder ? 1 : 0);
    }
    renderProductBlocks();
}

function onAnnualChange(pIdx, val) {
    products[pIdx].annual_target = parseFloat(val) || 0;
    updateSingleProductRemaining(pIdx);
    updateCalculations();
}

function applyTotalCompanyGoal() {
    const input = document.getElementById('companyTotalAnnualInput');
    const total = parseFloat(input.value) || 0;
    if (total <= 0) {
        alert("Please enter a valid company annual target greater than 0.");
        return;
    }
    if (products.length === 0) {
        addProductBlock();
    }
    const share = Math.floor(total / products.length);
    const remainder = total - (share * products.length);

    products.forEach((p, idx) => {
        p.annual_target = share + (idx < remainder ? 1 : 0);
        divideProductEvenly(idx);
    });

    renderProductBlocks();
}

function onMonthChange(pIdx, month, val) {
    products[pIdx].months[month] = parseFloat(val) || 0;
    updateSingleProductRemaining(pIdx);
    updateCalculations();
}

function updateSingleProductRemaining(pIdx) {
    const p = products[pIdx];
    let allocated = 0;
    for (let m = 1; m <= 12; m++) {
        allocated += parseFloat(p.months[m]) || 0;
    }
    const annual = parseFloat(p.annual_target) || 0;
    const remaining = annual - allocated;

    const remainingEl = document.getElementById(`remaining-badge-${pIdx}`);
    const divideBtn = document.getElementById(`divide-remainder-btn-${pIdx}`);

    if (remainingEl) {
        if (remaining === 0) {
            remainingEl.innerHTML = `✅ Fully Allocated (${formatNum(annual)} LKR)`;
            remainingEl.style.color = '#15803d';
            remainingEl.style.backgroundColor = '#f0fdf4';
            remainingEl.style.borderColor = '#bbf7d0';
            if (divideBtn) divideBtn.style.display = 'none';
        } else if (remaining > 0) {
            remainingEl.innerHTML = `⚠️ Remaining to divide: <strong>${formatNum(remaining)} LKR</strong>`;
            remainingEl.style.color = '#b45309';
            remainingEl.style.backgroundColor = '#fffbeb';
            remainingEl.style.borderColor = '#fde68a';
            if (divideBtn) {
                divideBtn.style.display = 'inline-flex';
                divideBtn.textContent = `Divide remaining ${formatNum(remaining)} LKR across remaining months`;
            }
        } else {
            remainingEl.innerHTML = `❌ Over-allocated by <strong>${formatNum(Math.abs(remaining))} LKR</strong>`;
            remainingEl.style.color = '#b91c1c';
            remainingEl.style.backgroundColor = '#fef2f2';
            remainingEl.style.borderColor = '#fecaca';
            if (divideBtn) divideBtn.style.display = 'none';
        }
    }
}

function divideRemainder(pIdx) {
    const p = products[pIdx];
    let allocated = 0;
    let emptyMonths = [];

    for (let m = 1; m <= 12; m++) {
        let val = parseFloat(p.months[m]) || 0;
        if (val > 0) {
            allocated += val;
        } else {
            emptyMonths.push(m);
        }
    }

    const annual = parseFloat(p.annual_target) || 0;
    const remaining = annual - allocated;

    if (remaining <= 0) return;

    if (emptyMonths.length === 0) {
        // If no empty months, divide across all 12
        divideProductEvenly(pIdx);
        return;
    }

    const share = Math.floor(remaining / emptyMonths.length);
    const rem = remaining - (share * emptyMonths.length);

    emptyMonths.forEach((m, i) => {
        p.months[m] = share + (i < rem ? 1 : 0);
    });

    renderProductBlocks();
}

function renderProductBlocks() {
    const container = document.getElementById('productBlocksContainer');
    container.innerHTML = '';

    products.forEach((p, pIdx) => {
        let allocated = 0;
        for (let m = 1; m <= 12; m++) allocated += (parseFloat(p.months[m]) || 0);
        let annual = parseFloat(p.annual_target) || 0;
        let remaining = annual - allocated;

        let div = document.createElement('div');
        div.className = 'card';
        div.style.background = '#ffffff';
        div.style.border = '1px solid #cbd5e1';
        div.style.padding = '18px';
        div.style.marginBottom = '16px';

        div.innerHTML = `
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; flex-wrap:wrap; gap:10px;">
                <div style="display:flex; align-items:center; gap:10px; flex:1; min-width:280px;">
                    <span style="background:#2563eb; color:#fff; font-size:12px; font-weight:700; padding:2px 8px; border-radius:4px;">#${pIdx+1}</span>
                    <input type="text" name="products[${pIdx}][name]" value="${p.name}" placeholder="Product/Service Name" style="font-weight:700; font-size:15px; max-width:320px;" onchange="products[${pIdx}].name=this.value; updateCalculations();" required>
                </div>
                <div style="display:flex; align-items:center; gap:14px;">
                    <div style="display:flex; align-items:center; gap:6px;">
                        <label style="font-size:13px; font-weight:600; white-space:nowrap;">Annual Goal (LKR):</label>
                        <input type="number" name="products[${pIdx}][annual_target]" value="${annual}" min="0" step="1" style="width:140px; font-weight:700; text-align:right;" oninput="onAnnualChange(${pIdx}, this.value)" required>
                    </div>
                    <button type="button" class="btn ghost" style="padding:4px 8px; font-size:12px;" onclick="divideProductEvenly(${pIdx})" title="Auto-divide annual goal into 12 equal months">➗ Split 12 Equal</button>
                    <button type="button" class="btn danger" style="padding:4px 8px;" onclick="removeProduct(${pIdx})" title="Delete this product">&times;</button>
                </div>
            </div>

            <!-- Remaining Notification Banner -->
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; padding:8px 14px; border-radius:6px; font-size:13px; font-weight:600; border:1px solid #cbd5e1;" id="remaining-badge-${pIdx}">
                <!-- filled by updateSingleProductRemaining -->
            </div>
            <div style="margin-bottom:10px;">
                <button type="button" id="divide-remainder-btn-${pIdx}" class="btn" style="background:#d97706; border-color:#b45309; font-size:12px; padding:5px 12px; display:none;" onclick="divideRemainder(${pIdx})"></button>
            </div>

            <!-- Month 1 to 12 Inputs -->
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(88px, 1fr)); gap:6px;">
                @php
                    $monthNames = [1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'May', 6=>'Jun', 7=>'Jul', 8=>'Aug', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dec'];
                @endphp
                ${[
                    {m:1, name:'Jan'}, {m:2, name:'Feb'}, {m:3, name:'Mar'}, {m:4, name:'Apr'},
                    {m:5, name:'May'}, {m:6, name:'Jun'}, {m:7, name:'Jul'}, {m:8, name:'Aug'},
                    {m:9, name:'Sep'}, {m:10, name:'Oct'}, {m:11, name:'Nov'}, {m:12, name:'Dec'}
                ].map(item => `
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:6px 4px; text-align:center;">
                        <span style="font-size:11px; font-weight:700; color:#475569; display:block;">${item.name} (M${item.m})</span>
                        <input type="number" step="1" min="0" name="products[${pIdx}][months][${item.m}]" value="${p.months[item.m] || 0}" style="padding:4px 4px; font-size:12.5px; text-align:right; font-weight:600;" oninput="onMonthChange(${pIdx}, ${item.m}, this.value)">
                    </div>
                `).join('')}
            </div>
        `;

        container.appendChild(div);
        updateSingleProductRemaining(pIdx);
    });

    updateCalculations();
}

function updateCalculations() {
    let repCheckboxes = document.querySelectorAll('.rep-checkbox:checked');
    let repCount = repCheckboxes.length;
    document.getElementById('selectedRepCount').textContent = repCount;
    document.getElementById('summaryRepCountLabel').textContent = repCount;

    let totalAnnual = 0;
    let monthlyTotals = Array(13).fill(0);

    products.forEach(p => {
        totalAnnual += parseFloat(p.annual_target) || 0;
        for (let m = 1; m <= 12; m++) {
            monthlyTotals[m] += (parseFloat(p.months[m]) || 0);
        }
    });

    let avgMonthly = totalAnnual / 12;
    let perRepMonthly = repCount > 0 ? (avgMonthly / repCount) : 0;

    const companyTotalInput = document.getElementById('companyTotalAnnualInput');
    if (companyTotalInput && document.activeElement !== companyTotalInput) {
        companyTotalInput.value = Math.round(totalAnnual);
    }

    document.getElementById('summaryTotalAnnual').textContent = `Rs. ${formatNum(totalAnnual)}`;
    document.getElementById('summaryMonthlyAvg').textContent = `Rs. ${formatNum(avgMonthly)} / mo`;
    document.getElementById('summaryPerRepMonth').textContent = `Rs. ${formatNum(perRepMonthly)} / rep / mo`;

    // Render Rep Preview Table
    const tbody = document.getElementById('repPreviewTbody');
    tbody.innerHTML = '';

    if (repCount === 0) {
        tbody.innerHTML = `<tr><td colspan="14" style="text-align:center; color:#dc2626; padding:16px;">⚠️ Please select at least one sales rep above.</td></tr>`;
        document.getElementById('submitCompanyBtn').disabled = true;
        return;
    } else {
        document.getElementById('submitCompanyBtn').disabled = false;
    }

    repCheckboxes.forEach(cb => {
        let repName = cb.closest('label').innerText.trim();
        let tr = document.createElement('tr');

        let repAnnual = 0;
        let cellsHtml = `<td><strong>${repName}</strong></td>`;

        for (let m = 1; m <= 12; m++) {
            let mTotal = monthlyTotals[m];
            let repShare = Math.round(mTotal / repCount);
            repAnnual += repShare;
            cellsHtml += `<td class="num">${formatNum(repShare)}</td>`;
        }

        cellsHtml += `<td class="num" style="background:#e0e7ff; font-weight:700;">${formatNum(repAnnual)}</td>`;
        tr.innerHTML = cellsHtml;
        tbody.appendChild(tr);
    });
}

// Initial setup
document.addEventListener('DOMContentLoaded', function() {
    renderProductBlocks();

    // If preselected plan was passed, load it
    @if($selectedPlan)
        loadPlanData("{{ $selectedPlan->id }}");
    @endif
});
</script>
@endsection
