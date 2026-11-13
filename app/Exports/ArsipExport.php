<?php

namespace App\Exports;

use App\Models\Arsip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArsipExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private int $rowNumber = 0;

    public function collection()
    {
        return Arsip::with('klasifikasi')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Arsip',
            'Judul Arsip',
            'Jenis Arsip',
            'Kode Klasifikasi',
            'Nama Klasifikasi',
            'Tanggal Arsip',
            'Keterangan',
            'Status File',
        ];
    }

    public function map($arsip): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $arsip->nomor_arsip,
            $arsip->judul,
            $arsip->jenis,
            $arsip->klasifikasi ? $arsip->klasifikasi->kode_klasifikasi : '-',
            $arsip->klasifikasi ? $arsip->klasifikasi->jenis_arsip : '-',
            $arsip->tanggal_arsip,
            $arsip->keterangan ?? '-',
            $arsip->file ? 'Ada File' : 'Tidak Ada File',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
