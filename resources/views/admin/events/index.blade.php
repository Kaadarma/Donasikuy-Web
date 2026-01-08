@extends('layouts.admin-dashboard')
@section('title', 'Verifikasi Event')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Verifikasi Event</h1>
        <p class="text-sm text-slate-500">Daftar pengajuan event yang perlu dicek oleh admin.</p>
    </div>

    @php
        $active = request('status', 'pending');
        $tabs = [
            'pending'  => 'Menunggu Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];
    @endphp

    <div class="flex flex-wrap gap-2 mb-5">
        @foreach($tabs as $key => $label)
            <a href="{{ route('admin.events.index', ['status' => $key, 'q' => request('q')]) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold border
               {{ $active === $key ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <p class="text-sm font-semibold text-slate-700">
                Total: <span class="text-slate-900">{{ $events->total() ?? 0 }}</span>
            </p>

            <form method="GET" class="flex gap-2">
                <input type="hidden" name="status" value="{{ $active }}">
                <input type="text" name="q" value="{{ request('q') }}"
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
                        <th class="px-5 py-3 text-left">Event</th>
                        <th class="px-5 py-3 text-left">Pemilik</th>
                        <th class="px-5 py-3 text-center">Target</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Tanggal</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($events as $e)
                        @php
                            $badge = match($e->status) {
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
                                        <img src="{{ $e->image ? asset('storage/'.$e->image) : 'https://source.unsplash.com/200x200/?event' }}"
                                            class="w-full h-full object-cover" alt="">
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 line-clamp-1">{{ $e->title }}</p>
                                        <p class="text-xs text-slate-500 line-clamp-1">{{ $e->short_description ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-medium text-slate-800">{{ $e->user->name ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $e->user->email ?? '-' }}</p>
                            </td>

                            <td class="px-5 py-4 text-center font-semibold text-slate-800">
                                Rp {{ number_format((int)($e->target ?? 0), 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                    {{ strtoupper($e->status) }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-center text-slate-600">
                                {{ optional($e->created_at)->format('d M Y') }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('admin.events.show', $e->id) }}"
                                   class="px-3 py-2 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-500">
                                Belum ada event pada status ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-100">
            {{ method_exists($events, 'withQueryString') ? $events->withQueryString()->links() : '' }}
        </div>
    </div>

</div>
@endsection
