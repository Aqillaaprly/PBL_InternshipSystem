<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiMagang extends Model
{
    use HasFactory;

    protected $fillable = [
        'bimbingan_magang_id',
        'tanggal',
        'status',
    ];

    public function bimbinganMagang()
    {
        return $this->belongsTo(BimbinganMagang::class);
    }
}