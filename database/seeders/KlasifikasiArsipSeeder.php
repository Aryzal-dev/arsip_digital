<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KlasifikasiArsip;

class KlasifikasiArsipSeeder extends Seeder
{
    public function run(): void
    {
        $klasifikasi = [
            [
                'kode_klasifikasi' => 'HK',
                'jenis_arsip' => 'HUKUM',
                'keterangan' => 'Klasifikasi bidang hukum',
            ],
            [
                'kode_klasifikasi' => 'HK01',
                'jenis_arsip' => 'Produk Hukum',
                'keterangan' => 'Produk hukum dan perundang-undangan',
            ],
            [
                'kode_klasifikasi' => 'HK0101',
                'jenis_arsip' => 'Produk Hukum Bersifat Pengaturan',
                'keterangan' => 'Peraturan, penetapan, dan kebijakan',
            ],
            [
                'kode_klasifikasi' => 'HK0102',
                'jenis_arsip' => 'Naskah Akademik',
                'keterangan' => 'Naskah akademik penyusunan peraturan',
            ],
            [
                'kode_klasifikasi' => 'HK0103',
                'jenis_arsip' => 'Daftar Inventarisasi Masalah (DIM)',
                'keterangan' => 'DIM pembahasan rancangan peraturan',
            ],
            [
                'kode_klasifikasi' => 'HK0104',
                'jenis_arsip' => 'Rancangan Awal Peraturan',
                'keterangan' => 'Draft rancangan awal peraturan',
            ],
        ];

        foreach ($klasifikasi as $item) {
            KlasifikasiArsip::create($item);
        }
    }
}