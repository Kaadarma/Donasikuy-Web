@extends('layouts.dashboard') {{-- atau layout yang kamu mau --}}

@section('title', 'Verifikasi Akun')

@section('content')

<div class="max-w-4xl mx-auto py-10">

    {{-- PROGRESS BAR --}}
    <div class="w-full h-2 bg-slate-200 rounded mb-6 overflow-hidden">
        <div id="progress" class="h-full bg-emerald-500 transition-all" style="width: 33%;"></div>
    </div>

    <div x-data="{ step: 1 }">

        {{-- ================= STEP 1 ================= --}}
        <div x-show="step === 1" x-transition>
            <h2 class="text-xl font-semibold mb-4">1. Informasi Dasar</h2>

            <form id="form-step1" class="space-y-4">

                <select name="jenis_akun" class="w-full border rounded p-3">
                    <option value="Individu">Individu</option>
                    <option value="Organisasi">Organisasi</option>
                </select>

                <input type="text" name="nama" class="w-full border rounded p-3" placeholder="Nama Individu/Organisasi">
                <input type="email" name="email" class="w-full border rounded p-3" placeholder="Email">
                <textarea name="alamat" class="w-full border rounded p-3" placeholder="Alamat"></textarea>

                <button type="button" onclick="submitStep1()" class="w-full bg-emerald-600 text-white py-3 rounded">
                    Lanjut
                </button>
            </form>
        </div>

        {{-- ================= STEP 2 ================= --}}
        <div x-show="step === 2" x-transition>
            <h2 class="text-xl font-semibold mb-4">2. Identitas Pemegang Akun</h2>

            <form id="form-step2" class="space-y-4" enctype="multipart/form-data">

                <input type="text" name="no_hp" class="w-full border rounded p-3" placeholder="No HP (Whatsapp)">
                <input type="text" name="no_ktp" class="w-full border rounded p-3" placeholder="No KTP">

                <label>Foto KTP</label>
                <input type="file" name="foto_ktp">

                <label>Foto Selfie Dengan KTP</label>
                <input type="file" name="selfie_ktp">

                <label>Foto Profile</label>
                <input type="file" name="foto_profile">

                <button type="button" onclick="submitStep2()" class="w-full bg-emerald-600 text-white py-3 rounded">
                    Lanjut
                </button>
                <button type="button" class="w-full bg-slate-200 py-2 rounded mt-2" @click="step--">Balik</button>
            </form>
        </div>

        {{-- ================= STEP 3 ================= --}}
        <div x-show="step === 3" x-transition>
            <h2 class="text-xl font-semibold mb-4">3. Informasi Pencairan Dana</h2>

            <form id="form-step3" class="space-y-4" enctype="multipart/form-data">

                <select name="bank" class="w-full border rounded p-3">
                    <option>Pilih Bank</option>
                    <option>BCA</option>
                    <option>BNI</option>
                    <option>BRI</option>
                </select>

                <input type="text" name="no_rek" class="w-full border rounded p-3" placeholder="Nomor Rekening">
                <input type="text" name="nama_rek" class="w-full border rounded p-3" placeholder="Nama Rekening">

                <label>Scan Buku Tabungan</label>
                <input type="file" name="buku_tabungan">

                <button type="button" onclick="submitStep3()" class="w-full bg-emerald-600 text-white py-3 rounded">
                    Kirim Verifikasi
                </button>

                <button type="button" class="w-full bg-slate-200 py-2 rounded mt-2" @click="step--">Balik</button>
            </form>
        </div>

    </div>

</div>

{{-- SCRIPT --}}
<script>
function submitStep1(){
    let fd = new FormData(document.getElementById('form-step1'));
    axios.post("{{ route('kyc.step1') }}", fd).then(() => {
        document.getElementById('progress').style.width = "66%";
        document.querySelector('[x-data]').__x.$data.step = 2;
    });
}
function submitStep2(){
    let fd = new FormData(document.getElementById('form-step2'));
    axios.post("{{ route('kyc.step2') }}", fd).then(() => {
        document.getElementById('progress').style.width = "100%";
        document.querySelector('[x-data]').__x.$data.step = 3;
    });
}
function submitStep3(){
    let fd = new FormData(document.getElementById('form-step3'));
    axios.post("{{ route('kyc.step3') }}", fd).then(() => {
        axios.post("{{ route('kyc.submit') }}").then(() => {
            window.location.href = "{{ route('dashboard.index') }}";
        });
    });
}
</script>

@endsection
