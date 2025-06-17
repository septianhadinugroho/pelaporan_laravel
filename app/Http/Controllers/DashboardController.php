<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelaporan; // Import the Pelaporan model
use App\Models\StatusLaporan; // Import the StatusLaporan model

class DashboardController extends Controller
{
    public function dashboard() {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch total reports
        $totalReports = Pelaporan::count(); //

        // Fetch status IDs for 'Dalam Antrian' and 'Selesai'
        $dalamAntrianStatus = StatusLaporan::where('nama_status', 'Dalam Antrian')->first(); //
        $selesaiStatus = StatusLaporan::where('nama_status', 'Selesai')->first(); //

        // Calculate pending reports (assuming 'Dalam Antrian' means pending)
        $pendingReports = $dalamAntrianStatus ? Pelaporan::where('status_id', $dalamAntrianStatus->id)->count() : 0; //

        // Calculate accepted reports (assuming 'Selesai' means accepted for admin dashboard context, or 'Sedang Diproses' + 'Selesai')
        // For simplicity, let's assume 'Selesai' represents handled/accepted reports here.
        $acceptedReports = $selesaiStatus ? Pelaporan::where('status_id', $selesaiStatus->id)->count() : 0; //

        // Fetch the 10 latest reports, eager loading 'kategori' and 'status' relationships
        $latestReports = Pelaporan::with(['kategori', 'status']) //
                           ->latest('created_at') //
                           ->take(10) //
                           ->get();

        // Pass all fetched data to the view
        return view('admin.dashboard', compact('user', 'totalReports', 'pendingReports', 'acceptedReports', 'latestReports')); //
    }
}