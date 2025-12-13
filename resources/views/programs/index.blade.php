@extends('layouts.app')

@section('title', 'Daftar Program')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-5">
        <h1 class="text-2xl font-bold text-emerald-800 mb-6">
            Program Pilihan Kami
        </h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($programs as $p)
                @php
                    $raised  = $p['raised'] ?? 0;
                    $target  = $p['target'] ?? 0;
                    $percent = $target > 0 ? min(100, round(($raised / $target) * 100)) : 0;

                    $slugOrId = $p['slug'] ?? ($p['id'] ?? null);
                    $detailUrl = $slugOrId ? route('programs.show', $slugOrId) : '#';
                @endphp

                <a href="{{ $detailUrl }}" class="block group">
                    <article
                        class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-200
                               hover:shadow-lg hover:-translate-y-1 transition">

                        <img src="{{ $p['image'] ?? 'https://via.placeholder.com/800x450' }}"
                             alt="{{ $p['title'] ?? 'Program' }}"
                             class="w-full aspect-[16/9] object-cover">

                        <div class="p-5">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs text-emerald-700 font-medium">
                                    {{ $p['category'] ?? 'Program' }}
                                </span>

                                {{-- badge status (optional) --}}
                                @if (!empty($p['status']))
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px]
                                        @if ($p['status'] === 'Selesai') bg-slate-100 text-slate-600
                                        @elseif($p['status'] === 'Berakhir Hari Ini')
                                            bg-orange-100 text-orange-700
                                        @else
                                            bg-emerald-50 text-emerald-700
                                        @endif">
                                        {{ $p['status'] }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="mt-1 font-semibold text-slate-800 leading-snug line-clamp-2 min-h-[3.25rem]
                                       group-hover:text-emerald-700 transition">
                                {{ $p['title'] ?? '-' }}
                            </h3>

                            {{-- Dana & sisa hari --}}
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
                                        @if (array_key_exists('days_left', $p) && is_null($p['days_left']))
                                            Tanpa batas
                                        @elseif(isset($p['days_left']))
                                            {{ $p['days_left'] }} Hari
                                        @else
                                            Habis
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Progress bar + indikator --}}
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
                        </div>
                    </article>
                </a>
            @endforeach
        </div>
    </div>
@endsection
