@extends('layouts.app')

@section('authpage', true)

@section('title', 'Buat Galang Dana')

@section('content')
<div class="min-h-[calc(100vh-4rem)] bg-slate-50 px-4 py-10">
    <div class="mx-auto w-full max-w-4xl">

        {{-- Header card --}}
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">
                Buat Galang Dana
            </h1>
            <p class="mt-2 text-slate-600">
                Lengkapi informasi dasar kampanye kamu. Nanti setelah ini kamu bisa lanjut isi detail & ajukan review admin.
            </p>
        </div>

        <div class="rounded-3xl bg-white border border-slate-200 shadow-sm px-6 py-8 md:px-10 md:py-10">

            {{-- Badge jenis --}}
            <div class="mb-8 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('galang.create') }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200
                              text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>

                    <div>
                        <div class="text-sm text-slate-500">Jenis Galang Dana</div>
                            <div class="mt-1 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700">
                                @if($jenis === 'medis')
                                    {{-- icon medis --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M10.5 4.5A1.5 1.5 0 0 1 12 3a1.5 1.5 0 0 1 1.5 1.5V7.5H16.5A1.5 1.5 0 0 1 18 9a1.5 1.5 0 0 1-1.5 1.5H13.5V13.5A1.5 1.5 0 0 1 12 15a1.5 1.5 0 0 1-1.5-1.5V10.5H7.5A1.5 1.5 0 0 1 6 9A1.5 1.5 0 0 1 7.5 7.5H10.5V4.5Z"/>
                                    </svg>
                                    <span>Medis</span>

                                @elseif($jenis === 'punia')
                                    {{-- icon punia (pura/temple) --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2L6 6v2h12V6l-6-4zm-6 8v10h12V10H6zm2 2h8v6H8v-6z"/>
                                    </svg>
                                    <span>Punia</span>

                                @else
                                    {{-- icon lainnya (hati) --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 21s-5.3-3.13-8.1-6A5.13 5.13 0 0 1 3 7.05 4.86 4.86 0 0 1 7.9 5a4.62 4.62 0 0 1 4.1 2.38A4.62 4.62 0 0 1 16.1 5 4.86 4.86 0 0 1 21 7.05 5.13 5.13 0 0 1 20.1 15C17.3 17.87 12 21 12 21Z"/>
                                    </svg>
                                    <span>Lainnya</span>
                                @endif
                            </div>

                    </div>
                </div>

                <div class="hidden md:flex items-center gap-2 text-xs text-slate-500">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-700 font-semibold">2</span>
                    <span>Isi data dasar</span>
                </div>
            </div>

            {{-- Error summary (opsional) --}}
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

            <form method="POST" action="{{ route('galang.store') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="jenis" value="{{ $jenis }}">

                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Judul Galang Dana <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
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
                        value="{{ old('short_description') }}"
                        placeholder="Deskripsikan secara singkat alasanmu mengajukan dana"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500"
                    >
                    <p class="mt-2 text-xs text-slate-500">
                        Opsional, tapi sangat membantu donatur memahami kampanye kamu cepat.
                    </p>
                </div>

                {{-- Kategori (muncul hanya kalau jenis lainnya) --}}
                @if($jenis === 'lainnya')
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">
                            Pilih Kategori <span class="text-red-600">*</span>
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($categories as $cat)
                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        name="category"
                                        value="{{ $cat['slug'] }}"
                                        class="peer hidden"
                                        {{ old('category') === $cat['slug'] ? 'checked' : '' }}
                                    >
                                    <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-4
                                                hover:border-emerald-500 hover:bg-emerald-50/40 transition
                                                peer-checked:border-emerald-600 peer-checked:ring-2 peer-checked:ring-emerald-100">
                                        <div class="h-10 w-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl">
                                            {{ $cat['icon'] }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-slate-900">{{ $cat['name'] }}</div>
                                            <div class="text-xs text-slate-500">
                                                @switch($cat['slug'])
                                                    @case('pendidikan') Biaya sekolah, buku, fasilitas belajar. @break
                                                    @case('bencana-alam') Bantuan untuk korban bencana. @break
                                                    @case('kemanusiaan') Dukungan untuk sesama yang membutuhkan. @break
                                                    @case('panti-asuhan') Kebutuhan anak-anak panti asuhan. @break
                                                    @case('lingkungan') Program hijau & bersih-bersih. @break
                                                    @case('sedekah') Sedekah umum untuk kebaikan. @break
                                                    @default Pilih kategori yang sesuai.
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <p class="mt-3 text-xs text-slate-500">
                            Kategori ini membantu donatur menemukan kampanye kamu.
                        </p>
                    </div>
                @endif


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
                            value="{{ old('target') ? number_format((int) old('target'), 0, ',', '.') : '0' }}"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3
                                focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500"
                        >

                        <input type="hidden" id="target" name="target" value="{{ old('target', 0) }}">

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
                            value="{{ old('deadline') }}"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3
                                focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500"
                        >
                        <p class="mt-2 text-xs text-slate-500">
                            Kalau kosong, kampanye tidak punya batas waktu.
                        </p>
                    </div>

                </div>

                {{-- Foto Kampanye --}}
                {{-- Foto Kampanye --}}
                <div x-data="{ preview: null }">
                    <label class="block text-sm font-medium text-slate-700 mb-3">
                        Upload Foto <span class="text-red-600">*</span>
                    </label>

                    <label
                        class="block cursor-pointer rounded-3xl border-2 border-dashed border-slate-200
                            bg-slate-50 hover:border-emerald-400 hover:bg-emerald-50/40
                            transition overflow-hidden">

                        {{-- Input file (hidden) --}}
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

                        {{-- Preview Area --}}
                        <div class="relative w-full aspect-[16/9] flex items-center justify-center">

                            {{-- Jika ada preview --}}
                            <template x-if="preview">
                                <img
                                    :src="preview"
                                    alt="Preview Foto Kampanye"
                                    class="absolute inset-0 h-full w-full object-cover"
                                >
                            </template>

                            {{-- Placeholder --}}
                            <template x-if="!preview">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-12 w-12 mb-3"
                                        viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path d="M19 3H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-1.586l4.293-4.293a1 1 0 0 1 1.414 0L12 14.414l2.293-2.293a1 1 0 0 1 1.414 0L20 16.414V18ZM8 9a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                                    </svg>
                                    <span class="text-sm font-medium">
                                        Klik untuk upload foto kampanye
                                    </span>
                                </div>
                            </template>
                        </div>
                    </label>
                    <p class="mt-2 text-xs text-slate-500">
                            Upload gambar yang menggambarkan kondisi 
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
                    >{{ old('description') }}</textarea>
                </div>

                {{-- Action buttons --}}
                <div class="pt-2 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('galang.create') }}"
                       class="w-full sm:w-auto inline-flex justify-center rounded-full border border-slate-200 bg-white px-6 py-3
                              text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Kembali
                    </a>

                    <button type="submit"
                        class="w-full sm:flex-1 rounded-full bg-emerald-600 px-6 py-3 text-center text-sm md:text-base font-semibold text-white
                               hover:bg-emerald-700 active:bg-emerald-800 shadow-md shadow-emerald-600/30 transition">
                        Simpan Draft
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const display = document.getElementById('target_display');
    const hidden = document.getElementById('target');

    if (!display || !hidden) return;

    const formatID = (numStr) => {
        // numStr sudah angka tanpa non-digit
        if (!numStr) return '0';
        // hapus leading zero berlebih
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

    // init
    sync();
});
</script>

@endsection
