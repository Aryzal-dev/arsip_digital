<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KlasifikasiArsip extends Model
{
    protected $table = 'klasifikasi_arsip';

    protected $fillable = [
        'kode_klasifikasi',
        'jenis_arsip',
        'keterangan',
    ];

    public function arsip(): HasMany
    {
        return $this->hasMany(Arsip::class, 'klasifikasi_id');
    }
}