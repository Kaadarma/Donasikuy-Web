@extends('layouts.dashboard')
@section('title', 'Campaign Ditolak')
@section('page_title', 'Campaign')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Ditolak</h1>
        <p class="mt-2 text-slate-600">Campaign yang ditolak admin. Kamu bisa perbaiki dan ajukan ulang.</p>
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
