<?php

namespace App\Http\Controllers;

use App\Models\Pelaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class RiwayatLaporanController extends Controller
{
    public function index()
    {
        // Fetch reports for the authenticated user
        // Assuming 'nama_pelapor' in 'pelaporan' table stores the user's name.
        // For a more robust solution, it's highly recommended to add a 'user_id'
        // foreign key to the 'pelaporan' table, linking to the 'users' table.
        $laporans = Pelaporan::where('nama_pelapor', Auth::user()->name)
                             ->with(['kategori', 'status']) // Eager load relationships
                             ->latest('created_at') // Order by latest reports
                             ->get();

        return view('user.riwayatlaporan', compact('laporans'));
    }
}