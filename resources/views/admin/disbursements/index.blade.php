@extends('layouts.admin-dashboard')
@section('title', 'Pencairan Dana')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Pencairan Dana</h1>
        <p class="text-sm text-slate-500">Daftar permintaan pencairan dana dari campaign.</p>
    </div>

    @php
        $active = request('status', 'requested');
        $tabs = [
            'requested' => 'Menunggu',
            'approved'  => 'Disetujui',
            'rejected'  => 'Ditolak',
            'paid'      => 'Sudah Dibayar',
        ];
    @endphp

    <div class="flex flex-wrap gap-2 mb-5">
        @foreach($tabs as $key => $label)
            <a href="{{ route('admin.disbursements.index', ['status' => $key, 'q' => request('q')]) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold border
               {{ $active === $key ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <p class="text-sm font-semibold text-slate-700">
                Total: <span class="text-slate-900">{{ $disbursements->total() ?? 0 }}</span>
            </p>

            <form method="GET" class="flex gap-2">
                <input type="hidden" name="status" value="{{ $active }}">
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Cari nama / campaign / bank..."
                    class="w-72 max-w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-emerald-200">
                <button class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800">
                    Cari
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-3 text-left">Campaign</th>
                        <th class="px-5 py-3 text-left">Pemohon</th>
                        <th class="px-5 py-3 text-center">Jumlah</th>
                        <th class="px-5 py-3 text-left">Rekening</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Tanggal</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($disbursements as $d)
                        @php
                            $badge = match($d->status) {
                                'approved'  => 'bg-emerald-100 text-emerald-700',
                                'requested' => 'bg-amber-100 text-amber-700',
                                'rejected'  => 'bg-rose-100 text-rose-700',
                                'paid'      => 'bg-sky-100 text-sky-700',
                                default     => 'bg-slate-100 text-slate-700',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-900">{{ $d->program->title ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $d->program->slug ?? '' }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-medium text-slate-800">{{ $d->user->name ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $d->user->email ?? '-' }}</p>
                            </td>

                            <td class="px-5 py-4 text-center font-semibold text-slate-900">
                                Rp {{ number_format((int)($d->amount ?? 0), 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-medium text-slate-800">{{ $d->bank_name ?? '-' }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $d->account_number ?? '-' }} a/n {{ $d->account_name ?? '-' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                    {{ strtoupper($d->status) }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-center text-slate-600">
                                {{ optional($d->created_at)->format('d M Y') }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('admin.disbursements.show', $d->id) }}"
                                   class="px-3 py-2 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-500">
                                Belum ada permintaan pencairan dana.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-100">
            {{ method_exists($disbursements, 'withQueryString') ? $disbursements->withQueryString()->links() : '' }}
        </div>
    </div>

</div>
@endsection
