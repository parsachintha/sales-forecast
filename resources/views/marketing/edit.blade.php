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
        <td><input type="number" step="0.01" class="m-input m7 num" name="sales_forecast_monthly[${index}][m7_12]" value="0"></td>
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
    let totM1 = 0, totM2 = 0, totM3 = 0, totM4 = 0, totM5 = 0, totM6 = 0, totM7 = 0, grandTot = 0;

    document.querySelectorAll('.monthly-row').forEach(row => {
        let m1 = parseFloat(row.querySelector('.m1')?.value || 0);
        let m2 = parseFloat(row.querySelector('.m2')?.value || 0);
        let m3 = parseFloat(row.querySelector('.m3')?.value || 0);
        let m4 = parseFloat(row.querySelector('.m4')?.value || 0);
        let m5 = parseFloat(row.querySelector('.m5')?.value || 0);
        let m6 = parseFloat(row.querySelector('.m6')?.value || 0);
        let m7 = parseFloat(row.querySelector('.m7')?.value || 0);
        let rowTot = m1 + m2 + m3 + m4 + m5 + m6 + m7;

        let rowTotCell = row.querySelector('.row-year-total');
        if (rowTotCell) rowTotCell.textContent = formatNum(rowTot);

        totM1 += m1; totM2 += m2; totM3 += m3; totM4 += m4; totM5 += m5; totM6 += m6; totM7 += m7;
        grandTot += rowTot;
    });

    const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = formatNum(val); };
    set('tot-m1', totM1); set('tot-m2', totM2); set('tot-m3', totM3);
    set('tot-m4', totM4); set('tot-m5', totM5); set('tot-m6', totM6);
    set('tot-m7', totM7); set('tot-year', grandTot);
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
