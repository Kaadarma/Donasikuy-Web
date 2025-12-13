<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BantuYuk')</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    {{-- Alpine & Tailwind --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .toast-show {
            animation: fadeIn 0.3s ease-out, fadeOut 0.3s ease-in 2.7s forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(50px);
            }
        }

        .progress-bar {
            height: 3px;
            background: #ef4444; /* warna merah / sesuai status */
            animation: progressRun 3s linear forwards;
        }

        @keyframes progressRun {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }
    </style>
</head>

<body class="antialiased text-slate-800 font-[Inter]">

    {{-- GLOBAL TOAST / POPUP --}}
    @if (session('success') || session('status') || session('error'))

        <div x-data="{ show: true }"
             x-init="setTimeout(() => show = false, 3500)"
             x-show="show"
             class="fixed bottom-4 right-4 z-50">

        @if (session('success') || session('status'))
            <div class="toast-show bg-emerald-600 text-white px-4 py-3 rounded-xl shadow-lg text-sm flex items-start gap-2 w-72">
                <span class="mt-0.5">✅</span>
                <div class="flex-1">
                    <p class="font-medium">Berhasil</p>
                    <p class="text-xs mt-0.5">
                        {{ session('success') ?? session('status') }}
                    </p>
                    <div class="progress-bar mt-2 rounded-full"></div>
                </div>
            </div>
        @endif

            @if (session('error'))
                <div class="toast-show bg-red-600 text-white px-4 py-3 rounded-xl shadow-lg text-sm flex items-start gap-2 w-72 mt-2">
                    <span class="mt-0.5">⚠️</span>
                    <div class="flex-1">
                        <p class="font-medium">Gagal</p>
                        <p class="text-xs mt-0.5">
                            {{ session('error') }}
                        </p>
                        <div class="progress-bar mt-2 rounded-full"></div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- HEADER / NAVBAR --}}
    @unless (View::hasSection('authpage'))
    <header class="sticky top-0 z-50 bg-white border-b shadow-sm">
        <div class="max-w-7xl mx-auto h-20 px-4 sm:px-6 lg:px-8 flex items-center justify-between">

            {{-- Logo kiri --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" class="h-20 w-20 object-contain" alt="Logo">
                <span class="font-bold text-xl text-emerald-700">
                    DonasiKuy<span class="text-sm text-emerald-400">.com</span>
                </span>
            </a>

            {{-- Search Bar Tengah --}}
            <form action="{{ route('program.search') }}" method="GET" class="hidden md:flex flex-1 mx-8">
                <div class="relative w-full max-w-xl">
                    <input
                        type="search"
                        name="q"
                        class="w-full h-12 border border-slate-300 rounded-xl pl-4 pr-10 text-sm outline-none
                               focus:ring-2 focus:ring-emerald-500"
                        placeholder="Cari Program"
                        value="{{ request('q') }}"
                    >
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m21 21-4.35-4.35M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" />
                        </svg>
                    </span>
                </div>
            </form>

            {{-- Menu kanan --}}
            <nav class="flex items-center gap-6">
                <a href="#" class="text-slate-700 font-medium hover:text-emerald-600">Donasi</a>
                <a href="#" class="text-slate-700 font-medium hover:text-emerald-600">Event</a>
                <a href="{{ route('dana-punia.index') }}" class="text-slate-700 font-medium hover:text-emerald-600">Punia</a>

                @guest
                    <a href="{{ route('login') }}" class="text-slate-700 font-medium hover:text-emerald-600">
                        Masuk
                    </a>
                @endguest

                {{-- Jika login --}}
                @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2">
                            <img
                                src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : asset('images/humans.jpg') }}"
                                class="h-8 w-8 rounded-full border object-cover"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-600" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open" @click.away="open=false"
                            class="absolute right-0 mt-3 w-48 bg-white border rounded-xl shadow-lg overflow-hidden z-50">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm hover:bg-slate-100">Profil
                                Saya</a>
                            <a href="{{ route('dashboard.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-slate-100">Dashboard</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                >
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth


                <a href="{{ route('galang.create') }}"
                   class="hidden md:inline-flex items-center rounded-full bg-emerald-600 hover:bg-emerald-700 
                          text-white text-sm font-semibold px-5 py-2.5 shadow-md shadow-emerald-500/30">
                    Galang Dana
                </a>
            </nav>
        </div>
    </header>
    @endunless

    {{-- MAIN --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @unless (View::hasSection('authpage'))
    <footer class="relative bg-white border-t mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

                {{-- Kolom 1: Brand & Sosmed --}}
                <div>
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo BantuYuk"
                             class="h-10 w-10 rounded-full object-cover">
                        <span class="font-semibold text-emerald-800">BantuYuk</span>
                    </div>

                    <p class="mt-6 font-medium text-slate-800">Saran dan masukan untuk kami</p>
                    <a href="#"
                       class="inline-flex items-center rounded-md border border-emerald-600 text-emerald-700 px-4 py-2 mt-2 text-sm hover:bg-emerald-50">
                        Kirim Saran
                    </a>

                    <div class="mt-5 flex items-center gap-4">
                        {{-- Instagram --}}
                        <a href="#"
                           class="h-8 w-8 rounded-full border border-slate-200 flex items-center justify-center hover:border-emerald-600">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 text-slate-600">
                                <path fill="currentColor"
                                      d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7Zm10 2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h10Zm-5 3a5 5 0 1 0 0 10a5 5 0 0 0 0-10Zm0 2a3 3 0 1 1 0 6a3 3 0 0 1 0-6Zm5.5-.75a.75.75 0 1 0 0 1.5a.75.75 0 0 0 0-1.5Z" />
                            </svg>
                        </a>

                        {{-- Facebook --}}
                        <a href="#"
                           class="h-8 w-8 rounded-full border border-slate-200 flex items-center justify-center hover:border-emerald-600">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 text-slate-600">
                                <path fill="currentColor"
                                      d="M13 22v-8h3l1-4h-4V8a1 1 0 0 1 1-1h3V3h-3a5 5 0 0 0-5 5v2H6v4h3v8z" />
                            </svg>
                        </a>

                        {{-- X / Twitter --}}
                        <a href="#"
                           class="h-8 w-8 rounded-full border border-slate-200 flex items-center justify-center hover:border-emerald-600">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 text-slate-600">
                                <path fill="currentColor"
                                      d="M3 3h4.5l4.05 5.85L16.5 3H21l-7.5 9.4L21 21h-4.5l-4.2-6.1L7.5 21H3l7.8-9.6z" />
                            </svg>
                        </a>

                        {{-- YouTube --}}
                        <a href="#"
                           class="h-8 w-8 rounded-full border border-slate-200 flex items-center justify-center hover:border-emerald-600">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 text-slate-600">
                                <path fill="currentColor"
                                      d="M10 15l5.19-3L10 9zm12-7.5s-.2-1.43-.82-2.06C20.35 4 19.5 4 19.5 4C16.57 3.8 12 3.8 12 3.8h0s-4.57 0-7.5.2c0 0-.85 0-1.68 1.44C1.2 6.07 1 7.5 1 7.5S.8 9.07.8 10.64v2.72C.8 15 1 16.5 1 16.5s.2 1.43.82 2.06C2.65 20 3.5 20 3.5 20c2.93.2 7.5.2 7.5.2s4.57 0 7.5-.2c0 0 .85 0 1.68-1.44c.62-.63.82-2.06.82-2.06s.2-1.5.2-3.14v-2.72c0-1.57-.2-3.14-.2-3.14" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Kolom 2: Tentang --}}
                <div>
                    <h4 class="font-semibold text-slate-900 mb-3">Tentang</h4>
                    <ul class="space-y-2 text-slate-600">
                        <li><a href="#" class="hover:text-emerald-700">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-emerald-700">Syarat dan Ketentuan</a></li>
                        <li><a href="#" class="hover:text-emerald-700">Privasi</a></li>
                        <li><a href="#" class="hover:text-emerald-700">Legalitas</a></li>
                    </ul>
                </div>

                {{-- Kolom 3: Pusat Bantuan --}}
                <div>
                    <h4 class="font-semibold text-slate-900 mb-3">Pusat Bantuan</h4>
                    <ul class="space-y-2 text-slate-600">
                        <li><a href="#" class="hover:text-emerald-700">FAQ</a></li>
                        <li><a href="#" class="hover:text-emerald-700">Hubungi Kami</a></li>
                        <li><a href="#" class="hover:text-emerald-700">Konfirmasi Donasi</a></li>
                    </ul>
                </div>

                {{-- Kolom 4: Alamat --}}
                <div>
                    <h4 class="font-semibold text-slate-900 mb-3">Alamat</h4>

                    <div class="space-y-3 text-slate-600 text-sm">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-700 mt-0.5" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5a2.5 2.5 0 1 1 0-5a2.5 2.5 0 0 1 0 5Z" />
                            </svg>
                            <p>
                                Jl. Raya Unud Jimbaran No. 23<br>
                                RT 01/ RW 02, Kel. Donasikuy, Kec. Yayasan, Kota Denpasar
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-700" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 4l-8 5L4 8V6l8 5l8-5Z" />
                            </svg>
                            <a href="mailto:info@mail.com" class="hover:text-emerald-700">info@mail.com</a>
                        </div>

                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-700" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M6.62 10.79a15.093 15.093 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.3 21 3 13.7 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.46.57 3.58a1 1 0 0 1-.24 1.01l-2.21 2.2Z" />
                            </svg>
                            <a href="tel:02134567890" class="hover:text-emerald-700">02134567890</a>
                        </div>
                    </div>
                </div>
            </div>
            

            {{-- Copyright --}}
            <div class="mt-10 pt-6 border-t text-sm text-slate-500">
                Copyright ©{{ date('Y') }} BantuYuk
            </div>
        </div>
    </footer>
    @endunless
    @yield('scripts')
    @stack('scripts')
</body>
</html>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('input[name="q"]');
    if (!input) return;

    input.addEventListener('input', function () {
        if (this.value.trim() === '') {
            window.location.href = "{{ route('landing') }}";
        }
    });
});
</script>
@endpush
