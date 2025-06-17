<?php

namespace App\Http\Controllers;

use App\Models\Pelaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\Kategori; // Import Kategori model
use App\Models\StatusLaporan; // Import StatusLaporan model


class LaporanController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi data
        $request->validate([
            // 'judul' => 'required|string|max:255', // If you decide to add a title field
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi' => 'required|string|max:255', // Changed from 110 to 255 based on your char limit in lapor.js
            'reportDate' => 'required|date',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,bmp,mp4|max:40960', // 10MB for image, 40MB for video
            // 'nama_pelapor' => 'required|string|max:255', // If you decide to make this editable
            // 'lokasi' => 'required|string|max:255', // If you decide to add a location field
        ]);

        $mediaPath = null;
        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('public/laporan_media'); // Store in storage/app/public/laporan_media
            // You might want to adjust the path to remove 'public/' prefix when saving to DB
            // $mediaPath = str_replace('public/', '', $mediaPath);
        }

        // Get default status_id for 'Dalam Antrian' or 'Diproses'
        $defaultStatus = StatusLaporan::where('nama_status', 'Dalam Antrian')->first();
        if (!$defaultStatus) {
            // Fallback if 'Dalam Antrian' status is not found (e.g., if seeder not run)
            $defaultStatus = StatusLaporan::create(['nama_status' => 'Dalam Antrian']);
        }


        // 2. Simpan data ke database
        $pelaporan = Pelaporan::create([
            'judul' => 'Laporan dari ' . (Auth::user()->name ?? 'Pengguna'),
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'tanggal_laporan' => $request->reportDate,
            'media' => $mediaPath, // Simpan path media
            'nama_pelapor' => Auth::user()->name ?? 'Pengguna Anonim', // Get from authenticated user
            'lokasi' => 'Lokasi Tidak Diketahui', // You can make this dynamic or add a field in the form
            'status_id' => $defaultStatus->id,
        ]);

        // 3. Beri feedback dan redirect
        if ($pelaporan) {
            Session::flash('success', 'Laporan berhasil dibuat!');
            return redirect()->route('riwayat.laporan'); // Redirect to report history
        } else {
            Session::flash('error', 'Gagal membuat laporan. Silakan coba lagi.');
            return back()->withInput(); // Stay on the form with old input
        }
    }

    public function indexAdmin()
    {
        // Fetch all reports with their categories and statuses
        $laporans = Pelaporan::with(['kategori', 'status'])->latest('created_at')->paginate(10);

        // Fetch all categories for the filter dropdown
        $kategoris = Kategori::all();

        // Fetch all statuses for the filter dropdown
        $statuses = StatusLaporan::all();

        return view('admin.listLaporan', compact('laporans', 'kategoris', 'statuses')); // Changed view name here
    }
}