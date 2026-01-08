<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'DonasiKuy')</title>

    {{-- Font & Icons --}}
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
<body class="bg-slate-50 antialiased">
<div class="min-h-screen bg-slate-50 flex">

    {{-- SIDEBAR --}}
    <aside class="hidden md:flex w-64 flex-col bg-white border-r border-slate-200">
        <div class="h-16 flex items-center px-6 border-b border-slate-200">
            <a href="{{ route('landing') }}" class="flex items-center">
                <span class="text-lg font-semibold text-emerald-700">
                    Donasi<span class="text-emerald-500">Kuy</span>
                </span>
            </a>
        </div>

        <nav class="flex-1 py-4 space-y-1 text-sm">
            <a href="{{ route('dashboard.index') }}"
               class="flex items-center gap-3 px-6 py-2.5 border-l-4
                      {{ request()->routeIs('dashboard.index') ? 'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="bi bi-house-door-fill text-base"></i>
                <span>Beranda</span>
            </a>

            
            <a href="{{ route('dashboard.campaigns.index') }}"
               class="flex items-center gap-3 px-6 py-2.5 border-l-4
                      {{ request()->routeIs('dashboard.campaigns.*') ? 'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="bi bi-collection-play text-base"></i>
                <span>Campaign</span>
            </a>

            <a href="{{ route('dashboard.events.index') }}"
               class="flex items-center gap-3 px-6 py-2.5 border-l-4
                      {{ request()->routeIs('dashboard.events.*') ? 'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="bi bi-calendar-event text-base"></i>
                <span>Event</span>
            </a>
            
            <a href="{{ route('dashboard.donations.index') }}"
            class="flex items-center gap-3 px-6 py-2.5 border-l-4
                    {{ request()->routeIs('dashboard.donations.*')
                        ? 'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium'
                        : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="bi bi-clock-history text-base"></i>
                <span>Riwayat Donasi</span>
            </a>



            <a href="{{ route('dashboard.disbursements.index') }}"
            class="flex items-center gap-3 px-6 py-2.5 border-l-4
                    {{ request()->routeIs('dashboard.disbursements.*') ? 'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="bi bi-arrow-up-right-square text-base"></i>
                <span>Pencairan Dana</span>
            </a>
        </nav>
    </aside>

    {{-- AREA KANAN --}}
    <div class="flex-1 flex flex-col">

        {{-- TOPBAR --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8">
            <div class="flex items-center gap-2">
                <button class="md:hidden inline-flex items-center justify-center p-2 rounded-lg border border-slate-200">
                    <i class="bi bi-list text-xl"></i>
                </button>

                {{-- Judul halaman dashboard --}}
                <h1 class="hidden md:block text-base font-semibold text-slate-800">
                    @yield('page_title', 'Dashboard')
                </h1>
            </div>

            {{-- USER DROPDOWN --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-3 focus:outline-none">
                    <div class="hidden sm:flex flex-col items-end text-right">
                        <span class="text-sm font-medium text-slate-800">{{ auth()->user()->name }}</span>
                        <span class="text-xs text-slate-500">{{ auth()->user()->email }}</span>
                    </div>

                    <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-400
                                flex items-center justify-center text-white text-sm font-semibold shadow">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </button>

                <div x-show="open" @click.away="open=false" x-transition
                     class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-lg border border-slate-200 py-2 z-50">
                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                        Profil Saya
                    </a>
                    <a href="{{ route('landing') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                        Beranda
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 px-4 md:px-8 py-6 space-y-6">
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                    {{ session('success') }}
                </div>
                @endif

            @yield('content')
        </main>

        <p class="text-[11px] text-slate-400 text-center py-4">
            Copyright © {{ now()->year }} DonasiKuy. All rights reserved.
        </p>
    </div>
</div>
</body>

</html>
