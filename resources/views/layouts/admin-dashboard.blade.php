<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin')</title>
    @vite('resources/css/app.css')
    <script defer src="//unpkg.com/alpinejs"></script>

    {{-- Font & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
    </style>

</head>

<body class="bg-slate-100">

<div class="min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white border-r">
        <div class="h-16 flex items-center px-6 border-b">
            <span class="font-semibold text-emerald-600">
                Admin DonasiKuy
            </span>
        </div>

        <nav class="p-4 space-y-1 text-sm">

            <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-4 py-2 rounded hover:bg-slate-100">
                <i class="bi bi-house-door"></i>
                <span>Beranda</span>
            </a>

            <a href="{{ route('admin.kyc.index') }}"
            class="flex items-center gap-3 px-4 py-2 rounded hover:bg-slate-100">
                <i class="bi bi-person-check text-base"></i>
                <span>Verifikasi KYC</span>
            </a>

            <a href="{{ route('admin.campaigns.index') }}"
            class="flex items-center gap-3 px-4 py-2 rounded hover:bg-slate-100">
                <i class="bi bi-collection-play text-xl"></i>
                <span>Verifikasi Campaign</span>

            <a href="{{ route('admin.events.index') }}"
            class="flex items-center gap-3 px-4 py-2 rounded hover:bg-slate-100">
                <i class="bi bi-calendar-event"></i>
                <span>Verifikasi Event</span>
            </a>

            <a href="{{ route('admin.disbursements.index') }}"
            class="flex items-center gap-3 px-4 py-2 rounded hover:bg-slate-100">
                <i class="bi bi-cash-coin"></i>
                <span>Pencairan Dana</span>
            </a>


        </nav>

    </aside>

    {{-- MAIN AREA --}}
    <div class="flex-1 flex flex-col">

        {{-- TOPBAR --}}
        <header class="h-16 bg-white border-b flex items-center justify-between px-6">
            <h1 class="font-semibold">@yield('title')</h1>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="text-sm text-red-600">Logout</button>
            </form>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
