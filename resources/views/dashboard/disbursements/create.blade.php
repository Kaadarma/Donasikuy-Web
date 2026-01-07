@extends('layouts.dashboard')
@section('title', 'Ajukan Pencairan')
@section('page_title', 'Pencairan Dana')

@section('content')
<div class="px-4 md:px-8 py-6 space-y-10">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Ajukan Pencairan</h1>
            <p class="mt-2 text-slate-600">
                Campaign: <span class="font-semibold text-slate-900">{{ $program->title }}</span>
            </p>
        </div>

        <a href="{{ route('dashboard.disbursements.index') }}"
           class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2.5
                  text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
            ← Kembali
        </a>
    </div>

    {{-- Info ringkas program --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">Dana Terkumpul</div>
            <div class="mt-1 text-2xl font-bold text-slate-900">
                Rp {{ number_format($totalRaised ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">Menunggu Pencairan</div>
            <div class="mt-1 text-2xl font-bold text-slate-900">
                Rp {{ number_format($totalPendingDisbursement ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">Sudah Dicairkan</div>
            <div class="mt-1 text-2xl font-bold text-slate-900">
                Rp {{ number_format($totalDisbursed ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">Dana Tersedia</div>
            <div class="mt-1 text-2xl font-bold text-slate-900">
                Rp {{ number_format($available ?? 0, 0, ',', '.') }}
            </div>
        </div>
    </div>


    {{-- Rekening KYC (READ ONLY) --}}
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Rekening Terverifikasi (KYC)</h2>

        @if(!$kyc)
            <div class="rounded-2xl bg-amber-50 border border-amber-200 p-5 text-amber-800 text-sm">
                Kamu belum mengisi data KYC. Selesaikan verifikasi dulu untuk bisa mencairkan dana.
            </div>
        @elseif($kyc->status !== 'approved')
            <div class="rounded-2xl bg-amber-50 border border-amber-200 p-5 text-amber-800 text-sm">
                Status KYC kamu: <span class="font-semibold">{{ ucfirst($kyc->status) }}</span>.
                Pencairan hanya bisa dilakukan setelah KYC disetujui.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <div class="text-xs font-medium text-slate-500">Nama Bank</div>
                    <div class="mt-1 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800">
                        {{ $kyc->bank_name ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium text-slate-500">Nomor Rekening</div>
                    <div class="mt-1 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800">
                        {{ $kyc->account_number ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium text-slate-500">Atas Nama</div>
                    <div class="mt-1 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800">
                        {{ $kyc->account_name ?? '—' }}
                    </div>
                </div>
            </div>

            @if(!empty($kyc->book_photo_path))
                <div class="mt-4">
                    <div class="text-xs font-medium text-slate-500 mb-2">Buku Tabungan</div>
                    <a href="{{ asset('storage/'.$kyc->book_photo_path) }}" target="_blank"
                       class="inline-flex items-center text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                        Lihat Foto Buku Tabungan →
                    </a>
                </div>
            @endif
        @endif
    </div>

    {{-- FORM AJUKAN PENCAIRAN --}}
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Ajukan Pencairan Dana</h2>

        @if(!$kyc || $kyc->status !== 'approved')
            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-6 text-slate-600 text-sm">
                Lengkapi dan tunggu persetujuan KYC agar kamu bisa mengajukan pencairan.
            </div>
        @else
            <form method="POST" action="{{ route('dashboard.disbursements.request', $program->id) }}" class="space-y-5">
                @csrf

                {{-- Amount --}}
                <div>
                    <label class="text-sm font-medium text-slate-700 mb-2 block">
                        Jumlah Pencairan (Rp)
                    </label>
                    <input type="number" name="amount" min="1000" required value="{{ old('amount') }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3
                                  focus:outline-none focus:ring-2 focus:ring-emerald-200">
                    @error('amount')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Note --}}
                <div>
                    <label class="text-sm font-medium text-slate-700 mb-2 block">
                        Catatan <span class="text-red-500">*</span>
                    </label>

                <textarea
                    name="note"
                    rows="3"
                    required
                    placeholder="Tuliskan rencana penggunaan dana (misalnya: operasional, produksi, distribusi, dll.)"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3
                        focus:outline-none focus:ring-2 focus:ring-emerald-200"
                >{{ old('note') }}</textarea>

                    @error('note')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <button type="submit"
                        class="inline-flex justify-center rounded-full bg-emerald-600 px-6 py-3
                               text-sm font-semibold text-white hover:bg-emerald-700 transition">
                    Ajukan Pencairan
                </button>
            </form>
        @endif
    </div>

    {{-- RIWAYAT PENCAIRAN (VERSI LEBIH LENGKAP) --}}
    <div class="space-y-4">
        <h2 class="text-lg font-semibold text-slate-900">Riwayat Pencairan Dana</h2>

        @if($disbursements->isEmpty())
            <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600">
                Belum ada riwayat pencairan untuk campaign ini.
            </div>
        @else
            <div class="space-y-4">
                @foreach($disbursements as $d)
                    @php
                        $badge = match($d->status) {
                            'requested' => ['bg'=>'bg-amber-100','tx'=>'text-amber-700','lb'=>'Menunggu Admin'],
                            'approved'  => ['bg'=>'bg-emerald-100','tx'=>'text-emerald-700','lb'=>'Disetujui'],
                            'paid'      => ['bg'=>'bg-emerald-100','tx'=>'text-emerald-700','lb'=>'Sudah Dibayar'],
                            'rejected'  => ['bg'=>'bg-red-100','tx'=>'text-red-700','lb'=>'Ditolak'],
                            default     => ['bg'=>'bg-slate-100','tx'=>'text-slate-700','lb'=>ucfirst($d->status)],
                        };
                    @endphp

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badge['bg'] }} {{ $badge['tx'] }}">
                                        {{ $badge['lb'] }}
                                    </span>

                                    <div class="text-xs text-slate-500">
                                        Diajukan: {{ $d->created_at->translatedFormat('d M Y, H:i') }}
                                    </div>

                                    @if($d->paid_at)
                                        <div class="text-xs text-slate-500">
                                            Dibayar: {{ \Carbon\Carbon::parse($d->paid_at)->translatedFormat('d M Y') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-3 text-2xl font-bold text-slate-900">
                                    Rp {{ number_format($d->amount,0,',','.') }}
                                </div>

                                @if(!empty($d->note))
                                    <p class="mt-2 text-sm text-slate-600">{{ $d->note }}</p>
                                @endif
                            </div>

                            {{-- Snapshot rekening (yang dipakai untuk request ini) --}}
                            <div class="w-full md:w-[340px] rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs font-semibold text-slate-700 mb-2">Rekening Tujuan (Snapshot)</div>
                                <div class="text-sm text-slate-700">
                                    <div><span class="text-slate-500">Bank:</span> {{ $d->bank_name ?? '—' }}</div>
                                    <div><span class="text-slate-500">No:</span> {{ $d->account_number ?? '—' }}</div>
                                    <div><span class="text-slate-500">A/N:</span> {{ $d->account_name ?? '—' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Items penggunaan dana --}}
                        @if($d->relationLoaded('items') ? $d->items->isNotEmpty() : $d->items()->exists())
                            @php
                                $items = $d->relationLoaded('items') ? $d->items : $d->items()->get();
                                $itemsTotal = (int) $items->sum('amount');
                            @endphp

                            <div class="mt-5">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-sm font-semibold text-slate-900">Rincian Penggunaan Dana</div>
                                    <div class="text-xs text-slate-500">
                                        Total rincian: <span class="font-semibold text-slate-700">Rp {{ number_format($itemsTotal,0,',','.') }}</span>
                                    </div>
                                </div>

                                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                                    <table class="min-w-full text-sm">
                                        <thead class="bg-slate-50 text-slate-600">
                                            <tr>
                                                <th class="px-4 py-3 text-left">Item</th>
                                                <th class="px-4 py-3 text-right">Nominal</th>
                                                <th class="px-4 py-3 text-left">Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($items as $it)
                                                <tr class="border-t">
                                                    <td class="px-4 py-3 font-medium text-slate-900">{{ $it->title }}</td>
                                                    <td class="px-4 py-3 text-right">Rp {{ number_format($it->amount,0,',','.') }}</td>
                                                    <td class="px-4 py-3 text-slate-600">{{ $it->note ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $disbursements->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
