@extends('layouts.dashboard')
@section('title', 'Campaign')
@section('page_title', 'Campaign')

@section('content')
<div class="px-4 md:px-8 py-6 space-y-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Campaign</h1>
            <p class="mt-2 text-slate-600">
                Status Campaign Kamu ada Di sini 
            </p>
        </div>

        <a href="{{ route('galang.create') }}"
           class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm md:text-base font-semibold text-white
                  hover:bg-emerald-700 active:bg-emerald-800 shadow-md shadow-emerald-600/30 transition">
            + Buat Galang Dana
        </a>
    </div>
        


    {{-- Quick nav  --}}
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('dashboard.campaigns.running') }}"
           class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Sedang Berjalan
        </a>
        <a href="{{ route('dashboard.campaigns.review') }}"
           class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Menunggu Review
        </a>
        <a href="{{ route('dashboard.campaigns.drafts') }}"
           class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Draft
        </a>
        <a href="{{ route('dashboard.campaigns.rejected') }}"
           class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Ditolak
        </a>
    </div>


    {{-- SECTION: Running --}}
    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">Sedang Berjalan</h2>
            <a class="text-sm font-semibold text-emerald-700 hover:underline"
               href="{{ route('dashboard.campaigns.running') }}">
                Lihat semua →
            </a>
        </div>

        @if($running->isEmpty())
            <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600">
                Belum ada campaign yang sedang berjalan.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($running as $p)
                    @include('dashboard.campaigns.partials.card', ['p' => $p])
                @endforeach
            </div>
        @endif
    </section>

    {{-- SECTION: Review --}}
    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">Menunggu Review</h2>
            <a class="text-sm font-semibold text-emerald-700 hover:underline"
               href="{{ route('dashboard.campaigns.review') }}">
                Lihat semua →
            </a>
        </div>

        @if($review->isEmpty())
            <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600">
                Tidak ada campaign yang sedang menunggu review.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($review as $p)
                    @include('dashboard.campaigns.partials.card', ['p' => $p])
                @endforeach
            </div>
        @endif
    </section>


{{-- SECTION: Draft --}}
<div class="mb-10">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-slate-900">Draft</h2>
        <a href="{{ route('dashboard.campaigns.drafts') }}" class="text-sm font-semibold text-emerald-700 hover:underline">
            Lihat semua →
        </a>
    </div>

    @if($drafts->count() === 0)
        <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600">
            Belum ada draft.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($drafts as $p)
                @include('dashboard.campaigns.partials.card', ['p' => $p])
            @endforeach
        </div>

        <div class="mt-6">
            {{ $drafts->links() }}
        </div>
    @endif
</div>

{{-- SECTION: Ditolak --}}
<div class="mb-10">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-slate-900">Ditolak</h2>
        <a href="#" class="text-sm font-semibold text-emerald-700 hover:underline">
            Lihat semua →
        </a>
    </div>

    @if($rejected->count() === 0)
        <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600">
            Belum ada campaign yang ditolak.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($rejected as $p)
                @include('dashboard.campaigns.partials.card', ['p' => $p])
            @endforeach
        </div>

        <div class="mt-6">
            {{ $rejected->links() }}
        </div>
    @endif
</div>
</div>
@endsection
