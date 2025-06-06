<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BimbinganFoto extends Model
{
    protected $fillable = ['bimbingan_id', 'path'];

    public function bimbingan()
    {
        return $this->belongsTo(BimbinganMagang::class, 'bimbingan_id');
    }
}

