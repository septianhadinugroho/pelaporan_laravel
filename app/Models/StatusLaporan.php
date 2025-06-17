<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusLaporan extends Model
{
    use HasFactory;
    protected $table = 'status'; // Nama tabel jika berbeda dari konvensi
    protected $fillable = ['nama_status']; // Kolom yang dapat diisi
}
