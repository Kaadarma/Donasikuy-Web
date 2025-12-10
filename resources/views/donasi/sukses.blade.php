@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-xl border border-slate-200 p-8 text-center">

            {{-- ICON --}}
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-emerald-50 flex items-center justify-center shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-emerald-500" fill="none"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>

            {{-- TITLE --}}
            <h1 class="text-xl font-semibold text-slate-900 mb-1">
                Terima kasih atas donasimu! 💚
            </h1>

            <p class="text-sm text-slate-600 mb-6">
                Donasi kamu sudah berhasil tercatat. Berikut ringkasan donasinya.
            </p>

            {{-- RINGKASAN SEDERHANA --}}
            <div class="text-left bg-slate-50 p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 space-y-3 text-sm">
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500">Program</span>
                    <span class="font-medium text-slate-900 text-right">
                        {{ $programTitle ?? '-' }}
                    </span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-slate-500">Jumlah Donasi</span>
                    <span class="font-semibold text-emerald-600 text-right">
                        Rp {{ number_format($amount ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- BUTTON --}}
            <a href="{{ route('landing') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl text-sm font-semibold
                       bg-gradient-to-r from-emerald-600 via-green-500 to-lime-400 text-white
                       shadow-lg shadow-emerald-500/30 hover:brightness-105 active:scale-[0.98] transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection
