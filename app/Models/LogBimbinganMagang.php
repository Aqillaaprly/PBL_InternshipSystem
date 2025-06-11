<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBimbinganMagang extends Model
{
    use HasFactory;

    protected $fillable = [
        'bimbingan_magang_id',
        'mahasiswa_id',
        'metode_bimbingan',
        'waktu_bimbingan',
        'topik_bimbingan',
        'deskripsi',
        'nilai',
        'komentar',
    ];

    public function bimbinganMagang()
    {
        return $this->belongsTo(BimbinganMagang::class, 'bimbingan_magang_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}