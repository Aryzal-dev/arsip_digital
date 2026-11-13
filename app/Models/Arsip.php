<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Arsip extends Model
{
    protected $table = 'arsip';

    protected $fillable = [
        'nomor_arsip',
        'judul',
        'jenis',
        'klasifikasi_id',
        'tanggal_arsip',
        'keterangan',
        'file',
    ];

    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(KlasifikasiArsip::class, 'klasifikasi_id');
    }
}
