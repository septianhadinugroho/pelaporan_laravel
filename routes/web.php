<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Hanya untuk admin
Route::middleware(['only_admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard'); // Dashboard admin
});

// Hanya untuk user
Route::middleware(['only_user'])->group(function () {
    Route::get('/index', [IndexController::class, 'index'])->name('index'); // Halaman User
    Route::get('/lapor', function() {
        return view('user.lapor'); // Halaman Lapor
    })->name('lapor'); // Nama route lapor
    Route::get('/riwayat-laporan', function(){
        return view('user.riwayat_laporan'); // Riwayat Laporan
    })->name('riwayat.laporan'); // Nama route riwayat
});

// Untuk guest (belum login)
Route::middleware(['only_guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login'); // View form login
    Route::get('/register', [AuthController::class, 'login'])->name('register'); // View yang sama, diberi tab Signup
    
    Route::post('/login', [AuthController::class, 'authentication'])->name('authentication'); // Proses login
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit'); // Proses register
});


// Rute logout (bisa diakses setelah login, jadi tidak di dalam only_guest)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Tombol Logout