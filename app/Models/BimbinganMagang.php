<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganMagang extends Model
{
    use HasFactory;

    protected $table = 'bimbingan_magangs';

    protected $fillable = [
        'mahasiswa_user_id',
        'pembimbing_id',
        'company_id',
        'lowongan_id',
        'periode_magang',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_bimbingan',
        'catatan_koordinator',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function mahasiswa()
    {
        // Relasi ke User model yang berperan sebagai mahasiswa
        return $this->belongsTo(User::class, 'mahasiswa_user_id');
    }

    public function pembimbing()
    {
        // Relasi ke Pembimbing model
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
    public function logBimbinganMagangs()
    {
        return $this->hasMany(LogBimbinganMagang::class);
    }

    public function absensiMagangs()
    {
        return $this->hasMany(AbsensiMagang::class);
    }
}
