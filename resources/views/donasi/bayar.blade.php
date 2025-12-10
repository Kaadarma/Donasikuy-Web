@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">

                <h1 class="text-xl font-semibold text-slate-900 mb-2">
                    Konfirmasi Pembayaran
                </h1>
                <p class="text-sm text-slate-600 mb-4">
                    Silakan selesaikan pembayaran donasi kamu melalui Midtrans.
                </p>

                <div class="space-y-2 text-sm text-slate-700 mb-4">
                    <div class="flex justify-between">
                        <span>Program</span>
                        <span class="font-medium">
                            {{ $program['title'] ?? 'Donasi' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Nominal</span>
                        <span class="font-semibold text-emerald-600">
                            Rp {{ number_format($data['nominal'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Atas nama</span>
                        <span class="font-medium">
                            {{ $displayName }}
                        </span>
                    </div>
                </div>

                <p class="text-xs text-slate-500">
                    Popup pembayaran akan muncul. Jika belum muncul, klik tombol di bawah.
                </p>

                <div class="mt-6">
                    <button id="pay-button"
                        class="w-full py-3.5 rounded-2xl text-sm font-semibold
                               bg-gradient-to-r from-emerald-600 via-green-500 to-lime-400
                               text-white shadow-lg shadow-emerald-500/30
                               hover:brightness-105 active:scale-[0.99] transition">
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Snap Midtrans (SANDBOX) --}}
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var payButton = document.getElementById('pay-button');
            var snapToken = @json($snapToken);

            // AUTO BUKA POPUP BEGITU HALAMAN TERLOAD
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log('success', result);
                    // TODO: arahkan ke halaman sukses beneran
                    window.location.href = "{{ route('donasi.sukses') }}";
                },
                onPending: function(result) {
                    console.log('pending', result);
                    window.location.href = "{{ route('donasi.sukses') }}"; // atau pending page
                },
                onError: function(result) {
                    console.log('error', result);
                    alert('Terjadi kesalahan saat memproses pembayaran.');
                },
                onClose: function() {
                    console.log('customer closed the popup without finishing the payment');
                }
            });

            // CADANGAN: kalau user klik tombol, buka lagi popup-nya
            payButton.addEventListener('click', function (e) {
                e.preventDefault();
                window.snap.pay(snapToken);
            });
        });
    </script>
@endsection
