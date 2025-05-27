<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    use HasFactory;

    // Definisikan fillable attributes jika diperlukan
    // protected $fillable = ['company_id', 'judul', 'deskripsi', ...];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}