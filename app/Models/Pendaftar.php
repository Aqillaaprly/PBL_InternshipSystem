<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    protected $table = 'pendaftars';

    protected $fillable = [
        'mahasiswa_id', 
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

    // Relasi ke Mahasiswa (Pendaftar punya satu Mahasiswa)
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    // Relasi ke Lowongan (Pendaftar melamar ke satu Lowongan)
    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }

    // Relasi ke DokumenPendaftar (Pendaftar memiliki banyak DokumenPendaftar)
    public function dokumenPendaftars()
    {
        return $this->hasMany(DokumenPendaftar::class, 'pendaftar_id');
    }

    // Jika Anda memiliki user_id di tabel pendaftar dan ingin tetap menggunakannya
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
