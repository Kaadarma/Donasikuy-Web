@extends('layouts.dashboard')
@section('title', 'Campaign Ditolak')
@section('page_title', 'Campaign')

@section('content')
<div class="px-4 md:px-8 py-6 space-y-6">

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Ditolak</h1>
            <p class="mt-2 text-slate-600">
            </p>
        </div>

        <a href="{{ route('galang.create') }}"
           class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm md:text-base font-semibold text-white
                  hover:bg-emerald-700 active:bg-emerald-800 shadow-md shadow-emerald-600/30 transition">
            + Buat Galang Dana
        </a>
    </div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

    {{-- Quick nav (KIRI) --}}
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

    {{-- Kembali ke Ringkasan (KANAN) --}}
    <a href="{{ route('dashboard.campaigns.index') }}"
       class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
        ← Kembali
    </a>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($rejected as $p)
            @include('dashboard.campaigns.partials.card', ['p' => $p, 'mode' => 'rejected'])
        @empty
            <div class="md:col-span-2 rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600">
                Tidak ada campaign ditolak.
            </div>
        @endforelse
    </div>

    <div>
        {{ $rejected->links() ?? '' }}
    </div>
</div>
@endsection
