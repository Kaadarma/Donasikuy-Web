@extends('layouts.app')

@section('title', 'Dana Punia Bali | DonasiKuy')

@section('content')
<div class="min-h-screen bg-slate-50">

    {{-- HERO: DANA PUNIA BALI --}}
    <section class="bg-gradient-to-br from-emerald-700 via-emerald-600 to-lime-400 text-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 sm:py-14">
            {{-- Breadcrumb --}}
            <nav class="text-xs mb-4 flex items-center gap-1">
                <a href="{{ url('/') }}" class="hover:underline/50">Beranda</a>
                <span class="text-white/60">/</span>
                <span class="text-white/80 font-medium">Dana Punia Bali</span>
            </nav>

            <div class="grid md:grid-cols-[3fr,2fr] gap-8 items-center">
                <div>
                    <p class="text-xs font-semibold tracking-[0.18em] uppercase text-emerald-100 mb-2">
                        Kategori Donasi • Dana Punia Bali
                    </p>
                    <h1 class="text-3xl sm:text-4xl font-semibold leading-tight">
                        Wujudkan <span class="underline decoration-lime-300 decoration-[4px] underline-offset-4">dana punia</span><br>
                        untuk umat & krama Bali
                    </h1>
                    <p class="mt-4 text-sm sm:text-base text-emerald-50 max-w-xl leading-relaxed">
                        Dana punia adalah bentuk bakti dan rasa tulus ikhlas umat Hindu, 
                        terutama di Bali, untuk mendukung kegiatan keagamaan, perbaikan pura, 
                        banjar, pasraman, hingga kegiatan sosial di lingkungan sekitar.
                        Melalui DonasiKuy, masyarakat Bali maupun diaspora Bali di mana pun berada 
                        bisa ikut ngayah dalam bentuk digital, dengan cara yang mudah dan transparan.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="#daftar-program" 
                           class="inline-flex items-center gap-2 rounded-full bg-white text-emerald-700 text-sm font-medium px-5 py-2.5 shadow-sm hover:shadow-md transition">
                            Lihat program dana punia
                            <i class="bi bi-arrow-down-right-circle"></i>
                        </a>
                        <span class="inline-flex items-center text-xs sm:text-sm text-emerald-100/80">
                            <i class="bi bi-shield-check me-1"></i> Aman, tercatat, & transparan
                        </span>
                    </div>
                </div>

                {{-- Kartu info singkat --}}
                <div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 p-5 sm:p-6 shadow-lg">
                    <h2 class="text-sm font-medium text-emerald-50 mb-3">
                        Mengapa Dana Punia Bali di DonasiKuy?
                    </h2>
                    <ul class="space-y-3 text-xs sm:text-sm text-emerald-50/90">
                        <li class="flex gap-2">
                            <i class="bi bi-heart-fill mt-0.5 text-xs"></i>
                            <span>
                                <span class="font-semibold">Fokus pada umat & lingkungan Bali.</span><br>
                                Program diarahkan untuk pura, banjar, pasraman, sekaa teruna, dan kegiatan sosial berbasis adat & budaya Bali.
                            </span>
                        </li>
                        <li class="flex gap-2">
                            <i class="bi bi-emoji-smile mt-0.5 text-xs"></i>
                            <span>
                                <span class="font-semibold">Cocok untuk krama & diaspora Bali.</span><br>
                                Baik yang tinggal di Bali maupun di luar daerah/luar negeri tetap bisa ikut nunas sareng melalui dana punia online.
                            </span>
                        </li>
                        <li class="flex gap-2">
                            <i class="bi bi-receipt mt-0.5 text-xs"></i>
                            <span>
                                <span class="font-semibold">Transparan & bisa dilacak.</span><br>
                                Riwayat donasi dan perkembangan program bisa dipantau, sehingga dana punia tidak “menghilang” begitu saja.
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


    {{-- FILTER + DAFTAR PROGRAM --}}
    <section id="daftar-program" class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        {{-- Header + filter --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-lg sm:text-xl font-semibold text-slate-900">
                    Program Dana Punia untuk Bali
                </h2>
                <p class="text-xs sm:text-sm text-slate-500">
                    Pilih program yang ingin kamu dukung. Setiap dana punia sangat berarti untuk umat & lingkungan Bali 🙏
                </p>
            </div>

            {{-- Filter bar (frontend dulu, nanti bisa disambungkan ke database) --}}
            <form method="GET" class="flex flex-wrap items-center gap-2 sm:gap-3">
                {{-- Search --}}
                <div class="relative">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari program (pura, banjar, sosial...)"
                        class="w-48 sm:w-56 rounded-full border border-slate-200 bg-white px-3 py-1.5 pl-9 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    >
                    <i class="bi bi-search text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-xs"></i>
                </div>

                {{-- Kategori --}}
                <select
                    name="kategori"
                    class="text-xs sm:text-sm rounded-full border border-slate-200 bg-white px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    onchange="this.form.submit()"
                >
                    <option value="">Semua kategori</option>
                    <option value="pura" {{ request('kategori') === 'pura' ? 'selected' : '' }}>Pura & Sanggah</option>
                    <option value="banjar" {{ request('kategori') === 'banjar' ? 'selected' : '' }}>Banjar & Desa Adat</option>
                    <option value="pendidikan" {{ request('kategori') === 'pendidikan' ? 'selected' : '' }}>Pendidikan & Pasraman</option>
                    <option value="sosial" {{ request('kategori') === 'sosial' ? 'selected' : '' }}>Sosial & Kemanusiaan</option>
                    <option value="lainnya" {{ request('kategori') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>

                {{-- Sort --}}
                <select
                    name="sort"
                    class="text-xs sm:text-sm rounded-full border border-slate-200 bg-white px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    onchange="this.form.submit()"
                >
                    @php $sort = request('sort', 'terbaru'); @endphp
                    <option value="terbaru" {{ $sort === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="terkumpul_terbanyak" {{ $sort === 'terkumpul_terbanyak' ? 'selected' : '' }}>
                        Dana terkumpul terbanyak
                    </option>
                </select>

                <button
                    type="submit"
                    class="inline-flex items-center gap-1 rounded-full bg-emerald-600 text-white text-xs sm:text-sm font-medium px-4 py-1.5 hover:bg-emerald-700 transition"
                >
                    <i class="bi bi-funnel"></i> Terapkan
                </button>
            </form>
        </div>

        {{-- Placeholder kalau belum ada data program --}}
        <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-8 text-center">
            <p class="text-sm font-medium text-slate-800">
                Program Dana Punia Bali akan segera hadir.
            </p>
            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                Admin dapat menambahkan program dana punia seperti renovasi pura, bantuan sosial umat, 
                dan kegiatan adat lainnya melalui dashboard. Setelah itu, daftar program akan tampil di halaman ini
                dan bisa difilter berdasarkan kategori, pencarian, dan urutan.
            </p>
        </div>

        {{--
        Nanti kalau sudah ada model & data, kamu bisa taruh loop card program di sini, contoh:

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6 mt-6">
            @foreach ($programs as $program)
                <article>...card program dana punia...</article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $programs->links() }}
        </div>
        --}}
    </section>

</div>
@endsection
