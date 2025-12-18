@extends('layouts.app')
@section('title','Event')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-emerald-700">Event Kemanusiaan</h1>
        <p class="mt-2 text-slate-600 max-w-2xl">
            Dukung event kemanusiaan yang membutuhkan bantuan dana agar dapat terlaksana.
        </p>
    </div>

    {{-- Grid Event --}}

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($events as $event)
            @php
                $progress = $event->target > 0
                    ? min(100, round(($event->raised / $event->target) * 100))
                    : 0;
            @endphp

            <div class="bg-white rounded-2xl border shadow-sm
                        hover:shadow-lg transition overflow-hidden flex flex-col">

                {{-- Image --}}
                <div class="h-48 w-full overflow-hidden bg-slate-200">
                    <img
                    src="{{ $event->image
                            ? asset('storage/'.$event->image)
                            : 'https://source.unsplash.com/600x400/?charity,event' }}"
                    alt="{{ $event->title }}"
                    class="w-full h-48 object-cover">
                </div>

                {{-- Content --}}
                <div class="p-5 flex flex-col flex-1 space-y-4">

                    <div>
                        <h3 class="font-semibold text-lg text-slate-800 line-clamp-2">
                            {{ $event->title }}
                        </h3>

                        <p class="mt-1 text-sm text-slate-600 line-clamp-2">
                            {{ $event->short_description }}
                        </p>
                    </div>

                    {{-- Progress --}}
                    <div class="mt-auto">
                        <div class="flex justify-between text-xs text-slate-600 mb-1">
                            <span>Terkumpul</span>
                            <span>
                                Rp {{ number_format($event->raised,0,',','.') }}
                            </span>
                        </div>

                        <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-emerald-600 h-2 rounded-full"
                                 style="width: {{ $progress }}%">
                            </div>
                        </div>

                        <div class="flex justify-between text-xs text-slate-500 mt-1">
                            <span>
                                Target Rp {{ number_format($event->target,0,',','.') }}
                            </span>
                            <span>{{ $progress }}%</span>
                        </div>
                    </div>

                    {{-- Action --}}
                    <a href="{{ route('events.show', $event->slug) }}"
                       class="mt-4 block text-center rounded-xl
                              bg-emerald-600 hover:bg-emerald-700
                              text-white font-semibold text-sm py-2.5 transition">
                        Lihat Event
                    </a>

                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-10">
        {{ $events->links() }}
    </div>

</div>
@endsection
