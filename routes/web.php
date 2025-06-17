<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RiwayatLaporanController;

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
        // Fetch categories to pass to the view
        $kategoris = App\Models\Kategori::all(); // Make sure to use App\Models\Kategori; at the top
        return view('user.lapor', compact('kategoris')); // Halaman Lapor
    })->name('lapor'); // Nama route lapor
    Route::post('/lapor', [LaporanController::class, 'store'])->name('laporan.store'); // Add this line for form submission
    Route::get('/riwayat-laporan', [RiwayatLaporanController::class, 'index'])->name('riwayat.laporan');
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