<?php

// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\DetailPembimbing;

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
}
