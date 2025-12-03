@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ openDobModal: false }">

    <h1 class="text-2xl font-bold text-slate-900 mb-6">
        Profil
    </h1>

    {{-- Kartu profil hijau --}}
    <section class="bg-emerald-800 text-white rounded-3xl p-6 md:p-8 shadow-lg mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

            {{-- Kiri: avatar + nama + kontak --}}
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 rounded-full overflow-hidden border-2 border-emerald-300 bg-emerald-900">
                    <img
                        src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : asset('images/humans.jpg') }}"
                        alt="Foto Profil"
                        class="h-full w-full object-cover">
                </div>
                <div>
                    <p class="text-sm text-emerald-200 mb-0.5">Donatur</p>
                    <h2 class="text-lg font-semibold leading-tight">
                        {{ $user->name }}
                    </h2>
                    <p class="text-xs text-emerald-100">
                        {{ $user->email }}<br>
                        {{-- sementara dummy, nanti bisa diganti no. HP user --}}
                        0817 8654 3222
                    </p>
                </div>
            </div>

            {{-- Kanan: tombol edit --}}
            <div class="flex md:flex-col items-end gap-3">
                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center px-4 py-2 text-xs md:text-sm rounded-full bg-white/10 hover:bg-white/20 border border-emerald-300 text-emerald-50 font-medium transition">
                    Edit Profil
                </a>
            </div>
        </div>

        {{-- Ringkasan donasi --}}
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white text-emerald-900 rounded-2xl px-4 py-3 flex items-center justify-between">
                <span class="text-sm font-medium">Frekuensi Donasi</span>
                <span class="text-base font-semibold">{{ $summary['frekuensi'] }} Kali</span>
            </div>
            <div class="bg-white text-emerald-900 rounded-2xl px-4 py-3 flex items-center justify-between">
                <span class="text-sm font-medium">Total Dana Donasi</span>
                <span class="text-base font-semibold">
                    Rp {{ number_format($summary['total_donasi'], 0, ',', '.') }}
                </span>
            </div>
        </div>
    </section>

    {{-- Aktifitas saya --}}
    <section class="bg-white rounded-3xl shadow-md border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-lg font-semibold text-slate-900">Aktifitas Saya</h3>
        </div>

        <div class="divide-y divide-slate-100 text-sm">

            {{-- Transaksi Saya --}}
            <button type="button"
                class="w-full text-left px-6 py-4 flex items-center justify-between hover:bg-emerald-50">
                <div class="flex items-center gap-3">
                    <span class="h-9 w-9 rounded-lg border border-emerald-500 bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i class="bi bi-receipt-cutoff text-lg"></i>
                    </span>
                    <span class="font-medium text-slate-800">Transaksi Saya</span>
                </div>
                <i class="bi bi-chevron-right text-slate-400"></i>
            </button>

            {{-- Akun bank --}}
            <button type="button"
                class="w-full text-left px-6 py-4 flex items-center justify-between hover:bg-slate-50">
                <div class="flex items-center gap-3">
                    <span class="h-9 w-9 rounded-lg border border-slate-300 bg-slate-50 flex items-center justify-center text-slate-600">
                        <i class="bi bi-credit-card-2-front text-lg"></i>
                    </span>
                    <span class="font-medium text-slate-800">Akun Bank</span>
                </div>
                <i class="bi bi-chevron-right text-slate-400"></i>
            </button>

            {{-- Pengaturan --}}
            <button type="button"
                class="w-full text-left px-6 py-4 flex items-center justify-between hover:bg-slate-50">
                <div class="flex items-center gap-3">
                    <span class="h-9 w-9 rounded-lg border border-slate-300 bg-slate-50 flex items-center justify-center text-slate-600">
                        <i class="bi bi-gear text-lg"></i>
                    </span>
                    <span class="font-medium text-slate-800">Pengaturan</span>
                </div>
                <i class="bi bi-chevron-right text-slate-400"></i>
            </button>

            {{-- Bantuan --}}
            <button type="button"
                class="w-full text-left px-6 py-4 flex items-center justify-between hover:bg-slate-50">
                <div class="flex items-center gap-3">
                    <span class="h-9 w-9 rounded-lg border border-slate-300 bg-slate-50 flex items-center justify-center text-slate-600">
                        <i class="bi bi-question-circle text-lg"></i>
                    </span>
                    <span class="font-medium text-slate-800">Bantuan</span>
                </div>
                <i class="bi bi-chevron-right text-slate-400"></i>
            </button>
        </div>
    </section>

    {{-- Popup tanggal lahir (UI saja, logic nanti) --}}
    <div x-show="openDobModal"
         style="display:none"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-2">
                Atur Tanggal Lahir
            </h2>
            <p class="text-sm text-slate-500 mb-4">
                Fitur simpan tanggal lahir akan dihubungkan nanti. Untuk sekarang ini hanya tampilan.
            </p>

            <div class="space-y-3">
                <label class="block text-sm font-medium text-slate-700">
                    Tanggal lahir
                </label>
                <input type="date"
                       class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button"
                        @click="openDobModal = false"
                        class="px-4 py-2 text-sm rounded-md border border-slate-200 text-slate-600 hover:bg-slate-50">
                    Tutup
                </button>
                <button type="button"
                        class="px-4 py-2 text-sm rounded-md bg-emerald-600 text-white hover:bg-emerald-700">
                    Simpan
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
