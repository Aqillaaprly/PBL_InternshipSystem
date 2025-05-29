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
    protected $table = 'mahasiswas'; // Eksplisit mendefinisikan nama tabel

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

    // Anda bisa menambahkan relasi lain di sini jika mahasiswa
    // memiliki hubungan dengan tabel lain secara langsung
    // Misalnya, jika pendaftar langsung dari Mahasiswa model:
    // public function pendaftarans()
    // {
    //     return $this->hasMany(Pendaftar::class, 'mahasiswa_id'); // Jika FK di pendaftar adalah mahasiswa_id
    // }
}