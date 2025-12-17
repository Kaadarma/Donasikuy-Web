@extends('layouts.app')
@section('title', 'Verifikasi Email Baru')
@section('authpage', true)

@section('content')
<div class="max-w-md mx-auto py-10 px-4">
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">

        <h1 class="text-lg font-semibold text-slate-900">Verifikasi Email Baru</h1>

        <p class="mt-2 text-sm text-slate-600">
            Kami sudah mengirim link verifikasi ke email baru kamu:
            <span class="font-medium text-slate-800">{{ session('pending_new_email') }}</span>.
            Silakan cek inbox atau folder spam.
        </p>

        @if (session('success'))
            <div class="mt-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg p-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.email.resend') }}" class="mt-4">
            @csrf
            <button class="w-full rounded-lg bg-emerald-600 text-white py-2.5 text-sm font-medium hover:bg-emerald-700">
                Kirim ulang link verifikasi
            </button>
        </form>

        <div class="flex items-center gap-3 my-4">
            <span class="h-px flex-1 bg-slate-200"></span>
            <span class="text-xs text-slate-400">atau</span>
            <span class="h-px flex-1 bg-slate-200"></span>
        </div>

        <a href="{{ route('profile.edit') }}"
           class="block w-full text-center rounded-lg border border-slate-300 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
            Kembali ke Edit Profil
        </a>
    </div>
</div>
@endsection
