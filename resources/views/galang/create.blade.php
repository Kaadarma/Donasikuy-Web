@extends('layouts.app')


@section('title', 'Galang Dana')

@section('content')
<div class="min-h-[calc(100vh-4rem)] bg-slate-50 flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-4xl rounded-3xl bg-white border border-slate-200 shadow-sm px-6 py-8 md:px-10 md:py-10">
        
        {{-- Header teks --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">
                Hai, sobat DonasiKuy!
            </h1>
            <p class="mt-2 text-slate-600">
                Yuk mulai galang dana untuk membantu sesama!
            </p>
        </div>

        {{-- FORM PILIH KATEGORI --}}
        <div class="space-y-4">
                <div id="jenisError"
                    class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    Silakan pilih jenis galang dana terlebih dahulu.
                </div>

            {{-- Pilihan 1: Galang Dana Medis --}}
            <label class="block cursor-pointer">
                <input type="radio" name="kategori_galang" value="medis" class="peer hidden">
                <div
                    class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white px-4 py-4 md:px-6 md:py-5 
                           shadow-xs hover:shadow-md transition
                           peer-checked:border-emerald-600 peer-checked:ring-2 peer-checked:ring-emerald-100">
                    
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        {{-- icon medis --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10.5 4.5A1.5 1.5 0 0 1 12 3a1.5 1.5 0 0 1 1.5 1.5V7.5H16.5A1.5 1.5 0 0 1 18 9a1.5 1.5 0 0 1-1.5 1.5H13.5V13.5A1.5 1.5 0 0 1 12 15a1.5 1.5 0 0 1-1.5-1.5V10.5H7.5A1.5 1.5 0 0 1 6 9A1.5 1.5 0 0 1 7.5 7.5H10.5V4.5Z"/>
                        </svg>
                    </div>

                    <div class="flex-1">
                        <div class="font-semibold text-slate-900">
                            Galang Dana Medis
                        </div>
                        <p class="mt-1 text-sm text-slate-600">
                            Bantu teman-teman pasien yang membutuhkan biaya medis.
                        </p>
                    </div>
                </div>
            </label>

            {{-- Pilihan 2: Galang Dana Lainnya --}}
            <label class="block cursor-pointer">
                <input type="radio" name="kategori_galang" value="lainnya" class="peer hidden">
                <div
                    class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white px-4 py-4 md:px-6 md:py-5 
                           shadow-xs hover:shadow-md transition
                           peer-checked:border-emerald-600 peer-checked:ring-2 peer-checked:ring-emerald-100">
                    
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        {{-- icon hati --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 21s-5.3-3.13-8.1-6A5.13 5.13 0 0 1 3 7.05 4.86 4.86 0 0 1 7.9 5a4.62 4.62 0 0 1 4.1 2.38A4.62 4.62 0 0 1 16.1 5 4.86 4.86 0 0 1 21 7.05 5.13 5.13 0 0 1 20.1 15C17.3 17.87 12 21 12 21Z"/>
                        </svg>
                    </div>

                    <div class="flex-1">
                        <div class="font-semibold text-slate-900">
                            Galang Dana Lainnya
                        </div>
                        <p class="mt-1 text-sm text-slate-600">
                            Untuk bantuan sosial, pendidikan, lingkungan, bencana alam, dan lainnya.
                        </p>
                    </div>
                </div>
            </label>

            {{-- Pilihan 3: Dana Punia --}}
            {{-- Pilihan 3: Dana Punia --}}
            <label class="block cursor-pointer">
                <input type="radio" name="kategori_galang" value="punia" class="peer hidden">
                <div
                    class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white px-4 py-4 md:px-6 md:py-5 
                        shadow-xs hover:shadow-md transition
                        peer-checked:border-emerald-600 peer-checked:ring-2 peer-checked:ring-emerald-100">

                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        {{-- icon punia --}}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7"
                            viewBox="0 0 24 24"
                            fill="currentColor">
                            <path d="M12 2L6 6v2h12V6l-6-4zm-6 8v10h12V10H6zm2 2h8v6H8v-6z"/>
                        </svg>
                    </div>

                    <div class="flex-1">
                        <div class="font-semibold text-slate-900">
                            Dana Punia
                        </div>
                        <p class="mt-1 text-sm text-slate-600">
                            Galang dana punia untuk mendukung kegiatan keagamaan dan yadnya.
                        </p>
                    </div>
                </div>
            </label>


            
            {{-- Tombol Lanjut --}}
            <div class="pt-2">
                <button id="btnLanjut"
                    class="w-full rounded-full bg-emerald-600 py-3 text-center text-sm md:text-base font-semibold text-white
                           hover:bg-emerald-700 active:bg-emerald-800 shadow-md shadow-emerald-600/30 transition">
                    Lanjut
                </button>
            </div>
        </div>
    </div>
</div>

{{-- JS untuk handle klik Lanjut --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btnLanjut');
    if (!btn) return;

    btn.addEventListener('click', function (e) {
        e.preventDefault(); // jangan submit form / reload

        const selected = document.querySelector('input[name="kategori_galang"]:checked');

        const errBox = document.getElementById('jenisError');

        if (!selected) {
            errBox?.classList.remove('hidden');
            return;
        } else {
            errBox?.classList.add('hidden');
        }


        console.log("Kategori terpilih:", selected.value);

        window.location.href = "{{ route('galang.form') }}" + "?jenis=" + selected.value;


    });
});
</script>
@endsection
