<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin DonasiKuy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Admin DonasiKuy</span>

        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-danger">Logout</button>
        </form>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

            {{-- Sidebar --}}
            <i class="bi bi-house"></i> TEST ICON

<aside class="col-md-2 bg-light vh-100 p-3 border-end">
    <ul class="nav flex-column gap-1">

        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2"
               href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2"
               href="{{ route('admin.kyc.index') }}">
                <i class="bi bi-person-check"></i>
                <span>Verifikasi KYC</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2"
               href="{{ route('admin.programs.index') }}">
                <i class="bi bi-collection"></i>
                <span>Verifikasi Program</span>
            </a>
        </li>

    </ul>
</aside>


            {{-- Content --}}
            <main class="col-md-10 p-4">
                @yield('content')
            </main>

        </div>
    </div>

    </body>
    </html>
