<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pendaftars'; // Sesuaikan dengan nama tabel pendaftar Anda jika berbeda

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', // ID mahasiswa yang mendaftar
        'lowongan_id', // ID lowongan yang dilamar
        'tanggal_daftar',
        'status_lamaran', // Contoh: Pending, Ditinjau, Diterima, Ditolak, Wawancara
        'surat_lamaran_path', // Path ke file surat lamaran jika ada
        'cv_path', // Path ke file CV jika ada
        'portofolio_path', // Path ke file portofolio jika ada
        'catatan_pendaftar', // Catatan dari pendaftar
        'catatan_admin', // Catatan dari admin atau perusahaan
        // Tambahkan kolom lain yang relevan dengan tabel pendaftar Anda
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    /**
     * Get the user (mahasiswa) that owns the pendaftaran.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the lowongan that the pendaftaran belongs to.
     */
    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }

     public function dokumenPendaftars()
    {
        // Pastikan Anda memiliki model DokumenPendaftar dan tabel yang sesuai
        // return $this->hasMany(DokumenPendaftar::class); 
        // Jika belum ada model DokumenPendaftar, ini akan error. 
        // Untuk sementara, Anda bisa mengomentari pemanggilan 'dokumenPendaftars' di controller jika fitur ini belum siap.
    }
}