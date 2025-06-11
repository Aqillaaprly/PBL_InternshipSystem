<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPendaftar extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id',
        'nama_dokumen',
        'file_path',
        'tipe_file',
        'status_validasi',
    ];

    // Define possible validation statuses
    const STATUS_BELUM_DIVERIFIKASI = 'Belum Diverifikasi';
    const STATUS_VALID = 'Valid';
    const STATUS_TIDAK_VALID = 'Tidak Valid';
    const STATUS_PERLU_REVISI = 'Perlu Revisi';

    public static function getValidationStatuses()
    {
        return [
            self::STATUS_BELUM_DIVERIFIKASI,
            self::STATUS_VALID,
            self::STATUS_TIDAK_VALID,
            self::STATUS_PERLU_REVISI,
        ];
    }

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
