<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelaporan extends Model
{
    use HasFactory;
    protected $table = 'pelaporan';

    protected $fillable = [
        'judul',
        'kategori_id',
        'deskripsi',
        'tanggal_laporan',
        'media',
        'nama_pelapor',
        'lokasi',
        'status_id',
    ];

    public function status()
    {
        return $this->belongsTo(StatusLaporan::class, 'status_id');
    }

    // Relasi ke model Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

}
