@extends('layouts.dashboard')

@section('title', 'Verifikasi KYC')

@section('content')
<div x-data="{ step: 1 }" class="max-w-4xl mx-auto py-10">

    {{-- PERINGATAN --}}
    <div class="mb-6 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-sm">
        Anda wajib melakukan verifikasi KYC terlebih dahulu sebelum mencairkan dana.
    </div>

    {{-- PROGRESS BAR --}}
    <div class="w-full bg-slate-200 h-2 rounded-full mb-6">
        <div class="bg-emerald-500 h-2 rounded-full transition-all duration-300"
             :style="'width: ' + (step * 33) + '%'">
        </div>
    </div>

    {{-- FORM KYC (semua step di 1 file) --}}
    <form action="{{ route('kyc.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- STEP 1: INFORMASI DASAR --}}
        <div x-show="step === 1" class="space-y-4">

            <h2 class="text-lg font-semibold">1. Informasi Dasar</h2>

            <label class="block">
                <span class="text-sm">Jenis Akun</span>
                <select name="jenis_akun" class="mt-1 w-full border rounded-lg p-2">
                    <option>Individu</option>
                    <option>Organisasi</option>
                </select>
            </label>

            <label class="block">
                <span class="text-sm">Nama Individu/Organisasi</span>
                <input type="text" name="nama" class="mt-1 w-full border rounded-lg p-2">
            </label>

            <label class="block">
                <span class="text-sm">Email</span>
                <input type="email" name="email" class="mt-1 w-full border rounded-lg p-2"
                       value="{{ auth()->user()->email }}" readonly>
            </label>

            <label class="block">
                <span class="text-sm">Alamat</span>
                <textarea name="alamat" class="mt-1 w-full border rounded-lg p-2"></textarea>
            </label>

            <button type="button"
                    @click="step = 2"
                    class="w-full bg-emerald-600 text-white py-3 rounded-lg font-semibold">
                Lanjut
            </button>
        </div>

        {{-- STEP 2: IDENTITAS PEMEGANG AKUN --}}
        <div x-show="step === 2" class="space-y-4">

            <h2 class="text-lg font-semibold">2. Identitas Pemegang Akun</h2>

            <label class="block">
                <span class="text-sm">No HP (Whatsapp)</span>
                <input type="text" name="nohp" class="mt-1 w-full border rounded-lg p-2">
            </label>

            <label class="block">
                <span class="text-sm">No KTP</span>
                <input type="text" name="ktp" class="mt-1 w-full border rounded-lg p-2">
            </label>

            <label>
                <span class="text-sm">Foto KTP</span>
                <input type="file" name="foto_ktp" class="mt-1 w-full">
            </label>

            <label>
                <span class="text-sm">Foto Selfie dengan KTP</span>
                <input type="file" name="foto_selfie" class="mt-1 w-full">
            </label>

            <div class="flex gap-3">
                <button type="button"
                        @click="step = 1"
                        class="w-1/2 bg-slate-200 py-3 rounded-lg font-semibold">
                    Kembali
                </button>
                <button type="button"
                        @click="step = 3"
                        class="w-1/2 bg-emerald-600 text-white py-3 rounded-lg font-semibold">
                    Lanjut
                </button>
            </div>
        </div>

        {{-- STEP 3: INFORMASI PENCARIAN DANA --}}
        <div x-show="step === 3" class="space-y-4">

            <h2 class="text-lg font-semibold">3. Informasi Pencairan Dana</h2>

            <label class="block">
                <span class="text-sm">Bank</span>
                <select name="bank" class="mt-1 w-full border rounded-lg p-2">
                    <option>BCA</option>
                    <option>BRI</option>
                    <option>BNI</option>
                    <option>Mandiri</option>
                </select>
            </label>

            <label class="block">
                <span class="text-sm">Nomor Rekening</span>
                <input type="text" name="rekening" class="mt-1 w-full border rounded-lg p-2">
            </label>

            <label class="block">
                <span class="text-sm">Nama Rekening</span>
                <input type="text" name="nama_rekening" class="mt-1 w-full border rounded-lg p-2">
            </label>

            <label>
                <span class="text-sm">Scan Buku Tabungan</span>
                <input type="file" name="foto_tabungan" class="mt-1 w-full">
            </label>

            <div class="flex gap-3">
                <button type="button"
                        @click="step = 2"
                        class="w-1/2 bg-slate-200 py-3 rounded-lg font-semibold">
                    Kembali
                </button>

                <button type="submit"
                        class="w-1/2 bg-emerald-600 text-white py-3 rounded-lg font-semibold">
                    Kirim KYC
                </button>
            </div>
        </div>

    </form>
</div>

@endsection
