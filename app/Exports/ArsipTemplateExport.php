<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArsipTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'nomor_arsip',
            'judul',
            'jenis',
            'kode_klasifikasi',
            'tanggal_arsip',
            'keterangan',
        ];
    }

    public function array(): array
    {
        return [
            [
                'nomor_arsip' => '001/SM/XI/2026',
                'judul' => 'Surat Undangan Rapat Koordinasi',
                'jenis' => 'Surat Masuk',
                'kode_klasifikasi' => 'HK01',
                'tanggal_arsip' => '2026-11-01',
                'keterangan' => 'Undangan rapat koordinasi dinas',
            ],
            [
                'nomor_arsip' => '002/SK/XI/2026',
                'judul' => 'Surat Pengantar Berkas Kerjasama',
                'jenis' => 'Surat Keluar',
                'kode_klasifikasi' => 'HK0101',
                'tanggal_arsip' => '2026-11-05',
                'keterangan' => 'Pengiriman naskah kesepakatan',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
