@extends('layouts.dashboard')
@section('title', 'Campaign - Draft / Ditolak')
@section('page_title', 'Campaign')

@section('content')
<div class="px-4 md:px-8 py-6 space-y-6">

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Draft</h1>
            <p class="mt-2 text-slate-600">
                Draft bisa kamu edit. Jika ditolak, edit lalu ajukan ulang (nanti kita bikin tombol “Ajukan”).
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('dashboard.campaigns.index') }}"
               class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                ← Ringkasan
            </a>

            <a href="{{ route('galang.create') }}"
               class="rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 shadow">
                + Buat Draft
            </a>
        </div>
    </div>

    @if($campaigns->count() === 0)
        <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600">
            Belum ada draft campaign.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($campaigns as $p)
                @include('dashboard.campaigns.partials.card', ['p' => $p])
            @endforeach
        </div>

        <div class="pt-4">
            {{ $campaigns->links() }}
        </div>
    @endif

</div>
@endsection
