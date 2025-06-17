<?php

namespace App\Http\Controllers;

use App\Models\Pelaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\Kategori;
use App\Models\StatusLaporan;

class LaporanController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi data
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi' => 'required|string|max:255',
            'reportDate' => 'required|date',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,bmp,mp4|max:40960',
        ]);

        $mediaPath = null;
        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('public/laporan_media');
        }

        // Get default status_id for 'Menunggu' (which was 'Dalam Antrian' before)
        $defaultStatus = StatusLaporan::where('nama_status', 'Menunggu')->first(); // Changed from 'Dalam Antrian'
        if (!$defaultStatus) {
            return response()->json(['success' => false, 'message' => 'Default status "Menunggu" not found.'], 500);
        }

        // 2. Simpan data ke database
        $pelaporan = Pelaporan::create([
            'judul' => 'Laporan dari ' . (Auth::user()->name ?? 'Pengguna'),
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'tanggal_laporan' => $request->reportDate,
            'media' => $mediaPath,
            'nama_pelapor' => Auth::user()->name ?? 'Pengguna Anonim',
            'lokasi' => 'Lokasi Tidak Diketahui',
            'status_id' => $defaultStatus->id,
        ]);

        // 3. Beri feedback dan redirect
        if ($pelaporan) {
            Session::flash('success', 'Laporan berhasil dibuat!');
            return redirect()->route('riwayat.laporan');
        } else {
            Session::flash('error', 'Gagal membuat laporan. Silakan coba lagi.');
            return back()->withInput();
        }
    }

    public function indexAdmin()
    {
        $laporans = Pelaporan::with(['kategori', 'status'])->latest('created_at')->paginate(10);
        $kategoris = Kategori::all();
        $statuses = StatusLaporan::all(); // This will now only fetch 'Menunggu' and 'Selesai'

        return view('admin.listLaporan', compact('laporans', 'kategoris', 'statuses'));
    }

    // New method to update report status via AJAX
    public function updateStatus(Request $request, $id)
    {
        // Ensure only authenticated admin can update status
        if (Auth::check() && Auth::user()->role_id == 1) { // 1 is admin role_id
            $pelaporan = Pelaporan::find($id);

            if (!$pelaporan) {
                return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan.'], 404);
            }

            $request->validate([
                'status_id' => 'required|exists:status,id',
            ]);

            $pelaporan->status_id = $request->status_id;
            $pelaporan->save();

            return response()->json(['success' => true, 'message' => 'Status laporan berhasil diperbarui.']);
        }

        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }
    public function delete($id)
    {
        if (Auth::check() && Auth::user()->role_id == 1) {
            $pelaporan = Pelaporan::find($id);

            if (!$pelaporan) {
                return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan.'], 404);
            }

            // Delete associated media file if it exists
            if ($pelaporan->media) {
                Storage::delete($pelaporan->media);
            }

            $pelaporan->delete();

            return response()->json(['success' => true, 'message' => 'Laporan berhasil dihapus.']);
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }
}