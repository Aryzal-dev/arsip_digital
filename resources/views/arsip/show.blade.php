@extends('layouts.arsip')

@section('title', 'Detail Arsip')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Detail Arsip</h2>
        <p class="text-muted mb-0">
            Informasi lengkap dokumen arsip
        </p>
    </div>

    <a href="/arsip" class="btn btn-secondary">
        ← Kembali
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <strong>{{ $arsip->judul }}</strong>
    </div>

    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                Nomor Arsip
            </div>

            <div class="col-md-9">
                {{ $arsip->nomor_arsip }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                Judul
            </div>

            <div class="col-md-9">
                {{ $arsip->judul }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                Jenis
            </div>

            <div class="col-md-9">
                {{ $arsip->jenis }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                Klasifikasi
            </div>

            <div class="col-md-9">
                @if ($arsip->klasifikasi)
                    <span class="badge bg-secondary me-1">{{ $arsip->klasifikasi->kode_klasifikasi }}</span>
                    {{ $arsip->klasifikasi->jenis_arsip }}
                    @if ($arsip->klasifikasi->keterangan)
                        <br><small class="text-muted">{{ $arsip->klasifikasi->keterangan }}</small>
                    @endif
                @else
                    <span class="text-muted">Tidak diklasifikasikan</span>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                Tanggal Arsip
            </div>

            <div class="col-md-9">
                {{ $arsip->tanggal_arsip }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                Keterangan
            </div>

            <div class="col-md-9">
                {{ $arsip->keterangan ?? '-' }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                File
            </div>

            <div class="col-md-9">

                @if ($arsip->file)

                    <a
                        href="{{ asset('storage/' . $arsip->file) }}"
                        target="_blank"
                        class="btn btn-success me-2"
                    >
                        📄 Lihat File
                    </a>

                    <a
                        href="{{ route('arsip.download', $arsip->id) }}"
                        class="btn btn-primary"
                    >
                        ⬇️ Download File
                    </a>

                @else

                    <span class="text-muted">
                        Arsip ini belum memiliki file.
                    </span>

                @endif

            </div>
        </div>

    </div>
</div>

@endsection