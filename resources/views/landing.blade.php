@extends('layouts.app')

@section('title', 'Donasikuy — Donasi untuk Sesama')

@section('content')

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/slider.js'])



    {{-- HERO --}}
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="relative overflow-hidden rounded-2xl">

            {{-- Gambar slider --}}
            <div id="hero-slides" class="relative w-full h-[400px] rounded-2xl overflow-hidden">
                <img src="{{ asset('images/bencana.jpg') }}"
                    class="absolute inset-0 w-full h-full object-cover opacity-100 transition-opacity duration-700"
                    alt="Banner 1">
                <img src="{{ asset('images/thumb-right.jpg') }}"
                    class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-700"
                    alt="Banner 2">
                <img src="{{ asset('images/bencana1.jpg') }}"
                    class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-700"
                    alt="Banner 3">
                <div class="absolute inset-0 bg-black/40 rounded-2xl"></div>

                {{-- Teks dan tombol donasi --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white px-6">
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-semibold max-w-3xl">
                        Bantuan Sosial untuk Masyarakat Menengah dimasa PPKM
                    </h1>
                    <a href="#program"
                        class="mt-4 inline-flex items-center justify-center rounded-lg bg-emerald-600 px-5 py-2.5
           text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        DONASI SEKARANG
                    </a>
                </div>

                <button id="prevSlide"
                    class="absolute top-1/2 -translate-y-1/2 left-3 h-10 w-10 flex items-center justify-center rounded-full bg-emerald-600 text-white shadow hover:bg-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button id="nextSlide"
                    class="absolute top-1/2 -translate-y-1/2 right-3 h-10 w-10 flex items-center justify-center rounded-full bg-emerald-600 text-white shadow hover:bg-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- letakkan di bawah hero -->
            <div id="slideDots" class="flex items-center justify-center gap-2 mt-4">
                <!-- jumlah span = jumlah slide -->
                <span class="h-2 w-2 rounded-full bg-slate-300 transition-all duration-300"></span>
                <span class="h-2 w-2 rounded-full bg-slate-300 transition-all duration-300"></span>
                <span class="h-2 w-2 rounded-full bg-slate-300 transition-all duration-300"></span>
            </div>

        </div>
    </section>

    {{-- STAT CARDS --}}
    <div class="relative z-10 mt-20 px-4">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-5">

            <div class="rounded-xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                <div class="text-slate-500 mb-1">Donasi Terkumpul</div>
                <div class="text-emerald-600 text-xl md:text-2xl font-semibold">
                    <span class="odometer" data-target="11289569" data-prefix="Rp " data-suffix="" data-duration="1100">Rp
                        0</span>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                <div class="text-slate-500 mb-1">Total Donatur</div>
                <div class="text-emerald-600 text-xl md:text-2xl font-semibold">
                    <span class="odometer" data-target="45" data-prefix="" data-suffix=" Donatur"
                        data-duration="1200">0</span>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                <div class="text-slate-500 mb-1">Total Program</div>
                <div class="text-emerald-600 text-xl md:text-2xl font-semibold">
                    <span class="odometer" data-target="16" data-prefix="" data-suffix=" Program"
                        data-duration="1900">0</span>
                </div>
            </div>

        </div>
    </div>


    {{-- PROGRAM --}}

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
        <h2 class="text-center text-2xl md:text-3xl font-semibold mt-20">Program Pilihan Kami</h2>
        <div class="relative rounded-t-xl pt-12 pb-8 mt-12">
            <div class="absolute -top-25 left-1/2 -translate-x-1/2 w-full flex justify-center gap-6">

            </div>
            <button id="progPrev"
                class="absolute left-4 top-[120px] z-20 h-9 w-9 flex items-center justify-center
             rounded-full bg-emerald-600 text-white shadow hover:bg-emerald-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            {{-- panah kanan --}}
            <button id="progNext"
                class="absolute right-4 top-[120px] z-20 h-9 w-9 flex items-center justify-center
             rounded-full bg-emerald-600 text-white shadow hover:bg-emerald-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            {{-- track kartu --}}
            <div class="overflow-hidden">
                <div id="progTrack" class="flex gap-6 px-8 scroll-smooth overflow-x-auto no-scrollbar">
                    @foreach ($programs as $p)
                        @php $percent = ($p['target'] ?? 0) ? min(100, round(($p['raised'] ?? 0) / $p['target'] * 100)) : 0; @endphp

                        <article class="shrink-0 w-[320px] bg-white rounded-2xl overflow-hidden shadow">
                            <img src="{{ $p['image'] }}" alt="" class="w-full aspect-[16/9] object-cover">
                            <div class="p-5">
                                <span class="text-xs text-emerald-700 font-medium">{{ $p['category'] ?? 'Program' }}</span>
                                <h3 class="mt-1 font-semibold text-slate-800 leading-snug line-clamp-2 min-h-[3.25rem]">
                                    {{ $p['title'] }}
                                </h3>

                                <div class="mt-4 grid grid-cols-2 text-xs text-slate-500">
                                    <div>
                                        <div>Dana Terkumpul</div>
                                        <div class="text-slate-900 font-medium">
                                            Rp {{ number_format($p['raised'] ?? 0, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div>Sisa Waktu</div>
                                        <div class="text-slate-900 font-medium">
                                            {{ $p['days_left'] ?? '-' }} {{ isset($p['days_left']) ? 'Hari' : '' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                                        <div class="h-1.5 bg-emerald-600 rounded-full"
                                            style="width: {{ $percent }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            {{-- dots indikator --}}
            <div id="progDots" class="mt-6 flex items-center justify-center gap-2">
                {{-- jumlah span = jumlah slide --}}
                @foreach ($programs as $i => $ignore)
                    <span
                        class="h-1.5 {{ $i === 0 ? 'w-6 bg-emerald-500' : 'w-2 bg-emerald-200' }} rounded-full transition-all"></span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- BAGIAN PROGRAM LAINNYA --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-20">
        <h2 class="text-center text-2xl md:text-3xl font-semibold mb-2">
            Bantuan Anda Sangat Diperlukan
        </h2>
        <p class="text-center text-slate-500 mb-8">
            Pilih kategori program yang ingin anda bantu
        </p>


        {{-- FILTER KATEGORI --}}
        <div id="categoryBar" class="flex justify-center flex-wrap gap-2 mb-10">
            @php
                $categories = ['Semua', 'Kemanusiaan', 'Bencana', 'Yatim Piatu', 'Pendidikan', 'Sedekah'];
            @endphp

            @foreach ($categories as $cat)
                @php $slug = Str::slug($cat); @endphp
                <button
                    class="cat-btn px-4 py-1.5 rounded-full text-sm font-medium border border-slate-300 text-slate-700 hover:border-emerald-500 hover:text-emerald-700 data-[active=true]:bg-emerald-600 data-[active=true]:text-white"
                    data-cat="{{ $slug }}">
                    {{ $cat }}
                </button>
            @endforeach
        </div>


        {{-- GRID PROGRAM --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($programs as $p)
                @php $percent = ($p['target'] ?? 0) ? min(100, round(($p['raised'] ?? 0) / $p['target'] * 100)) : 0; @endphp

                @php $catSlug = Str::slug($p['category'] ?? ''); @endphp

                <article
                    class="program-card bg-white rounded-2xl shadow-sm hover:shadow-lg transition overflow-hidden border border-slate-200"
                    data-cat="{{ $catSlug }}">
                    <img src="{{ $p['image'] }}" alt="{{ $p['title'] }}" class="w-full aspect-[16/9] object-cover">
                    <div class="p-5">
                        <span class="text-xs text-emerald-700 font-medium">{{ $p['category'] ?? 'Program' }}</span>
                        <h3 class="mt-1 font-semibold text-slate-800 leading-snug line-clamp-2 min-h-[3rem]">
                            {{ $p['title'] }}
                        </h3>

                        <div class="mt-4 grid grid-cols-2 text-xs text-slate-500">
                            <div>
                                <div>Dana Terkumpul</div>
                                <div class="text-slate-900 font-medium">
                                    Rp {{ number_format($p['raised'] ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div>Sisa Waktu</div>
                                <div class="text-slate-900 font-medium">
                                    {{ $p['days_left'] ?? 'Habis' }} {{ isset($p['days_left']) ? 'Hari' : '' }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-1.5 bg-emerald-600 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- CTA LIHAT PROGRAM --}}
        <div class="flex justify-center mt-10">
            <a href="{{ route('programs.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium text-sm flex items-center gap-1">
                Lihat Program Lainnya
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </section>

    @props(['posts' => []])

    {{-- ...section lain... --}}

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-2xl md:text-3xl font-semibold text-slate-900">Inspirasi</h2>
        <p class="mt-1 text-slate-500">Artikel & kisah terbaru dari para relawan dan donatur.</p>

        <ul class="mt-6 divide-y divide-slate-200">
            @foreach ($posts as $post)
                <li class="py-5">
                    <a href="{{ $post['url'] }}" class="group flex items-start gap-4 md:gap-6">
                        <div class="relative shrink-0 w-28 h-20 md:w-40 md:h-28 overflow-hidden rounded-lg bg-slate-100">
                            <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </div>

                        <div class="min-w-0">
                            <h3
                                class="text-base md:text-lg font-semibold text-slate-900 leading-snug group-hover:text-emerald-700 line-clamp-2">
                                {{ $post['title'] }}
                            </h3>

                            <div class="mt-1 text-xs md:text-sm text-slate-500">
                                {{ $post['date'] }}
                            </div>

                            @if (!empty($post['excerpt']))
                                <p class="mt-2 text-sm text-slate-600 line-clamp-2">
                                    {{ $post['excerpt'] }}
                                </p>
                            @endif
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- Tombol minimalis "Lihat Inspirasi Lainnya" --}}
        <div class="mt-8 flex justify-center">
            <a href="{{ route('inspirasi.index') }}"
                class="inline-flex items-center gap-2 text-emerald-600 font-medium hover:text-emerald-700 transition">
                Lihat Inspirasi Lainnya
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </path>
                </svg>
            </a>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Header --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900">FAQ</h1>
            <p class="mt-3 text-slate-500 max-w-2xl mx-auto">
                Temukan jawaban dari pertanyaan umum seputar Donasi, Cara Kerja Platform, dan Penggalangan Dana.
            </p>
        </div>

        {{-- FAQ --}}
        <div class="space-y-4" x-data="{ open: null }">

            {{-- ITEM 1 --}}
            <div class="border rounded-xl bg-white shadow-sm">
                <button class="w-full flex justify-between items-center px-5 py-4 text-left"
                    @click="open === 1 ? open = null : open = 1">
                    <span class="font-semibold text-slate-800">Apa itu DonasiKuy?</span>
                    <svg x-show="open !== 1" class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <svg x-show="open === 1" class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </button>

                <div x-show="open === 1" x-collapse class="px-5 pb-4 text-slate-600">
                    DonasiKuy adalah platform galang dana online untuk membantu sesama melalui berbagai program sosial,
                    kemanusiaan, pendidikan, kesehatan, dan kebutuhan darurat.
                </div>
            </div>

            {{-- ITEM 2 --}}
            <div class="border rounded-xl bg-white shadow-sm">
                <button class="w-full flex justify-between items-center px-5 py-4 text-left"
                    @click="open === 2 ? open = null : open = 2">
                    <span class="font-semibold text-slate-800">Bagaimana cara berdonasi?</span>
                    <svg x-show="open !== 2" class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <svg x-show="open === 2" class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </button>

                <div x-show="open === 2" x-collapse class="px-5 pb-4 text-slate-600">
                    Anda hanya perlu memilih program yang ingin dibantu, klik tombol <b>Donasi Sekarang</b>,
                    pilih nominal donasi, lalu lakukan pembayaran melalui metode yang tersedia.
                </div>
            </div>

            {{-- ITEM 3 --}}
            <div class="border rounded-xl bg-white shadow-sm">
                <button class="w-full flex justify-between items-center px-5 py-4 text-left"
                    @click="open === 3 ? open = null : open = 3">
                    <span class="font-semibold text-slate-800">Bagaimana keamanan transaksi saya?</span>
                    <svg x-show="open !== 3" class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <svg x-show="open === 3" class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </button>

                <div x-show="open === 3" x-collapse class="px-5 pb-4 text-slate-600">
                    Transaksi di DonasiKuy aman karena menggunakan sistem pembayaran yang sudah tersertifikasi,
                    dilengkapi enkripsi, dan terhubung langsung dengan penyedia pembayaran resmi.
                </div>
            </div>

            {{-- ITEM 4 --}}
            <div class="border rounded-xl bg-white shadow-sm">
                <button class="w-full flex justify-between items-center px-5 py-4 text-left"
                    @click="open === 4 ? open = null : open = 4">
                    <span class="font-semibold text-slate-800">Bagaimana cara membuat galang dana?</span>
                    <svg x-show="open !== 4" class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <svg x-show="open === 4" class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </button>

                <div x-show="open === 4" x-collapse class="px-5 pb-4 text-slate-600">
                    Klik menu <b>Galang Dana</b>, pilih kategori galangan, isi detail cerita, target dana,
                    dan unggah foto pendukung. Setelah diverifikasi, galangan dana Anda akan tayang.
                </div>
            </div>

            {{-- ITEM 5 --}}
            <div class="border rounded-xl bg-white shadow-sm">
                <button class="w-full flex justify-between items-center px-5 py-4 text-left"
                    @click="open === 5 ? open = null : open = 5">
                    <span class="font-semibold text-slate-800">Kapan dana donasi dicairkan?</span>
                    <svg x-show="open !== 5" class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <svg x-show="open === 5" class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </button>

                <div x-show="open === 5" x-collapse class="px-5 pb-4 text-slate-600">
                    Dana dapat dicairkan setelah Anda mengajukan pencairan dan proses verifikasi selesai.
                    Biasanya membutuhkan waktu 1–3 hari kerja.
                </div>
            </div>

        </div>

    </div>

    {{-- Section: Ajakan Donasi --}}
    <section class="relative mt-16 mb-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-2xl overflow-hidden h-56 md:h-64 flex items-center justify-center text-center"
            style="background-image: url('{{ asset('images/bencana.jpg') }}'); background-size: cover; background-position: center;">

            <div class="absolute inset-0 bg-black bg-opacity-40"></div>

            {{-- Konten di tengah --}}
            <div class="relative z-10 text-white">
                <h2 class="text-xl md:text-3xl font-semibold mb-4">
                    Yuk Bantu Saudara Kita <br class="hidden md:block" /> Dengan Berdonasi
                </h2>

                <a href="#program"
                    class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white text-sm md:text-base font-semibold px-6 py-2 rounded-md transition mt-5">
                    DONASI SEKARANG
                </a>
            </div>
        </div>
    </section>

@endsection
