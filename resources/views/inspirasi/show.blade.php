@extends('layouts.app')
@section('title', $article['title'])

@section('content')
    {{-- Reading progress bar (atas halaman) --}}
    <div id="readProgress" class="fixed top-0 left-0 right-0 h-1 bg-emerald-600 scale-x-0 origin-left z-[60]"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10 relative">

        {{-- Breadcrumb --}}
        <nav class="text-sm text-slate-500 mb-4">
            <a href="{{ url('/') }}" class="hover:text-emerald-700">Beranda</a>
            <span class="mx-2">/</span>
            <a href="{{ route('inspirasi.index') }}" class="hover:text-emerald-700">Inspirasi</a>
            <span class="mx-2">/</span>
            <span class="text-slate-700 line-clamp-1">{{ $article['title'] }}</span>
        </nav>

        {{-- Hero section --}}
        <section class="rounded-2xl overflow-hidden border bg-white">
            <div class="relative">
                <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}"
                    class="w-full h-64 md:h-[420px] object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 text-white">
                    <div class="flex flex-wrap items-center gap-2 text-xs opacity-90">
                        @if (!empty($article['category']))
                            <span
                                class="inline-flex items-center rounded-full bg-emerald-600/90 px-3 py-1">{{ $article['category'] }}</span>
                        @endif
                        <span>{{ \Carbon\Carbon::parse($article['published_at'])->translatedFormat('d F Y') }}</span>
                        @if (!empty($article['read_time']))
                            <span>• {{ $article['read_time'] }} menit baca</span>
                        @endif
                    </div>
                    <h1 class="mt-3 text-2xl md:text-4xl font-bold leading-tight">{{ $article['title'] }}</h1>
                </div>
            </div>
        </section>

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-[56px,1fr,280px] gap-6">

            {{-- Sticky share (desktop) --}}
            <aside class="hidden lg:block">
                <div class="sticky top-24 space-y-3">
                    <button class="share-btn h-10 w-10 grid place-content-center rounded-full border hover:bg-slate-50"
                        data-share="fb" title="Bagikan ke Facebook">
                        <svg class="h-4 w-4 text-slate-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13 22v-8h3l1-4h-4V8a1 1 0 0 1 1-1h3V3h-3a5 5 0 0 0-5 5v2H6v4h3v8z" />
                        </svg>
                    </button>
                    <button class="share-btn h-10 w-10 grid place-content-center rounded-full border hover:bg-slate-50"
                        data-share="tw" title="Bagikan ke X/Twitter">
                        <svg class="h-4 w-4 text-slate-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 3h4.5l4.05 5.85L16.5 3H21l-7.5 9.4L21 21h-4.5l-4.2-6.1L7.5 21H3l7.8-9.6z" />
                        </svg>
                    </button>
                    <button class="h-10 w-10 grid place-content-center rounded-full border hover:bg-slate-50"
                        title="Salin tautan" onclick="navigator.clipboard.writeText('{{ request()->fullUrl() }}')">
                        <svg class="h-4 w-4 text-slate-600" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M16 1H4a2 2 0 0 0-2 2v12h2V3h12V1Zm3 4H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Zm0 16H8V7h11v14Z" />
                        </svg>
                    </button>
                </div>
            </aside>

            {{-- Artikel + TOC mobile --}}
            <main>
                {{-- TOC (mobile/tablet) --}}
                <details class="lg:hidden mb-4 border rounded-lg bg-white open:shadow">
                    <summary class="cursor-pointer px-4 py-3 text-sm font-medium text-slate-700">Daftar Isi</summary>
                    <nav id="tocMobile" class="p-4 text-sm space-y-2 text-slate-600"></nav>
                </details>

                {{-- Body artikel --}}
                <article id="articleBody" class="prose prose-slate max-w-none">
                    <h2>Dampak & Penyaluran</h2>
                    @foreach ($article['content'] as $para)
                        <p>{{ $para }}</p>
                    @endforeach

                    <blockquote
                        class="not-prose mt-6 rounded-xl border-l-4 border-emerald-600 bg-emerald-50 p-4 text-slate-800">
                        “Gotong royong adalah kekuatan terbesar kita. Setiap donasi menghidupkan harapan.”
                    </blockquote>

                    <h2>Rencana Tahap Lanjutan</h2>
                    <p>Kami menargetkan prioritas lansia, disabilitas, dan keluarga dengan balita untuk distribusi
                        berikutnya.</p>

                    {{-- Gallery + lightbox --}}
                    @if (!empty($article['gallery'] ?? []))
                        <div class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-3 not-prose">
                            @foreach ($article['gallery'] as $g)
                                <button class="group relative overflow-hidden rounded-lg"
                                    data-lightbox="{{ $g }}">
                                    <img src="{{ $g }}"
                                        class="h-36 w-full object-cover group-hover:scale-[1.03] transition" alt="Gallery">
                                    <span class="absolute inset-0 bg-black/0 group-hover:bg-black/15 transition"></span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </article>

                {{-- Tags --}}
                @if (!empty($article['tags']))
                    <div class="mt-8 flex flex-wrap items-center gap-2">
                        @foreach ($article['tags'] as $tag)
                            <a href="#"
                                class="text-xs rounded-full bg-slate-100 text-slate-700 px-3 py-1 hover:bg-slate-200">#{{ $tag }}</a>
                        @endforeach
                    </div>
                @endif

                {{-- Prev / Next (disisipkan di sini) --}}
                <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @isset($prev)
                        <a href="{{ route('inspirasi.show', $prev['slug']) }}"
                            class="group rounded-xl border p-4 bg-white hover:shadow-md transition">
                            <div class="text-xs text-slate-500">← Sebelumnya</div>
                            <div class="mt-1 font-semibold text-slate-900 group-hover:text-emerald-700 line-clamp-2">
                                {{ $prev['title'] }}</div>
                        </a>
                    @endisset

                    @isset($next)
                        <a href="{{ route('inspirasi.show', $next['slug']) }}"
                            class="group rounded-xl border p-4 bg-white hover:shadow-md transition text-right md:text-left">
                            <div class="text-xs text-slate-500">Selanjutnya →</div>
                            <div class="mt-1 font-semibold text-slate-900 group-hover:text-emerald-700 line-clamp-2">
                                {{ $next['title'] }}</div>
                        </a>
                    @endisset
                </div>
            </main>

            {{-- Sidebar kanan --}}
            <aside class="lg:block">
                <div class="sticky top-24 space-y-6">
                    {{-- Author box --}}
                    @if (!empty($article['author']))
                        <div class="rounded-xl border bg-white p-5 flex items-center gap-4">
                            <img src="{{ $article['author']['avatar'] ?? asset('images/avatar1.jpg') }}"
                                class="h-12 w-12 rounded-full object-cover" alt="Author">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $article['author']['name'] }}</div>
                                <div class="text-sm text-slate-500">Kontributor BantuYuk</div>
                            </div>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>

    {{-- JS (tetap sama seperti versi kamu) --}}
    <script>
        // ... semua JS share, lightbox, TOC, progress tetap sama
    </script>
@endsection
