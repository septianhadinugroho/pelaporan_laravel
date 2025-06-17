<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini jika belum ada

class DashboardController extends Controller
{
    public function dashboard() {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Mengirim data user ke view
        return view('user.dashboard', compact('user'));
    }
}