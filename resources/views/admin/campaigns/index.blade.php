@extends('layouts.admin-dashboard') {{-- sesuaikan dengan layout admin kamu --}}
@section('title', 'Verifikasi Campaign')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Verifikasi Campaign</h1>
            <p class="text-sm text-slate-500">Daftar pengajuan galang dana yang perlu dicek oleh admin.</p>
        </div>
    </div>

    {{-- Tabs status --}}
    @php
        $active = request('status', 'pending');
        $tabs = [
            'pending'  => ['label' => 'Menunggu Review'],
            'approved' => ['label' => 'Disetujui'],
            'rejected' => ['label' => 'Ditolak'],
        ];
    @endphp

    <div class="flex flex-wrap gap-2 mb-5">
        @foreach($tabs as $key => $tab)
            <a href="{{ route('admin.campaigns.index', ['status' => $key]) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold border
                      {{ $active === $key ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <p class="text-sm font-semibold text-slate-700">
                Total: <span class="text-slate-900">{{ $campaigns->total() }}</span>
            </p>

            {{-- Search --}}
            <form method="GET" class="flex gap-2">
                <input type="hidden" name="status" value="{{ $active }}">
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Cari judul / email user..."
                       class="w-64 max-w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-emerald-200">
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
                        <th class="px-5 py-3 text-left">Pemilik</th>
                        <th class="px-5 py-3 text-center">Target</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Tanggal</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($campaigns as $c)
                        @php
                            $badge = match($c->status) {
                                'approved' => 'bg-emerald-100 text-emerald-700',
                                'pending'  => 'bg-amber-100 text-amber-700',
                                'rejected' => 'bg-rose-100 text-rose-700',
                                'draft'    => 'bg-slate-100 text-slate-700',
                                default    => 'bg-slate-100 text-slate-700',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-200 shrink-0">
                                        <img
                                            src="{{ $c->image ? asset('storage/'.$c->image) : 'https://source.unsplash.com/200x200/?charity' }}"
                                            class="w-full h-full object-cover" alt="">
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 line-clamp-1">{{ $c->title }}</p>
                                        <p class="text-xs text-slate-500 line-clamp-1">{{ $c->short_description ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-medium text-slate-800">{{ $c->user->name ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $c->user->email ?? '-' }}</p>
                            </td>

                            <td class="px-5 py-4 text-center font-semibold text-slate-800">
                                Rp {{ number_format((int)($c->target ?? 0), 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                    {{ strtoupper($c->status) }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-center text-slate-600">
                                {{ optional($c->created_at)->format('d M Y') }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.campaigns.show', $c->id) }}"
                                       class="px-3 py-2 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100">
                                        Detail
                                    </a>

                                    @if($c->status === 'pending')
                                        <form method="POST" action="{{ route('admin.campaigns.approve', $c->id) }}">
                                            @csrf
                                            <button class="px-3 py-2 rounded-xl text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.campaigns.reject', $c->id) }}">
                                            @csrf
                                            <button class="px-3 py-2 rounded-xl text-xs font-semibold bg-rose-600 text-white hover:bg-rose-700">
                                                Reject
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-500">
                                Belum ada campaign pada status ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-100">
            {{ $campaigns->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection
