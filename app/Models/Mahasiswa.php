<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mahasiswas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'email',
        'kelas',
        'program_studi',
        'nomor_hp',
        'alamat',
    ];

    /**
     * Get the user that owns the mahasiswa profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function pendaftar() // Changed from pendaftarans to pendaftar (singular) to match the call in controller
    {
        return $this->hasMany(Pendaftar::class, 'mahasiswa_id'); // Foreign key in pendaftars table is 'mahasiswa_id'
    }

    public function bimbinganMagangs()
    {
        return $this->hasMany(BimbinganMagang::class, 'mahasiswa_user_id', 'user_id');
    }
    public function aktivitasMagangs()
    {
        return $this->hasMany(AktivitasMagang::class);
    }
}
