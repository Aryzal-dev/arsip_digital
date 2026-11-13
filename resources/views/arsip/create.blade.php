@extends('layouts.arsip')

@section('title', 'Tambah Arsip')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Arsip</h2>

    <a href="/arsip" class="btn btn-secondary">
        Kembali
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Terjadi kesalahan!</strong>

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="/arsip" method="POST" enctype="multipart/form-data">

    @csrf

    <div class="mb-3">
        <label class="form-label">Nomor Arsip</label>
        <input
            type="text"
            name="nomor_arsip"
            class="form-control"
            value="{{ old('nomor_arsip') }}"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Judul</label>
        <input
            type="text"
            name="judul"
            class="form-control"
            value="{{ old('judul') }}"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Jenis</label>
        <select name="jenis" class="form-select">
            <option value="">-- Pilih Jenis --</option>
            <option value="Surat Masuk" {{ old('jenis') == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
            <option value="Surat Keluar" {{ old('jenis') == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
            <option value="Dokumen" {{ old('jenis') == 'Dokumen' ? 'selected' : '' }}>Dokumen</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Klasifikasi Arsip</label>
        <select name="klasifikasi_id" class="form-select">
            <option value="">-- Pilih Klasifikasi (Opsional) --</option>
            @foreach ($klasifikasiList as $klasifikasi)
                <option value="{{ $klasifikasi->id }}" {{ old('klasifikasi_id') == $klasifikasi->id ? 'selected' : '' }}>
                    [{{ $klasifikasi->kode_klasifikasi }}] {{ $klasifikasi->jenis_arsip }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Pilih kode klasifikasi yang sesuai dengan dokumen arsip.</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Tanggal Arsip</label>
        <input
            type="date"
            name="tanggal_arsip"
            class="form-control"
            value="{{ old('tanggal_arsip') }}"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Keterangan</label>
        <textarea
            name="keterangan"
            class="form-control"
            rows="4"
        >{{ old('keterangan') }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">File Arsip</label>
        <input
            type="file"
            name="file"
            class="form-control"
            accept=".pdf,.doc,.docx"
        >

        <small class="text-muted">
            Format: PDF, DOC, DOCX. Maksimal 10 MB.
        </small>
    </div>

    <button type="submit" class="btn btn-primary">
        Simpan Arsip
    </button>

</form>

@endsection