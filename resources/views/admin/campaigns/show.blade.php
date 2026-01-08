@extends('layouts.admin')
@section('title', 'Detail Campaign')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-6">

    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Campaign</h1>
            <p class="text-sm text-slate-500">Cek data campaign sebelum disetujui / ditolak.</p>
        </div>

        <a href="{{ route('admin.campaigns.index', ['status' => request('status','pending')]) }}"
           class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50">
            ← Kembali
        </a>
    </div>

    @php
        $badge = match($campaign->status) {
            'approved' => 'bg-emerald-100 text-emerald-700',
            'pending'  => 'bg-amber-100 text-amber-700',
            'rejected' => 'bg-rose-100 text-rose-700',
            'draft'    => 'bg-slate-100 text-slate-700',
            default    => 'bg-slate-100 text-slate-700',
        };
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: info pemilik --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h2 class="text-sm font-bold text-slate-800 mb-4">Data Pemilik</h2>

            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-slate-500">Nama</p>
                    <p class="font-semibold text-slate-900">{{ $campaign->user->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Email</p>
                    <p class="font-semibold text-slate-900">{{ $campaign->user->email ?? '-' }}</p>
                </div>

                <div class="pt-2">
                    <p class="text-slate-500">Status Campaign</p>
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                        {{ strtoupper($campaign->status) }}
                    </span>
                </div>
            </div>

            @if($campaign->status === 'pending')
                <div class="mt-6 space-y-2">
                    <form method="POST" action="{{ route('admin.campaigns.approve', $campaign->id) }}">
                        @csrf
                        <button class="w-full py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                            Approve
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.campaigns.reject', $campaign->id) }}">
                        @csrf
                        <button class="w-full py-2.5 rounded-xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700">
                            Reject
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Right: info campaign --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="relative">
                <img
                    src="{{ $campaign->image ? asset('storage/'.$campaign->image) : 'https://source.unsplash.com/1200x500/?charity' }}"
                    class="w-full h-56 object-cover" alt="">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent"></div>
                <div class="absolute bottom-4 left-5 right-5 text-white">
                    <h2 class="text-xl font-bold leading-snug">{{ $campaign->title }}</h2>
                    <p class="text-xs opacity-90 mt-1">{{ $campaign->short_description ?? '' }}</p>
                </div>
            </div>

            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Target</p>
                        <p class="text-lg font-bold text-slate-900 mt-1">
                            Rp {{ number_format((int)($campaign->target ?? 0), 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Deadline</p>
                        <p class="text-lg font-bold text-slate-900 mt-1">
                            {{ $campaign->deadline ? \Carbon\Carbon::parse($campaign->deadline)->format('d M Y') : 'Tanpa batas' }}
                        </p>
                    </div>
                </div>

                <div>
                    <p class="text-sm font-bold text-slate-800 mb-2">Deskripsi</p>
                    <div class="prose prose-sm max-w-none text-slate-700">
                        {{ $campaign->description ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
