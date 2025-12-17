@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-slate-900 mb-6">
        Edit Profil
    </h1>

    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6 md:p-8">

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Avatar --}}
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 rounded-full overflow-hidden border bg-slate-100">
                    <img
                        id="avatarPreview"
                        src="{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : asset('images/humans.jpg') }}"
                        data-original="{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : asset('images/humans.jpg') }}"
                        alt="Foto Profil"
                        class="h-full w-full object-cover">
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-800">Foto Profil</p>
                    <p class="text-xs text-slate-500 mb-2">Format: JPG, PNG, max 2MB.</p>

                    <div class="flex items-center gap-2">
                        <label class="inline-flex items-center px-3 py-2 rounded-md border border-slate-300 text-xs md:text-sm text-slate-700 bg-slate-50 hover:bg-slate-100 cursor-pointer">
                            <input id="avatarInput" type="file" name="avatar" class="hidden" accept="image/*">
                            <i class="bi bi-upload mr-2"></i> Pilih Foto
                        </label>

                        {{-- tombol batal preview (balik ke avatar lama) --}}
                        <button type="button" id="avatarReset"
                            class="hidden px-3 py-2 rounded-md border border-slate-300 text-xs md:text-sm text-slate-700 bg-white hover:bg-slate-50">
                            Batal
                        </button>
                    </div>

                    @error('avatar')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            {{-- Nama --}}
            <div class="space-y-1">
                <label class="block text-sm font-medium text-slate-700">
                    Nama Lengkap
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email (bisa diubah) --}}
            <div class="space-y-1" id="email">
                <label class="block text-sm font-medium text-slate-700">
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nomor HP --}}
            <div class="space-y-1" id="phone">
                <label class="block text-sm font-medium text-slate-700">
                    Nomor HP
                </label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    placeholder="Contoh: 081234567890">
                @error('phone')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between gap-3 pt-2">
                <a href="{{ route('profile') }}"
                    class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700">
                    ← Kembali ke Profil
                </a>

                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@section('scripts')
<script>
(function () {
    const input = document.getElementById('avatarInput');
    const img = document.getElementById('avatarPreview');
    const resetBtn = document.getElementById('avatarReset');
    if (!input || !img) return;

    const originalSrc = img.dataset.original;

    function resetPreview() {
        img.src = originalSrc;
        input.value = '';          // penting: supaya batal beneran
        resetBtn?.classList.add('hidden');
    }

    input.addEventListener('change', () => {
        const file = input.files && input.files[0];
        if (!file) {
            resetPreview();
            return;
        }

        // validasi cepat client-side (optional, tapi enak)
        if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar.');
            resetPreview();
            return;
        }

        // preview pakai blob URL (lebih cepat dari FileReader)
        const url = URL.createObjectURL(file);
        img.src = url;
        resetBtn?.classList.remove('hidden');

        // revoke setelah gambar keload (hemat memory)
        img.onload = () => URL.revokeObjectURL(url);
    });

    resetBtn?.addEventListener('click', resetPreview);
})();
</script>
@endsection

@endsection
