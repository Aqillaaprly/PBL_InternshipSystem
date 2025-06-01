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
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}