@extends('layouts.dashboard')

@section('title', 'Beranda')

@section('content')
    <div class="min-h-screen bg-slate-50 flex">

        {{-- SIDEBAR --}}
        <aside class="hidden md:flex w-64 flex-col bg-white border-r border-slate-200">
            <div class="h-16 flex items-center px-6 border-b border-slate-200">
                <a href="{{ route('landing') }}" class="flex items-center">
                    <span class="text-lg font-semibold text-emerald-700">
                        Donasi<span class="text-emerald-500">Kuy</span>
                    </span>
                </a>

            </div>

            <nav class="flex-1 py-4 space-y-1 text-sm">
                {{-- Beranda (active) --}}
                <a href="{{ route('dashboard.index') }}"
                    class="flex items-center gap-3 px-6 py-2.5 border-l-4 border-emerald-500 bg-emerald-50 text-emerald-700 font-medium">
                    <i class="bi bi-house-door-fill text-base"></i>
                    <span>Beranda</span>
                </a>

                {{-- Nanti kalau sudah ada route campaign, tinggal ganti href="#" --}}
                <a href="#"
                    class="flex items-center gap-3 px-6 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-l-4 border-transparent">
                    <i class="bi bi-collection-play text-base"></i>
                    <span>Campaign</span>
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-6 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-l-4 border-transparent">
                    <i class="bi bi-calendar-event text-base"></i>
                    <span>Event</span>
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-6 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-l-4 border-transparent">
                    <i class="bi bi-clock-history text-base"></i>
                    <span>Riwayat Donasi</span>
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-6 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-l-4 border-transparent">
                    <i class="bi bi-arrow-up-right-square text-base"></i>
                    <span>Pencairan Dana</span>
                </a>
            </nav>
        </aside>

        {{-- AREA KANAN (TOPBAR + CONTENT) --}}
        <div class="flex-1 flex flex-col">

            {{-- TOPBAR --}}
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8">
                <div class="flex items-center gap-2">
                    <button
                        class="md:hidden inline-flex items-center justify-center p-2 rounded-lg border border-slate-200">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    <h1 class="hidden md:block text-base font-semibold text-slate-800">
                        Beranda
                    </h1>
                </div>

                <div class="flex items-center gap-4">
                    <button
                        class="hidden sm:inline-flex items-center justify-center h-9 w-9 rounded-full border border-slate-200 text-slate-500">
                        <i class="bi bi-bell"></i>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="text-sm font-medium text-slate-800">
                                {{ auth()->user()->name ?? 'Nama User' }}
                            </span>
                            <span class="text-xs text-slate-500">
                                {{ auth()->user()->email ?? 'user@example.com' }}
                            </span>
                        </div>
                        <div
                            class="h-9 w-9 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-white text-sm font-semibold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'DK', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

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
                @if (!($isKycVerified ?? false))
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        <span class="font-semibold">Akun Anda belum diverifikasi (KYC).</span>
                        <span class="ml-1">Lengkapi dokumen verifikasi untuk mulai membuat galang dana.</span>
                        <a href="{{ route('kyc.index') }}" class="font-semibold underline underline-offset-2 ml-1">
                            Verifikasi di sini
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
