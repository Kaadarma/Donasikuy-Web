@extends('layouts.app')

@section('title', 'Hasil Pencarian Program')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-20">

    <h2 class="text-2xl font-bold mb-6">
        Hasil pencarian untuk: <span class="text-emerald-600">"{{ $keyword }}"</span>
    </h2>

    @if ($programs->count() == 0)
        <p class="text-slate-500">Tidak ada program ditemukan.</p>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($programs as $p)
            @php
                $raised = (int) ($p['raised'] ?? 0);
                $target = (int) ($p['target'] ?? 0);
                $percent = $target > 0 ? min(100, (int) round(($raised / $target) * 100)) : 0;

                $category = $p['category'] ?? 'Program';
                $daysLeft = $p['days_left'] ?? null;
                $status = $p['status'] ?? null;

                // id/slug untuk link
                $slugOrId = $p['slug'] ?? ($p['id'] ?? null);

                // fallback gambar
                $img = $p['image'] ?? 'https://via.placeholder.com/600x340?text=Program';
            @endphp

            <a href="{{ $slugOrId ? route('programs.show', $slugOrId) : '#' }}"
               class="block group">
                <article
                    class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm
                           hover:shadow-lg hover:-translate-y-0.5 transition">

                    <img src="{{ $img }}" alt="{{ $p['title'] ?? 'Program' }}"
                         class="w-full aspect-[16/9] object-cover">

                    <div class="p-5">

                        {{-- Kategori + Status --}}
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs text-emerald-700 font-medium">
                                {{ $category }}
                            </span>

                            @if (!empty($status))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px]
                                    @if ($status === 'Selesai') bg-slate-100 text-slate-600
                                    @elseif ($status === 'Berakhir Hari Ini') bg-orange-100 text-orange-700
                                    @elseif ($status === 'Sedang Berjalan') bg-emerald-50 text-emerald-700
                                    @else bg-slate-50 text-slate-600 @endif">
                                    {{ $status }}
                                </span>
                            @endif
                        </div>

                        {{-- Judul --}}
                        <h3 class="mt-1 font-semibold text-slate-800 leading-snug line-clamp-2 min-h-[3.25rem]">
                            {{ $p['title'] ?? 'Program' }}
                        </h3>

                        {{-- Dana & sisa waktu --}}
                        <div class="mt-4 grid grid-cols-2 text-xs text-slate-500">
                            <div>
                                <div>Dana Terkumpul</div>
                                <div class="text-slate-900 font-medium">
                                    Rp {{ number_format($raised, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div>Sisa Waktu</div>
                                <div class="text-slate-900 font-medium">
                                    @if (is_null($daysLeft))
                                        Tanpa batas
                                    @else
                                        {{ $daysLeft }} Hari
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Progress --}}
                        <div class="mt-3">
                            <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-1.5 bg-emerald-600 rounded-full"
                                     style="width: {{ $percent }}%"></div>
                            </div>

                            <div class="mt-1 flex items-center justify-between text-[11px] text-slate-500">
                                <span>
                                    Rp {{ number_format($raised, 0, ',', '.') }}
                                    <span class="text-slate-400">dari</span>
                                    Rp {{ number_format($target, 0, ',', '.') }}
                                </span>
                                <span class="font-medium text-emerald-600">
                                    {{ $percent }}%
                                </span>
                            </div>
                        </div>

                        {{-- CTA kecil --}}
                        <div class="mt-4 text-sm font-semibold text-emerald-700 group-hover:text-emerald-800">
                            Lihat Detail →
                        </div>

                    </div>
                </article>
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $programs->links() }}
    </div>

</div>
@endsection
