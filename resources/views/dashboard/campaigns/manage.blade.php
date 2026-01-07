@extends('layouts.dashboard')
@section('title', 'Kelola Campaign')
@section('page_title', 'Campaign')

@section('content')
@php
    // Amanin variable supaya view gak error walau controller beda-beda
    $updates = $updates ?? ($program->updates ?? collect());
    $disbursements = $disbursements ?? ($program->disbursements ?? collect());
    $items = $items ?? collect();

    // ✅ tambahin ini
    $totalRaised   = $totalRaised   ?? 0;
    $feePercent    = $feePercent    ?? 0;
    $feeAmount     = $feeAmount     ?? 0;
    $disbursedPaid = $disbursedPaid ?? 0;
    $available     = $available     ?? 0;

    $status = $program->status ?? null;
    $publicUrl = route('programs.show', $program->slug);
@endphp

<div class="px-4 md:px-8 py-6 space-y-6" x-data="{ tab: 'updates' }">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Kelola Campaign</h1>
            <p class="mt-2 text-slate-600">
                Tambahkan kabar terbaru, rincian pencairan dana, dan lihat riwayat pencairan.
            </p>
            <div class="mt-3 inline-flex items-center gap-2 text-xs">
                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-semibold">
                    Status: {{ ucfirst($status) }}
                </span>
                <a href="{{ $publicUrl }}" target="_blank"
                   class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold hover:bg-emerald-100 transition">
                    Lihat Halaman Publik ↗
                </a>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('dashboard.campaigns.index') }}"
               class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            <div class="font-semibold">Berhasil</div>
            <div class="text-sm">{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <div class="font-semibold">Ada yang perlu diperbaiki:</div>
            <ul class="list-disc ml-5 mt-1 text-sm space-y-1">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabs --}}
    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="px-5 md:px-6 py-4 border-b border-slate-200 flex flex-wrap gap-2">
            <button type="button"
                @click="tab='updates'"
                :class="tab==='updates' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                class="px-4 py-2 rounded-full text-sm font-semibold transition">
                Kabar Terbaru
            </button>

            <button type="button"
                @click="tab='items'"
                :class="tab==='items' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                class="px-4 py-2 rounded-full text-sm font-semibold transition">
                Rincian Pencairan
            </button>

            <button type="button"
                @click="tab='disbursements'"
                :class="tab==='disbursements' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                class="px-4 py-2 rounded-full text-sm font-semibold transition">
                Riwayat Pencairan
            </button>
        </div>

        {{-- =========================
            TAB: KABAR TERBARU
        ========================= --}}
        <div x-show="tab==='updates'" x-transition class="p-5 md:p-6 space-y-6">

            {{-- Form tambah kabar --}}
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 md:p-6">
                <h2 class="text-lg font-bold text-slate-900">Tambah Kabar Terbaru</h2>
                <p class="mt-1 text-sm text-slate-600">Kabar ini bisa kamu tampilkan juga di halaman publik (nanti bisa disambung).</p>

                <form method="POST"
                      action="{{ route('dashboard.campaigns.updates.store', $program->id) }}"
                      enctype="multipart/form-data"
                      class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Judul</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500"
                               placeholder="Contoh: Update kondisi terbaru / penyaluran tahap 1" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Isi Kabar</label>
                        <textarea name="body" rows="6"
                                  class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500"
                                  placeholder="Tulis kabar terbaru tentang campaign..." required>{{ old('body') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Foto (opsional)</label>
                        <input type="file" name="image" accept="image/*"
                               class="block w-full text-sm text-slate-600">
                        <p class="mt-1 text-xs text-slate-500">jpg/jpeg/png/webp, max 2MB.</p>
                    </div>

                    <button type="submit"
                            class="inline-flex justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white
                                   hover:bg-emerald-700 active:bg-emerald-800 transition">
                        + Tambah Kabar
                    </button>
                </form>
            </div>

            {{-- List kabar --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900">Daftar Kabar</h3>
                    <div class="text-xs text-slate-500">
                        Total: {{ $updates instanceof \Illuminate\Pagination\LengthAwarePaginator ? $updates->total() : $updates->count() }}
                    </div>
                </div>

                @if(($updates instanceof \Illuminate\Pagination\LengthAwarePaginator ? $updates->count() : $updates->count()) === 0)
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600">
                        Belum ada kabar terbaru.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($updates as $u)
                            <div class="rounded-3xl border border-slate-200 bg-white p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="text-sm text-slate-500">
                                            {{ optional($u->created_at)->translatedFormat('d M Y, H:i') ?? '-' }}
                                        </div>
                                        <div class="mt-1 text-lg font-bold text-slate-900">
                                            {{ $u->title }}
                                        </div>
                                    </div>

                                    <form method="POST"
                                          action="{{ route('dashboard.campaigns.updates.destroy', [$program->id, $u->id]) }}"
                                          onsubmit="return confirm('Hapus kabar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                                <div class="mt-3 text-sm text-slate-700 whitespace-pre-line">
                                    {{ $u->body }}
                                </div>

                                @if(!empty($u->image))
                                    <div class="mt-4">
                                        <img src="{{ asset('storage/'.$u->image) }}"
                                             class="w-full max-h-[360px] object-cover rounded-2xl border border-slate-200"
                                             alt="Update image">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination kalau paginator --}}
                    @if($updates instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="pt-3">
                            {{ $updates->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- =========================
            TAB: RINCIAN PENCAIRAN
        ========================= --}}
        <div x-show="tab==='items'" x-transition class="p-5 md:p-6 space-y-6">
            {{-- Rincian Penggunaan Dana --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Rincian Penggunaan Dana</h3>
                    <p class="mt-1 text-sm text-slate-600">
                        Ringkasan dana terkumpul, fee platform, dan dana yang sudah/ belum dicairkan.
                    </p>
                </div>

                <div class="text-right">
                    <div class="text-xs text-slate-500">Dana terkumpul</div>
                    <div class="mt-1 text-lg font-bold text-slate-900">
                        Rp {{ number_format((int)$totalRaised, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="mt-5 rounded-2xl bg-slate-50 border border-slate-200 p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-700">Dana untuk penggalangan dana</div>
                    <div class="font-semibold text-slate-900">
                        Rp {{ number_format((int)$totalRaised, 0, ',', '.') }}
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-700">
                        Biaya transaksi & teknologi
                        <span class="text-xs text-slate-400">({{ $feePercent }}%)</span>
                    </div>
                    <div class="font-semibold text-slate-900">
                        Rp {{ number_format((int)$feeAmount, 0, ',', '.') }}
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-700">Sudah dicairkan</div>
                    <div class="font-semibold text-slate-900">
                        Rp {{ number_format((int)$disbursedPaid, 0, ',', '.') }}
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-3 flex items-center justify-between">
                    <div class="text-sm font-bold text-slate-900">Belum dicairkan</div>
                    <div class="text-lg font-bold text-emerald-700">
                        Rp {{ number_format((int)$available, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            {{-- Rincian penggunaan dana (items) --}}
            <div class="mt-6">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-bold text-slate-900">Rincian penggunaan</h4>
                    <span class="text-xs text-slate-500">Total item: {{ $items->count() }}</span>
                </div>

                @if($items->count() === 0)
                    <div class="mt-3 text-sm text-slate-600">
                        Belum ada rincian penggunaan dana.
                    </div>
                @else
                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-slate-500">
                                    <th class="py-2 pr-4">Tanggal</th>
                                    <th class="py-2 pr-4">Judul</th>
                                    <th class="py-2 pr-4">Nominal</th>
                                    <th class="py-2 pr-4">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($items as $it)
                                    <tr class="align-top">
                                        <td class="py-3 pr-4 text-slate-700 whitespace-nowrap">
                                            {{ optional($it->created_at)->translatedFormat('d M Y') ?? '-' }}
                                        </td>
                                        <td class="py-3 pr-4 font-semibold text-slate-900">
                                            {{ $it->title }}
                                        </td>
                                        <td class="py-3 pr-4 font-semibold text-slate-900 whitespace-nowrap">
                                            Rp {{ number_format((int)$it->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 pr-4 text-slate-700">
                                            {{ $it->note ?? '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>


        {{-- =========================
            TAB: RIWAYAT PENCAIRAN
        ========================= --}}
        <div x-show="tab==='disbursements'" x-transition class="p-5 md:p-6 space-y-6">

            {{-- List riwayat --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900">Riwayat Pencairan</h3>
                    <span class="text-xs text-slate-500">
                        Total: {{ $disbursements instanceof \Illuminate\Pagination\LengthAwarePaginator ? $disbursements->total() : $disbursements->count() }}
                    </span>
                </div>

                @if(($disbursements instanceof \Illuminate\Pagination\LengthAwarePaginator ? $disbursements->count() : $disbursements->count()) === 0)
                    <div class="mt-4 text-sm text-slate-600">
                        Belum ada pengajuan pencairan.
                    </div>
                @else
                   <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-500">
                                <th class="py-2 pr-4">Tanggal Ajukan</th>
                                <th class="py-2 pr-4">Nominal</th>
                                <th class="py-2 pr-4">Status</th>
                                <th class="py-2 pr-4">Dibayar</th>

                                {{-- pecah rekening biar jelas --}}
                                <th class="py-2 pr-4">Nama Bank</th>
                                <th class="py-2 pr-4">Atas Nama</th>
                                <th class="py-2 pr-4">No. Rekening</th>

                                <th class="py-2 pr-4">Catatan</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach($disbursements as $d)
                                @php
                                    $st = $d->status ?? 'requested';
                                    $stBadge = match ($st) {
                                        'requested' => ['bg'=>'bg-amber-100','text'=>'text-amber-700','label'=>'Requested'],
                                        'approved'  => ['bg'=>'bg-emerald-100','text'=>'text-emerald-700','label'=>'Approved'],
                                        'paid'      => ['bg'=>'bg-emerald-100','text'=>'text-emerald-700','label'=>'Paid'],
                                        'rejected'  => ['bg'=>'bg-red-100','text'=>'text-red-700','label'=>'Rejected'],
                                        default     => ['bg'=>'bg-slate-100','text'=>'text-slate-700','label'=>ucfirst($st)],
                                    };

                                    $paidAt = $d->paid_at
                                        ? \Carbon\Carbon::parse($d->paid_at)->translatedFormat('d M Y')
                                        : '—';
                                @endphp

                                <tr class="align-top">
                                    <td class="py-3 pr-4 text-slate-700">
                                        {{ optional($d->created_at)->translatedFormat('d M Y') ?? '—' }}
                                    </td>

                                    <td class="py-3 pr-4 font-semibold text-slate-900 whitespace-nowrap">
                                        Rp {{ number_format((int) $d->amount, 0, ',', '.') }}
                                    </td>

                                    <td class="py-3 pr-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $stBadge['bg'] }} {{ $stBadge['text'] }}">
                                            {{ $stBadge['label'] }}
                                        </span>
                                    </td>

                                    <td class="py-3 pr-4 text-slate-700 whitespace-nowrap">
                                        {{ $paidAt }}
                                    </td>

                                    <td class="py-3 pr-4 text-slate-700">
                                        {{ $d->bank_name ?? '—' }}
                                    </td>

                                    <td class="py-3 pr-4 text-slate-700">
                                        {{ $d->account_name ?? '—' }}
                                    </td>

                                    <td class="py-3 pr-4 text-slate-700">
                                        {{ $d->account_number ?? '—' }}
                                    </td>

                                    <td class="py-3 pr-4 text-slate-700">
                                        {{ $d->note ?? '—' }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                    </table>
                </div>


                    @if($disbursements instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="pt-4">
                            {{ $disbursements->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>

</div>
@endsection
