@extends('layouts.admin-dashboard')

@section('title', 'Detail KYC')

@section('content')

<h2 class="text-xl font-semibold mb-6">Detail Verifikasi KYC</h2>

<div class="grid lg:grid-cols-3 gap-6">

    {{-- ================= DATA PEMOHON ================= --}}
    <div class="lg:col-span-1 bg-white border rounded-2xl p-6 space-y-3">
        <h3 class="font-semibold text-slate-800 mb-3">Data Pemohon</h3>

        <div class="text-sm space-y-2">
            <p><span class="text-slate-500">Nama Lengkap</span><br>
                <span class="font-medium">{{ $kyc->full_name }}</span>
            </p>

            <p><span class="text-slate-500">Email</span><br>
                <span class="font-medium">{{ $kyc->user->email ?? '-' }}</span>
            </p>

            <p><span class="text-slate-500">NIK</span><br>
                <span class="font-medium">{{ $kyc->nik }}</span>
            </p>

            <p><span class="text-slate-500">Telepon</span><br>
                <span class="font-medium">{{ $kyc->phone ?? '-' }}</span>
            </p>

            <p><span class="text-slate-500">Alamat</span><br>
                <span class="font-medium">{{ $kyc->address ?? '-' }}</span>
            </p>

            <p>
                <span class="text-slate-500">Status</span><br>
                <span class="inline-block mt-1 px-2 py-1 rounded text-xs font-semibold
                    @if($kyc->status=='pending') bg-amber-100 text-amber-700
                    @elseif($kyc->status=='approved') bg-emerald-100 text-emerald-700
                    @else bg-red-100 text-red-700
                    @endif">
                    {{ strtoupper($kyc->status) }}
                </span>
            </p>
        </div>
    </div>

    {{-- ================= DOKUMEN ================= --}}
    <div class="lg:col-span-2 bg-white border rounded-2xl p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Dokumen</h3>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- FOTO KTP --}}
            <div>
                <p class="text-sm font-medium mb-2">Foto KTP</p>
                <a href="{{ asset('storage/' . $kyc->id_card_path) }}" target="_blank">
                    <img
                        src="{{ asset('storage/' . $kyc->id_card_path) }}"
                        class="w-full max-w-[160px] rounded-lg border hover:shadow transition"
                    >
                </a>
            </div>

            {{-- SELFIE KTP --}}
            <div>
                <p class="text-sm font-medium mb-2">Selfie dengan KTP</p>
                <a href="{{ asset('storage/' . $kyc->selfie_path) }}" target="_blank">
                    <img
                        src="{{ asset('storage/' . $kyc->selfie_path) }}"
                        class="w-full max-w-[160px] rounded-lg border hover:shadow transition"
                    >
                </a>
            </div>

            {{-- FOTO PROFIL --}}
            <div>
                <p class="text-sm font-medium mb-2">Foto Profil</p>
                <a href="{{ asset('storage/' . $kyc->profile_photo_path) }}" target="_blank">
                    <img
                        src="{{ asset('storage/' . $kyc->profile_photo_path) }}"
                        class="w-full max-w-[160px] rounded-lg border hover:shadow transition"
                    >
                </a>
            </div>

        </div>
    </div>

</div>

{{-- ================= CATATAN PENOLAKAN ================= --}}
@if($kyc->note)
<div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
    <strong>Catatan Penolakan:</strong><br>
    {{ $kyc->note }}
</div>
@endif

@if($kyc->status === 'pending')
<div x-data="{ openApprove:false, openReject:false }" class="mt-8 flex gap-4">

    {{-- APPROVE BUTTON --}}
    <button
        @click="openApprove = true"
        class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl font-medium hover:bg-emerald-700 transition">
        Approve
    </button>

    {{-- REJECT BUTTON --}}
    <button
        @click="openReject = true"
        class="px-6 py-2.5 bg-red-600 text-white rounded-xl font-medium hover:bg-red-700 transition">
        Reject
    </button>

    {{-- ================= MODAL APPROVE ================= --}}
    <div
        x-show="openApprove"
        x-transition
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-2">Konfirmasi Approve</h3>
            <p class="text-sm text-slate-600 mb-6">
                Apakah kamu yakin ingin <strong>menyetujui</strong> KYC ini?
            </p>

            <div class="flex justify-end gap-3">
                <button
                    @click="openApprove=false"
                    class="px-4 py-2 rounded-lg border text-slate-600">
                    Batal
                </button>

                <form method="POST" action="{{ route('admin.kyc.approve', $kyc->id) }}">
                    @csrf
                    <button
                        class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                        Ya, Approve
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ================= MODAL REJECT ================= --}}
    <div
        x-show="openReject"
        x-transition
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-2 text-red-600">
                Tolak KYC
            </h3>
            <p class="text-sm text-slate-600 mb-4">
                Berikan alasan penolakan agar user dapat memperbaiki data.
            </p>

            <form method="POST" action="{{ route('admin.kyc.reject', $kyc->id) }}">
                @csrf

                <textarea
                    name="note"
                    required
                    rows="3"
                    placeholder="Contoh: Foto KTP buram / data tidak sesuai"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none mb-4"></textarea>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        @click="openReject=false"
                        class="px-4 py-2 rounded-lg border text-slate-600">
                        Batal
                    </button>

                    <button
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Tolak KYC
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endif



@endsection
