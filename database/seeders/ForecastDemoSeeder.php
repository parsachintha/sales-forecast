<?php

namespace Database\Seeders;

use App\Models\Rep;
use App\Models\Sale;
use App\Models\SalesGoal;
use App\Support\TargetMath;
use Illuminate\Database\Seeder;

/**
 *  data matching the scenario:
 *  - Nimal: 2026 income 100,000 -> 2027 goal 360,000 (30,000 per month),
 *           with 16,000 already sold by 15 Jan 2027.
 *  - Kasun: goal 240,000 with a New Year (April) and Christmas (December) uplift,
 *           the other months rebalanced so the total is still 240,000.
 *
 * Run:  php artisan db:seed --class=ForecastSeeder
 */
class ForecastSeeder extends Seeder
{
    public function run(): void
    {
        $nimal  = Rep::firstOrCreate(['name' => 'Nimal Perera']);
        $kasun  = Rep::firstOrCreate(['name' => 'Kasun Silva']);
        Rep::firstOrCreate(['name' => 'Dilani Fernando']);

        // --- Nimal: flat 30,000 / month ---------------------------------
        $g1 = SalesGoal::firstOrCreate(
            ['rep_id' => $nimal->id, 'year' => 2027],
            ['title' => '2027 Sales Income Goal', 'baseline_amount' => 100000, 'target_amount' => 360000]
        );

        if ($g1->months()->count() === 0) {
            foreach (TargetMath::evenSplit(360000, 12) as $i => $amount) {
                $g1->months()->create(['month' => $i + 1, 'target_amount' => $amount, 'is_fixed' => false]);
            }
        }

        // 16,000 sold by 15 January 2027 -> cell should show "Remaining 14,000 / 30,000"
        Sale::firstOrCreate(['rep_id' => $nimal->id, 'sold_at' => '2027-01-05 10:00:00'], ['amount' => 9000]);
        Sale::firstOrCreate(['rep_id' => $nimal->id, 'sold_at' => '2027-01-12 15:30:00'], ['amount' => 7000]);

        // --- Kasun: seasonal uplift --------------------------------------
        $g2 = SalesGoal::firstOrCreate(
            ['rep_id' => $kasun->id, 'year' => 2027],
            ['title' => '2027 Sales Income Goal (seasonal)', 'baseline_amount' => 80000, 'target_amount' => 240000]
        );

        if ($g2->months()->count() === 0) {
            $targets = array_combine(range(1, 12), TargetMath::evenSplit(240000, 12));
            $targets[4]  = 35000;   // April - Sinhala & Tamil New Year
            $targets[12] = 45000;   // December - Christmas
            $fixed = [4 => true, 12 => true];

            $result = TargetMath::rebalance(240000, $targets, $fixed);

            foreach ($result['targets'] as $month => $amount) {
                $g2->months()->create([
                    'month'         => $month,
                    'target_amount' => $amount,
                    'is_fixed'      => ! empty($fixed[$month]),
                ]);
            }
        }
    }
}
