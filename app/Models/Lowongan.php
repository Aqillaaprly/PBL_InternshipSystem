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
        'lokasi',
        'gaji_min',
        'gaji_max',
        'tanggal_buka',
        'tanggal_tutup',
        'status'
    ];
    public function company()
    {

        return $this->belongsTo(Company::class);
    }
}
