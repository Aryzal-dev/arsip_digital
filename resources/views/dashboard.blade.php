@extends('layouts.arsip')

@section('title', 'Dashboard Arsip Digital')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Dashboard Arsip Digital</h1>
        <p class="text-muted mb-0">
            Selamat datang, {{ auth()->user()->name }}.
        </p>
    </div>

    @if (auth()->user()->role === 'admin')
        <a href="/arsip/create" class="btn btn-primary">
            + Tambah Arsip
        </a>
    @endif
</div>

<div class="row">

    {{-- Total Arsip --}}
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body">
                <h6>Total Arsip</h6>
                <h2>{{ $totalArsip }}</h2>
            </div>
        </div>
    </div>

    {{-- Arsip Masuk --}}
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body">
                <h6>Arsip Masuk</h6>
                <h2>{{ $arsipMasuk }}</h2>
            </div>
        </div>
    </div>

    {{-- Arsip Keluar --}}
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning shadow-sm">
            <div class="card-body">
                <h6>Arsip Keluar</h6>
                <h2>{{ $arsipKeluar }}</h2>
            </div>
        </div>
    </div>

    {{-- Dokumen --}}
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-dark shadow-sm">
            <div class="card-body">
                <h6>Dokumen</h6>
                <h2>{{ $dokumen }}</h2>
            </div>
        </div>
    </div>

</div>

@if ($arsipTanpaFile > 0)
    <div class="alert alert-warning mt-4">
        <strong>⚠️ Perhatian!</strong>
        Ada {{ $arsipTanpaFile }} arsip yang belum memiliki file.

        <a href="/arsip?tanpa_file=1" class="alert-link ms-2">
            Lihat Arsip
        </a>
    </div>
@endif

<div class="card shadow-sm mt-4">
    <div class="card-header">
        <strong>Grafik Arsip</strong>
    </div>

    <div class="card-body">
        <canvas id="arsipChart" height="100"></canvas>
    </div>
</div>


<div class="card shadow-sm mt-3">

    <div class="card-header">
        <strong>Arsip Terbaru</strong>
    </div>

    <div class="card-body">

        @if ($arsip->count() > 0)

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nomor Arsip</th>
                            <th>Judul</th>
                            <th>Jenis</th>
                            <th>Tanggal</th>
                            <th>File</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($arsip->take(5) as $item)

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $item->nomor_arsip }}
                                </td>

                                <td>
                                    <a href="/arsip/{{ $item->id }}" class="text-decoration-none fw-semibold text-primary">
                                        {{ $item->judul }}
                                    </a>
                                </td>

                                <td>
                                    {{ $item->jenis }}
                                </td>

                                <td>
                                    {{ $item->tanggal_arsip }}
                                </td>

                                <td>
                                    @if ($item->file)
                                        <a
                                            href="{{ asset('storage/' . $item->file) }}"
                                            target="_blank"
                                            class="btn btn-success btn-sm">
                                            Lihat File
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="alert alert-info mb-0">
                Belum ada data arsip.
            </div>

        @endif

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('arsipChart');

    const angkaDiAtasBatang = {
        id: 'angkaDiAtasBatang',

        afterDatasetsDraw(chart) {
            const { ctx } = chart;

            chart.data.datasets.forEach((dataset, datasetIndex) => {
                const meta = chart.getDatasetMeta(datasetIndex);

                meta.data.forEach((bar, index) => {
                    const value = dataset.data[index];

                    ctx.save();
                    ctx.font = 'bold 14px Arial';
                    ctx.textAlign = 'center';
                    ctx.fillStyle = '#212529';

                    ctx.fillText(
                        value,
                        bar.x,
                        bar.y - 8
                    );

                    ctx.restore();
                });
            });
        }
    };

    new Chart(ctx, {
        type: 'bar',

        data: {
            labels: [
                'Surat Masuk',
                'Surat Keluar',
                'Dokumen'
            ],

            datasets: [{
                label: 'Jumlah Arsip',

                data: [
                    {{ $arsipMasuk }},
                    {{ $arsipKeluar }},
                    {{ $dokumen }}
                ],

                borderWidth: 1
            }]
        },

        options: {
            responsive: true,

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        stepSize: 1
                    }
                }
            }
        },

        plugins: [angkaDiAtasBatang]
    });
</script>
@endsection