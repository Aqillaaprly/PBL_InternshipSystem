<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembimbing extends Model
{
    use HasFactory;

    protected $table = 'detail_pembimbings';

    protected $fillable = [
        'user_id',
        'nip',
        'jabatan_fungsional',
        'program_studi_pengampu',
        'bidang_keahlian',
        'nomor_telepon_kantor',
        'ruang_kantor',
        'kuota_bimbingan_utama',
        'kuota_bimbingan_pendamping',
        'catatan_tambahan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}