@extends('layouts.dashboard')
@section('title', 'Pencairan Dana')
@section('page_title', 'Pencairan Dana')

@section('content')
<div class="px-4 md:px-8 py-6 space-y-10">


    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900">
            Pencairan Dana
        </h1>
        <p class="mt-2 text-slate-600">
            Ajukan pencairan dana dari campaign yang sedang berjalan.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="text-sm text-slate-500">Total Dana Terkumpul</div>
        <div class="mt-1 text-2xl font-bold text-slate-900">
            Rp {{ number_format($totalRaisedAll ?? 0, 0, ',', '.') }}
        </div>
        <div class="mt-1 text-xs text-slate-500">Dari seluruh campaign kamu</div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="text-sm text-slate-500">Total Campaign</div>
        <div class="mt-1 text-2xl font-bold text-slate-900">
            {{ $totalCampaigns ?? 0 }}
        </div>
        <div class="mt-1 text-xs text-slate-500">Campaign yang kamu miliki</div>
    </div>
</div>

    {{-- FORM AJUKAN PENCAIRAN --}}
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">
            Ajukan Pencairan Dana
        </h2>

        @if($programs->isEmpty())
            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-6 text-slate-600 text-sm">
                Kamu belum memiliki campaign yang bisa dicairkan.
            </div>
        @else
            <form method="POST"
                  action="{{ route('dashboard.disbursements.request', $programs->first()->id) }}"
                  class="space-y-5">
                @csrf

                {{-- Campaign --}}
                <div>
                    <label class="text-sm font-medium text-slate-700 mb-2 block">
                        Pilih Campaign
                    </label>
                    <select name="program_id"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-emerald-200">
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}">
                                {{ $program->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Amount --}}
                <div>
                    <label class="text-sm font-medium text-slate-700 mb-2 block">
                        Jumlah Pencairan (Rp)
                    </label>
                    <input type="number" name="amount" min="1000" required
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-emerald-200">
                </div>

                {{-- Rekening --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium text-slate-700 mb-2 block">
                            Nama Bank
                        </label>
                        <input type="text" name="bank_name"
                            value="{{ old('bank_name', $kyc->bank_name ?? '') }}"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700 mb-2 block">
                            Nomor Rekening
                        </label>
                        <input type="text" name="account_number"
                            value="{{ old('account_number', $kyc->account_number ?? '') }}"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700 mb-2 block">
                            Atas Nama
                        </label>
                        <input type="text" name="account_name"
                            value="{{ old('account_name', $kyc->account_name ?? '') }}"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    </div>
                </div>

                {{-- Note --}}
                <div>
                    <label class="text-sm font-medium text-slate-700 mb-2 block">
                        Catatan (opsional)
                    </label>
                    <textarea name="note" rows="3"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3"></textarea>
                </div>

                <button type="submit"
                    class="inline-flex justify-center rounded-full bg-emerald-600 px-6 py-3
                           text-sm font-semibold text-white hover:bg-emerald-700 transition">
                    Ajukan Pencairan
                </button>
            </form>
        @endif
    </div>

    {{-- RIWAYAT PENCAIRAN --}}
    <div class="space-y-4">
        <h2 class="text-lg font-semibold text-slate-900">
            Riwayat Pencairan Dana
        </h2>

        @if($disbursements->isEmpty())
            <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600">
                Belum ada riwayat pencairan.
            </div>
        @else
            <div class="overflow-x-auto rounded-3xl border border-slate-200 bg-white">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Campaign</th>
                            <th class="px-4 py-3 text-right">Jumlah</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($disbursements as $d)
                            <tr class="border-t">
                                <td class="px-4 py-3">
                                    {{ $d->created_at->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $d->program->title ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    Rp {{ number_format($d->amount,0,',','.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                        @if($d->status === 'requested') bg-amber-100 text-amber-700
                                        @elseif($d->status === 'approved') bg-emerald-100 text-emerald-700
                                        @elseif($d->status === 'paid') bg-emerald-100 text-emerald-700
                                        @elseif($d->status === 'rejected') bg-red-100 text-red-700
                                        @endif">
                                        {{ ucfirst($d->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $disbursements->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
