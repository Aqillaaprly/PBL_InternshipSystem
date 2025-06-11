<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'judul',
        'deskripsi',
        'kualifikasi',
        'tipe',
        'provinsi', // Add this
        'kota',     // Add this
        'alamat',   // Add this
        'kode_pos', // Add this
        'lokasi',   // Keep if used for a combined string, otherwise consider removing if it conflicts with separate address fields
        'gaji_min',
        'gaji_max',
        'tanggal_buka',
        'tanggal_tutup',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function pendaftars()
    {
        return $this->hasMany(Pendaftar::class);
    }
}