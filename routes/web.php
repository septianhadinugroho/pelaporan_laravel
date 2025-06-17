<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RiwayatLaporanController;
use App\Models\Kategori; // Ensure this is imported

Route::get('/', function () {
    return redirect()->route('login');
});

// Hanya untuk admin
Route::middleware(['only_admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin/laporan', [LaporanController::class, 'indexAdmin'])->name('admin.laporan.index');
    // New route for updating report status
    Route::post('/admin/laporan/{id}/update-status', [LaporanController::class, 'updateStatus'])->name('admin.laporan.update_status');
    Route::post('/admin/laporan/{id}/delete', [LaporanController::class, 'delete'])->name('admin.laporan.delete');
});

// Hanya untuk user
Route::middleware(['only_user'])->group(function () {
    Route::get('/index', [IndexController::class, 'index'])->name('index');
    Route::get('/lapor', function() {
        $kategoris = Kategori::all(); // Use the imported Kategori model
        return view('user.lapor', compact('kategoris'));
    })->name('lapor');
    Route::post('/lapor', [LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/riwayat-laporan', [RiwayatLaporanController::class, 'index'])->name('riwayat.laporan');
});

// Untuk guest (belum login)
Route::middleware(['only_guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'login'])->name('register');

    Route::post('/login', [AuthController::class, 'authentication'])->name('authentication');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Rute logout (bisa diakses setelah login, jadi tidak di dalam only_guest)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');