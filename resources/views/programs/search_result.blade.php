@extends('layouts.app')

@section('title', 'Hasil Pencarian Program')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

    <h2 class="text-2xl font-bold mb-6">
        Hasil pencarian untuk: <span class="text-emerald-600">"{{ $keyword }}"</span>
    </h2>

    @if ($programs->count() == 0)
        <p class="text-slate-500">Tidak ada program ditemukan.</p>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($programs as $p)
            <div class="bg-white border rounded-xl shadow-sm p-4">

                <img src="{{ $p['image'] }}" class="rounded-lg mb-3">

                <h3 class="font-semibold text-lg mb-2">{{ $p['title'] }}</h3>

                <p class="text-sm text-slate-600 mb-3">
                    {{ Str::limit($p['category'], 120) }}
                </p>

                <a href="{{ route('programs.show', $p['id']) }}"
                   class="text-emerald-600 font-semibold text-sm">
                    Lihat Detail →
                </a>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $programs->links() }}
    </div>
</div>
@endsection
