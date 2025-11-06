@extends('layouts.app')

@section('title', 'Daftar Program')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-20">
        <h1 class="text-2xl font-bold mb-6 text-emerald-800">Program Pilihan Kami</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($programs as $p)
                <a href="{{ route('programs.show', $p['id']) }}"
                    class="block border rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition">
                    <img src="{{ $p['image'] }}" alt="{{ $p['title'] }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <p class="text-sm text-emerald-600 font-medium">{{ $p['category'] }}</p>
                        <h3 class="text-lg font-semibold text-slate-800 mt-1">{{ $p['title'] }}</h3>
                        <div class="mt-3 text-sm text-slate-600 flex justify-between">
                            <span>Dana Terkumpul</span>
                            <span>Sisa {{ $p['days_left'] }} Hari</span>
                        </div>
                        <div class="text-sm font-semibold mt-1">
                            Rp {{ number_format($p['raised'], 0, ',', '.') }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
