@extends('layouts.app')
@section('title', $program['title'])

@push('head')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
    @php
        $raised = $program['raised'] ?? 0;
        $target = $program['target'] ?? 0;
        $progress = $target > 0 ? min(100, round(($raised / $target) * 100)) : 0;
    @endphp

    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Banner --}}
        <div class="relative rounded-xl overflow-hidden mt-3">
            <img src="{{ asset($program['banner']) }}" alt="Banner"class="w-full h-[260px] md:h-[320px] object-cover">

            <span class="absolute top-0 left-0 w-full h-[3px] bg-emerald-600"></span>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Kolom kiri --}}
            <div class="lg:col-span-2">

                {{-- Informasi Utama Program --}}
                <div class="mt-4">
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Kategori --}}
                        <span class="px-3 py-1 text-xs rounded-full bg-emerald-50 text-emerald-700 font-semibold">
                            {{ $program['category'] }}
                        </span>

                        {{-- Status --}}
                        @if ($program['status'] === 'Selesai')
                            <span class="px-3 py-1 text-xs rounded-full bg-slate-200 text-slate-700">
                                Selesai
                            </span>
                        @elseif($program['status'] === 'Berakhir Hari Ini')
                            <span class="px-3 py-1 text-xs rounded-full bg-orange-100 text-orange-700">
                                Berakhir Hari Ini
                            </span>
                        @elseif($program['status'] === 'Sedang Berjalan')
                            <span class="px-3 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700">
                                Sedang Berjalan
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs rounded-full bg-slate-100 text-slate-600">
                                Tanpa Batas Waktu
                            </span>
                        @endif
                    </div>

                    <h1 class="text-xl font-semibold text-slate-900 mt-2 leading-snug">
                        {{ $program['title'] }}
                    </h1>
                </div>

                {{-- Tabs --}}
                <div x-data="{ tab: 'cerita' }" class="mt-6">

                    <div class="flex gap-10 text-sm font-medium border-b border-slate-200">
                        <button class="relative py-3" :class="tab === 'cerita' ? 'text-emerald-700' : 'text-slate-500'"
                            @click="tab='cerita'">
                            Cerita
                            <span class="absolute -bottom-[1px] left-0 h-[2px] w-12 bg-emerald-700"
                                x-show="tab==='cerita'"></span>
                        </button>
                        <button class="relative py-3" :class="tab === 'kabar' ? 'text-emerald-700' : 'text-slate-500'"
                            @click="tab='kabar'">
                            Kabar Terbaru
                            <span class="absolute -bottom-[1px] left-0 h-[2px] w-20 bg-emerald-700"
                                x-show="tab==='kabar'"></span>
                        </button>
                        <button class="relative py-3" :class="tab === 'donatur' ? 'text-emerald-700' : 'text-slate-500'"
                            @click="tab='donatur'">
                            Donatur
                            <span class="absolute -bottom-[1px] left-0 h-[2px] w-12 bg-emerald-700"
                                x-show="tab==='donatur'"></span>
                        </button>
                    </div>

                    {{-- Cerita --}}
                    <div x-show="tab==='cerita'" x-transition class="mt-4">
                        <h3 class="text-sm font-semibold text-slate-900 mb-2">Cerita Program</h3>

                        @if(!empty($program['description']))
                            <div class="text-[13px] leading-relaxed text-slate-700 space-y-2">
                                {!! nl2br(e($program['description'])) !!}
                            </div>
                        @else
                            <p class="text-[13px] leading-relaxed text-slate-500">
                                Belum ada cerita program.
                            </p>
                        @endif

                        @if(!empty($program['image']))
                            <img src="{{ $program['image'] }}" class="mt-4 rounded-lg border w-full object-cover">
                        @endif
                    </div>


                    {{-- Kabar Terbaru --}}
                    <div x-show="tab==='kabar'" x-transition class="mt-4">
                        <div class="space-y-4">
                            @forelse($updates as $up)
                                <div class="rounded-2xl border p-4 bg-white">
                                    <div class="font-semibold">{{ $up['title'] }}</div>
                                    <div class="text-xs text-slate-500 mb-3">{{ $up['date'] }}</div>

                                    @foreach($up['body'] as $line)
                                        <p class="text-sm text-slate-700 mb-2">{{ $line }}</p>
                                    @endforeach

                                    @if(!empty($up['images']))
                                        <div class="grid grid-cols-2 gap-2 mt-3">
                                            @foreach($up['images'] as $img)
                                                <img src="{{ $img }}" class="rounded-xl w-full object-cover">
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">Belum ada kabar terbaru.</p>
                            @endforelse
                        </div>
                    </div>


                    {{-- Donatur --}}

                    <div x-show="tab==='donatur'" x-transition class="mt-4">
                        @if (!isset($donations) || $donations->isEmpty())
                            <div class="rounded-xl border border-slate-200 bg-white p-4 text-[13px] text-slate-600">
                                Belum ada donatur.
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach ($donations as $d)
                                    @php
                                        $isAnon = (int) ($d->is_anonymous ?? 0) === 1;
                                        $name = $isAnon ? 'Siapa Ya?' : ($d->donor_name ?: 'Tanpa Nama');
                                        $amount = (int) ($d->amount ?? 0);
                                    @endphp

                                    <div class="rounded-xl border border-slate-200 bg-white p-4 flex items-start gap-3">
                                        <div
                                            class="h-10 w-10 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center font-semibold">
                                            {{ strtoupper(mb_substr($name, 0, 1)) }}
                                        </div>

                                        <div class="flex-1">
                                            <div class="flex items-center justify-between gap-3">
                                                <div>
                                                    <div class="text-sm font-semibold text-slate-900">
                                                        {{ $name }}
                                                        @if ($isAnon)
                                                            <span
                                                                class="ml-1 text-[11px] text-slate-400 font-medium">(anonimus)</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-[11px] text-slate-500">
                                                        {{ optional($d->created_at)->format('d M Y, H:i') }}
                                                    </div>
                                                </div>

                                                <div class="text-sm font-semibold text-emerald-700 whitespace-nowrap">
                                                    Rp {{ number_format($amount, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            @if (!empty($d->message))
                                                <p class="mt-2 text-[13px] text-slate-700 leading-relaxed">
                                                    “{{ $d->message }}”
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>


                </div>
            </div>

            {{-- Kolom kanan (Sidebar) --}}
            <div class="lg:sticky lg:top-20 mt-10">
                <div class="rounded-2xl border border-slate-200 shadow-sm bg-white overflow-hidden">
                    <div class="p-4">

                        {{-- Kategori --}}
                        <p class="text-xs font-semibold text-emerald-700 mb-1">{{ $program['category'] }}</p>

                        {{-- Judul --}}
                        <h3 class="text-[15px] font-semibold text-slate-900">{{ $program['title'] }}</h3>

                        {{-- Sisa Waktu --}}
                        <div class="mt-2 text-[12px] text-slate-600">
                            Sisa Waktu :
                            <span class="font-medium text-slate-800">
                                @if (is_null($program['days_left']))
                                    Tanpa batas waktu
                                @else
                                    {{ $program['days_left'] }} Hari Lagi
                                @endif
                            </span>
                        </div>

                        {{-- Terkumpul --}}
                        <div class="mt-3">
                            <div class="flex justify-between text-[12px] text-slate-600">
                                <span>Terkumpul</span>
                                <span>Target</span>
                            </div>
                            <div class="flex justify-between text-[13px] font-semibold text-slate-900">
                                <span>Rp {{ number_format($raised, 0, ',', '.') }}</span>
                                <span>Rp {{ number_format($target, 0, ',', '.') }}</span>
                            </div>

                            {{-- Progress --}}
                            <div class="mt-3 space-y-3">
                                <div class="relative h-2.5 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="absolute left-0 top-0 h-2.5 bg-emerald-600 rounded-full"
                                        style="width: {{ $progress }}%"></div>
                                    <span class="absolute -top-[3px] h-3 w-3 rounded-full bg-emerald-700"
                                        style="left: calc({{ $progress }}% - 6px)"></span>
                                </div>

                                <div class="text-[11px] text-slate-500 flex justify-between">
                                    <span>{{ $progress }}% tercapai</span>
                                    <span>Terima kasih para donatur 💚</span>
                                </div>

                                {{-- Tombol Donasi --}}
                                <a href="{{ route('donasi.nominal', ['slug' => $program['slug']]) }}"
                                    class="w-full py-2.5 rounded-full text-[13px] font-semibold
          bg-gradient-to-r from-emerald-600 via-green-500 to-lime-400
          text-white shadow-md hover:brightness-105 active:scale-[0.98]
          transition inline-flex items-center justify-center">
                                    DONASI SEKARANG
                                </a>

                                
                          </div>
                        </div>
                    </div>

                    {{-- Share --}}
                    <div class="border-t px-4 py-3.5 bg-slate-50/60">
                        <p class="text-[13px] text-slate-700 mb-2 font-medium">Sebarkan Program</p>

                        <div class="flex items-center gap-3">
                            <a href="#"
                                class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-emerald-600 hover:bg-emerald-50">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#"
                                class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-emerald-600 hover:bg-emerald-50">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="#"
                                class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-emerald-600 hover:bg-emerald-50">
                                <i class="bi bi-twitter-x"></i>
                            </a>
                            <button onclick="navigator.clipboard.writeText(window.location.href)"
                                class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-emerald-600 hover:bg-emerald-50">
                                <i class="bi bi-link-45deg"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
