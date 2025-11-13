@extends('layouts.app')
@section('title', 'Inspirasi')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold text-emerald-800 mb-6">Inspirasi</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($items as $i)
                <a href="{{ route('inspirasi.show', $i['slug']) }}"
                    class="group block rounded-xl overflow-hidden border hover:shadow-lg transition">
                    <img src="{{ $i['image'] }}" alt="{{ $i['title'] }}"
                        class="h-44 w-full object-cover group-hover:scale-[1.02] transition">
                    <div class="p-4">
                        <div class="text-xs text-slate-500">
                            {{ \Carbon\Carbon::parse($i['published_at'])->translatedFormat('d M Y') }}
                        </div>
                        <h3 class="mt-1 font-semibold text-slate-900">{{ $i['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-600 line-clamp-2">{{ $i['excerpt'] }}</p>
                        <span class="mt-3 inline-flex items-center text-emerald-700 text-sm">
                            Baca selengkapnya
                            <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1
                  1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0
                  110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
