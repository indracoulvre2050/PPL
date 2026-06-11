<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Belum Login
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'submitLogin'])->name('login.submit');
});

// Sesudah Login (Akan masuk ke dashboard)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sensor', [DashboardController::class, 'sensor'])->name('sensor');
    Route::post('/sensor/ambang-batas', [DashboardController::class, 'updateAmbangBatas'])->name('sensor.update-batas');
    Route::get('/notifikasi', [DashboardController::class, 'notifikasi'])->name('notifikasi');
    Route::post('/simpan-subscription', [DashboardController::class, 'simpanSubscription'])->name('simpan.subscription');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});