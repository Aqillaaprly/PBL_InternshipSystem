<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'telepon',
        'email_perusahaan',
        'website',
        'about',
        'deskripsi',
        'logo_path',
        'status_kerjasama',
    ];

    protected $appends = ['logo_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lowongans()
    {
        return $this->hasMany(Lowongan::class);
    }

    public function aktivitasMagangs()
    {
        return $this->hasMany(AktivitasMagang::class, 'company_id');
    }

    public function bimbinganMagangs()
    {
        return $this->hasMany(BimbinganMagang::class, 'company_id');
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo_path && Storage::disk('public')->exists($this->logo_path)) {
            return asset('storage/' . $this->logo_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama_perusahaan) . '&size=128&background=2563EB&color=fff';
    }

    public function getStatusAttribute()
    {
        return $this->status_kerjasama ?? 'Review';
    }

    public function getLocationAttribute()
    {
        return implode(', ', array_filter([$this->kota, $this->provinsi]));
    }
}
