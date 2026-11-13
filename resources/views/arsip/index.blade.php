@extends('layouts.arsip')

@section('title', 'Daftar Arsip')

@section('content')

<div class="container py-5">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2>Data Arsip</h2>
            <p class="lead mb-0">Daftar seluruh dokumen arsip digital</p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('arsip.export') }}" class="btn btn-success">
                📊 Export Excel
            </a>

            @if(auth()->user()->role === 'admin')
                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#importModal">
                    📥 Import Arsip
                </button>

                <a href="/arsip/create" class="btn btn-primary">
                    + Tambah Arsip
                </a>
            @endif
        </div>
    </div>

    {{-- Filter Pencarian --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="/arsip" method="GET">
                <div class="row g-2">
                    <div class="col-md-5">
                        <label class="form-label small text-muted">Pencarian</label>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari nomor, judul, klasifikasi, keterangan..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Jenis Arsip</label>
                        <select name="jenis" class="form-select">
                            <option value="">Semua Jenis</option>
                            <option value="Surat Masuk" {{ request('jenis') == 'Surat Masuk' ? 'selected' : '' }}>
                                Surat Masuk
                            </option>
                            <option value="Surat Keluar" {{ request('jenis') == 'Surat Keluar' ? 'selected' : '' }}>
                                Surat Keluar
                            </option>
                            <option value="Dokumen" {{ request('jenis') == 'Dokumen' ? 'selected' : '' }}>
                                Dokumen
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Klasifikasi</label>
                        <select name="klasifikasi_id" class="form-select">
                            <option value="">Semua Klasifikasi</option>
                            @foreach ($klasifikasiList as $klasifikasi)
                                <option value="{{ $klasifikasi->id }}" {{ request('klasifikasi_id') == $klasifikasi->id ? 'selected' : '' }}>
                                    [{{ $klasifikasi->kode_klasifikasi }}] {{ $klasifikasi->jenis_arsip }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            Cari
                        </button>
                    </div>

                    <div class="col-12 mt-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="form-check">
                            <input
                                type="checkbox"
                                name="tanpa_file"
                                value="1"
                                class="form-check-input"
                                id="tanpaFile"
                                {{ request('tanpa_file') ? 'checked' : '' }}>

                            <label class="form-check-label" for="tanpaFile">
                                Hanya tampilkan arsip tanpa file
                            </label>
                        </div>

                        <div>
                            <a href="/arsip" class="btn btn-secondary btn-sm">
                                Reset Filter
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (request('tanpa_file'))
        <div class="alert alert-warning mb-3">
            Daftar arsip yang belum memiliki lampiran berkas file.
        </div>
    @endif

    {{-- Tabel Data Arsip --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nomor Arsip</th>
                            <th>Judul</th>
                            <th>Jenis</th>
                            <th>Klasifikasi</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>File</th>
                            <th style="width: 170px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($arsip as $item)
                            <tr>
                                <td>{{ $arsip->firstItem() + $loop->index }}</td>
                                <td>
                                    <strong>{{ $item->nomor_arsip }}</strong>
                                </td>
                                <td>
                                    <a href="/arsip/{{ $item->id }}" class="text-decoration-none fw-semibold">
                                        {{ $item->judul }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $item->jenis }}</span>
                                </td>
                                <td>
                                    @if ($item->klasifikasi)
                                        <span class="badge bg-info text-dark" title="{{ $item->klasifikasi->jenis_arsip }}">
                                            {{ $item->klasifikasi->kode_klasifikasi }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($item->klasifikasi->jenis_arsip, 25) }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $item->tanggal_arsip }}</td>
                                <td>{{ $item->keterangan ?? '-' }}</td>
                                <td>
                                    @if ($item->file)
                                        <a
                                            href="{{ asset('storage/' . $item->file) }}"
                                            target="_blank"
                                            class="btn btn-success btn-sm mb-1 d-block text-nowrap">
                                            📄 Lihat File
                                        </a>

                                        <a
                                            href="{{ route('arsip.download', $item->id) }}"
                                            class="btn btn-outline-primary btn-sm d-block text-nowrap">
                                            ⬇️ Download
                                        </a>
                                    @else
                                        <span class="text-muted fst-italic">Tanpa File</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a
                                            href="/arsip/{{ $item->id }}"
                                            class="btn btn-info btn-sm text-white">
                                            Detail
                                        </a>

                                        @if(auth()->user()->role === 'admin')
                                            <a
                                                href="/arsip/{{ $item->id }}/edit"
                                                class="btn btn-warning btn-sm">
                                                Edit
                                            </a>

                                            <form
                                                action="/arsip/{{ $item->id }}"
                                                method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus arsip ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    Belum ada data arsip.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $arsip->links() }}
            </div>
        </div>
    </div>

</div>

{{-- Modal Import Arsip --}}
@if(auth()->user()->role === 'admin')
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('arsip.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="importModalLabel">📥 Import Arsip dari Excel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        Pilih berkas Excel (<code>.xlsx</code>, <code>.xls</code>, atau <code>.csv</code>) untuk mengimpor data arsip secara otomatis.
                    </p>

                    <div class="alert alert-info py-2">
                        <small>
                            <strong>Format kolom:</strong> <code>nomor_arsip</code>, <code>judul</code>, <code>jenis</code>, <code>kode_klasifikasi</code>, <code>tanggal_arsip</code> (YYYY-MM-DD), <code>keterangan</code>.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Berkas</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>

                    <div class="mb-2">
                        <a href="{{ route('arsip.template') }}" class="btn btn-outline-secondary btn-sm">
                            ⬇️ Unduh Contoh Template Excel
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
