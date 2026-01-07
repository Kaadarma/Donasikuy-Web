@extends('layouts.dashboard')
@section('title', 'Pencairan Dana')
@section('page_title', 'Pencairan Dana')

@section('content')
<div class="px-4 md:px-8 py-6 space-y-10">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Pencairan Dana</h1>
        <p class="mt-2 text-slate-600">Pilih campaign untuk mengajukan pencairan dana.</p>
    </div>

    {{-- Kartu Kecil Kesayanganku --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">Total Dana Terkumpul Keseluruhan</div>
            <div class="mt-1 text-2xl font-bold text-slate-900">
                Rp {{ number_format($totalRaisedAll ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">Total Campaign</div>
            <div class="mt-1 text-2xl font-bold text-slate-900">
                {{ $totalCampaigns ?? 0 }}
            </div>
        </div>
    </div>


    {{-- Sedang berjalan --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">Campaign Sedang Berjalan</h2>
            <span class="text-sm text-slate-500">{{ $runningPrograms->count() }} campaign</span>
        </div>

        @if($runningPrograms->isEmpty())
            <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600">
                Belum ada campaign yang sedang berjalan.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($runningPrograms as $p)
                    @include('dashboard.disbursements.partials.card', [
                        'p' => $p,
                        'kyc' => $kyc ?? null,
                        // ✅ arahkan klik card ke form pencairan program ini
                        'overrideCardUrl' => route('dashboard.disbursements.create', $p->id),
                        // ✅ label CTA khusus pencairan (opsional)
                        'overridePrimaryCta' => [
                            'label' => 'Ajukan Pencairan',
                            'url'   => route('dashboard.disbursements.create', $p->id),
                        ],
                    ])
                @endforeach
            </div>
        @endif
    </div>

    {{-- Selesai --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">Campaign Selesai</h2>
            <span class="text-sm text-slate-500">{{ $completedPrograms->count() }} campaign</span>
        </div>

        @if($completedPrograms->isEmpty())
            <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600">
                Belum ada campaign yang selesai.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($completedPrograms as $p)
                    @include('dashboard.disbursements.partials.card', [
                        'p' => $p,
                        'kyc' => $kyc ?? null,
                        'overrideCardUrl' => route('dashboard.disbursements.create', $p->id),
                        'overridePrimaryCta' => [
                            'label' => 'Ajukan Pencairan',
                            'url'   => route('dashboard.disbursements.create', $p->id),
                        ],
                    ])
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
