@extends('layouts.dashboard')

@section('title', 'Event Saya')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Event Saya</h1>
            <p class="text-sm text-slate-500">
                Daftar event yang kamu buat
            </p>
        </div>

        <a href="#"
           class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
            + Buat Event
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-5 py-3 text-left">Event</th>
                    <th class="px-5 py-3">Target</th>
                    <th class="px-5 py-3">Terkumpul</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($events as $event)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4">
                            <p class="font-semibold text-slate-800">
                                {{ $event->title }}
                            </p>
                            <p class="text-xs text-slate-500">
                                {{ $event->created_at->format('d M Y') }}
                            </p>
                        </td>

                        <td class="px-5 py-4 text-center">
                            Rp {{ number_format($event->target,0,',','.') }}
                        </td>

                        <td class="px-5 py-4 text-center text-emerald-600 font-semibold">
                            Rp {{ number_format($event->raised,0,',','.') }}
                        </td>

                        <td class="px-5 py-4 text-center">
                            @php
                                $color = match($event->status) {
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'pending'  => 'bg-amber-100 text-amber-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    default    => 'bg-slate-100 text-slate-600'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                {{ strtoupper($event->status) }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-center space-x-2">
                            <a href="{{ route('dashboard.events.show', $event) }}"
                               class="text-emerald-600 hover:underline text-xs font-semibold">
                                Lihat
                            </a>

                            @if($event->status !== 'approved')
                                <a href="{{ route('dashboard.events.edit', $event) }}"
                                   class="text-slate-600 hover:underline text-xs font-semibold">
                                    Edit
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-slate-500">
                            Kamu belum membuat event apapun
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $events->links() }}
    </div>
</div>
@endsection
