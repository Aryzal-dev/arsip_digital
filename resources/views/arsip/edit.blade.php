@extends('layouts.arsip')

@section('title', 'Edit Arsip')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">

                <div class="card-header bg-warning">
                    <h4 class="mb-0">Edit Arsip</h4>
                </div>

                <div class="card-body">
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

                    <form action="/arsip/{{ $arsip->id }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nomor Arsip</label>

                        <input
                            type="text"
                            name="nomor_arsip"
                            class="form-control"
                            value="{{ old('nomor_arsip', $arsip->nomor_arsip) }}"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Judul Arsip</label>

                        <input
                            type="text"
                            name="judul"
                            class="form-control"
                            value="{{ old('judul', $arsip->judul) }}"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Arsip</label>

                        <select class="form-select" name="jenis">
                            <option
                                value="Surat Masuk"
                                {{ old('jenis', $arsip->jenis) == 'Surat Masuk' ? 'selected' : '' }}
                            >
                                Surat Masuk
                            </option>

                            <option
                                value="Surat Keluar"
                                {{ old('jenis', $arsip->jenis) == 'Surat Keluar' ? 'selected' : '' }}
                            >
                                Surat Keluar
                            </option>

                            <option
                                value="Dokumen"
                                {{ old('jenis', $arsip->jenis) == 'Dokumen' ? 'selected' : '' }}
                            >
                                Dokumen
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Klasifikasi Arsip</label>

                        <select class="form-select" name="klasifikasi_id">
                            <option value="">-- Pilih Klasifikasi (Opsional) --</option>
                            @foreach ($klasifikasiList as $klasifikasi)
                                <option
                                    value="{{ $klasifikasi->id }}"
                                    {{ old('klasifikasi_id', $arsip->klasifikasi_id) == $klasifikasi->id ? 'selected' : '' }}
                                >
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
                            value="{{ old('tanggal_arsip', $arsip->tanggal_arsip) }}"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>

                        <textarea
                            class="form-control"
                            name="keterangan"
                            rows="4"
                        >{{ old('keterangan', $arsip->keterangan) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File</label>

                        @if ($arsip->file)
                            <div class="mb-2">
                                <span class="badge bg-success">File tersimpan</span>
                                <a href="{{ asset('storage/' . $arsip->file) }}" target="_blank" class="ms-1 text-decoration-none">Lihat file saat ini</a>
                            </div>
                        @endif

                        <input
                            type="file"
                            name="file"
                            class="form-control"
                            accept=".pdf,.doc,.docx"
                        >
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file. Format: PDF, DOC, DOCX. Maks: 10 MB.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                    <a href="/arsip" class="btn btn-secondary">
                        Batal
                    </a>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection