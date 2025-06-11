<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivitasFoto extends Model
{
    protected $fillable = ['aktivitas_absensi_id', 'path'];

    public function aktivitas()
    {
        return $this->belongsTo(AktivitasAbsensi::class, 'aktivitas_absensi_id');
    }
}

