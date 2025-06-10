<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        // ... atribut fillable lainnya ...
        'user_id',
        'nama_perusahaan',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'telepon',
        'email_perusahaan',
        'website',
        'deskripsi',
        'logo_path',
        'status_kerjasama',
    ];

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
}
