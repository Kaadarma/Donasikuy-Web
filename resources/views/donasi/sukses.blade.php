@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-xl border border-slate-200 p-8 text-center">

            {{-- ICON --}}
            <div class="w-20 h-20 mx-auto mb-5 rounded-full bg-emerald-50 flex items-center justify-center shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-emerald-500" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>

            {{-- TITLE --}}
            <h1 class="text-2xl font-semibold text-slate-900 mb-2">
                Terima Kasih atas Donasimu! 💚
            </h1>

            <p class="text-sm text-slate-600 leading-relaxed mb-6">
                Donasi kamu telah kami terima dan sedang menunggu konfirmasi pembayaran.
                Kamu bisa cek status pembayaran melalui halaman riwayat atau email.
            </p>

            {{-- SUMMARY CARD --}}
            <div class="text-left bg-slate-50 p-5 rounded-2xl border border-slate-200 shadow-sm mb-6">
                <h2 class="text-sm font-semibold text-slate-800 mb-3">Ringkasan Donasi</h2>

                <div class="space-y-2 text-sm text-slate-700">
                    <p>
                        <span class="font-medium text-slate-900">Program:</span><br>
                        {{ $program['title'] ?? '-' }}
                    </p>

                    <p>
                        <span class="font-medium text-slate-900">Nama Donatur:</span><br>
                        {{ $donatur['nama'] ?? '-' }}
                    </p>

                    <p>
                        <span class="font-medium text-slate-900">Nominal:</span><br>
                        Rp {{ number_format($nominal ?? 0, 0, ',', '.') }}
                    </p>

                    @if (!empty($paymentMethod))
                        <p>
                            <span class="font-medium text-slate-900">Metode Pembayaran:</span><br>
                            {{ strtoupper($paymentMethod) }}
                        </p>
                    @endif

                    @if (!empty($voucherCode))
                        <p>
                            <span class="font-medium text-slate-900">Voucher Digunakan:</span><br>
                            {{ $voucherCode }}
                        </p>
                    @endif
                </div>
            </div>

    
            <a href="/"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl text-sm font-semibold
          bg-gradient-to-r from-emerald-600 via-green-500 to-lime-400 text-white
          shadow-lg shadow-emerald-500/30 hover:brightness-105 active:scale-[0.98] transition">
                Kembali ke Beranda
            </a>

        </div>
    </div>
@endsection
