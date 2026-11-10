<?php

use App\Http\Controllers\SalesGoalController;
use Illuminate\Support\Facades\Route;

/*
 * Reps Allocation & Forecasting: Managed by the Company Team.
 */
Route::prefix('forecast')->name('forecast.')->group(function () {
    // Company Team exclusive: Assigning goals, partitioning & cascading targets to reps
    // MUST be declared before /{goal} so /forecast/create is not treated as a goal ID
    Route::middleware(['auth', 'role:company'])->group(function () {
        Route::get('/create', [SalesGoalController::class, 'create'])->name('create');
        Route::post('/', [SalesGoalController::class, 'store'])->name('store');
        Route::put('/{goal}/months', [SalesGoalController::class, 'update'])->name('update');
    });

    // Viewing forecasts and goals
    Route::get('/', [SalesGoalController::class, 'index'])->name('index');
    Route::get('/{goal}', [SalesGoalController::class, 'show'])->name('show');
});
