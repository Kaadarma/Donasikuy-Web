@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 via-slate-100 to-slate-50 py-10 px-4">
    <div class="max-w-2xl mx-auto">

        {{-- HERO / HEADER --}}
        <div class="relative overflow-hidden rounded-3xl border border-slate-200 shadow-xl bg-white">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 via-green-500 to-lime-400 opacity-[0.14]"></div>
            <div class="relative p-8 sm:p-10">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/70 backdrop-blur border border-white/60 shadow flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-emerald-600" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>

                    <div class="flex-1">
                        <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900 leading-tight">
                            Pembayaran Berhasil! 🎉
                        </h1>
                        <p class="text-sm sm:text-[15px] text-slate-600 mt-2 max-w-prose">
                            Terima kasih, donasi kamu sudah tercatat. Semoga jadi kebaikan yang terus mengalir.
                        </p>

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            @php
                                $st = strtolower($donation->status ?? 'paid');
                                $label = in_array($st, ['paid','success'], true) ? 'Berhasil' : ($st === 'pending' ? 'Menunggu' : 'Gagal');
                                $badge = in_array($st, ['paid','success'], true)
                                    ? 'bg-emerald-100 text-emerald-700 border-emerald-200'
                                    : ($st === 'pending'
                                        ? 'bg-amber-100 text-amber-700 border-amber-200'
                                        : 'bg-rose-100 text-rose-700 border-rose-200');
                            @endphp
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold border {{ $badge }}">
                                <span class="w-2 h-2 rounded-full
                                    {{ in_array($st, ['paid','success'], true) ? 'bg-emerald-500' : ($st === 'pending' ? 'bg-amber-500' : 'bg-rose-500') }}">
                                </span>
                                Status: {{ $label }}
                            </span>

                            @if(!empty($orderId))
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border border-slate-200 bg-white text-slate-700">
                                    Order ID: <span class="ml-1 font-bold">{{ $orderId }}</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- PROGRAM CARD --}}
            <div class="relative px-6 sm:px-10 pb-8">
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-200 flex-shrink-0 border border-slate-200">
                                <img
                                    src="{{ $program['image'] ?? 'https://via.placeholder.com/400x400?text=Program' }}"
                                    alt="{{ $program['title'] ?? 'Program' }}"
                                    class="w-full h-full object-cover">
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-500">Program Donasi</p>
                                <p class="text-base sm:text-lg font-semibold text-slate-900 truncate">
                                    {{ $program['title'] ?? '-' }}
                                </p>

                                @if(!empty($program['slug']))
                                    <a href="{{ route('donasi.nominal', $program['slug']) }}"
                                       class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 hover:text-emerald-800 mt-1">
                                        Lihat program
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- SUMMARY --}}
                        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs text-slate-500">Nama Donatur</p>
                                <p class="mt-1 font-semibold text-slate-900">
                                    {{ ($donation->is_anonymous ?? 0) ? 'Siapa Ya?' : ($donation->donor_name ?? '-') }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs text-slate-500">Jumlah Donasi</p>
                                <p class="mt-1 font-extrabold text-emerald-700 text-lg">
                                    Rp {{ number_format((int) ($nominal ?? 0), 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        @if(!empty($donation->message))
                            <div class="mt-3 rounded-2xl border border-slate-200 bg-white p-4">
                                <p class="text-xs text-slate-500">Pesan</p>
                                <p class="text-sm text-slate-700 mt-1 leading-relaxed">
                                    {{ $donation->message }}
                                </p>
                            </div>
                        @endif

                        {{-- OPTIONAL: PROGRESS INFO --}}
                        @php
                            $raised = (int) data_get($program, 'raised', 0);
                            $target = (int) data_get($program, 'target', 0);
                            $unlimited = $target <= 0;
                            $percent = $unlimited ? 0 : min(100, (int) round(($raised / max(1, $target)) * 100));
                        @endphp

                        <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="flex items-center justify-between gap-4">
                                <p class="text-sm font-semibold text-slate-900">Progress Program</p>
                                <p class="text-xs font-semibold text-slate-600">
                                    {{ $unlimited ? 'Tanpa target' : $percent . '%' }}
                                </p>
                            </div>

                            <div class="mt-2 h-2 w-full bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-2 rounded-full bg-gradient-to-r from-emerald-600 via-green-500 to-lime-400"
                                     style="width: {{ $unlimited ? 0 : $percent }}%"></div>
                            </div>

                            <div class="mt-2 flex items-center justify-between text-xs text-slate-600">
                                <span>Rp {{ number_format($raised, 0, ',', '.') }}</span>
                                <span>{{ $unlimited ? '' : 'dari Rp ' . number_format($target, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- FOOT ACTIONS --}}
                    <div class="border-t border-slate-200 bg-slate-50 p-5 sm:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @if(!empty($program['slug']))
                                <a href="{{ route('donasi.nominal', $program['slug']) }}"
                                   class="inline-flex items-center justify-center px-5 py-3 rounded-2xl text-sm font-semibold
                                          bg-white border border-slate-200 text-slate-800 hover:bg-slate-50 transition">
                                    Donasi Lagi
                                </a>
                            @endif

                            <a href="{{ route('landing') ?? '/' }}"
                               class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl text-sm font-semibold
                                      bg-gradient-to-r from-emerald-600 via-green-500 to-lime-400 text-white
                                      shadow-lg shadow-emerald-500/30 hover:brightness-105 active:scale-[0.98] transition">
                                Kembali ke Beranda
                            </a>
                        </div>

                        <p class="text-[11px] text-slate-500 mt-4 text-center">
                            Jika status masih “Menunggu”, biasanya notifikasi Midtrans belum masuk (pending → paid).
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection