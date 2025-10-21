<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BantuYuk')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="antialiased text-slate-800 font-[Inter]">

    {{-- HEADER --}}
    <header
        class="sticky top-0 z-50 bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/60 border-b shadow-sm">
        <div class="max-w-7xl mx-auto h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <a href="{{ url('') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo BantuYuk" class="h-8 w-auto">
                    <span class="font-semibold text-emerald-800 text-sm sm:text-base">BantuYuk</span>
                </a>
            </div>

            <form class="hidden md:flex items-center ml-auto w-auto max-w-sm">
                <div class="relative">
                    {{-- Icon search --}}
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m21 21-4.35-4.35M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" />
                        </svg>
                    </span>
                    {{-- Input --}}
                    <input type="search" placeholder="Cari Program"
                        class="block w-64 rounded-md bg-slate-50 border border-slate-200 pl-9 pr-3 py-2 text-sm text-slate-700
                        placeholder:text-slate-400 focus:bg-white outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" />
                </div>
            </form>

            <nav class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm text-slate-600 hover:text-slate-900">Masuk</a>
                <a href="/register"
                    class="inline-flex items-center rounded-md bg-emerald-700 hover:bg-emerald-800 text-white text-sm px-4 py-2">
                    Daftar
                </a>
                {{-- Bahasa --}}
                <button
                    class="inline-flex items-center gap-2 text-sm border border-slate-200 rounded-md px-3 py-1.5 hover:bg-slate-50">
                    <span class="relative inline-flex h-4 w-4 overflow-hidden rounded-full ring-1 ring-slate-300">
                        <span class="absolute inset-x-0 top-0 h-1/2 bg-red-600"></span>
                        <span class="absolute inset-x-0 bottom-0 h-1/2 bg-white"></span>
                    </span>
                    ID
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </nav>
        </div>
    </header>

    {{-- MAIN --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="relative bg-white border-t mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

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

                    {{-- Sosial media --}}
                    <div class="mt-5 flex items-center gap-4">
                        <a href="#"
                            class="h-8 w-8 rounded-full border border-slate-200 flex items-center justify-center hover:border-emerald-600">
                            {{-- instagram --}}
                            <svg viewBox="0 0 24 24" class="h-4 w-4 text-slate-600">
                                <path fill="currentColor"
                                    d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7Zm10 2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h10Zm-5 3a5 5 0 1 0 0 10a5 5 0 0 0 0-10Zm0 2a3 3 0 1 1 0 6a3 3 0 0 1 0-6Zm5.5-.75a.75.75 0 1 0 0 1.5a.75.75 0 0 0 0-1.5Z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="h-8 w-8 rounded-full border border-slate-200 flex items-center justify-center hover:border-emerald-600">
                            {{-- facebook --}}
                            <svg viewBox="0 0 24 24" class="h-4 w-4 text-slate-600">
                                <path fill="currentColor"
                                    d="M13 22v-8h3l1-4h-4V8a1 1 0 0 1 1-1h3V3h-3a5 5 0 0 0-5 5v2H6v4h3v8z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="h-8 w-8 rounded-full border border-slate-200 flex items-center justify-center hover:border-emerald-600">
                            {{-- twitter (X) --}}
                            <svg viewBox="0 0 24 24" class="h-4 w-4 text-slate-600">
                                <path fill="currentColor"
                                    d="M3 3h4.5l4.05 5.85L16.5 3H21l-7.5 9.4L21 21h-4.5l-4.2-6.1L7.5 21H3l7.8-9.6z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="h-8 w-8 rounded-full border border-slate-200 flex items-center justify-center hover:border-emerald-600">
                            {{-- youtube --}}
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
                            <p>Jl. Raya Unud Jimbaran No. 23<br>RT 01/ RW 02, Kel. Donasikuy, Kec. Yayasan, Kota
                                Denpasar
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

</body>

</html>
