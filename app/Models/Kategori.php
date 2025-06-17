<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';
    protected $fillable = [
        'name',
    ];

    // public function jenis() // Relasi ke Jenis
    // {
    //     return $this->hasMany(Jenis::class);
    // }

    public function pelaporans()
    {
        return $this->hasMany(Pelaporan::class);
    }
}
