<?php

namespace App\Support;

/**
 * Pure maths for monthly targets. No framework or database code in here,
 * so it is easy to test and easy to reuse.
 *
 * Money is handled as whole LKR (integers) to avoid float rounding drift.
 */
final class TargetMath
{
    /**
     * Split $total into $parts whole-number pieces. Any leftover rupees go to
     * the earliest pieces, so the pieces always add up to exactly $total.
     *
     * @return int[]
     */
    public static function evenSplit(int $total, int $parts): array
    {
        if ($parts <= 0) {
            return [];
        }

        $base  = intdiv($total, $parts);
        $extra = $total % $parts;
        $out   = [];

        for ($i = 0; $i < $parts; $i++) {
            $out[] = $base + ($i < $extra ? 1 : 0);
        }

        return $out;
    }

    /**
     * Keep the annual goal intact after the manager changed some months:
     * months marked "fixed" keep their value, the rest share what is left.
     *
     * @param  array<int,int>  $targets  month (1-12) => target
     * @param  array<int,bool> $fixed    month (1-12) => true when manager fixed it
     * @return array{ok: bool, targets: array<int,int>, message: string}
     */
    public static function rebalance(int $goalTotal, array $targets, array $fixed): array
    {
        $fixedSum = 0;
        $free     = [];

        foreach ($targets as $month => $target) {
            if (! empty($fixed[$month])) {
                $fixedSum += $target;
            } else {
                $free[] = $month;
            }
        }

        if (count($free) === 0) {
            return [
                'ok'      => false,
                'targets' => $targets,
                'message' => 'Every month is fixed, so there is nothing to rebalance.',
            ];
        }

        $remaining = $goalTotal - $fixedSum;

        if ($remaining < 0) {
            return [
                'ok'      => false,
                'targets' => $targets,
                'message' => 'Fixed months already exceed the annual goal by LKR '
                    . number_format(-$remaining) . '. Raise the goal or lower a fixed month.',
            ];
        }

        $split = self::evenSplit($remaining, count($free));
        foreach ($free as $i => $month) {
            $targets[$month] = $split[$i];
        }

        return ['ok' => true, 'targets' => $targets, 'message' => ''];
    }

    /**
     * Everything a month cell needs to display.
     *
     * @param string $phase 'past' | 'current' | 'future'
     */
    public static function cell(int $month, int $target, int $achieved, string $phase): array
    {
        $remaining = max($target - $achieved, 0);
        $over      = max($achieved - $target, 0);

        if ($target > 0) {
            $pct = min(100, (int) floor($achieved * 100 / $target));
        } else {
            $pct = $achieved > 0 ? 100 : 0;
        }

        return [
            'month'     => $month,
            'name'      => date('F', mktime(0, 0, 0, $month, 1)),
            'target'    => $target,
            'achieved'  => $achieved,
            'remaining' => $remaining,
            'over'      => $over,
            'pct'       => $pct,
            'phase'     => $phase,
            'label'     => self::label($phase, $target, $achieved),
        ];
    }

    /**
     * The one-line status shown in a month cell. The page's JavaScript
     * mirrors this so the text updates live while the manager types.
     */
    public static function label(string $phase, int $target, int $achieved): string
    {
        $remaining = max($target - $achieved, 0);
        $over      = max($achieved - $target, 0);

        if ($target === 0) {
            return 'No target set';
        }
        if ($over > 0) {
            return 'Target exceeded by ' . number_format($over);
        }
        if ($phase === 'past') {
            return $remaining > 0
                ? 'Missed by ' . number_format($remaining) . ' / ' . number_format($target)
                : 'Target hit';
        }

        return 'Remaining ' . number_format($remaining) . ' / ' . number_format($target);
    }
}
