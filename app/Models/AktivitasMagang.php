<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasMagang extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_magangs'; // Pastikan nama tabel benar

    protected $fillable = [
        'mahasiswa_id',
        'tanggal',
        'deskripsi_kegiatan',
        'jam_kerja',
        'status_verifikasi',
        'dosen_pembimbing_id',
        'perusahaan_pic_id',
        'bukti_kegiatan',
        'catatan_verifikasi_dosen',
        'catatan_verifikasi_perusahaan',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    // Relasi ke User (Dosen Pembimbing)
    public function dosenPembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing_id');
    }

    // Relasi ke User (Perusahaan PIC)
    public function perusahaanPic()
    {
        return $this->belongsTo(User::class, 'perusahaan_pic_id');
    }
}