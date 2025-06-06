<?php

// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // Ini memastikan $admin adalah objek Eloquent
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role_id',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function detailMahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'user_id', 'id');
    }

    public function pembimbingDetail() // atau nama lain yang Anda inginkan
    {
        return $this->hasOne(Pembimbing::class, 'user_id');
    }

    public function company()
    {
        return $this->hasOne(\App\Models\Company::class, 'user_id');
    }

    // Relasi untuk mahasiswa yang memiliki bimbingan
    public function bimbinganMagangSebagaiMahasiswa()
    {
        return $this->hasMany(BimbinganMagang::class, 'mahasiswa_user_id');
    }

    // Relasi untuk dosen (pembimbing) yang memiliki mahasiswa bimbingan
    // Ini mengasumsikan Pembimbing model memiliki user_id
    public function mahasiswaYangDibimbing()
    {
        // Seorang user (dosen) punya satu detail pembimbing,
        // dari detail pembimbing itu bisa punya banyak bimbingan magang
        return $this->hasManyThrough(
            BimbinganMagang::class, // Model tujuan akhir
            Pembimbing::class,      // Model perantara
            'user_id',              // Foreign key di tabel pembimbings (menghubungkan User ke Pembimbing)
            'pembimbing_id',        // Foreign key di tabel bimbingan_magangs (menghubungkan Pembimbing ke BimbinganMagang)
            'id',                   // Local key di tabel users
            'id'                    // Local key di tabel pembimbings
        );
    }
}
