@extends('layouts.dashboard')
@section('title', 'Riwayat Donasi')
@section('page_title', 'Riwayat Donasi')

@section('content')
<div class="px-4 md:px-8 py-6 space-y-6">

    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Riwayat Donasi</h1>
        <p class="mt-2 text-slate-600">Daftar donasi yang kamu lakukan.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">Total Donasi Sukses</div>
            <div class="mt-2 text-2xl font-bold text-slate-900">
                Rp {{ number_format((int)$totalDonasi, 0, ',', '.') }}
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">Total Transaksi</div>
            <div class="mt-2 text-2xl font-bold text-slate-900">
                {{ $donations->total() }}
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-base font-bold text-slate-900">Daftar Donasi</h2>
        </div>

        @if($donations->count() === 0)
            <div class="p-8 text-center text-slate-600">
                Kamu belum punya riwayat donasi.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b border-slate-100">
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Campaign</th>
                            <th class="px-6 py-3">Nominal</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach($donations as $d)
                            @php
                                $st = $d->status ?? '-';
                                $badge = match ($st) {
                                    'success','settlement','capture','paid' => ['bg'=>'bg-emerald-100','text'=>'text-emerald-700','label'=>'Sukses'],
                                    'pending' => ['bg'=>'bg-amber-100','text'=>'text-amber-700','label'=>'Pending'],
                                    'failed','expire','cancel' => ['bg'=>'bg-red-100','text'=>'text-red-700','label'=>ucfirst($st)],
                                    default => ['bg'=>'bg-slate-100','text'=>'text-slate-700','label'=>ucfirst($st)],
                                };

                                $program = $d->program;
                            @endphp

                            <tr>
                                <td class="px-6 py-4 text-slate-700 whitespace-nowrap">
                                    {{ optional($d->created_at)->translatedFormat('d M Y, H:i') ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-slate-900">
                                    {{ $program->title ?? '—' }}
                                </td>

                                <td class="px-6 py-4 font-semibold text-slate-900 whitespace-nowrap">
                                    Rp {{ number_format((int)($d->amount ?? 0), 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge['bg'] }} {{ $badge['text'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    @if($program && !empty($program->slug))
                                        <a href="{{ route('programs.show', $program->slug) }}"
                                           class="text-sm font-semibold text-emerald-700 hover:underline">
                                            Lihat Campaign →
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {{ $donations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
