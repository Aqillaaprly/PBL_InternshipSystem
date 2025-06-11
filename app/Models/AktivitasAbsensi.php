<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasAbsensi extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_absensis'; // updated table name

    protected $fillable = [
        'mahasiswa_user_id',
        'company_id',
        'lowongan_id',
        'periode_magang',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_bimbingan',
        'catatan_koordinator',
        'mahasiswa_id',
        'pembimbing_id',
        'tanggal',
        'jenis_aktivitas', // assuming you renamed jenis_aktivitas to jenis_bimbingan for a reason
        'catatan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_user_id');
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class, 'pembimbing_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }

    // ✅ Updated: relasi ke aktivitas_fotos
    public function foto()
    {
        return $this->hasMany(AktivitasFoto::class, 'aktivitas_absensi_id');
    }
}
