@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ openDobModal: false }">

    <h1 class="text-2xl font-bold text-slate-900 mb-6">
        Profil
    </h1>

    {{-- Kartu profil hijau --}}
    <section class="bg-emerald-600 text-white rounded-3xl p-6 md:p-8 shadow-lg mb-8">
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
                    <h2 class="text-lg font-semibold leading-tight">
                        {{ $user->name }}
                    </h2>
                    <p class="text-xs text-emerald-100">
                        {{ $user->email }}
                        @if ($user->phone)
                            <br>{{ $user->phone }}
                        @endif
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

<div class="mt-8">
    <h3 class="text-lg font-semibold text-slate-900 mb-4">
        Campaign yang Pernah Kamu Donasi
    </h3>

    @if($donatedPrograms->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($donatedPrograms as $row)
                @include('profile.partials.card', ['row' => $row])
            @endforeach
        </div>
    @else
    <div class="flex justify-center items-center py-12">
        <p class="text-sm text-slate-500 text-center">
            Kamu belum pernah berdonasi ke campaign mana pun.
        </p>
    </div>
    @endif
</div>



</div>
@endsection
