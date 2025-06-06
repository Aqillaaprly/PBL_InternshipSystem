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

    /**
     * Relasi ke tabel users.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel lowongans.
     */
    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class);
    }

    /**
     * [Opsional] Relasi ke tabel dokumen_pendaftars jika digunakan terpisah.
     * Jika tidak ada tabel dokumen_pendaftars, hapus method ini.
     */
    public function dokumenPendaftars()
    {
        return $this->hasMany(DokumenPendaftar::class, 'pendaftar_id');
    }

    public function applyFromPerusahaan($lowonganId)
    {
        $lowongan = Lowongan::with('company')->findOrFail($lowonganId);

        return view('mahasiswa.pendaftar', [
            'prefilledLowongan' => $lowongan,
            'lowongans' => Lowongan::with('company')->get(),
            'pendaftars' => Pendaftar::with('lowongan')->get()
        ]);
    }

}
