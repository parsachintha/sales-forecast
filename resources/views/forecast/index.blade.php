@extends('forecast.layout')

@section('title', 'Income forecast')

@section('content')
<div class="head">
    <div>
        <h1>Sales income forecast</h1>
        <div class="mute">Goals the shop manager has assigned to sales reps.</div>
    </div>
    <a class="btn" href="{{ route('forecast.create') }}">+ Assign new goal</a>
</div>

@if(session('status'))
    <div class="note ok">{{ session('status') }}</div>
@endif

<div class="card" style="margin-top:18px; padding:0; overflow:hidden;">
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
            <tr><td colspan="7" class="mute">No goals yet. Assign the first one.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
