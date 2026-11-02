<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_plan_id')->nullable()->constrained('marketing_plans')->nullOnDelete();
            $table->string('title');
            $table->unsignedSmallInteger('year');
            $table->unsignedBigInteger('total_annual_target')->default(0);
            $table->json('products')->nullable(); // [{name, annual_target, months: [m1..m12]}]
            $table->json('assigned_rep_ids')->nullable(); // [1, 2, 3..]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_goals');
    }
};
