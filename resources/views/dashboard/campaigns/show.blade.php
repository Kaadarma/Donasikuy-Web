@extends('layouts.dashboard')
@section('title', 'Detail Campaign')
@section('page_title', 'Campaign')

@section('content')
@php
    $status = $program->status ?? 'draft';

    $badge = match ($status) {
        'draft'     => ['bg'=>'bg-slate-100','text'=>'text-slate-700','label'=>'Draft'],
        'pending'   => ['bg'=>'bg-amber-100','text'=>'text-amber-700','label'=>'Menunggu Review'],
        'rejected'  => ['bg'=>'bg-red-100','text'=>'text-red-700','label'=>'Ditolak'],
        'approved'  => ['bg'=>'bg-emerald-100','text'=>'text-emerald-700','label'=>'Disetujui'],
        'running'   => ['bg'=>'bg-emerald-100','text'=>'text-emerald-700','label'=>'Sedang Berjalan'],
        'completed' => ['bg'=>'bg-slate-100','text'=>'text-slate-700','label'=>'Selesai'],
        'expired'   => ['bg'=>'bg-slate-100','text'=>'text-slate-700','label'=>'Berakhir'],
        default     => ['bg'=>'bg-slate-100','text'=>'text-slate-700','label'=>ucfirst($status)],
    };

    $imageUrl = $program->image
        ? asset('storage/' . $program->image)
        : asset('images/placeholder-campaign.jpg');

    $deadlineText = $program->deadline
        ? \Carbon\Carbon::parse($program->deadline)->translatedFormat('d M Y')
        : '—';

    $categoryLabel = $program->category
        ? ucfirst(str_replace('-', ' ', $program->category))
        : 'Tanpa Kategori';

    $target = (int) ($program->target ?? 0);
    $raised = (int) ($program->raised_sum ?? 0); // from withSum alias
    $progress = ($target > 0) ? (int) min(100, round(($raised / max(1, $target)) * 100)) : 0;

    $canEdit = in_array($status, ['draft','rejected']);
@endphp

<div class="px-4 md:px-8 py-6 space-y-6">

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Detail Campaign</h1>
            <p class="mt-2 text-slate-600">
                Informasi campaign kamu berdasarkan status saat ini.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('dashboard.campaigns.index') }}"
               class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                ← Kembali   
            </a>

        </div>
    </div>

    {{-- Card utama --}}
    <div class="rounded-3xl border border-slate-200 bg-white overflow-hidden shadow-sm">
        <div class="relative h-56 bg-slate-100">
            <img src="{{ $imageUrl }}" class="h-full w-full object-cover" alt="Campaign image">

            <div class="absolute inset-x-0 top-0 p-4 flex items-center justify-between gap-3">
                <div class="text-sm font-semibold text-emerald-700 bg-white/90 backdrop-blur px-3 py-1.5 rounded-full">
                    {{ $categoryLabel }}
                </div>

                <div class="text-xs font-semibold {{ $badge['bg'] }} {{ $badge['text'] }} px-3 py-1.5 rounded-full">
                    {{ $badge['label'] }}
                </div>
            </div>
        </div>

        <div class="p-6 space-y-5">
            <div>
                <h2 class="text-xl font-bold text-slate-900">{{ $program->title }}</h2>
                @if(!empty($program->short_description))
                    <p class="mt-2 text-slate-600">{{ $program->short_description }}</p>
                @endif
            </div>

            {{-- Status note khusus rejected (kalau ada kolom note/admin_note) --}}
            @php
                $rejectNote = $program->note ?? $program->admin_note ?? null;
            @endphp

            @if($status === 'rejected')
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                    <div class="font-semibold text-red-700">Alasan Penolakan</div>
                    <div class="mt-1 text-sm text-red-700/90">
                        {{ $rejectNote ?: 'Belum ada catatan penolakan dari admin.' }}
                    </div>
                    <div class="mt-3 text-xs text-red-700/80">
                        Kamu bisa edit campaign lalu ajukan ulang.
                    </div>
                </div>
            @endif

            {{-- Info --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-2xl border border-slate-200 p-4">
                    <div class="text-xs text-slate-500">Dana Terkumpul</div>
                    <div class="mt-1 text-lg font-bold text-slate-900">
                        Rp {{ number_format($raised, 0, ',', '.') }}
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 p-4">
                    <div class="text-xs text-slate-500">Target</div>
                    <div class="mt-1 text-lg font-bold text-slate-900">
                        {{ $target > 0 ? 'Rp ' . number_format($target, 0, ',', '.') : 'Unlimited' }}
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 p-4">
                    <div class="text-xs text-slate-500">Deadline</div>
                    <div class="mt-1 text-lg font-bold text-slate-900">{{ $deadlineText }}</div>
                </div>
            </div>

            {{-- Progress --}}
            <div>
                <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                    <span>
                        Rp {{ number_format($raised, 0, ',', '.') }}
                        dari
                        {{ $target > 0 ? 'Rp ' . number_format($target, 0, ',', '.') : 'Unlimited' }}
                    </span>
                    @if($target > 0)
                        <span class="font-semibold text-emerald-700">{{ $progress }}%</span>
                    @endif
                </div>

                @php $barWidth = $target > 0 ? $progress : 25; @endphp
                <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full bg-emerald-600" style="width: {{ $barWidth }}%"></div>
                </div>
            </div>

            {{-- Actions bawah --}}
{{-- Actions bawah --}}
<div class="pt-2 flex flex-col sm:flex-row gap-3">

    {{-- DRAFT: boleh ajukan --}}
    @if($status === 'draft')
        <form method="POST"
              action="{{ route('dashboard.campaigns.submit', $program->id) }}"
              class="w-full sm:w-auto">
            @csrf
            <button type="submit"
                onclick="return confirm('Ajukan campaign ini ke admin untuk direview?')"
                class="w-full inline-flex justify-center rounded-full bg-emerald-600 px-6 py-3
                       text-sm font-semibold text-white
                       hover:bg-emerald-700 active:bg-emerald-800 transition">
                Ajukan ke Admin
            </button>
        </form>
    @endif

    {{-- REJECTED: TIDAK ADA ACTION --}}
    @if($status === 'rejected')
        {{-- sengaja kosong --}}
    @endif

    {{-- RUNNING / APPROVED --}}
    @if(in_array($status, ['approved','running']))
        <a href="{{ route('dashboard.campaigns.manage', $program->id) }}"
           class="w-full sm:w-auto inline-flex justify-center rounded-full border border-slate-200 bg-white px-6 py-3
                  text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
            Kelola Campaign
        </a>

        <a href="{{ route('programs.show', $program->slug) }}"
           class="w-full sm:w-auto inline-flex justify-center rounded-full bg-emerald-600 px-6 py-3
                  text-sm font-semibold text-white
                  hover:bg-emerald-700 active:bg-emerald-800 transition">
            Lihat Halaman Publik
        </a>
    @endif




                @if(in_array($status, ['approved','running']))
                    <a href="{{ route('dashboard.campaigns.manage', $program->id) }}"
                       class="w-full sm:w-auto inline-flex justify-center rounded-full border border-slate-200 bg-white px-6 py-3
                              text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Kelola Campaign
                    </a>

                    <a href="{{ route('programs.show', $program->slug) }}"
                       class="w-full sm:w-auto inline-flex justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white
                              hover:bg-emerald-700 active:bg-emerald-800 transition">
                        Lihat Halaman Publik
                    </a>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection
