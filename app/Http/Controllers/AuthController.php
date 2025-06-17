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
        return view('user.login');
    }
    // public function register() {
    //     return view('login');
    // }

    public function authentication(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role_id == 1) {
                return redirect()->route('dashboard'); // Dashboard untuk admin
            } else if (Auth::user()->role_id == 2) {
                return redirect()->route('index'); // Halaman User
            }
        }

        // Jika authentication gagal
        Session::flash('error', 'Email atau password salah.');
        return redirect()->route('login'); // Kembali ke form login
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
        $request->validate([ /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
            'name' => 'required|string|max:255', /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
            'email' => 'required|string|email|max:255|unique:users', /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
            'password' => 'required|string|min:8|confirmed', // memastikan password_confirmation cocok /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
        ]);

        // Membuat user baru dengan role_id default 2 (User)
        $user = User::create([ /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
            'name' => $request->name, /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
            'email' => $request->email, /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
            'password' => Hash::make($request->password), /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
            'role_id' => 2, // default role_id = 2 untuk user biasa /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
        ]);

    // Cek apakah berhasil menyimpan
        if ($user) { /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
            // Notifikasi berhasil
            Session::flash('success', 'Registrasi berhasil! Silakan login.'); /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
            return redirect()->route('login'); // Redirect ke halaman login /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
        }

        // Jika gagal, tampilkan notifikasi error
        Session::flash('error', 'Registrasi gagal. Silakan coba lagi.'); /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
        return redirect()->route('login'); // Kembali ke halaman register /* cite: septianhadinugroho/pelaporan_laravel/pelaporan_laravel-d0033e0b8381abd760e5c525f99a0d53fb824c4f/app/Http/Controllers/AuthController.php */
    }
}
