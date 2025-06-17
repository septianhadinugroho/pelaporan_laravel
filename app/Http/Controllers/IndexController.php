<?php

namespace App\Http\Controllers;

use App\Models\Pelaporan;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        // Mengambil 5 laporan terakhir
        $laporan = Pelaporan::with(['status', 'kategori']) // jika memang relasi dibutuhkan
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('user.index', compact('laporan'));
    }
}
