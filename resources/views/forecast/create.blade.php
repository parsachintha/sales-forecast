@extends('forecast.layout')

@section('title', 'Assign goal')

@section('content')
<p class="crumb"><a href="{{ route('forecast.index') }}">&larr; All goals</a></p>
<h1>Assign a sales income goal</h1>
<p class="mute">The annual target is split into 12 equal months. You can change individual months on the next screen.</p>

@if($errors->any())
    <div class="note bad">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('forecast.store') }}" class="form card" style="margin-top:16px;">
    @csrf

    <label>Sales rep
        <select name="rep_id" required>
            <option value="">Choose a rep...</option>
            @foreach($reps as $rep)
                <option value="{{ $rep->id }}" @selected(old('rep_id') == $rep->id)>{{ $rep->name }}</option>
            @endforeach
        </select>
    </label>

    <label>Goal title
        <input type="text" name="title" value="{{ old('title', 'Annual Sales Income Goal') }}" required>
    </label>

    <label>Goal year
        <input type="number" name="year" value="{{ old('year', 2027) }}" min="2020" max="2100" required>
    </label>

    <label>Last year's income (LKR)
        <input type="number" name="baseline_amount" value="{{ old('baseline_amount', 100000) }}" min="0">
        <span class="hint">For context only - shows the growth the goal asks for.</span>
    </label>

    <label>Annual target (LKR)
        <input type="number" name="target_amount" value="{{ old('target_amount', 360000) }}" min="1" required>
        <span class="hint">360,000 becomes 30,000 per month.</span>
    </label>

    <div><button class="btn" type="submit">Create goal</button></div>
</form>
@endsection
