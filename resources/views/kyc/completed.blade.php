@extends('layouts.dashboard')

@section('title', 'Verifikasi Akun')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50">
    <div class="bg-white rounded-2xl shadow-md p-8 max-w-md text-center">
        <h1 class="text-lg font-semibold text-slate-900 mb-2">
            Verifikasi Dikirim
        </h1>
        <p class="text-sm text-slate-600 mb-4">
            Terima kasih, data KYC kamu sudah kami terima.
            Tim kami akan memeriksa data kamu dalam beberapa waktu ke depan.
        </p>
        <a href="{{ route('dashboard.index') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg
                  bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
