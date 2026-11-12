<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketingPlanController;
use App\Http\Controllers\AuthController;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Marketing plan & root route
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    if (auth()->user()->isCompany()) {
        return redirect()->route('forecast.index');
    }
    return redirect()->route('marketing-plan.index');
})->name('home');

// Division Officer exclusive routes for creating / editing / managing marketing plans
Route::middleware(['auth', 'role:officer'])->group(function () {
    Route::get('/marketing-plan/create', [MarketingPlanController::class, 'create'])->name('marketing-plan.create');
    Route::post('/marketing-plan', [MarketingPlanController::class, 'store'])->name('marketing-plan.store');
    Route::get('/marketing-plan/{marketing_plan}/edit', [MarketingPlanController::class, 'edit'])->name('marketing-plan.edit');
    Route::put('/marketing-plan/{marketing_plan}', [MarketingPlanController::class, 'update'])->name('marketing-plan.update');
    Route::delete('/marketing-plan/{marketing_plan}', [MarketingPlanController::class, 'destroy'])->name('marketing-plan.destroy');
});

// Plans listing & viewing (Accessible to authenticated users)
Route::get('/marketing-plan', [MarketingPlanController::class, 'index'])->name('marketing-plan.index');
Route::get('/marketing-plan/{marketing_plan}', [MarketingPlanController::class, 'show'])->name('marketing-plan.show');

require __DIR__.'/forecast.php';

