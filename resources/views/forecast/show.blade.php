@extends('forecast.layout')

@section('title', $goal->title)

@section('content')
<p class="crumb"><a href="{{ route('forecast.index') }}">&larr; All goals</a></p>

<div class="head">
    <div>
        <h1>{{ $goal->title }}</h1>
        <div class="mute">Rep: <strong>{{ $goal->rep->name }}</strong> &middot; Year {{ $goal->year }}</div>
    </div>

    {{--  helper: pretend today is another date, e.g. 2027-01-15 --}}
    <form method="GET" action="{{ route('forecast.show', $goal) }}" class="asof">
        <label>Simulate today's date
            <input type="date" name="as_of" value="{{ $asOf->toDateString() }}">
        </label>
        <button class="btn ghost" type="submit">Apply</button>
    </form>
</div>

@if(session('status'))
    <div class="note ok">{{ session('status') }}</div>
@endif
@if($errors->any())
    <div class="note bad">{{ $errors->first() }}</div>
@endif

<div class="grid stats">
    <div class="card stat">
        <div class="k">Annual goal</div>
        <div class="v">LKR {{ number_format($s['annualTarget']) }}</div>
        @if($s['baseline'] > 0)
            <div class="s">Last year {{ number_format($s['baseline']) }}
                @if($s['growthPct'] !== null) &middot; +{{ $s['growthPct'] }}% growth needed @endif
            </div>
        @endif
    </div>
    <div class="card stat">
        <div class="k">Achieved so far</div>
        <div class="v">LKR {{ number_format($s['achieved']) }}</div>
        <div class="s">{{ $s['annualTarget'] > 0 ? min(100, (int) floor($s['achieved'] * 100 / $s['annualTarget'])) : 0 }}% of annual goal</div>
    </div>
    <div class="card stat">
        <div class="k">Still to earn</div>
        <div class="v">LKR {{ number_format($s['remainingAnnual']) }}</div>
        <div class="s">{{ $s['monthsLeft'] }} month(s) left including this one</div>
    </div>
    <div class="card stat">
        <div class="k">Needed per remaining month</div>
        <div class="v">{{ $s['requiredPerMonth'] === null ? '-' : 'LKR ' . number_format($s['requiredPerMonth']) }}</div>
        <div class="s">To still reach the annual goal</div>
    </div>
    <div class="card stat">
        <div class="k">Allocated across months</div>
        <div class="v" id="allocated">LKR {{ number_format($s['allocated']) }}</div>
        <div class="s">Should equal the annual goal</div>
    </div>
</div>

<div id="mismatch" class="note warn" hidden></div>

<form method="POST" action="{{ route('forecast.update', $goal) }}">
    @csrf
    @method('PUT')
    <input type="hidden" name="as_of" value="{{ $asOf->toDateString() }}">

    <div class="grid months">
        @foreach($s['cells'] as $c)
            <div class="card cell {{ $c['phase'] }}" data-phase="{{ $c['phase'] }}" data-achieved="{{ $c['achieved'] }}">
                <div class="top">
                    <span class="name">{{ $c['name'] }} {{ $goal->year }}</span>
                    <span class="badge">
                        @if($c['phase'] === 'current') This month &middot; {{ $c['daysLeft'] }} day(s) left
                        @elseif($c['phase'] === 'past') Past
                        @else Upcoming @endif
                    </span>
                </div>

                <div class="tgt">
                    <span class="prefix">Target LKR</span>
                    <input type="number" min="0" step="1" name="targets[{{ $c['month'] }}]" value="{{ $c['target'] }}" required>
                </div>

                <label class="fixed" title="Fixed months keep their value when you rebalance the others">
                    <input type="checkbox" name="fixed[{{ $c['month'] }}]" value="1" @checked($c['fixed'])>
                    Fixed (set by manager)
                </label>

                <div class="bar"><span style="width: {{ $c['pct'] }}%"></span></div>

                <div class="remaining {{ $c['over'] > 0 ? 'good' : ($c['phase'] === 'past' && $c['remaining'] > 0 ? 'short' : '') }}">
                    {{ $c['label'] }}
                </div>
                <div class="mute small">Earned: LKR {{ number_format($c['achieved']) }}</div>
            </div>
        @endforeach
    </div>

    <div class="actions">
        <button class="btn" type="submit" name="action" value="save">Save targets</button>
        <button class="btn ghost" type="submit" name="action" value="rebalance"
                title="Months not marked Fixed are re-split so the total equals the annual goal">
            Save &amp; rebalance other months
        </button>
    </div>
</form>

<script>
    (function () {
        var goalTotal = {{ (int) $goal->target_amount }};
        var fmt = function (n) { return Number(n).toLocaleString('en-US'); };

        // Mirrors TargetMath::label() in PHP so the text updates while typing.
        function label(phase, target, achieved) {
            var remaining = Math.max(target - achieved, 0);
            var over = Math.max(achieved - target, 0);
            if (target === 0) return 'No target set';
            if (over > 0) return 'Target exceeded by ' + fmt(over);
            if (phase === 'past') return remaining > 0 ? 'Missed by ' + fmt(remaining) + ' / ' + fmt(target) : 'Target hit';
            return 'Remaining ' + fmt(remaining) + ' / ' + fmt(target);
        }

        function refresh() {
            var total = 0;
            document.querySelectorAll('.cell').forEach(function (cell) {
                var input = cell.querySelector('input[type=number]');
                var target = parseInt(input.value, 10) || 0;
                var achieved = parseInt(cell.dataset.achieved, 10) || 0;
                var phase = cell.dataset.phase;
                total += target;

                var pct = target > 0 ? Math.min(100, Math.floor(achieved * 100 / target)) : (achieved > 0 ? 100 : 0);
                cell.querySelector('.bar > span').style.width = pct + '%';

                var el = cell.querySelector('.remaining');
                el.textContent = label(phase, target, achieved);
                el.classList.toggle('good', achieved > target);
                el.classList.toggle('short', phase === 'past' && target > achieved);
            });

            document.getElementById('allocated').textContent = 'LKR ' + fmt(total);

            var diff = total - goalTotal;
            var box = document.getElementById('mismatch');
            if (diff === 0) {
                box.hidden = true;
            } else {
                box.hidden = false;
                box.textContent = diff > 0
                    ? 'Months add up to LKR ' + fmt(diff) + ' MORE than the annual goal of LKR ' + fmt(goalTotal) + '. Use "Save & rebalance other months" to lower the unfixed months, or raise the annual goal.'
                    : 'Months add up to LKR ' + fmt(-diff) + ' LESS than the annual goal of LKR ' + fmt(goalTotal) + '. Use "Save & rebalance other months" to raise the unfixed months.';
            }
        }

        document.querySelectorAll('.cell input[type=number]').forEach(function (input) {
            input.addEventListener('input', function () {
                // Editing a month marks it as set by hand.
                if (input.value !== input.defaultValue) {
                    input.closest('.cell').querySelector('input[type=checkbox]').checked = true;
                }
                refresh();
            });
        });

        refresh();
    })();
</script>
@endsection
