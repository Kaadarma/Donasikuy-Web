@extends('layouts.app')

@section('title', 'Dana Punia')

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
                            Wujudkan <span class="underline decoration-lime-300 decoration-[4px] underline-offset-4">dana
                                punia</span><br>
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
                                    Program diarahkan untuk pura, banjar, pasraman, sekaa teruna, dan kegiatan sosial
                                    berbasis adat & budaya Bali.
                                </span>
                            </li>
                            <li class="flex gap-2">
                                <i class="bi bi-emoji-smile mt-0.5 text-xs"></i>
                                <span>
                                    <span class="font-semibold">Cocok untuk krama & diaspora Bali.</span><br>
                                    Baik yang tinggal di Bali maupun di luar daerah/luar negeri tetap bisa ikut nunas sareng
                                    melalui dana punia online.
                                </span>
                            </li>
                            <li class="flex gap-2">
                                <i class="bi bi-receipt mt-0.5 text-xs"></i>
                                <span>
                                    <span class="font-semibold">Transparan & bisa dilacak.</span><br>
                                    Riwayat donasi dan perkembangan program bisa dipantau, sehingga dana punia tidak
                                    “menghilang” begitu saja.
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- FILTER + DAFTAR PROGRAM --}}
        <section id="daftar-program" class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-10">

            {{-- Header --}}
            <div class="mb-6">
                <h2 class="text-xl sm:text-2xl font-semibold text-slate-900">
                    Program Dana Punia untuk Bali
                </h2>
                <p class="mt-1 text-sm text-slate-500 max-w-2xl">
                    Pilih program yang ingin kamu dukung. Setiap dana punia sangat berarti untuk umat & lingkungan Bali 🙏
                </p>
            </div>

            {{-- Filter Card --}}
            <form method="GET" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 mb-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

                    {{-- Search --}}
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Cari program (pura, banjar, sosial...)"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 pl-9 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    {{-- Kategori --}}
                    <div class="relative">
                        <i class="bi bi-tags absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="kategori"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 pl-9 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Semua kategori</option>
                            <option value="pura" {{ request('kategori') === 'pura' ? 'selected' : '' }}>Pura & Sanggah
                            </option>
                            <option value="banjar" {{ request('kategori') === 'banjar' ? 'selected' : '' }}>Banjar & Desa
                                Adat</option>
                            <option value="pendidikan" {{ request('kategori') === 'pendidikan' ? 'selected' : '' }}>
                                Pendidikan & Pasraman</option>
                            <option value="sosial" {{ request('kategori') === 'sosial' ? 'selected' : '' }}>Sosial &
                                Kemanusiaan</option>
                            <option value="lainnya" {{ request('kategori') === 'lainnya' ? 'selected' : '' }}>Lainnya
                            </option>
                        </select>
                    </div>

                    {{-- Sort --}}
                    <div class="relative">
                        <i class="bi bi-sort-down absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        @php $sort = request('sort', 'terbaru'); @endphp
                        <select name="sort"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 pl-9 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="terbaru" {{ $sort === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terkumpul_terbanyak" {{ $sort === 'terkumpul_terbanyak' ? 'selected' : '' }}>
                                Dana Terkumpul Terbanyak
                            </option>
                        </select>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 text-white
                           text-sm font-semibold py-2 hover:bg-emerald-700 transition">
                            <i class="bi bi-funnel"></i>
                            Terapkan
                        </button>

                        <a href="{{ route('dana-punia.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3
                           text-sm text-slate-600 hover:bg-slate-100 transition"
                            title="Reset filter">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>

                </div>
            </form>

            {{-- LIST PROGRAM --}}
            @if (!isset($programs) || $programs->isEmpty())
                <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-8 sm:p-10 text-center">
                    <div
                        class="mx-auto h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-4">
                        <i class="bi bi-box2-heart text-xl"></i>
                    </div>

                    <p class="text-base font-semibold text-slate-900">
                        Program Dana Punia Bali akan segera hadir.
                    </p>
                    <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                        Admin dapat menambahkan program dana punia seperti renovasi pura, bantuan sosial umat,
                        dan kegiatan adat lainnya melalui dashboard.
                    </p>

                    <div class="mt-5 flex items-center justify-center gap-2">
                        <a href="{{ url('/') }}"
                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold
                           border border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                            <i class="bi bi-house"></i>
                            Beranda
                        </a>
                        <a href="#daftar-program"
                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold
                           bg-emerald-600 text-white hover:bg-emerald-700 transition">
                            <i class="bi bi-arrow-clockwise"></i>
                            Muat Ulang
                        </a>
                    </div>
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 mt-6">
                    @foreach ($programs as $program)
                        @php
                            $raised = (int) ($program['raised'] ?? 0);
                            $target = (int) ($program['target'] ?? 0);
                            $isUnlimited = $target <= 0;
                            $progress = $isUnlimited ? 0 : min(100, (int) round(($raised / max(1, $target)) * 100));

                            $img = $program['image'] ?? null;
                            $imgSrc = $img
                                ? (Str::startsWith($img, ['http://', 'https://'])
                                    ? $img
                                    : asset($img))
                                : 'https://via.placeholder.com/1200x800?text=Dana+Punia';
                        @endphp


                        {{-- CARD PROGRAM (compact) --}}
                        <a href="{{ route('programs.show', $program['slug']) }}"
                            class="group block bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition overflow-hidden">

                            {{-- Thumbnail --}}
                            <div class="relative overflow-hidden">
                                <img src="{{ $imgSrc }}" alt="{{ $program['title'] }}"
                                    class="w-full h-[140px] object-cover group-hover:scale-[1.03] transition duration-300">
                                <span class="absolute top-0 left-0 w-full h-[3px] bg-emerald-600"></span>

                                @if ($isUnlimited)
                                    <span
                                        class="absolute top-3 right-3 text-[11px] font-semibold px-2.5 py-1 rounded-full
                                       bg-white/90 text-emerald-700 border border-emerald-100 shadow-sm">
                                        Tanpa target
                                    </span>
                                @endif
                            </div>

                            <div class="p-3 space-y-2">

                                {{-- Kategori --}}
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px]
                                     bg-emerald-50 text-emerald-700 font-semibold">
                                    <i class="bi bi-tag"></i>
                                    {{ $program['category'] ?? 'Program' }}
                                </span>

                                {{-- Judul --}}
                                <h3
                                    class="text-[13px] font-semibold text-slate-900 leading-snug line-clamp-2 min-h-[2.4rem]">
                                    {{ $program['title'] }}
                                </h3>

                                {{-- Dana --}}
                                <div class="mt-1">
                                    <div class="text-[11px] text-slate-500 flex justify-between">
                                        <span>Terkumpul</span>
                                        <span>Target</span>
                                    </div>
                                    <div class="text-[12px] font-semibold text-slate-900 flex justify-between">
                                        <span>Rp {{ number_format($raised, 0, ',', '.') }}</span>
                                        <span>{{ $isUnlimited ? 'Tanpa target' : 'Rp ' . number_format($target, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                {{-- Progress --}}
                                <div class="space-y-1.5">
                                    @if ($isUnlimited)
                                        <div class="punia-indet">
                                            <span class="first"></span>
                                            <span class="second"></span>
                                        </div>

                                        <div class="text-[11px] text-slate-500 flex justify-between">
                                            <span>Terus berjalan</span>
                                        </div>
                                    @else
                                        <div class="relative h-2 bg-slate-200 rounded-full overflow-hidden">
                                            <div class="absolute left-0 top-0 h-2 bg-emerald-600 rounded-full"
                                                style="width: {{ $progress }}%"></div>
                                        </div>

                                        <div class="text-[11px] text-slate-500 flex justify-between">
                                            <span>{{ $progress }}% tercapai</span>

                                        </div>
                                    @endif
                                </div>

                                {{-- CTA --}}
                                <div class="pt-1">
                                    <span
                                        class="inline-flex items-center gap-1 text-[12px] font-semibold text-emerald-700">
                                        Lihat detail <i class="bi bi-arrow-right-short text-base"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="mt-8">
                    {{ $programs->links() }}
                </div>

                {{-- Animasi bar unlimited (sekali aja) --}}
                <style>
                    @keyframes indet-1 {
                        0% {
                            transform: translateX(-120%) scaleX(0.35);
                            opacity: 0.9;
                        }

                        60% {
                            transform: translateX(180%) scaleX(0.85);
                            opacity: 0.9;
                        }

                        100% {
                            transform: translateX(220%) scaleX(0.55);
                            opacity: 0;
                        }
                    }

                    @keyframes indet-2 {
                        0% {
                            transform: translateX(-140%) scaleX(0.25);
                            opacity: 0.6;
                        }

                        40% {
                            transform: translateX(120%) scaleX(0.65);
                            opacity: 0.6;
                        }

                        100% {
                            transform: translateX(240%) scaleX(0.45);
                            opacity: 0;
                        }
                    }

                    .punia-indet {
                        position: relative;
                        height: 8px;
                        border-radius: 9999px;
                        overflow: hidden;
                        background: #e2e8f0;
                    }

                    .punia-indet>span {
                        position: absolute;
                        top: 0;
                        bottom: 0;
                        left: 0;
                        width: 60%;
                        border-radius: 9999px;
                        background: linear-gradient(90deg,
                                rgba(16, 185, 129, 0),
                                rgba(16, 185, 129, 1),
                                rgba(132, 204, 22, 1),
                                rgba(16, 185, 129, 0));
                        filter: drop-shadow(0 2px 6px rgba(16, 185, 129, .25));
                    }

                    .punia-indet>span.first {
                        animation: indet-1 1.35s cubic-bezier(.2, .9, .25, 1) infinite;
                    }

                    .punia-indet>span.second {
                        animation: indet-2 1.65s cubic-bezier(.2, .9, .25, 1) infinite;
                    }
                </style>
            @endif

        </section>


    </div>
@endsection
