@extends('layouts.dashboard')

@section('title', 'Beranda')

@section('content')

            {{-- MAIN CONTENT --}}
            <main class="flex-1 px-4 md:px-8 py-6 space-y-6">

                {{-- BANNER REFERRAL --}}
                <div class="rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800">
                    <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
                        <p>
                            Dapatkan <span class="font-semibold">“Hadiah Kebaikan”</span> dengan membagikan kode referral
                            DonasiKuy.
                            Ajak teman berdonasi dan kumpulkan reward kebaikanmu!
                        </p>
                        <div class="mt-2 md:mt-0 flex items-center gap-3 text-xs">
                            <span class="px-2 py-1 rounded-lg bg-white text-sky-700 border border-sky-200 font-mono">
                                donasikuy.com?ref={{ auth()->user()->ref_code ?? 'REF123' }}
                            </span>
                            <a href="#" class="font-semibold underline underline-offset-2">
                                Lihat Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>

                {{-- BANNER KYC --}}
                @if (!$hasSubmittedKyc)
                    {{-- Belum pernah ajukan KYC --}}
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        <span class="font-semibold">Akun Anda belum diverifikasi (KYC).</span>
                        <span class="ml-1">Lengkapi dokumen verifikasi untuk mulai membuat galang dana.</span>
                        <a href="{{ route('kyc.step1') }}" class="font-semibold underline underline-offset-2 ml-1">
                            Verifikasi di sini
                        </a>
                    </div>

                @elseif ($kycStatus === 'pending')
                    {{-- Sudah ajukan, sedang diproses --}}
                    <div class="rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800">
                        <span class="font-semibold">Pengajuan KYC Anda sedang diproses.</span>
                        <span class="ml-1">Tim kami sedang meninjau dokumen yang Anda kirimkan.</span>
                    </div>

                @elseif ($kycStatus === 'approved')
                    {{-- Sudah diverifikasi --}}
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        <span class="font-semibold">Akun Anda sudah terverifikasi 🎉</span>
                        <span class="ml-1">Anda dapat membuat galang dana dan mengajukan pencairan dana.</span>
                    </div>

                @elseif ($kycStatus === 'rejected')
                    {{-- Ditolak --}}
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <span class="font-semibold">Pengajuan KYC Anda ditolak.</span>
                        @if ($kycNote)
                            <span class="ml-1">Alasan: {{ $kycNote }}</span>
                        @endif
                        <span class="ml-1">Silakan perbaiki data dan ajukan ulang.</span>
                        <a href="{{ route('kyc.step1') }}" class="font-semibold underline underline-offset-2 ml-1">
                            Ajukan ulang KYC
                        </a>
                    </div>
                @endif
                {{-- KARTU RINGKASAN --}}
                <div class="grid gap-4 lg:grid-cols-3">
                    {{-- Total Donasi --}}
                    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm px-4 py-4 flex items-center gap-4">
                        <div
                            class="h-12 w-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-400 flex items-center justify-center text-white">
                            <i class="bi bi-heart-fill text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Donasi</p>
                            <p class="mt-1 text-xl font-semibold text-slate-900">
                                Rp{{ number_format($totalDonasi ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Total Campaign --}}
                    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm px-4 py-4 flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="bi bi-collection-play-fill text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Campaign</p>
                            <p class="mt-1 text-xl font-semibold text-slate-900">
                                {{ $totalCampaign ?? 0 }}
                            </p>
                        </div>
                    </div>

                    {{-- Pencairan Dana --}}
                    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm px-4 py-4 flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500">
                            <i class="bi bi-arrow-up-right-circle-fill text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                                Pencairan Dana Keseluruhan
                            </p>
                            <p class="mt-1 text-xl font-semibold text-slate-900">
                                Rp{{ number_format($totalPencairan ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- TABEL DONASI 1 MINGGU TERAKHIR --}}
                <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-4 md:px-6 py-3 border-b border-slate-200">
                        <h2 class="text-sm font-semibold text-slate-900">
                            Donasi Anda dalam 1 Minggu Terakhir
                        </h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th
                                        class="text-left px-4 md:px-6 py-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                                        Tanggal
                                    </th>
                                    <th
                                        class="text-right px-4 md:px-6 py-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                                        Jumlah Donasi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($weeklyDonations ?? [] as $row)
                                    <tr class="border-b border-slate-100">
                                        <td class="px-4 md:px-6 py-2 text-slate-800">
                                            {{ \Carbon\Carbon::parse($row['date'])->format('d-F-Y') }}
                                        </td>
                                        <td class="px-4 md:px-6 py-2 text-right font-medium text-slate-900">
                                            Rp{{ number_format($row['amount'] ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 md:px-6 py-4 text-center text-xs text-slate-500">
                                            Belum ada donasi dalam 1 minggu terakhir.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <p class="text-[11px] text-slate-400 text-center mt-6">
                    Copyright © {{ now()->year }} DonasiKuy. All rights reserved.
                </p>
            </main>
        </div>
    </div>
@endsection
