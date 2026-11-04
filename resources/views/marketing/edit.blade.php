@extends('marketing.layout')

@section('title', 'Edit - ' . ($plan->business_name ?? 'Marketing Plan'))

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('marketing-plan.show', $plan) }}" style="color:var(--mute); text-decoration:none; font-weight:600;">&larr; Back to view document</a>
    <h1 style="font-size:24px; margin:8px 0 4px; color:#1e3a8a;">Edit SEDD MSME Marketing Plan</h1>
    <div style="color:var(--mute);">Updating marketing plan for <strong>{{ $plan->business_name }}</strong></div>
</div>

<form action="{{ route('marketing-plan.update', $plan) }}" method="POST" id="marketingPlanForm">
    @csrf
    @method('PUT')
    @include('marketing.form-fields')
</form>
@endsection

@section('scripts')
<script>
// Dynamic row addition & auto-calculation helpers
function addSwotPoint(category) {
    const container = document.getElementById(`swot-${category}-container`);
    const div = document.createElement('div');
    div.className = 'swot-row';
    div.style.cssText = 'display:flex; gap:6px; align-items:center;';

    const colors = {
        strengths: '#16a34a',
        weaknesses: '#dc2626',
        opportunities: '#2563eb',
        threats: '#d97706'
    };
    const color = colors[category] || '#16a34a';

    div.innerHTML = `
        <span style="color:${color}; font-weight:700;">•</span>
        <input type="text" name="swot_${category}_list[]" value="" placeholder="Enter ${category.slice(0, -1)}..." style="flex:1;">
        <button type="button" class="btn danger remove-swot-btn" style="padding:2px 6px; font-size:11px;">&times;</button>
    `;
    container.appendChild(div);
    div.querySelector('input').focus();
}

function addRow(tableId, arrayName, fields, types = {}, isTextarea = false) {
    const table = document.getElementById(tableId);
    const tbody = table.querySelector('tbody');
    const index = Date.now();
    const tr = document.createElement('tr');

    let html = '';
    fields.forEach(f => {
        if (types[f] && types[f][0] === 'select') {
            let options = types[f][1].map(opt => `<option value="${opt}">${opt}</option>`).join('');
            html += `<td><select name="${arrayName}[${index}][${f}]">${options}</select></td>`;
        } else if (isTextarea) {
            html += `<td><textarea rows="3" name="${arrayName}[${index}][${f}]"></textarea></td>`;
        } else {
            html += `<td><input type="text" name="${arrayName}[${index}][${f}]"></td>`;
        }
    });

    html += `<td style="text-align:center;"><button type="button" class="btn danger remove-row-btn">&times;</button></td>`;
    tr.innerHTML = html;
    tbody.appendChild(tr);
}

function addMonthlyRow() {
    const table = document.getElementById('forecast-monthly-table');
    const tbody = table.querySelector('tbody');
    const totalRow = tbody.querySelector('.total-row');
    const index = Date.now();
    const tr = document.createElement('tr');
    tr.className = 'monthly-row';

    tr.innerHTML = `
        <td><input type="text" name="sales_forecast_monthly[${index}][product_service]" placeholder="Product Name"></td>
        <td><input type="number" step="0.01" class="m-input m1 num" name="sales_forecast_monthly[${index}][m1]" value="0"></td>
        <td><input type="number" step="0.01" class="m-input m2 num" name="sales_forecast_monthly[${index}][m2]" value="0"></td>
        <td><input type="number" step="0.01" class="m-input m3 num" name="sales_forecast_monthly[${index}][m3]" value="0"></td>
        <td><input type="number" step="0.01" class="m-input m4 num" name="sales_forecast_monthly[${index}][m4]" value="0"></td>
        <td><input type="number" step="0.01" class="m-input m5 num" name="sales_forecast_monthly[${index}][m5]" value="0"></td>
        <td><input type="number" step="0.01" class="m-input m6 num" name="sales_forecast_monthly[${index}][m6]" value="0"></td>
        <td><input type="number" step="0.01" class="m-input m7 num" name="sales_forecast_monthly[${index}][m7]" value="0"></td>
        <td><input type="number" step="0.01" class="m-input m8 num" name="sales_forecast_monthly[${index}][m8]" value="0"></td>
        <td><input type="number" step="0.01" class="m-input m9 num" name="sales_forecast_monthly[${index}][m9]" value="0"></td>
        <td><input type="number" step="0.01" class="m-input m10 num" name="sales_forecast_monthly[${index}][m10]" value="0"></td>
        <td><input type="number" step="0.01" class="m-input m11 num" name="sales_forecast_monthly[${index}][m11]" value="0"></td>
        <td><input type="number" step="0.01" class="m-input m12 num" name="sales_forecast_monthly[${index}][m12]" value="0"></td>
        <td class="num row-year-total" style="font-weight:700; background:#f4f6ff;">0.00</td>
        <td style="text-align:center;"><button type="button" class="btn danger remove-row-btn">&times;</button></td>
    `;

    tbody.insertBefore(tr, totalRow);
    calcAll();
}

