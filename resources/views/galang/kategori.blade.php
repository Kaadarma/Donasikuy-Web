@extends('layouts.app')

@section('title', 'Pilih Kategori Galang Dana')

@section('content')
<div class="min-h-[calc(100vh-4rem)] flex items-center justify-center bg-slate-50 py-10">
    <div class="w-full max-w-5xl">
        {{-- Kartu utama --}}
        <div class="bg-white rounded-3xl shadow-[0_18px_45px_rgba(15,23,42,.12)] border border-slate-100 px-6 sm:px-10 py-6 sm:py-8">

            {{-- Header kartu --}}
            <div class="flex items-center gap-4 mb-6">
                {{-- Tombol back --}}
                <a href="{{ route('galang.create') }}"
                   class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200
                          text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-900">
                        Pilih Kategori Galang Dana
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Pilih kategori yang paling sesuai dengan tujuan penggalangan dana kamu.
                    </p>
                </div>
            </div>

            {{-- List kategori --}}
            <div class="mt-4 space-y-3">
                @foreach ($categories as $cat)
                    <a href="#"
                       class="group flex items-center justify-between rounded-2xl border border-slate-200
                              hover:border-emerald-500 bg-white hover:bg-emerald-50/40 px-4 sm:px-6 py-3 sm:py-4
                              transition">
                        <div class="flex items-center gap-4">
                            {{-- Icon kiri --}}
                            <div
                                class="h-10 w-10 sm:h-11 sm:w-11 rounded-2xl bg-emerald-50 text-emerald-600
                                       flex items-center justify-center text-xl">
                                {{ $cat['icon'] }}
                            </div>
                            <div>
                                <div class="text-sm sm:text-base font-medium text-slate-900">
                                    {{ $cat['name'] }}
                                </div>
                                <p class="hidden sm:block text-xs text-slate-500">
                                    {{-- Deskripsi singkat per kategori (opsional) --}}
                                    @switch($cat['slug'])
                                        @case('pendidikan') Bantuan biaya sekolah, buku, atau fasilitas belajar. @break
                                        @case('bencana-alam') Bantu korban banjir, gempa, atau bencana lainnya. @break
                                        @case('kemanusiaan') Dukung warga kurang mampu, difabel, dan lansia. @break
                                        @case('panti-asuhan') Bantu kebutuhan anak-anak panti asuhan. @break
                                        @case('lingkungan') Dukung program bersih-bersih dan hijaukan lingkungan. @break
                                        @case('sedekah') Sedekah umum untuk berbagai kebaikan. @break
                                        @default Pilih kategori yang sesuai dengan kampanyemu.
                                    @endswitch
                                </p>
                            </div>
                        </div>

                        {{-- Arrow kanan --}}
                        <div class="flex items-center gap-2 text-emerald-600">
                            <span class="hidden sm:inline text-xs font-medium">Pilih</span>
                            <span
                                class="h-8 w-8 rounded-full border border-emerald-200 flex items-center justify-center
                                       group-hover:bg-emerald-500 group-hover:text-white transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
