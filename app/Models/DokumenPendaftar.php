<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPendaftar extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id',
        'nama_dokumen',
        'file_path',
        'tipe_file',
        'status_validasi', // Tambahkan ini
    ];

    // Opsional: Casting untuk enum jika diperlukan, tapi biasanya tidak wajib
    // protected $casts = [
    //     'status_validasi' => 'string', // Laravel akan menanganinya dengan baik
    // ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}