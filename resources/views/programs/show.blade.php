@extends('layouts.app')
@section('title', $program['title'])

{{-- Alpine untuk tab --}}
@push('head')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
    @php
        $progress = max(0, min(100, $program['target'] ? ($program['raised'] / $program['target']) * 100 : 0));
    @endphp

    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Banner --}}
        <div class="relative rounded-xl overflow-hidden mt-3">
            <img src="{{ $program['banner'] }}" alt="Banner" class="w-full h-[260px] md:h-[320px] object-cover">
            {{-- aksen garis hijau di atas --}}
            <span class="absolute top-0 left-0 w-full h-[3px] bg-emerald-600"></span>
        </div>

        {{-- Grid konten + sidebar --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Kolom kiri --}}
            <div class="lg:col-span-2">


                {{-- Tabs --}}
                <div x-data="{ tab: 'cerita' }" class="mt-2">
                    <div class="flex gap-10 text-sm font-medium border-b border-slate-200">
                        <button class="relative py-3"
                            :class="tab === 'cerita' ? 'text-emerald-700' : 'text-slate-500 hover:text-emerald-700'"
                            @click="tab='cerita'">
                            Cerita
                            <span class="absolute -bottom-[1px] left-0 h-[2px] w-12 bg-emerald-700"
                                x-show="tab==='cerita'"></span>
                        </button>
                        <button class="relative py-3"
                            :class="tab === 'kabar' ? 'text-emerald-700' : 'text-slate-500 hover:text-emerald-700'"
                            @click="tab='kabar'">
                            Kabar Terbaru
                            <span class="absolute -bottom-[1px] left-0 h-[2px] w-20 bg-emerald-700"
                                x-show="tab==='kabar'"></span>
                        </button>
                        <button class="relative py-3"
                            :class="tab === 'donatur' ? 'text-emerald-700' : 'text-slate-500 hover:text-emerald-700'"
                            @click="tab='donatur'">
                            Donatur
                            <span class="absolute -bottom-[1px] left-0 h-[2px] w-12 bg-emerald-700"
                                x-show="tab==='donatur'"></span>
                        </button>
                    </div>

                    {{-- CERITA --}}
                    <div x-show="tab==='cerita'" x-transition class="mt-4">
                        <h3 class="text-sm font-semibold text-slate-900 mb-2">Subjudul Teks #1</h3>
                        <div class="text-[13px] leading-relaxed text-slate-700 space-y-3">
                            <p>Ad deserunt officia aliqua veniam do. Velit ex duis tempor dolor mollit duis voluptate
                                incididunt ea.
                                Ullamco ea laborum ipsum sit irure id. Irure reprehenderit eu voluptate elit officia laboris
                                excepteur
                                exercitation cillum sunt nisi. Officia consectetur esse culpa sint dolore et proident
                                eiusmod sint labore.</p>
                            <p>Excepteur enim nostrud duis velit. Deserunt enim amet do proident commodo in incididunt nulla
                                duis velit nisi.
                                Ad eu proident exercitation nulla dolore aliquip labore. Nisi consectetur eu mollit aute
                                sunt irure nulla
                                est consequat ullamco laborum labore in esse.</p>
                            <p>Fugiat fugiat nisi aliqua veniam nisi nostrud. Dolor mollit est nostrud aliqua pariatur. Non
                                deserunt ipsum ea
                                dolor amet aliqua enim. Reprehenderit id ut consectetur ad reprehenderit et laboris sunt
                                ullamco laboris.</p>
                        </div>

                        {{-- Gambar konten --}}
                        <div class="mt-6">
                            <img src="{{ asset('images/bencana1.jpg') }}" alt="Story"
                                class="w-full rounded-lg border object-cover">
                        </div>

                        <h3 class="mt-8 text-sm font-semibold text-slate-900 mb-2">Subjudul Teks #2</h3>
                        <div class="text-[13px] leading-relaxed text-slate-700 space-y-3">
                            <p>Ad deserunt officia aliqua veniam do. Velit ex duis tempor dolor mollit duis voluptate
                                incididunt ea.
                                Ullamco ea laborum ipsum sit irure id. Irure reprehenderit eu voluptate elit officia laboris
                                excepteur
                                exercitation cillum sunt nisi. Officia consectetur esse culpa…</p>
                            <p>Non deserunt ipsum ea dolor amet aliqua enim. Reprehenderit id ut consectetur ad
                                reprehenderit et laboris…</p>
                        </div>
                    </div>

                    {{-- KABAR TERBARU (dummy) --}}
    

                    {{-- DONATUR (dummy) --}}
                    <div x-show="tab==='donatur'" x-transition class="mt-4">
                        <div class="border rounded-lg divide-y">
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="h-8 w-8 rounded-full bg-slate-200"></span>
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">Anonim</p>
                                        <p class="text-xs text-slate-500">10 menit lalu</p>
                                    </div>
                                </div>
                                <p class="text-sm font-semibold text-emerald-700">Rp 100.000</p>
                            </div>
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="h-8 w-8 rounded-full bg-slate-200"></span>
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">Budi</p>
                                        <p class="text-xs text-slate-500">1 jam lalu</p>
                                    </div>
                                </div>
                                <p class="text-sm font-semibold text-emerald-700">Rp 250.000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom kanan: kartu donasi (overlap + sticky) --}}
            <div class="lg:sticky lg:top-20 mt-10">
                <div class="rounded-xl border shadow-sm bg-white overflow-hidden">
                    <div class="p-4">
                        <p class="text-xs font-semibold text-emerald-700 mb-1">{{ $program['category'] }}</p>
                        <h3 class="text-[14px] font-semibold text-slate-900 leading-snug">
                            {{ $program['title'] }}
                        </h3>

                        <div class="mt-2 text-[12px] text-slate-600">
                            Sisa Waktu : <span class="font-medium text-slate-800">{{ $program['days_left'] }} Hari
                                Lagi</span>
                        </div>

                        <div class="mt-3">
                            <div class="flex justify-between text-[12px] text-slate-600">
                                <span>Terkumpul</span><span>Target</span>
                            </div>
                            <div class="flex justify-between text-[13px] font-semibold text-slate-900">
                                <span>Rp {{ number_format($program['raised'], 0, ',', '.') }}</span>
                                <span>Rp {{ number_format($program['target'], 0, ',', '.') }}</span>
                            </div>

                            {{-- Progress + dot --}}
                            <div class="relative mt-2 h-2 bg-slate-200 rounded-full">
                                <div class="absolute left-0 top-0 h-2 bg-emerald-600 rounded-full"
                                    style="width: {{ $progress }}%"></div>
                                <span class="absolute -top-[3px] h-3 w-3 rounded-full bg-emerald-700"
                                    style="left: calc({{ $progress }}% - 6px)"></span>
                            </div>
                        </div>

                        <a href="#"
                            class="mt-4 inline-flex w-full items-center justify-center rounded-md bg-emerald-600 px-4 py-2.5 text-white text-sm font-medium hover:bg-emerald-700">
                            DONASI SEKARANG
                        </a>
                    </div>

                    {{-- Sebarkan Program --}}
                    <div class="border-t p-4">
                        <p class="text-sm text-slate-700 mb-2">Sebarkan Program</p>
                        <div class="flex items-center gap-2">
                            <a href="#"
                                class="h-8 w-8 rounded-full border flex items-center justify-center text-slate-600 hover:text-emerald-600 hover:border-emerald-600">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="#"
                                class="h-8 w-8 rounded-full border flex items-center justify-center text-slate-600 hover:text-emerald-600 hover:border-emerald-600">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#"
                                class="h-8 w-8 rounded-full border flex items-center justify-center text-slate-600 hover:text-emerald-600 hover:border-emerald-600">
                                <i class="bi bi-twitter-x"></i>
                            </a>
                            <button type="button"
                                class="h-8 w-8 rounded-full border flex items-center justify-center text-slate-600 hover:text-emerald-600 hover:border-emerald-600"
                                onclick="navigator.clipboard.writeText(window.location.href)">
                                <i class="bi bi-link-45deg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            </aside>

        </div>
    </div>
@endsection
