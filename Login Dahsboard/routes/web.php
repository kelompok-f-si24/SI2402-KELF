<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Employer;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Seeker;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route(auth()->user()->dashboardRouteName()))->name('dashboard');
});

Route::middleware(['auth', 'role:job_seeker'])->prefix('seeker')->name('seeker.')->group(function () {
    Route::get('/dashboard', Seeker\DashboardController::class)->name('dashboard');
});

Route::middleware(['auth', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/dashboard', Employer\DashboardController::class)->name('dashboard');
});
