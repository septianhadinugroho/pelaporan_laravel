<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Session\Session;
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class AuthController extends Controller
{
    public function login() {
        return view('login');
    }
    // public function register() {
    //     return view('login');
    // }

    public function authentication(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Mengarahkan berdasarkan role
            if (Auth::user()->role_id == 1) {
                return redirect('dashboard'); // Untuk role 1
            } if (Auth::user()->role_id == 2) {
                return redirect('index'); // Untuk role 2
            }

        }
        Session::flash('error', 'Email atau Password salah.');
        return redirect()->route('login'); // Pastikan kamu menggunakan nama rute
    }
    // fungsi untuk logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    public function register(Request $request) {
        // Validasi input form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // memastikan password_confirmation cocok
        ]);

        // Membuat user baru dengan role_id default 2 (User)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2, // default role_id = 2 untuk user biasa
        ]);

       // Cek apakah berhasil menyimpan
        if ($user) {
            // Notifikasi berhasil
            Session::flash('success', 'Registrasi berhasil! Silakan login.');
            return redirect()->route('login'); // Redirect ke halaman login
        }

        // Jika gagal, tampilkan notifikasi error
        Session::flash('error', 'Registrasi gagal. Silakan coba lagi.');
        return redirect()->route('login'); // Kembali ke halaman register
    }
}
