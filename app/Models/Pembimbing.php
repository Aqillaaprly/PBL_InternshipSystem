<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembimbing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'email_institusi',
        'nomor_telepon',
        'jabatan_fungsional',
        'program_studi_homebase',
        'bidang_keahlian_utama',
        'kuota_bimbingan_aktif', // This column will be updated programmatically
        'maks_kuota_bimbingan',
        'status_aktif',
    ];

    // Define relationship to User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define relationship to BimbinganMagang model
    public function bimbinganMagangs()
    {
        return $this->hasMany(BimbinganMagang::class, 'pembimbing_id');
    }

    /**
     * Get the active quota of the pembimbing.
     * This can be an accessor if you want to calculate it on the fly.
     * Or, you can update the 'kuota_bimbingan_aktif' column when a bimbingan status changes.
     *
     * For real-time active count, an accessor is good.
     * For persistent storage (e.g., for filtering/queries without joins), update the column.
     */
    public function getKuotaAktifAttribute()
    {
        // Count bimbingan where status is 'Aktif'
       return $this->bimbinganMagangs()->where('status_bimbingan', 'Aktif')->count();
    }

    // You might also want to ensure this 'kuota_bimbingan_aktif' column is updated
    // whenever a BimbinganMagang status changes or a new bimbingan is created/deleted.
    // This is usually done in an Observer or directly in the controller/service.
    // For example, when a BimbinganMagang is created or its status becomes 'Aktif',
    // you would increment Pembimbing->kuota_bimbingan_aktif.
    // When it becomes 'Selesai' or 'Dibatalkan', you would decrement it.

    /**
     * Check if the pembimbing has reached their maximum quota.
     */
    public function hasReachedQuota()
    {
        return $this->kuota_aktif >= $this->maks_kuota_bimbingan;
    }

    /**
     * Check if the pembimbing has available slots.
     */
    public function hasAvailableSlots()
    {
        return $this->kuota_aktif < $this->maks_kuota_bimbingan;
    }
}