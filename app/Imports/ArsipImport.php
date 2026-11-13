<?php

namespace App\Imports;

use App\Models\Arsip;
use App\Models\KlasifikasiArsip;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ArsipImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Support multiple variations of header naming
        $nomorArsip = $row['nomor_arsip'] ?? $row['nomor'] ?? null;
        $judul = $row['judul_arsip'] ?? $row['judul'] ?? null;
        $jenis = $row['jenis_arsip'] ?? $row['jenis'] ?? null;

        if (empty($nomorArsip) || empty($judul)) {
            return null;
        }

        // Validate or normalize jenis
        $validJenis = ['Surat Masuk', 'Surat Keluar', 'Dokumen'];
        if (!in_array($jenis, $validJenis)) {
            $jenis = 'Dokumen';
        }

        // Tanggal arsip parsing
        $rawTanggal = $row['tanggal_arsip'] ?? $row['tanggal'] ?? null;
        $tanggalArsip = date('Y-m-d');
        if ($rawTanggal) {
            if (is_numeric($rawTanggal)) {
                $tanggalArsip = Carbon::instance(ExcelDate::excelToDateTimeObject($rawTanggal))->format('Y-m-d');
            } else {
                try {
                    $tanggalArsip = Carbon::parse($rawTanggal)->format('Y-m-d');
                } catch (\Exception $e) {
                    $tanggalArsip = date('Y-m-d');
                }
            }
        }

        // Klasifikasi lookup by kode_klasifikasi
        $klasifikasiId = null;
        $kodeKlasifikasi = $row['kode_klasifikasi'] ?? $row['kode'] ?? null;
        if (!empty($kodeKlasifikasi)) {
            $klasifikasi = KlasifikasiArsip::where('kode_klasifikasi', trim($kodeKlasifikasi))->first();
            if ($klasifikasi) {
                $klasifikasiId = $klasifikasi->id;
            }
        }

        $keterangan = $row['keterangan'] ?? null;

        return Arsip::updateOrCreate(
            ['nomor_arsip' => trim($nomorArsip)],
            [
                'judul' => trim($judul),
                'jenis' => $jenis,
                'klasifikasi_id' => $klasifikasiId,
                'tanggal_arsip' => $tanggalArsip,
                'keterangan' => $keterangan,
            ]
        );
    }
}
