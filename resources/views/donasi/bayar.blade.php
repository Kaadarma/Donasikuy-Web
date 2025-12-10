@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 md:p-7">

                {{-- HEADER INVOICE + STATUS --}}
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-6">
                    <div>
                        <p class="text-xs font-semibold tracking-[0.18em] text-slate-400 uppercase">
                            Ringkasan Pembayaran
                        </p>
                        <h1 class="text-xl md:text-2xl font-semibold text-slate-900 mt-1">
                            Invoice Donasi
                        </h1>

                        <div class="mt-2 space-y-1 text-xs text-slate-500">
                            <p>
                                ID Transaksi:
                                <span class="font-medium text-slate-800">
                                    {{ $orderId ?? '-' }}
                                </span>
                            </p>
                            <p>
                                Tanggal:
                                <span class="font-medium text-slate-800">
                                    {{ now()->format('d M Y, H:i') }} WITA
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="text-right space-y-2">
                        <span
                            class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-[11px] font-semibold text-amber-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Menunggu Pembayaran
                        </span>

                        {{-- COUNTDOWN --}}
                        <div class="text-xs text-slate-500">
                            <p class="mb-0.5">Batas waktu pembayaran:</p>
                            <p id="countdown"
                                class="text-sm font-semibold text-rose-600 bg-rose-50 rounded-full px-3 py-1 inline-flex items-center justify-end gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 6v6l3 3m5-3a8 8 0 11-16 0 8 8 0 0116 0z" />
                                </svg>
                                <span id="countdown-text">--:--</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- BADGE PROGRAM --}}
                <div class="mb-4 border border-slate-100 bg-slate-50/70 rounded-2xl px-4 py-3">
                    <p class="text-[11px] font-medium text-slate-500 uppercase tracking-[0.16em] mb-1">
                        Program Donasi
                    </p>
                    <p class="text-sm font-semibold text-slate-900">
                        {{ $program['title'] ?? 'Donasi Tanpa Judul' }}
                    </p>
                </div>

                {{-- RINCIAN DONASI (LAYOUT INVOICE) --}}
                <div class="mb-5">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-[0.18em] mb-2">
                        Rincian Donasi
                    </p>

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 divide-y divide-slate-100">
                        <div class="flex items-center justify-between px-4 py-3 text-sm">
                            <span class="text-slate-600">Nominal Donasi</span>
                            <span class="font-semibold text-slate-900">
                                Rp {{ number_format($data['nominal'], 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between px-4 py-3 text-sm">
                            <span class="text-slate-600">Atas Nama</span>
                            <span class="font-medium text-slate-900">
                                {{ $displayName ?? $data['nama'] }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between px-4 py-3 text-sm">
                            <span class="text-slate-600">Metode Pembayaran</span>
                            <span class="font-semibold text-slate-900 uppercase">
                                {{ $data['payment_method'] }}
                            </span>
                        </div>

                        {{-- Total baris paling bawah --}}
                        <div class="flex items-center justify-between px-4 py-3 bg-white rounded-b-2xl">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-[0.18em]">
                                Total Dibayar
                            </span>
                            <span class="text-base font-semibold text-emerald-600">
                                Rp {{ number_format($data['nominal'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- INFORMASI MIDTRANS --}}
                <div
                    class="mb-5 text-xs text-slate-500 leading-relaxed bg-amber-50 border border-amber-100 rounded-2xl px-4 py-3">
                    <p class="font-medium text-amber-700 mb-1">
                        Cara membayar:
                    </p>
                    <p>
                        Setelah kamu klik <span class="font-semibold text-slate-800">"Bayar Sekarang"</span>,
                        akan muncul popup pembayaran dari Midtrans di halaman ini. Silakan ikuti instruksi sesuai
                        metode pembayaran yang kamu pilih.
                    </p>
                    <p class="mt-1">
                        Jika halaman ini tertutup sebelum selesai, kamu bisa kembali ke halaman ini dari riwayat
                        donasi (jika disediakan).
                    </p>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="mt-4 space-y-2">
                    <button id="pay-button"
                        class="w-full py-3.5 rounded-2xl text-sm font-semibold
                               bg-gradient-to-r from-emerald-600 via-green-500 to-lime-400
                               text-white shadow-lg shadow-emerald-500/30
                               hover:brightness-105 active:scale-[0.99] transition
                               disabled:opacity-60 disabled:cursor-not-allowed">
                        Bayar Sekarang
                    </button>
                    {{-- Tombol testing untuk langsung sukses --}}
                    <button onclick="testSuccess()"
                        class="mt-2 w-full py-3 rounded-xl text-sm font-semibold bg-slate-200 text-slate-700 hover:bg-slate-300 transition">
                        🔧 Paksa Sukses (Testing)
                    </button>

                    <script>
                        function testSuccess() {
                            const orderId = @json($orderId);
                            window.location.href =
                                "{{ route('donasi.sukses') }}?order_id=" + encodeURIComponent(orderId);
                        }
                    </script>


                    <p class="text-[11px] text-center text-slate-400">
                        Dengan melanjutkan, kamu setuju dengan syarat & ketentuan donasi yang berlaku.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Snap Midtrans (SANDBOX) --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');
            const countdownEl = document.getElementById('countdown');
            const countdownText = document.getElementById('countdown-text');

            const snapToken = @json($snapToken);
            const orderId = @json($orderId);

            // === COUNTDOWN (misal 15 menit) ===
            const EXPIRE_MINUTES = 15;
            const storageKey = 'donasi_expiry_' + orderId;

            let expiryTime = localStorage.getItem(storageKey);

            if (!expiryTime) {
                expiryTime = Date.now() + EXPIRE_MINUTES * 60 * 1000;
                localStorage.setItem(storageKey, expiryTime);
            } else {
                expiryTime = parseInt(expiryTime, 10);
            }

            function updateCountdown() {
                const now = Date.now();
                const diff = expiryTime - now;

                if (diff <= 0) {
                    // Waktu habis
                    countdownText.textContent = 'Waktu habis';
                    countdownEl.classList.remove('bg-rose-50', 'text-rose-600');
                    countdownEl.classList.add('bg-slate-100', 'text-slate-500');

                    if (payButton) {
                        payButton.disabled = true;
                        payButton.textContent = 'Waktu Pembayaran Habis';
                    }

                    return;
                }

                const totalSeconds = Math.floor(diff / 1000);
                const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
                const seconds = String(totalSeconds % 60).padStart(2, '0');

                countdownText.textContent = `${minutes}:${seconds}`;
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);

            // === FUNGSI BUKA SNAP MIDTRANS ===
            function openSnap() {
                if (!snapToken) {
                    alert('Token pembayaran tidak tersedia. Silakan muat ulang halaman.');
                    return;
                }

                window.snap.pay(snapToken, {
                    onSuccess: function(result) {
                        window.location.href = "{{ route('donasi.sukses') }}";
                    },


                    onPending: function(result) {
                        alert(
                            'Pembayaran kamu masih PENDING / menunggu.\n' +
                            'Silakan selesaikan pembayaran sesuai instruksi yang diberikan.'
                        );
                    },
                    onError: function(result) {
                        alert(
                            'Terjadi kesalahan saat memproses pembayaran.\n' +
                            'Silakan coba lagi atau gunakan metode pembayaran lain.'
                        );
                    },
                    onClose: function() {
                        alert(
                            'Kamu menutup halaman pembayaran sebelum selesai.\n' +
                            'Donasi kamu belum tercatat sebagai berhasil.'
                        );
                    }
                });
            }

            if (payButton) {
                payButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    openSnap();
                });
            }
        });
    </script>
@endsection
