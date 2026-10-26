<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *  ONLY. These two tables stand in for the reps and sales you already
 * have in your real system. When you integrate, delete this file and point
 * the module at your own tables (see README, "Moving into your real system").
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rep_id')->constrained('reps')->cascadeOnDelete();
            $table->unsignedBigInteger('amount');      // whole LKR
            $table->dateTime('sold_at');
            $table->timestamps();

            $table->index(['rep_id', 'sold_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
        Schema::dropIfExists('reps');
    }
};