function addLongtermRow() {
    const table = document.getElementById('forecast-longterm-table');
    const tbody = table.querySelector('tbody');
    const totalRow = tbody.querySelector('.total-row');
    const index = Date.now();
    const tr = document.createElement('tr');
    tr.className = 'lt-row';

    tr.innerHTML = `
        <td><input type="text" name="sales_forecast_longterm[${index}][product_service]" placeholder="Product Name"></td>
        <td><input type="number" step="0.01" class="lt-input lt-base num" name="sales_forecast_longterm[${index}][base_year]" value="0"></td>
        <td><input type="number" step="0.01" class="lt-input lt-p1 num" name="sales_forecast_longterm[${index}][proposed_year1]" value="0"></td>
        <td><input type="number" step="0.01" class="lt-input lt-p2 num" name="sales_forecast_longterm[${index}][proposed_year2]" value="0"></td>
        <td><input type="number" step="0.01" class="lt-input lt-p3 num" name="sales_forecast_longterm[${index}][proposed_year3]" value="0"></td>
        <td style="text-align:center;"><button type="button" class="btn danger remove-row-btn">&times;</button></td>
    `;

    tbody.insertBefore(tr, totalRow);
    calcAll();
}

// Event delegation for removal
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('remove-row-btn')) {
        const tr = e.target.closest('tr');
        if (tr) {
            tr.remove();
            calcAll();
        }
    }
    if (e.target && e.target.classList.contains('remove-swot-btn')) {
        const row = e.target.closest('.swot-row');
        const container = row.parentElement;
        if (container && container.querySelectorAll('.swot-row').length > 1) {
            row.remove();
        } else if (row) {
            row.querySelector('input').value = '';
        }
    }
});

// Auto-calculation functions
function formatNum(num) {
    return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function calcBudget() {
    let totEx = 0, totP1 = 0, totP2 = 0, totP3 = 0;
    document.querySelectorAll('.bg-ex').forEach(el => totEx += parseFloat(el.value || 0));
    document.querySelectorAll('.bg-p1').forEach(el => totP1 += parseFloat(el.value || 0));
    document.querySelectorAll('.bg-p2').forEach(el => totP2 += parseFloat(el.value || 0));
    document.querySelectorAll('.bg-p3').forEach(el => totP3 += parseFloat(el.value || 0));

    const exEl = document.getElementById('total-bg-ex');
    if (exEl) exEl.textContent = formatNum(totEx);
    const p1El = document.getElementById('total-bg-p1');
    if (p1El) p1El.textContent = formatNum(totP1);
    const p2El = document.getElementById('total-bg-p2');
    if (p2El) p2El.textContent = formatNum(totP2);
    const p3El = document.getElementById('total-bg-p3');
    if (p3El) p3El.textContent = formatNum(totP3);
}

function calcMonthly() {
    let totM = Array(13).fill(0);
    let grandTot = 0;

    document.querySelectorAll('.monthly-row').forEach(row => {
        let rowTot = 0;
        for (let m = 1; m <= 12; m++) {
            let val = parseFloat(row.querySelector(`.m${m}`)?.value || 0);
            rowTot += val;
            totM[m] += val;
        }

        let rowTotCell = row.querySelector('.row-year-total');
        if (rowTotCell) rowTotCell.textContent = formatNum(rowTot);

        grandTot += rowTot;
    });

    const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = formatNum(val); };
    for (let m = 1; m <= 12; m++) {
        set(`tot-m${m}`, totM[m]);
    }
    set('tot-year', grandTot);
}

function calcLongterm() {
    let totBase = 0, totP1 = 0, totP2 = 0, totP3 = 0;
    document.querySelectorAll('.lt-base').forEach(el => totBase += parseFloat(el.value || 0));
    document.querySelectorAll('.lt-p1').forEach(el => totP1 += parseFloat(el.value || 0));
    document.querySelectorAll('.lt-p2').forEach(el => totP2 += parseFloat(el.value || 0));
    document.querySelectorAll('.lt-p3').forEach(el => totP3 += parseFloat(el.value || 0));

    const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = formatNum(val); };
    set('tot-lt-base', totBase); set('tot-lt-p1', totP1); set('tot-lt-p2', totP2); set('tot-lt-p3', totP3);
}

function calcAll() {
    calcBudget();
    calcMonthly();
    calcLongterm();
}

document.addEventListener('input', function(e) {
    if (e.target && (e.target.classList.contains('budget-input') || e.target.classList.contains('m-input') || e.target.classList.contains('lt-input'))) {
        calcAll();
    }
});

// Run initial calculation on page load
document.addEventListener('DOMContentLoaded', calcAll);
</script>
@endsection
