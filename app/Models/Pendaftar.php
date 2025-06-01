<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    protected $table = 'pendaftars';

    protected $fillable = [
        'user_id',
        'lowongan_id',
        'tanggal_daftar',
        'status_lamaran',
        'surat_lamaran_path',
        'cv_path',
        'portofolio_path',
        'catatan_pendaftar',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }

    /**
     * Get all of the dokumen for the Pendaftar
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dokumenPendaftars() // Pastikan nama method konsisten
    {
        return $this->hasMany(DokumenPendaftar::class, 'pendaftar_id');
    }
}