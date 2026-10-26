<?php

use App\Http\Controllers\SalesGoalController;
use Illuminate\Support\Facades\Route;

/*
 * Include this from routes/web.php:   require __DIR__.'/forecast.php';
 *
 * In your real system wrap the group in your auth + "shop manager" middleware,
 * e.g.  Route::middleware(['auth', 'can:manage-goals'])->prefix('forecast')...
 * The  has no login on purpose.
 */
Route::prefix('forecast')->name('forecast.')->group(function () {
    Route::get('/', [SalesGoalController::class, 'index'])->name('index');
    Route::get('/create', [SalesGoalController::class, 'create'])->name('create');
    Route::post('/', [SalesGoalController::class, 'store'])->name('store');
    Route::get('/{goal}', [SalesGoalController::class, 'show'])->name('show');
    Route::put('/{goal}/months', [SalesGoalController::class, 'update'])->name('update');
});
