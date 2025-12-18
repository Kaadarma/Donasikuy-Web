@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded-xl">
    <h2 class="text-lg font-semibold mb-4">Pembayaran Donasi Event</h2>

    <p class="mb-2">{{ $event->title }}</p>
    <p class="mb-4 font-semibold">
        Rp {{ number_format($donation->amount, 0, ',', '.') }}
    </p>

    <button id="pay-button"
        class="w-full py-3 bg-emerald-600 text-white rounded-lg">
        Bayar Sekarang
    </button>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.getElementById('pay-button').addEventListener('click', function () {
    window.snap.pay(@json($snapToken), {
        onSuccess: function(result) {
            window.location.href = "/events/{{ $event->slug }}";
        },
        onPending: function(result) {
            alert("Menunggu pembayaran");
        },
        onError: function(result) {
            alert("Pembayaran gagal");
        }
    });
});
</script>
@endsection
