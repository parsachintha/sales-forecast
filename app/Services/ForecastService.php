<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SalesGoal;
use App\Support\TargetMath;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class ForecastService
{
    /**
     * THE ONLY PLACE that reads actual sales income.
     * When you plug this into your real system, change this one query
     * (e.g. sum paid invoices for the rep between $from and $to).
     */
    public function achievedBetween(int $repId, CarbonInterface $from, CarbonInterface $to): int
    {
        return (int) Sale::where('rep_id', $repId)
            ->whereBetween('sold_at', [$from, $to])
            ->sum('amount');
    }

    public function yearAchieved(SalesGoal $goal): int
    {
        return $this->achievedBetween(
            $goal->rep_id,
            Carbon::create($goal->year, 1, 1)->startOfDay(),
            Carbon::create($goal->year, 12, 31)->endOfDay()
        );
    }

    /**
     * Build the 12-month grid plus the summary numbers.
     * $asOf is "today" - the page lets you simulate other dates for the .
     */
    public function grid(SalesGoal $goal, CarbonInterface $asOf): array
    {
        $asOf    = $asOf->copy()->startOfDay();
        $saved   = $goal->months()->get()->keyBy('month');

        $cells         = [];
        $achievedTotal = 0;
        $allocated     = 0;
        $monthsLeft    = 0;   // current month + future months

        for ($m = 1; $m <= 12; $m++) {
            $start = Carbon::create($goal->year, $m, 1)->startOfDay();
            $end   = $start->copy()->endOfMonth();

            $row      = $saved->get($m);
            $target   = (int) ($row?->target_amount ?? 0);
            $achieved = $this->achievedBetween($goal->rep_id, $start, $end);

            $phase = $asOf->lt($start) ? 'future' : ($asOf->gt($end) ? 'past' : 'current');
            if ($phase !== 'past') {
                $monthsLeft++;
            }

            $cell             = TargetMath::cell($m, $target, $achieved, $phase);
            $cell['fixed']    = (bool) ($row?->is_fixed ?? false);
            $cell['daysLeft'] = $phase === 'current' ? $end->day - $asOf->day : null;
            $cells[]          = $cell;

            $achievedTotal += $achieved;
            $allocated     += $target;
        }

        $annual          = (int) $goal->target_amount;
        $remainingAnnual = max($annual - $achievedTotal, 0);
        $baseline        = (int) $goal->baseline_amount;

        return [
            'cells'           => $cells,
            'annualTarget'    => $annual,
            'achieved'        => $achievedTotal,
            'remainingAnnual' => $remainingAnnual,
            'monthsLeft'      => $monthsLeft,
            // What the rep must average from now on to still hit the annual goal.
            'requiredPerMonth' => $monthsLeft > 0 ? (int) ceil($remainingAnnual / $monthsLeft) : null,
            'allocated'       => $allocated,
            'diff'            => $allocated - $annual,    // + means months add up to more than the goal
            'baseline'        => $baseline,
            'growthPct'       => $baseline > 0 ? (int) round(($annual / $baseline - 1) * 100) : null,
        ];
    }
}
