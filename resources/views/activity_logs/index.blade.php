@extends('layouts.arsip')

@section('title', 'Log Aktivitas')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Log Aktivitas</h2>
        <p class="text-muted mb-0">Riwayat aktivitas pengguna dalam sistem arsip digital</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <strong>Riwayat Aktivitas</strong>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Aktivitas</th>
                        <th>Keterangan</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $log)
                        <tr>
                            <td>{{ $activities->firstItem() + $loop->index }}</td>
                            <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                            <td>
                                @if ($log->user)
                                    <strong>{{ $log->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $log->user->email }}</small>
                                @else
                                    <span class="text-muted">Sistem / Terhapus</span>
                                @endif
                            </td>
                            <td>
                                @if (str_contains(strtolower($log->aktivitas), 'tambah'))
                                    <span class="badge bg-success">{{ $log->aktivitas }}</span>
                                @elseif (str_contains(strtolower($log->aktivitas), 'edit') || str_contains(strtolower($log->aktivitas), 'ubah'))
                                    <span class="badge bg-warning text-dark">{{ $log->aktivitas }}</span>
                                @elseif (str_contains(strtolower($log->aktivitas), 'hapus'))
                                    <span class="badge bg-danger">{{ $log->aktivitas }}</span>
                                @elseif (str_contains(strtolower($log->aktivitas), 'download'))
                                    <span class="badge bg-info text-dark">{{ $log->aktivitas }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $log->aktivitas }}</span>
                                @endif
                            </td>
                            <td>{{ $log->keterangan ?? '-' }}</td>
                            <td><code>{{ $log->ip_address ?? '-' }}</code></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Belum ada aktivitas yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $activities->links() }}
        </div>
    </div>
</div>

@endsection
