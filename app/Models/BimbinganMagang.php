<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganMagang extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_user_id',
        'pembimbing_id',
        'company_id',
        'lowongan_id',
        'periode_magang',        // Added to fillable
        'jenis_bimbingan',       // Added to fillable
        'tanggal_mulai',         // Added to fillable
        'tanggal_selesai',       // Added to fillable
        'status_bimbingan',      // Added to fillable
        'catatan_koordinator',   // Added to fillable
    ];

    /**
     * Get the mahasiswa (user) that owns the bimbingan magang.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_user_id');
    }

    /**
     * Get the pembimbing (dosen) that owns the bimbingan magang.
     */
    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class, 'pembimbing_id');
    }

    /**
     * Get the company associated with the bimbingan magang.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the lowongan associated with the bimbingan magang.
     */
    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }
}

