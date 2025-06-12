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
        'tanggung_jawab',
        'tipe',
        'provinsi',
        'kota',
        'alamat',
        'kode_pos',
        'gaji_min',
        'gaji_max',
        'tanggal_buka',
        'tanggal_tutup',
        'status'
    ];
    // Add this if you want to enforce enum values
    protected $casts = [
        'tipe' => 'string',
        'status' => 'string'
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
