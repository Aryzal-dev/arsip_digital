<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arsip;
use App\Models\KlasifikasiArsip;
use App\Models\ActivityLog;
use App\Exports\ArsipExport;
use App\Exports\ArsipTemplateExport;
use App\Imports\ArsipImport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $jenis = $request->jenis;
        $klasifikasiId = $request->klasifikasi_id;
        $tanpaFile = $request->tanpa_file;

        $arsip = Arsip::with('klasifikasi')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_arsip', 'like', '%' . $search . '%')
                        ->orWhere('judul', 'like', '%' . $search . '%')
                        ->orWhere('jenis', 'like', '%' . $search . '%')
                        ->orWhere('keterangan', 'like', '%' . $search . '%')
                        ->orWhereHas('klasifikasi', function ($kq) use ($search) {
                            $kq->where('kode_klasifikasi', 'like', '%' . $search . '%')
                                ->orWhere('jenis_arsip', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($jenis, function ($query) use ($jenis) {
                $query->where('jenis', $jenis);
            })
            ->when($klasifikasiId, function ($query) use ($klasifikasiId) {
                $query->where('klasifikasi_id', $klasifikasiId);
            })
            ->when($tanpaFile, function ($query) {
                $query->whereNull('file');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $klasifikasiList = KlasifikasiArsip::orderBy('kode_klasifikasi')->get();

        return view('arsip.index', compact('arsip', 'klasifikasiList'));
    }

    public function show($id)
    {
        $arsip = Arsip::with('klasifikasi')->findOrFail($id);

        return view('arsip.show', compact('arsip'));
    }

    public function create()
    {
        $klasifikasiList = KlasifikasiArsip::orderBy('kode_klasifikasi')->get();

        return view('arsip.create', compact('klasifikasiList'));
    }

    public function edit($id)
    {
        $arsip = Arsip::findOrFail($id);
        $klasifikasiList = KlasifikasiArsip::orderBy('kode_klasifikasi')->get();

        return view('arsip.edit', compact('arsip', 'klasifikasiList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_arsip' => 'required|unique:arsip,nomor_arsip,' . $id,
            'judul' => 'required',
            'jenis' => 'required|in:Surat Masuk,Surat Keluar,Dokumen',
            'klasifikasi_id' => 'nullable|exists:klasifikasi_arsip,id',
            'tanggal_arsip' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $arsip = Arsip::findOrFail($id);

        if ($request->hasFile('file')) {

            // Simpan file baru terlebih dahulu
            $file = $request->file('file')->store('arsip', 'public');

            // Hapus file lama setelah file baru berhasil disimpan
            if ($arsip->file) {
                Storage::disk('public')->delete($arsip->file);
            }
        } else {

            // Jika tidak memilih file baru, gunakan file lama
            $file = $arsip->file;
        }

        $arsip->update([
            'nomor_arsip' => $request->nomor_arsip,
            'judul' => $request->judul,
            'jenis' => $request->jenis,
            'klasifikasi_id' => $request->klasifikasi_id,
            'tanggal_arsip' => $request->tanggal_arsip,
            'keterangan' => $request->keterangan,
            'file' => $file,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Edit Arsip',
            'keterangan' => 'Mengubah arsip: ' . $arsip->judul,
            'ip_address' => $request->ip(),
        ]);

        return redirect('/arsip')->with('success', 'Arsip berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $arsip = Arsip::findOrFail($id);

        // Catat aktivitas sebelum arsip dihapus
        ActivityLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Hapus Arsip',
            'keterangan' => 'Menghapus arsip: ' . $arsip->judul,
            'ip_address' => request()->ip(),
        ]);

        // Hapus file fisik dari storage jika ada
        if ($arsip->file && Storage::disk('public')->exists($arsip->file)) {
            Storage::disk('public')->delete($arsip->file);
        }

        $arsip->delete();

        return redirect('/arsip')->with('success', 'Arsip berhasil dihapus.');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nomor_arsip' => 'required|unique:arsip,nomor_arsip',
            'judul' => 'required',
            'jenis' => 'required|in:Surat Masuk,Surat Keluar,Dokumen',
            'klasifikasi_id' => 'nullable|exists:klasifikasi_arsip,id',
            'tanggal_arsip' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $file = $request->hasFile('file') ? $request->file('file')->store('arsip', 'public') : null;

        $arsip = Arsip::create([
            'nomor_arsip' => $request->nomor_arsip,
            'judul' => $request->judul,
            'jenis' => $request->jenis,
            'klasifikasi_id' => $request->klasifikasi_id,
            'tanggal_arsip' => $request->tanggal_arsip,
            'keterangan' => $request->keterangan,
            'file' => $file,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tambah Arsip',
            'keterangan' => 'Menambahkan arsip: ' . $arsip->judul,
            'ip_address' => $request->ip(),
        ]);

        return redirect('/arsip')->with('success', 'Arsip berhasil ditambahkan.');
    }


    public function download($id)
    {
        $arsip = Arsip::findOrFail($id);

        if (!$arsip->file || !Storage::disk('public')->exists($arsip->file)) {
            abort(404, 'File arsip tidak ditemukan.');
        }

        // Catat aktivitas download
        ActivityLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Download Arsip',
            'keterangan' => 'Mengunduh arsip: ' . $arsip->judul,
            'ip_address' => request()->ip(),
        ]);

        return Storage::disk('public')->download($arsip->file);
    }

    public function export()
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Export Arsip',
            'keterangan' => 'Mengunduh seluruh data arsip ke berkas Excel',
            'ip_address' => request()->ip(),
        ]);

        return Excel::download(new ArsipExport, 'data_arsip_' . date('Y-m-d_His') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new ArsipImport, $request->file('file'));

            ActivityLog::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Import Arsip',
                'keterangan' => 'Mengimpor arsip dari file Excel: ' . $request->file('file')->getClientOriginalName(),
                'ip_address' => $request->ip(),
            ]);

            return redirect('/arsip')->with('success', 'Data arsip berhasil diimpor dari berkas Excel.');
        } catch (\Exception $e) {
            return redirect('/arsip')->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new ArsipTemplateExport, 'template_import_arsip.xlsx');
    }
}
