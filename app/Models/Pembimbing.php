<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini jika belum ada
use Illuminate\Database\Eloquent\Model;

class Pembimbing extends Model
{
    use HasFactory; // Tambahkan ini untuk bisa menggunakan factory

    /**
     * Nama tabel yang terhubung dengan model ini.
     * Laravel akan otomatis mengasumsikan 'pembimbings' jika nama model adalah 'Pembimbing',
     * tapi baik untuk didefinisikan secara eksplisit.
     *
     * @var string
     */
    protected $table = 'pembimbings';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',            // ID dari tabel users, jika pembimbing memiliki akun sistem
        'nip',                // Nomor Induk Pegawai, diasumsikan unik
        'nama_lengkap',
        'email_institusi',    // Email resmi institusi, diasumsikan unik
        'nomor_telepon',
        'jabatan_fungsional',
        'program_studi_homebase',
        'bidang_keahlian_utama',
        'kuota_bimbingan_aktif',
        'maks_kuota_bimbingan',
        'status_aktif',       // boolean (true/false atau 1/0)
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status_aktif' => 'boolean', // Casting status_aktif ke tipe boolean
        'kuota_bimbingan_aktif' => 'integer',
        'maks_kuota_bimbingan' => 'integer',
        'email_verified_at' => 'datetime', // Jika Anda menambahkan verifikasi email untuk pembimbing
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model User.
     * Seorang pembimbing (jika memiliki akun) terhubung ke satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

       // Seorang pembimbing bisa membimbing banyak mahasiswa (melalui tabel BimbinganMagang)
    public function bimbinganMagangs()
    {
        return $this->hasMany(BimbinganMagang::class, 'pembimbing_id');
    }

    /**
     * Contoh relasi: Seorang pembimbing bisa memiliki banyak mahasiswa bimbingan.
     * Ini memerlukan tabel 'mahasiswas' memiliki kolom 'pembimbing_id'.
     *
     * public function mahasiswas()
     * {
     * return $this->hasMany(Mahasiswa::class, 'pembimbing_id');
     * }
     */

    /**
     * Contoh relasi: Seorang pembimbing bisa terdaftar di banyak lowongan sebagai kontak.
     * Ini memerlukan tabel pivot jika relasinya many-to-many.
     *
     * public function lowongans()
     * {
     * return $this->belongsToMany(Lowongan::class, 'lowongan_pembimbing', 'pembimbing_id', 'lowongan_id');
     * }
     */
}