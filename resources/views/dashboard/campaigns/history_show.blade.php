@extends('layouts.dashboard')
@section('title', 'Rincian Campaign')
@section('page_title', 'Campaign')

@section('content')
<div class="px-4 md:px-8 py-6 space-y-6">




    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Rincian Campaign</h1>
            <p class="mt-2 text-slate-600">{{ $program->title }}</p>
        </div>

        <a href="{{ route('dashboard.campaigns.completed') }}"
           class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            ← Kembali ke Riwayat
        </a>
    </div>

    {{-- Quick stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">Dana Terkumpul</div>
            <div class="mt-2 text-2xl font-bold text-slate-900">
                Rp {{ number_format((int)$totalRaised, 0, ',', '.') }}
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">Total Donasi</div>
            <div class="mt-2 text-2xl font-bold text-slate-900">
                {{ $donations->total() }}
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">Total Pencairan</div>
            <div class="mt-2 text-2xl font-bold text-slate-900">
                {{ $disbursements->total() }}
            </div>
        </div>
    </div>

    {{-- Riwayat Donasi --}}
    <div class="rounded-3xl border border-slate-200 bg-white p-6">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900">Riwayat Donasi</h2>
            <span class="text-xs text-slate-500">Total: {{ $donations->total() }}</span>
        </div>

        @if($donations->count() === 0)
            <div class="mt-4 text-sm text-slate-600">Belum ada donasi.</div>
        @else
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500">
                            <th class="py-2 pr-4">Tanggal</th>
                            <th class="py-2 pr-4">Donatur</th>
                            <th class="py-2 pr-4">Nominal</th>
                            <th class="py-2 pr-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($donations as $d)
                            <tr>
                                <td class="py-3 pr-4 text-slate-700 whitespace-nowrap">
                                    {{ optional($d->created_at)->translatedFormat('d M Y, H:i') ?? '—' }}
                                </td>
                                <td class="py-3 pr-4 text-slate-700">
                                    {{ $d->user->name ?? 'Anonim' }}
                                </td>
                                <td class="py-3 pr-4 font-semibold text-slate-900 whitespace-nowrap">
                                    Rp {{ number_format((int)($d->amount ?? 0), 0, ',', '.') }}
                                </td>
                                <td class="py-3 pr-4 text-slate-700">
                                    {{ $d->status ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4">
                {{ $donations->links() }}
            </div>
        @endif
    </div>

    {{-- Riwayat Pencairan --}}
    <div class="rounded-3xl border border-slate-200 bg-white p-6">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900">Riwayat Pencairan</h2>
            <span class="text-xs text-slate-500">Total: {{ $disbursements->total() }}</span>
        </div>

        @if($disbursements->count() === 0)
            <div class="mt-4 text-sm text-slate-600">Belum ada pengajuan pencairan.</div>
        @else
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500">
                            <th class="py-2 pr-4">Tanggal Ajukan</th>
                            <th class="py-2 pr-4">Nominal</th>
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2 pr-4">Dibayar</th>
                            <th class="py-2 pr-4">Bank</th>
                            <th class="py-2 pr-4">Atas Nama</th>
                            <th class="py-2 pr-4">No. Rek</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($disbursements as $x)
                            <tr>
                                <td class="py-3 pr-4 text-slate-700 whitespace-nowrap">
                                    {{ optional($x->created_at)->translatedFormat('d M Y') ?? '—' }}
                                </td>
                                <td class="py-3 pr-4 font-semibold text-slate-900 whitespace-nowrap">
                                    Rp {{ number_format((int)($x->amount ?? 0), 0, ',', '.') }}
                                </td>
                                <td class="py-3 pr-4 text-slate-700">
                                    {{ $x->status ?? '—' }}
                                </td>
                                <td class="py-3 pr-4 text-slate-700 whitespace-nowrap">
                                    {{ $x->paid_at ? \Carbon\Carbon::parse($x->paid_at)->translatedFormat('d M Y') : '—' }}
                                </td>
                                <td class="py-3 pr-4 text-slate-700">{{ $x->bank_name ?? '—' }}</td>
                                <td class="py-3 pr-4 text-slate-700">{{ $x->account_name ?? '—' }}</td>
                                <td class="py-3 pr-4 text-slate-700">{{ $x->account_number ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4">
                {{ $disbursements->links() }}
            </div>
        @endif
    </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-slate-500">Deadline saat ini</div>
                <div class="mt-1 font-semibold text-slate-900">
                    {{ $program->deadline ? \Carbon\Carbon::parse($program->deadline)->translatedFormat('d M Y') : '—' }}
                </div>
                <div class="mt-2 text-sm text-slate-600">
                    Kamu bisa perpanjang deadline. Setelah disimpan, status campaign akan menjadi <b>Menunggu Review</b>.
                </div>
            </div>

            <form method="POST" action="{{ route('dashboard.campaigns.history.extend', $program->id) }}" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="date" name="deadline"
                    class="rounded-2xl border border-slate-200 px-4 py-2.5 text-sm"
                    required>

                <button type="submit"
                        onclick="return confirm('Perpanjang deadline dan ajukan ulang ke admin?')"
                        class="rounded-full bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                    Perpanjang Deadline
                </button>
            </form>
        </div>

        @error('deadline')
            <div class="mt-3 text-sm text-red-600">{{ $message }}</div>
        @enderror
    </div>

</div>
@endsection
