@extends('layouts.dashboard')
@section('title', 'Edit Campaign')
@section('page_title', 'Campaign')

@section('content')
@php
    $status  = $program->status ?? 'draft';
    $canEdit = $status === 'draft'; // sesuai revisi: edit cuma untuk draft
@endphp

<div class="min-h-[calc(100vh-4rem)] bg-slate-50 px-0 py-2">
    <div class="mx-auto w-full max-w-4xl px-0 md:px-0">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">
                Edit Campaign
            </h1>
            <p class="mt-2 text-slate-600">
                Ubah informasi dasar campaign kamu. Pastikan datanya sudah rapi sebelum diajukan ke admin.
            </p>
        </div>

        @unless($canEdit)
            <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 text-amber-800">
                Campaign ini tidak bisa diedit karena statusnya sudah berubah.
            </div>
        @else
            <div class="rounded-3xl bg-white border border-slate-200 shadow-sm px-6 py-8 md:px-10 md:py-10">

                {{-- Badge status --}}
                <div class="mb-8 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('dashboard.campaigns.index') }}"
                           class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200
                                  text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>

                        <div>
                            <div class="text-sm text-slate-500">Status Campaign</div>
                            <div class="mt-1 inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">
                                <span>📝</span>
                                <span>Draft</span>
                            </div>
                        </div>
                    </div>

                    <div class="hidden md:flex items-center gap-2 text-xs text-slate-500">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-700 font-semibold">E</span>
                        <span>Edit data dasar</span>
                    </div>
                </div>

                {{-- Error summary --}}
                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <div class="font-semibold mb-1">Ada yang perlu diperbaiki:</div>
                        <ul class="list-disc ml-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORM UPDATE --}}
                <form id="form-update"
                      method="POST"
                      action="{{ route('dashboard.campaigns.update', $program->id) }}"
                      class="space-y-6"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Judul --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Judul Galang Dana <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $program->title) }}"
                            placeholder="Contoh: Bantu Biaya Pengobatan Ayah"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3
                                   focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500"
                            required
                        >
                        <p class="mt-2 text-xs text-slate-500">
                            Buat judul yang jelas dan menyentuh. Maks 150 karakter.
                        </p>
                    </div>

                    {{-- Short description --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Deskripsi Singkat
                        </label>
                        <input
                            type="text"
                            name="short_description"
                            value="{{ old('short_description', $program->short_description) }}"
                            placeholder="Deskripsikan secara singkat alasanmu mengajukan dana"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3
                                   focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500"
                        >
                        <p class="mt-2 text-xs text-slate-500">
                            Opsional, tapi membantu donatur memahami kampanye kamu cepat.
                        </p>
                    </div>

                    {{-- Target & Deadline --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- TARGET --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Target Donasi (Rp)
                            </label>

                            <input
                                type="text"
                                id="target_display"
                                inputmode="numeric"
                                autocomplete="off"
                                value="{{ old('target', $program->target ?? 0) ? number_format((int) old('target', $program->target ?? 0), 0, ',', '.') : '0' }}"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3
                                       focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500"
                            >

                            <input type="hidden" id="target" name="target" value="{{ old('target', (int) ($program->target ?? 0)) }}">

                            <p class="mt-2 text-xs text-slate-500">
                                Isi 0 jika kampanye tanpa target (unlimited).
                            </p>
                        </div>

                        {{-- DEADLINE --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Deadline (opsional)
                            </label>
                            <input
                                type="date"
                                name="deadline"
                                value="{{ old('deadline', $program->deadline ? \Carbon\Carbon::parse($program->deadline)->format('Y-m-d') : '') }}"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3
                                       focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500"
                            >
                            <p class="mt-2 text-xs text-slate-500">
                                Kalau kosong, kampanye tidak punya batas waktu.
                            </p>
                        </div>

                    </div>

                    {{-- Foto Kampanye --}}
                    <div x-data="{ preview: null }">
                        <label class="block text-sm font-medium text-slate-700 mb-3">
                            Update Foto (opsional)
                        </label>

                        <label class="block cursor-pointer rounded-3xl border-2 border-dashed border-slate-200
                                      bg-slate-50 hover:border-emerald-400 hover:bg-emerald-50/40
                                      transition overflow-hidden">

                            <input
                                type="file"
                                name="image"
                                accept="image/*"
                                class="hidden"
                                @change="
                                    const file = $event.target.files?.[0];
                                    if (!file) return;
                                    preview = URL.createObjectURL(file);
                                "
                            >

                            <div class="relative w-full aspect-[16/9] flex items-center justify-center">

                                {{-- Preview baru --}}
                                <template x-if="preview">
                                    <img :src="preview" alt="Preview Foto Kampanye"
                                         class="absolute inset-0 h-full w-full object-cover">
                                </template>

                                {{-- Kalau belum ada preview baru, tampilkan gambar lama --}}
                                <template x-if="!preview">
                                    <div class="absolute inset-0">
                                        @if($program->image)
                                            <img src="{{ asset('storage/' . $program->image) }}"
                                                 alt="Foto Campaign"
                                                 class="h-full w-full object-cover">
                                            <div class="absolute inset-0 bg-black/20"></div>
                                            <div class="absolute bottom-4 left-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-700">
                                                Klik untuk ganti foto
                                            </div>
                                        @else
                                            <div class="h-full w-full flex flex-col items-center justify-center text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3"
                                                     viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M19 3H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-1.586l4.293-4.293a1 1 0 0 1 1.414 0L12 14.414l2.293-2.293a1 1 0 0 1 1.414 0L20 16.414V18ZM8 9a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                                                </svg>
                                                <span class="text-sm font-medium">Klik untuk upload foto kampanye</span>
                                            </div>
                                        @endif
                                    </div>
                                </template>

                            </div>
                        </label>

                        <p class="mt-2 text-xs text-slate-500">
                            Kalau kamu upload foto baru, foto lama akan terganti.
                        </p>

                        @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi lengkap --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Cerita / Deskripsi Lengkap
                        </label>
                        <textarea
                            name="description"
                            rows="7"
                            placeholder="Ceritakan latar belakang, kebutuhan dana, dan rencana penggunaan dana..."
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3
                                   focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500"
                        >{{ old('description', $program->description) }}</textarea>
                    </div>
                </form>

                {{-- ACTION BUTTONS (di luar form update, biar gak nested) --}}
                <div class="pt-2 flex flex-col sm:flex-row gap-3">

                    {{-- KIRI --}}
                    <a href="{{ route('dashboard.campaigns.index') }}"
                    class="w-full sm:w-auto inline-flex justify-center rounded-full
                            border border-slate-200 bg-white
                            px-6 py-3 text-sm font-semibold text-slate-700
                            hover:bg-slate-50 transition">
                        Batal
                    </a>

                    <form method="POST" action="{{ route('dashboard.campaigns.destroy', $program->id) }}"
                        class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('Yakin hapus campaign ini?')"
                            class="w-full inline-flex justify-center rounded-full
                                border border-red-200 bg-red-50
                                px-6 py-3 text-sm font-semibold text-red-700
                                hover:bg-red-100 transition">
                            Hapus
                        </button>
                    </form>

                    {{-- KANAN --}}
                    <button form="form-update" type="submit"
                        class="w-full sm:w-auto sm:ml-auto inline-flex justify-center rounded-full
                            bg-emerald-600
                            px-6 py-3 text-sm font-semibold text-white
                            hover:bg-emerald-700 active:bg-emerald-800
                            shadow-md shadow-emerald-600/30 transition">
                        Simpan
                    </button>

                </div>

            </div>
        @endunless
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const display = document.getElementById('target_display');
    const hidden = document.getElementById('target');
    if (!display || !hidden) return;

    const formatID = (numStr) => {
        if (!numStr) return '0';
        numStr = numStr.replace(/^0+(?=\d)/, '');
        return numStr.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    };

    const sync = () => {
        const raw = (display.value || '').replace(/[^\d]/g, '');
        const safeRaw = raw === '' ? '0' : raw;

        hidden.value = parseInt(safeRaw, 10) || 0;
        display.value = formatID(safeRaw);
    };

    display.addEventListener('input', sync);
    display.addEventListener('blur', sync);
    sync();
});
</script>
@endsection
