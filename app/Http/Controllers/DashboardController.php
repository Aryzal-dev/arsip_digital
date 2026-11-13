<?php

namespace App\Http\Controllers;

use App\Models\Arsip;

class DashboardController extends Controller
{
    public function index()
    {
        $totalArsip = Arsip::count();

        $arsipMasuk = Arsip::where('jenis', 'Surat Masuk')->count();

        $arsipKeluar = Arsip::where('jenis', 'Surat Keluar')->count();

        $dokumen = Arsip::where('jenis', 'Dokumen')->count();

        $arsip = Arsip::latest()->get();
        $arsipTanpaFile = Arsip::whereNull('file')->count();

        return view('dashboard', compact(
            'totalArsip',
            'arsipMasuk',
            'arsipKeluar',
            'dokumen',
            'arsip',
            'arsipTanpaFile'
        ));
    }
}