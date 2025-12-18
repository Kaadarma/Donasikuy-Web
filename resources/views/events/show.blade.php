@extends('layouts.app')
@section('title', $event->title)

@section('content')
@php
  $progress = $event->target > 0 ? min(100, round(($event->raised / $event->target) * 100)) : 0;
@endphp

<div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <div class="relative rounded-xl overflow-hidden">
    <img
  src="{{ $event->image
        ? asset('storage/'.$event->image)
        : 'https://source.unsplash.com/1200x500/?charity,event' }}"
  alt="{{ $event->title }}"
  class="w-full h-[260px] md:h-[320px] object-cover">
    <span class="absolute top-0 left-0 w-full h-[3px] bg-emerald-600"></span>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-6">
    <div class="lg:col-span-2">

      <h1 class="text-2xl font-semibold text-slate-900 mt-3">{{ $event->title }}</h1>
      <p class="mt-3 text-slate-700 text-sm leading-relaxed">
        {{ $event->description }}
      </p>
    </div>

    <div class="lg:sticky lg:top-20">
      <div class="rounded-2xl border border-slate-200 shadow-sm bg-white overflow-hidden">
        <div class="p-4">
          <div class="flex justify-between text-[12px] text-slate-600">
            <span>Terkumpul</span><span>Target</span>
          </div>
          <div class="flex justify-between text-[13px] font-semibold text-slate-900">
            <span>Rp {{ number_format($event->raised,0,',','.') }}</span>
            <span>Rp {{ number_format($event->target,0,',','.') }}</span>
          </div>

          <div class="mt-3">
            <div class="w-full bg-slate-200 rounded-full h-2">
              <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
            </div>
            <div class="text-[11px] text-slate-500 flex justify-between mt-1">
              <span>{{ $progress }}% tercapai</span>
              <span>💚</span>
            </div>
          </div>

          {{-- tombol donasi event nanti --}}
          <a href="{{ route('events.donate', ['event' => $event->slug]) }}"
            class="w-full py-2.5 rounded-full text-[13px] font-semibold
                    bg-emerald-600 text-white text-center block">
                DONASI SEKARANG
            </a>


        </div>
      </div>
    </div>

  </div>
</div>
@endsection
