@extends('layouts.admin-dashboard') {{-- sesuaikan dengan layout admin kamu --}}

@section('title', 'Verifikasi KYC')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900">Verifikasi KYC</h1>
        <p class="mt-1 text-slate-600">Daftar pengajuan verifikasi identitas pengguna</p>
    </div>

    {{-- Tabs (filter status) --}}
    @php
        $active = request('status', 'pending');
        $tabs = [
            'pending'  => 'Menunggu Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'all'      => 'Semua',
        ];

        $badgeClass = fn($status) => match($status) {
            'approved' => 'bg-emerald-100 text-emerald-700',
            'pending'  => 'bg-amber-100 text-amber-700',
            'rejected' => 'bg-rose-100 text-rose-700',
            default    => 'bg-slate-100 text-slate-700',
        };
    @endphp

    <div class="flex flex-wrap gap-3 mb-6">
        @foreach($tabs as $key => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $key === 'all' ? null : $key, 'page' => null]) }}"
               class="px-5 py-2 rounded-full text-sm font-semibold border transition
                    {{ $active === $key ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Card wrapper --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

        {{-- Toolbar: total + search --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 px-6 py-4 border-b bg-slate-50">
            <div class="text-sm font-semibold text-slate-700">
                Total: {{ $kycs->total() ?? (method_exists($kycs,'count') ? $kycs->count() : 0) }}
            </div>

            <form method="GET" class="flex items-center gap-2">
                {{-- keep status when searching --}}
                @if(request()->has('status') && request('status') !== 'all')
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Cari nama / email..."
                       class="w-full md:w-72 px-4 py-2 rounded-xl border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 outline-none">
                <button class="px-5 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                    Cari
                </button>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white text-slate-600 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Nama</th>
                        <th class="px-6 py-4 text-left font-semibold">Email</th>
                        <th class="px-6 py-4 text-center font-semibold">Status</th>
                        <th class="px-6 py-4 text-center font-semibold">Tanggal</th>
                        <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($kycs as $kyc)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">
                                    {{ $kyc->full_name ?? $kyc->user->name ?? '-' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-slate-700">
                                {{ $kyc->user->email ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass($kyc->status) }}">
                                    {{ strtoupper($kyc->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center text-slate-600">
                                {{ optional($kyc->created_at)->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.kyc.show', $kyc->id) }}"
                                   class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-semibold hover:bg-emerald-100 transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-slate-500">
                                Belum ada pengajuan KYC pada status ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(method_exists($kycs, 'links'))
            <div class="px-6 py-4 border-t bg-white">
                {{ $kycs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
