@extends('layouts.admin-dashboard')

@section('title', 'Verifikasi KYC')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Verifikasi KYC</h2>
            <p class="text-sm text-slate-500">
                Daftar pengajuan verifikasi identitas pengguna
            </p>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('danger'))
        <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
            {{ session('danger') }}
        </div>
    @endif

    
    {{-- TABLE CARD --}}
    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b text-slate-600">
                <tr>
                    <th class="px-6 py-3 text-left font-medium">Nama</th>
                    <th class="px-6 py-3 text-left font-medium">Email</th>
                    <th class="px-6 py-3 text-left font-medium">Status</th>
                    <th class="px-6 py-3 text-left font-medium">Tanggal</th>
                    <th class="px-6 py-3 text-center font-medium">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($kycs as $kyc)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 font-medium text-slate-800">
                        {{ $kyc->full_name }}
                    </td>

                    <td class="px-6 py-4 text-slate-600">
                        {{ $kyc->user->email ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            @if($kyc->status=='pending') bg-amber-100 text-amber-700
                            @elseif($kyc->status=='approved') bg-emerald-100 text-emerald-700
                            @else bg-red-100 text-red-700
                            @endif">
                            {{ strtoupper($kyc->status) }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-slate-500">
                        {{ $kyc->created_at->format('d M Y') }}
                    </td>

                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.kyc.show', $kyc->id) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg
                                  bg-emerald-50 text-emerald-700 hover:bg-emerald-100
                                  text-xs font-medium transition">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-10 text-center text-slate-400">
                        Belum ada data KYC
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@endsection
