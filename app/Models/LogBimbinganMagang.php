<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBimbinganMagang extends Model
{
    use HasFactory;

    protected $fillable = [
        'bimbingan_magang_id',
        'metode',
        'waktu_bimbingan',
        'topik',
        'deskripsi',
        'status',
    ];

    public function bimbinganMagang()
    {
        return $this->belongsTo(BimbinganMagang::class);
    }
}