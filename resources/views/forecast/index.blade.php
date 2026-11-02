@extends('forecast.layout')

@section('title', 'Sales Forecast & Goals')

@section('content')
<div class="head">
    <div>
        <h1 style="font-size:24px; margin:0 0 6px;">Sales Income Forecast & Goals</h1>
        <div class="mute">Company product-wise goals and assigned rep targets.</div>
    </div>
    <a class="btn" href="{{ route('forecast.create') }}">+ Set New Goal / Cascade</a>
</div>

@if(session('status'))
    <div class="note ok">{{ session('status') }}</div>
@endif

<!-- Company Master Goals Section -->
@if(isset($companyGoals) && count($companyGoals) > 0)
    <div style="margin-top:24px;">
        <h3 style="font-size:16px; color:#1e3a8a; margin:0 0 10px;">🏢 Company Product-Wise Master Goals</h3>
        <div class="card" style="padding:0; overflow:hidden;">
            <table>
                <thead>
                    <tr>
                        <th>Goal Title</th>
                        <th>Year</th>
                        <th>Products / Services</th>
                        <th class="num">Annual Goal (LKR)</th>
                        <th>Assigned Reps</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companyGoals as $cg)
                        <tr>
                            <td><strong>{{ $cg->title }}</strong></td>
                            <td>{{ $cg->year }}</td>
                            <td>
                                @if(is_array($cg->products))
                                    @foreach($cg->products as $cp)
                                        <span style="display:inline-block; background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe; font-size:12px; font-weight:600; padding:2px 7px; border-radius:4px; margin-right:4px;">
                                            {{ $cp['name'] }}: LKR {{ number_format($cp['annual_target']) }}
                                        </span>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                            <td class="num" style="font-weight:700;">{{ number_format($cg->total_annual_target) }}</td>
                            <td>
                                <span style="background:#f1f5f9; padding:3px 8px; border-radius:4px; font-size:12px; font-weight:600;">
                                    {{ count($cg->assigned_rep_ids ?? []) }} Reps Cascaded
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Sales Rep Assigned Targets Section -->
<div style="margin-top:28px;">
    <h3 style="font-size:16px; color:#1e3a8a; margin:0 0 10px;">👤 Assigned Sales Rep Goals (Monthly Tracking)</h3>
    <div class="card" style="padding:0; overflow:hidden;">
        <table>
            <thead>
            <tr>
                <th>Rep</th>
                <th>Goal</th>
                <th>Year</th>
                <th class="num">Annual target (LKR)</th>
                <th class="num">Achieved (LKR)</th>
                <th class="num">Progress</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($goals as $goal)
                @php $pct = $goal->target_amount > 0 ? min(100, (int) floor($goal->achieved * 100 / $goal->target_amount)) : 0; @endphp
                <tr>
                    <td><strong>{{ $goal->rep->name }}</strong></td>
                    <td>{{ $goal->title }}</td>
                    <td>{{ $goal->year }}</td>
                    <td class="num">{{ number_format($goal->target_amount) }}</td>
                    <td class="num">{{ number_format($goal->achieved) }}</td>
                    <td class="num">{{ $pct }}%</td>
                    <td class="num"><a class="btn ghost" href="{{ route('forecast.show', $goal) }}">Open forecast</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="mute" style="text-align:center; padding:24px;">No goals yet. Click "+ Set New Goal / Cascade" to create one.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
