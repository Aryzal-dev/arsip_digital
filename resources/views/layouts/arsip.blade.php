<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Arsip Digital')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">

            {{-- Logo / Nama Aplikasi --}}
            <a class="navbar-brand fw-bold" href="/dashboard">
                📁 Arsip Digital
            </a>

            {{-- Menu --}}
            <div class="d-flex align-items-center gap-2">

                <a href="/dashboard" class="btn btn-outline-light btn-sm">
                    Dashboard
                </a>

                <a href="/arsip" class="btn btn-outline-light btn-sm">
                    Arsip
                </a>

                @if (auth()->user()->role === 'admin')
                    <a href="/users" class="btn btn-outline-light btn-sm">
                        Manajemen User
                    </a>

                    <a href="/activity-logs" class="btn btn-outline-light btn-sm">
                        Log Aktivitas
                    </a>

                    <a href="/arsip/create" class="btn btn-primary btn-sm">
                        + Tambah Arsip
                    </a>
                @endif

                
                {{-- User --}}
                <span class="text-white ms-2">
                    {{ auth()->user()->name }}
                </span>

                {{-- Logout --}}
                <form action="/logout" method="POST" class="d-inline">
                    @csrf

                    <button type="submit" class="btn btn-danger btn-sm">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </nav>

    {{-- Konten halaman --}}
    <main class="container py-4">

        @yield('content')

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>