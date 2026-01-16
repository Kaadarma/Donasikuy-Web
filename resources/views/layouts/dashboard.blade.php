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
        body { font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-slate-50 antialiased">

<div
    x-data="{
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        userOpen: false
    }"
    x-init="$watch('sidebarCollapsed', v => localStorage.setItem('sidebarCollapsed', v))"
    x-cloak
    class="min-h-screen bg-slate-50 flex"
>

    {{-- OVERLAY (mobile) --}}
    <div x-cloak x-show="sidebarOpen" x-transition.opacity
         class="fixed inset-0 bg-black/40 z-40 md:hidden"
         @click="sidebarOpen=false"></div>

    {{-- MOBILE SIDEBAR (drawer) --}}
    <aside x-cloak
        class="fixed inset-y-0 left-0 w-72 max-w-[85vw] bg-white border-r border-slate-200 z-50 md:hidden
               transform transition-transform duration-200"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200">
            <a href="{{ route('landing') }}" class="flex items-center">
                <span class="text-lg font-semibold text-emerald-700">
                    Donasi<span class="text-emerald-500">Kuy</span>
                </span>
            </a>

        <button class="h-10 w-10 inline-flex items-center justify-center rounded-xl border border-slate-200"
                @click="sidebarOpen=false">
            <i class="bi bi-x-lg"></i>
        </button>

        </div>

        <nav class="py-4 space-y-1 text-sm">
            <a href="{{ route('dashboard.index') }}"
               class="flex items-center gap-3 px-6 py-3 border-l-4
                      {{ request()->routeIs('dashboard.index') ? 'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="bi bi-house-door-fill text-base"></i>
                <span>Beranda</span>
            </a>

            <a href="{{ route('dashboard.campaigns.index') }}"
               class="flex items-center gap-3 px-6 py-3 border-l-4
                      {{ request()->routeIs('dashboard.campaigns.*') ? 'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="bi bi-collection-play text-base"></i>
                <span>Campaign</span>
            </a>

            <a href="{{ route('dashboard.events.index') }}"
               class="flex items-center gap-3 px-6 py-3 border-l-4
                      {{ request()->routeIs('dashboard.events.*') ? 'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="bi bi-calendar-event text-base"></i>
                <span>Event</span>
            </a>

            <a href="{{ route('dashboard.donations.index') }}"
               class="flex items-center gap-3 px-6 py-3 border-l-4
                      {{ request()->routeIs('dashboard.donations.*') ? 'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="bi bi-clock-history text-base"></i>
                <span>Riwayat Donasi</span>
            </a>

            <a href="{{ route('dashboard.disbursements.index') }}"
               class="flex items-center gap-3 px-6 py-3 border-l-4
                      {{ request()->routeIs('dashboard.disbursements.*') ? 'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="bi bi-arrow-up-right-square text-base"></i>
                <span>Pencairan Dana</span>
            </a>
        </nav>
    </aside>

    {{-- DESKTOP SIDEBAR (collapse-able) --}}
    <aside
        class="hidden md:flex flex-col bg-white border-r border-slate-200 transition-all duration-200"
        :class="sidebarCollapsed ? 'w-20' : 'w-64'">

    <div
        class="h-16 flex items-center border-b border-slate-200"
    >
        <a href="{{ route('dashboard.index') }}"
        class="flex items-center w-full"
        :class="sidebarCollapsed ? 'justify-center px-0' : 'justify-start px-6'">

            {{-- TEXT (normal) --}}
            <span
                x-show="!sidebarCollapsed"
                class="text-lg font-semibold text-emerald-700 whitespace-nowrap"
            >
                Donasi<span class="text-emerald-500">Kuy</span>
            </span>

            {{-- LOGO (collapse) --}}
            <img
                x-show="sidebarCollapsed"
                src="{{ asset('images/logo.png') }}"
                alt="Logo"
                class="h-16 w-16 object-contain shrink-0"
            >
        </a>
    </div>



        <nav class="flex-1 py-4 space-y-1 text-sm">

            <a href="{{ route('dashboard.index') }}" title="Beranda"
            @class([
                'flex items-center py-2.5 border-l-4 transition',
                'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' => request()->routeIs('dashboard.index'),
                'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' => !request()->routeIs('dashboard.index'),
            ])
            :class="sidebarCollapsed ? 'w-full justify-center px-0' : 'gap-3 px-6'"
            >
                <i class="bi bi-house-door-fill text-base"></i>
                <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Beranda</span>
            </a>


            <a href="{{ route('dashboard.campaigns.index') }}" title="Campaign"
            @class([
                'flex items-center py-2.5 border-l-4 transition',
                'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' => request()->routeIs('dashboard.campaigns.*'),
                'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' => !request()->routeIs('dashboard.campaigns.*'),
            ])
            :class="sidebarCollapsed ? 'w-full justify-center px-0' : 'gap-3 px-6'"
            >
                <i class="bi bi-collection-play text-base"></i>
                <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Campaign</span>
            </a>

            <a href="{{ route('dashboard.events.index') }}" title="Event"
            @class([
                'flex items-center py-2.5 border-l-4 transition',
                'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' => request()->routeIs('dashboard.events.*'),
                'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' => !request()->routeIs('dashboard.events.*'),
            ])
            :class="sidebarCollapsed ? 'w-full justify-center px-0' : 'gap-3 px-6'"
            >
                <i class="bi bi-calendar-event text-base"></i>
                <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Event</span>
            </a>

            <a href="{{ route('dashboard.donations.index') }}" title="Riwayat Donasi"
            @class([
                'flex items-center py-2.5 border-l-4 transition',
                'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' => request()->routeIs('dashboard.donations.*'),
                'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' => !request()->routeIs('dashboard.donations.*'),
            ])
            :class="sidebarCollapsed ? 'w-full justify-center px-0' : 'gap-3 px-6'"
            >
                <i class="bi bi-clock-history text-base"></i>
                <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Riwayat Donasi</span>
            </a>

            <a href="{{ route('dashboard.disbursements.index') }}" title="Pencairan Dana"
            @class([
                'flex items-center py-2.5 border-l-4 transition',
                'border-emerald-500 bg-emerald-50 text-emerald-700 font-medium' => request()->routeIs('dashboard.disbursements.*'),
                'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' => !request()->routeIs('dashboard.disbursements.*'),
            ])
            :class="sidebarCollapsed ? 'w-full justify-center px-0' : 'gap-3 px-6'"
            >
                <i class="bi bi-arrow-up-right-square text-base"></i>
                <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Pencairan Dana</span>
            </a>

        </nav>
    </aside>

    {{-- RIGHT AREA --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- TOPBAR --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8">
            <div class="flex items-center gap-2 min-w-0">

                {{-- Hamburger (mobile) --}}
                <button class="md:hidden inline-flex items-center justify-center h-10 w-10 rounded-xl border border-slate-200"
                        @click="sidebarOpen=true">
                    <i class="bi bi-list text-xl"></i>
                </button>

                {{-- Collapse (desktop) --}}
                <button class="hidden md:inline-flex items-center justify-center h-10 w-10 rounded-xl border border-slate-200"
                        @click="sidebarCollapsed = !sidebarCollapsed"
                        title="Toggle Sidebar">
                    <i class="bi" :class="sidebarCollapsed ? 'bi-layout-sidebar-inset' : 'bi-layout-sidebar'"></i>
                </button>

                {{-- Page title --}}
                <h1 class="text-base font-semibold text-slate-800 truncate">
                    @yield('page_title', 'Dashboard')
                </h1>
            </div>

            {{-- USER DROPDOWN --}}
            <div class="relative">
                <button @click="userOpen = !userOpen" class="flex items-center gap-3 focus:outline-none">
                    <div class="hidden sm:flex flex-col items-end text-right">
                        <span class="text-sm font-medium text-slate-800">{{ auth()->user()->name }}</span>
                        <span class="text-xs text-slate-500">{{ auth()->user()->email }}</span>
                    </div>

                    <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-400
                                flex items-center justify-center text-white text-sm font-semibold shadow">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </button>

                <div x-cloak x-show="userOpen" @click.away="userOpen=false" x-transition
                     class="absolute right-0 mt-2 w-52 rounded-xl bg-white shadow-lg border border-slate-200 py-2 z-50">
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
        <main class="flex-1 px-4 md:px-8 py-6 space-y-6 min-w-0">
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>

        <p class="text-[11px] text-slate-400 text-center py-4 px-4">
            Copyright © {{ now()->year }} DonasiKuy. All rights reserved.
        </p>
    </div>
</div>

</body>
</html>
