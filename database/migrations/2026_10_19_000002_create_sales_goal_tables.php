<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One annual goal per rep per year, set by the shop manager.
        Schema::create('sales_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rep_id')->constrained('reps')->cascadeOnDelete(); // -> your users/employees table later
            $table->string('title');
            $table->unsignedSmallInteger('year');
            $table->unsignedBigInteger('baseline_amount')->default(0); // last year's income, for context only
            $table->unsignedBigInteger('target_amount');               // annual goal, e.g. 360000
            $table->timestamps();

            $table->unique(['rep_id', 'year']);
        });

        // The annual goal split into 12 monthly targets the manager can edit.
        Schema::create('goal_months', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_goal_id')->constrained('sales_goals')->cascadeOnDelete();
            $table->unsignedTinyInteger('month');                      // 1-12
            $table->unsignedBigInteger('target_amount');
            $table->boolean('is_fixed')->default(false);               // manager set this month by hand
            $table->timestamps();

            $table->unique(['sales_goal_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goal_months');
        Schema::dropIfExists('sales_goals');
    }
};
