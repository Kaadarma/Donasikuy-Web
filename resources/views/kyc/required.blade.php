@extends('layouts.dashboard')

@section('title', 'Verifikasi Diperlukan')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4">

    <div class="max-w-lg w-full bg-white shadow-md rounded-2xl border border-slate-200 p-8 text-center">

        {{-- JUDUL --}}
        <h2 class="text-xl font-semibold text-slate-800 mb-2">
            Verifikasi KYC Diperlukan
        </h2>

        {{-- KONTEN DINAMIS --}}
        @php
            $status = $kyc->status ?? null;
            $note   = $kyc->note ?? null;
        @endphp

        {{-- STATUS BELUM PERNAH AJUKAN --}}
        @if (!$status)
            <p class="text-sm text-slate-600 mb-6">
                Untuk mengakses fitur ini, akun Anda harus melewati proses verifikasi identitas (KYC).
                Silakan mulai proses verifikasi sekarang.
            </p>

            <div class="flex justify-center mb-6">
                <div class="h-20 w-20 rounded-full bg-amber-100 flex items-center justify-center">
                    <i class="bi bi-shield-exclamation text-4xl text-amber-600"></i>
                </div>
            </div>

            <a href="{{ route('kyc.step1') }}"
                class="inline-flex items-center justify-center px-6 py-2.5 rounded-full
                       bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm transition">
                Mulai Verifikasi KYC
            </a>

        {{-- STATUS PENDING --}}
        @elseif ($status === 'pending')
            <div class="flex justify-center mb-4">
                <div class="h-20 w-20 rounded-full bg-sky-100 flex items-center justify-center">
                    <i class="bi bi-hourglass-split text-4xl text-sky-600"></i>
                </div>
            </div>

            <p class="text-sm text-slate-700 mb-4">
                <span class="font-semibold">Pengajuan KYC Anda sedang diproses.</span><br>
                Tim kami sedang meninjau dokumen yang Anda kirimkan. Anda akan diberi tahu setelah proses selesai.
            </p>

        {{-- STATUS REJECTED --}}
        @elseif ($status === 'rejected')
            <div class="flex justify-center mb-4">
                <div class="h-20 w-20 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="bi bi-x-circle text-4xl text-red-600"></i>
                </div>
            </div>

            <p class="text-sm text-red-700 mb-4">
                <span class="font-semibold">Pengajuan KYC Anda ditolak.</span><br>
                @if ($note)
                    Alasan: <span class="italic">{{ $note }}</span><br>
                @endif
                Silakan perbaiki data Anda dan ajukan ulang.
            </p>

            <a href="{{ route('kyc.step1') }}"
                class="inline-flex items-center justify-center px-6 py-2.5 rounded-full
                       bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition">
                Ajukan Ulang KYC
            </a>

        {{-- STATUS APPROVED (opsional) --}}
        @elseif ($status === 'approved')
            <div class="flex justify-center mb-4">
                <div class="h-20 w-20 rounded-full bg-emerald-100 flex items-center justify-center">
                    <i class="bi bi-check-circle text-4xl text-emerald-600"></i>
                </div>
            </div>

            <p class="text-sm text-emerald-700 mb-4">
                Akun Anda sudah terverifikasi. Anda kini dapat mengakses fitur penuh.
            </p>

            <a href="{{ route('dashboard.index') }}"
                class="inline-flex items-center justify-center px-6 py-2.5 rounded-full
                       bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm transition">
                Kembali ke Dashboard
            </a>
        @endif

        {{-- FOOTER --}}
        <div class="mt-6">
            <a href="{{ route('dashboard.index') }}" class="text-xs text-slate-500 underline">
                Kembali ke Dashboard
            </a>
        </div>

    </div>

</div>
@endsection
