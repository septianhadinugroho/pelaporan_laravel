<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Hanya untuk admin
Route::middleware(['only_admin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

// Hanya untuk user
Route::middleware(['only_user'])->group(function () {
    Route::get('index', [IndexController::class, 'index'])->name('index');
});

// Untuk guest (belum login)
Route::middleware(['only_guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
});
