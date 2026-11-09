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
Route::get('/', [MarketingPlanController::class, 'index'])->name('home');
Route::resource('marketing-plan', MarketingPlanController::class);

require __DIR__.'/forecast.php';

