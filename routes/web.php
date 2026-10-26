<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketingPlanController;

Route::get('/', [MarketingPlanController::class, 'index'])->name('home');

Route::resource('marketing-plan', MarketingPlanController::class);

require __DIR__.'/forecast.php';
