@extends('layouts.arsip')

@section('title', 'Manajemen User')

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen User</h1>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <strong>Daftar User</strong>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($users as $user)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>

                        <td>
                            @if ($user->role === 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @else
                                <span class="badge bg-secondary">User</span>
                            @endif
                        </td>

                        <td>
                            {{ $user->created_at->format('d-m-Y') }}
                        </td>
                        <td>
                            @if ($user->id !== auth()->id())
                            <form action="/users/{{ $user->id }}/role" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')

                                @if ($user->role === 'admin')
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        Jadikan User
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        Jadikan Admin
                                    </button>
                                @endif
                            </form>

                            <form action="/users/{{ $user->id }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus user ini?')">
                                    Hapus
                                </button>
                            </form>
                            @else

                                <span class="text-muted">Akun aktif</span>

                            @endif
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="text-center">
                            Belum ada user.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
</div>

@endsection