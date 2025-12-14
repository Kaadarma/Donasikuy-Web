@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="max-w-5xl mx-auto px-4">

            <div class="md:flex md:items-start md:gap-10">
                {{-- KIRI: Info Program (gambar + judul) --}}
                <div class="max-w-3xl mx-auto px-4">
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5 md:p-6">

                        {{-- HEADER PROGRAM DI DALAM CARD --}}
                        <div class="flex items-start gap-3 mb-5">
                            <div class="w-20 h-20 rounded-lg overflow-hidden bg-slate-200 flex-shrink-0 shadow">
                                <img src="{{ $program['image'] ?? 'https://via.placeholder.com/300x300' }}"
                                    alt="{{ $program['title'] ?? 'Program' }}" class="w-full h-full object-cover">
                            </div>

                            <div class="flex-1">
                                <h1 class="text-lg md:text-xl font-semibold text-slate-900 leading-snug">
                                    {{ $program['title'] }}
                                </h1>

                                <div class="mt-2 flex items-center gap-2 text-sm text-slate-600">
                                    <span>{{ $program['organizer'] ?? 'DonasiKuy' }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-500"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2a10 10 0 100 20 10 10 0 100-20zm-1 12l-3-3 1.4-1.4L11 11.2l4.6-4.6L17 8l-6 6z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-200 mb-4">

                        {{-- TAMPILKAN ERROR VALIDASI --}}
                        @if ($errors->any())
                            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl px-3 py-2">
                                <p class="font-semibold mb-1">Ups, ada yang perlu kamu cek lagi:</p>
                                <ul class="list-disc ml-4 space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- FORM TUNGGAL DI DALAM CARD --}}
                        <form id="donasiForm" action="{{ route('donasi.proses', $program['slug']) }}" method="POST"
                            class="space-y-4">
                            @csrf

                            {{-- Hidden input utama --}}
                            {{-- nominal WAJIB integer untuk lolos rule "integer|min:10000" --}}
                            <input type="hidden" name="nominal"
                                value="{{ old('nominal', (int) str_replace('.', '', $nominal)) }}">

                            <input type="hidden" name="payment_method" id="paymentMethodInput"
                                value="{{ old('payment_method', 'qris') }}">

                            <input type="hidden" name="voucher_code" id="voucherCodeInput"
                                value="{{ old('voucher_code') }}">

                            {{-- Ringkasan --}}
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h2 class="text-sm font-semibold text-slate-900">Ringkasan Donasi</h2>
                                    <p class="mt-0.5 text-xs text-slate-600">Nominal donasi Anda</p>
                                </div>

                                <div class="text-right">
                                    <a href="{{ route('donasi.nominal', ['slug' => $program['slug']]) }}"
                                        class="px-2.5 py-1 rounded-md text-xs font-medium
                                                bg-emerald-50 border border-emerald-200 text-emerald-600
                                                 hover:bg-emerald-100 transition">
                                        Ubah
                                    </a>


                                    <p class="mt-1 text-sm font-bold text-slate-900">
                                        Rp
                                        {{ number_format((int) str_replace('.', '', old('nominal', $nominal)), 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <hr class="border-slate-200">

                            {{-- Metode Pembayaran --}}
                            <div class="flex items-center justify-between text-sm py-2 cursor-pointer select-none"
                                id="openPaymentModal">
                                <span class="font-medium text-slate-800">Metode Pembayaran</span>

                                <div class="flex items-center gap-2">
                                    <div id="selectedPaymentDisplay"
                                        class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-slate-50 border border-slate-200">
                                        {{-- default QRIS / tampilan akan diupdate JS saat pilih metode --}}
                                        <img src="{{ asset('images/qris.png') }}" alt="QRIS" class="h-4">
                                        <span class="text-[11px] font-semibold text-slate-800">
                                            {{ strtoupper(old('payment_method', 'QRIS')) }}
                                        </span>
                                    </div>

                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>

                            <hr class="border-slate-200">

                            {{-- Voucher --}}
                            <div class="flex items-center justify-between py-2 mb-1">
                                <div class="flex items-center gap-2 text-sm text-slate-700">
                                    🏷️ <span>Voucher</span>
                                </div>

                                <button type="button" id="openVoucherModal"
                                    class="text-xs text-emerald-600 font-medium hover:text-emerald-700">
                                    Masukkan Kode &rsaquo;
                                </button>
                            </div>

                            {{-- Info Donatur --}}
                            <div class="mt-3">
                                <h3 class="text-sm font-semibold text-slate-900 mb-1">Info Donatur</h3>
                                <p class="text-[11px] text-slate-600 mb-3">
                                    <a href="{{ route('login') }}"
                                        class="text-emerald-600 font-medium hover:underline">Masuk Akun</a>
                                    <span> atau isi data berikut</span>
                                </p>

                                {{-- Nama --}}
                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-slate-700 mb-1">
                                        Nama Lengkap *
                                    </label>
                                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs
                  focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                                </div>

                                {{-- Telepon --}}
                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-slate-700 mb-1">
                                        No Telepon *
                                    </label>
                                    <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs
                  focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-slate-700 mb-1">
                                        Email (Opsional)
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs
                  focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                                </div>


                                {{-- Toggle Anonim --}}
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-medium text-slate-800">Sembunyikan nama saya</span>

                                    <input type="hidden" name="is_anonymous" value="0">

                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_anonymous" value="1" class="sr-only peer"
                                            @checked(old('is_anonymous') == 1)>
                                        <div
                                            class="w-9 h-5 bg-slate-300 rounded-full peer-checked:bg-emerald-500 transition
            after:content-[''] after:absolute after:top-[2px] after:left-[2px]
            after:h-4 after:w-4 after:bg-white after:rounded-full after:shadow
            peer-checked:after:translate-x-4 after:transition-all">
                                        </div>
                                    </label>
                                </div>


                                {{-- Toggle Pesan --}}
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-medium text-slate-800">Beri Pesan (Opsional)</span>

                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="toggleMessage" class="sr-only peer"
                                            @checked(old('pesan'))>
                                        <div
                                            class="w-9 h-5 bg-slate-300 rounded-full peer-checked:bg-emerald-500 transition
                                   after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                   after:h-4 after:w-4 after:bg-white after:rounded-full after:shadow
                                   peer-checked:after:translate-x-4 after:transition-all">
                                        </div>
                                    </label>
                                </div>

                                <div id="messageBox" class="{{ old('pesan') ? '' : 'hidden' }} mb-3">
                                    <textarea name="pesan" rows="3"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs
                                     focus:ring-1 focus:ring-emerald-500 resize-none"
                                        placeholder="Tulis pesan dukungan...">{{ old('pesan') }}</textarea>
                                </div>
                            </div>

                            <p class="text-[10px] text-slate-500 leading-relaxed">
                                Dengan melanjutkan donasi, saya setuju
                                <a href="#" class="underline">Syarat & Ketentuan</a>.
                            </p>

                            {{-- Tombol Donasi -> buka modal konfirmasi --}}
                            <button type="button" onclick="validateAndOpenConfirm()"
                                class="w-full py-3.5 rounded-2xl text-sm font-semibold
               bg-gradient-to-r from-emerald-600 via-green-500 to-lime-400
               text-white shadow-lg shadow-emerald-500/30
               hover:brightness-105 active:scale-[0.99] transition">
                                Donasi Sekarang
                            </button>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL KONFIRMASI DONASI --}}
    <div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full mx-4 p-8 relative text-center">

            {{-- Tombol X --}}
            <button type="button"
                onclick="document.getElementById('confirmModal').classList.add('hidden');
                             document.getElementById('confirmModal').classList.remove('flex');"
                class="absolute top-3 right-4 text-slate-400 hover:text-slate-600 text-xl leading-none">
                &times;
            </button>

            {{-- Ilustrasi --}}
            <div class="w-40 h-40 mx-auto mb-6">
                <img src="{{ asset('images/pay.jpg') }}" alt="Konfirmasi donasi" class="w-full h-full object-contain">
            </div>

            <h2 class="text-2xl font-semibold text-slate-900 mb-3">
                Konfirmasi
            </h2>

            <p class="text-sm text-slate-700 leading-relaxed mb-6">
                Donasi Anda senilai
                <span class="font-bold text-slate-900">
                    Rp {{ number_format((int) str_replace('.', '', old('nominal', $nominal)), 0, ',', '.') }}
                </span>
                akan disalurkan ke campaign
                <span class="font-semibold text-amber-500">
                    {{ $program['title'] }}
                </span>
            </p>

            <div class="flex items-center justify-center gap-3 mt-2">
                <button type="button"
                    onclick="document.getElementById('confirmModal').classList.add('hidden');
                                 document.getElementById('confirmModal').classList.remove('flex');"
                    class="px-5 py-2.5 rounded-xl text-sm font-medium
                               bg-slate-200 text-slate-800 hover:bg-slate-300 transition">
                    Kembali
                </button>

                <button type="button" onclick="document.getElementById('donasiForm').submit();"
                    class="px-6 py-2.5 rounded-xl text-sm font-semibold
                               bg-gradient-to-r from-emerald-600 via-green-500 to-lime-400
                               text-white shadow-md hover:brightness-105 active:scale-[0.98] transition">
                    OK, Kirim
                </button>
            </div>
        </div>
    </div>

    <div id="paymentModal" class="fixed inset-0 z-50 hidden items-start justify-center bg-black/40">
        <div class="mt-10 w-full max-w-md rounded-2xl bg-white shadow-xl max-h-[80vh] overflow-hidden flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
                <h2 class="text-sm font-semibold text-slate-900">Metode Pembayaran</h2>
                <button type="button" id="closePaymentModal"
                    class="text-slate-500 hover:text-slate-700 text-lg leading-none">
                    &times;
                </button>
            </div>

            <div class="flex-1 overflow-y-auto">
                {{-- E-Wallet --}}
                <div class="px-4 pt-3 pb-1 text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                    E-Wallet
                </div>

                <div class="divide-y divide-slate-100 text-sm">
                    {{-- DANA --}}
                    <button type="button" class="w-full flex items-center justify-between px-4 py-2.5 hover:bg-slate-50"
                        data-method-label="DANA" data-method-value="dana"
                        data-method-logo="{{ asset('images/dana.png') }}">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/dana.png') }}" class="h-5" alt="DANA">
                            <span>DANA</span>
                        </div>
                        <span class="text-slate-400">&rsaquo;</span>
                    </button>

                    {{-- Gopay --}}
                    <button type="button" class="w-full flex items-center justify-between px-4 py-2.5 hover:bg-slate-50"
                        data-method-label="Gopay" data-method-value="gopay"
                        data-method-logo="{{ asset('images/gopay.png') }}">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/gopay.png') }}" class="h-5" alt="Gopay">
                            <span>Gopay</span>
                        </div>
                        <span class="text-slate-400">&rsaquo;</span>
                    </button>

                    {{-- OVO --}}
                    <button type="button" class="w-full flex items-center justify-between px-4 py-2.5 hover:bg-slate-50"
                        data-method-label="OVO" data-method-value="ovo"
                        data-method-logo="{{ asset('images/ovo.png') }}">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/ovo.png') }}" class="h-5" alt="OVO">
                            <span>OVO</span>
                        </div>
                        <span class="text-slate-400">&rsaquo;</span>
                    </button>

                    {{-- QRIS --}}
                    <button type="button" class="w-full flex items-center justify-between px-4 py-2.5 hover:bg-slate-50"
                        data-method-label="QRIS" data-method-value="qris"
                        data-method-logo="{{ asset('images/qris.png') }}">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/qris.png') }}" class="h-5" alt="QRIS">
                            <span>QRIS</span>
                        </div>
                        <span class="text-slate-400">&rsaquo;</span>
                    </button>

                    {{-- ShopeePay --}}
                    <button type="button" class="w-full flex items-center justify-between px-4 py-2.5 hover:bg-slate-50"
                        data-method-label="ShopeePay" data-method-value="shopeepay"
                        data-method-logo="{{ asset('images/shopeepay.png') }}">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/shopeepay.png') }}" class="h-5" alt="ShopeePay">
                            <span>ShopeePay</span>
                        </div>
                        <span class="text-slate-400">&rsaquo;</span>
                    </button>
                </div>

                {{-- Virtual Account --}}
                <div class="px-4 pt-4 pb-1 text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                    Virtual Account
                </div>

                <div class="divide-y divide-slate-100 text-sm mb-2">
                    {{-- BCA VA --}}
                    <button type="button" class="w-full flex items-center justify-between px-4 py-2.5 hover:bg-slate-50"
                        data-method-label="BCA Virtual Account" data-method-value="bca_va"
                        data-method-logo="{{ asset('images/bca.png') }}">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/bca.png') }}" class="h-5" alt="BCA">
                            <span>BCA</span>
                        </div>
                        <span class="text-slate-400">&rsaquo;</span>
                    </button>

                    {{-- BNI VA --}}
                    <button type="button" class="w-full flex items-center justify-between px-4 py-2.5 hover:bg-slate-50"
                        data-method-label="BNI Virtual Account" data-method-value="bni_va"
                        data-method-logo="{{ asset('images/bni.png') }}">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/bni.png') }}" class="h-5" alt="BNI">
                            <span>BNI</span>
                        </div>
                        <span class="text-slate-400">&rsaquo;</span>
                    </button>

                    {{-- BRI VA --}}
                    <button type="button" class="w-full flex items-center justify-between px-4 py-2.5 hover:bg-slate-50"
                        data-method-label="BRI Virtual Account" data-method-value="bri_va"
                        data-method-logo="{{ asset('images/bri.png') }}">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/bri.png') }}" class="h-5" alt="BRI">
                            <span>BRI</span>
                        </div>
                        <span class="text-slate-400">&rsaquo;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL VOUCHER --}}
    <div id="voucherModal" class="fixed inset-0 z-50 hidden items-start justify-center bg-black/40">
        <div class="mt-16 w-full max-w-md rounded-2xl bg-white shadow-xl overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
                <h2 class="text-sm font-semibold text-slate-900">Voucher</h2>
                <button type="button" id="closeVoucherModal"
                    class="text-slate-500 hover:text-slate-700 text-lg leading-none">
                    &times;
                </button>
            </div>

            {{-- Isi --}}
            <div class="px-4 py-4 space-y-3 text-sm">
                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1">
                        Kode Voucher
                    </label>
                    <input type="text" id="voucherInput"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Masukkan kode voucher" value="{{ old('voucher_code') }}">
                </div>

                <div class="text-[11px] text-slate-600 leading-relaxed">
                    <p>- Jika nominal voucher lebih kecil dari nominal donasi, maka nominal voucher yang akan diambil.</p>
                    <p>- Jika nominal voucher lebih besar dari nominal donasi, maka pendonor hanya perlu membayar sisanya.
                    </p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-4 py-3 border-t border-slate-200 flex justify-end bg-slate-50">
                <button type="button" id="applyVoucherBtn"
                    class="px-4 py-2 rounded-lg text-xs font-semibold
                               bg-emerald-600 text-white hover:bg-emerald-700 transition">
                    Gunakan
                </button>
            </div>
        </div>
    </div>

    <div id="toastContainer" class="fixed top-4 right-4 z-[9999] space-y-2"></div>

    <script>
        // Toggle pesan
        document.getElementById('toggleMessage')?.addEventListener('change', function() {
            document.getElementById('messageBox')?.classList.toggle('hidden');
        });

        // ----- Modal Metode Pembayaran -----
        const openBtn = document.getElementById('openPaymentModal');
        const closeBtn = document.getElementById('closePaymentModal');
        const modal = document.getElementById('paymentModal');
        const inputEl = document.getElementById('paymentMethodInput');
        const displayEl = document.getElementById('selectedPaymentDisplay');

        if (openBtn && modal && closeBtn) {
            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });

            closeBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });

            document.querySelectorAll('#paymentModal [data-method-value]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const label = btn.getAttribute('data-method-label');
                    const value = btn.getAttribute('data-method-value');
                    const logo = btn.getAttribute('data-method-logo');

                    inputEl.value = value;
                    displayEl.innerHTML = `
                        <img src="${logo}" class="h-4" alt="${label}">
                        <span class="text-[11px] font-semibold text-slate-800">${label}</span>
                    `;

                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            });
        }

        // ----- Modal Voucher -----
        const openVoucherBtn = document.getElementById('openVoucherModal');
        const closeVoucherBtn = document.getElementById('closeVoucherModal');
        const voucherModal = document.getElementById('voucherModal');
        const voucherInput = document.getElementById('voucherInput');
        const voucherHidden = document.getElementById('voucherCodeInput');
        const applyVoucherBtn = document.getElementById('applyVoucherBtn');

        if (openVoucherBtn && voucherModal) {
            openVoucherBtn.addEventListener('click', () => {
                voucherModal.classList.remove('hidden');
                voucherModal.classList.add('flex');
                setTimeout(() => voucherInput.focus(), 100);
            });

            closeVoucherBtn.addEventListener('click', () => {
                voucherModal.classList.add('hidden');
                voucherModal.classList.remove('flex');
            });

            voucherModal.addEventListener('click', (e) => {
                if (e.target === voucherModal) {
                    voucherModal.classList.add('hidden');
                    voucherModal.classList.remove('flex');
                }
            });

            applyVoucherBtn.addEventListener('click', () => {
                const code = voucherInput.value.trim();

                if (code === "") {
                    showToast('error', 'Masukkan kode voucher');
                    return;
                }

                voucherHidden.value = code;
                showToast('success', 'Voucher berhasil diterapkan');

                voucherModal.classList.add('hidden');
                voucherModal.classList.remove('flex');
            });
        }

        function showToast(type, message) {
            const container = document.getElementById('toastContainer');

            let icon = '';
            let borderColor = '';
            let barColor = '';

            if (type === 'error') {
                icon = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v3m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>`;
                borderColor = 'border-red-500';
                barColor = 'bg-red-500';
            } else {
                icon = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4.5 12.75l6 6 9-13.5" />
                </svg>`;
                borderColor = 'border-emerald-500';
                barColor = 'bg-emerald-500';
            }

            const toast = document.createElement('div');
            toast.className = `toast-show flex flex-col gap-2 bg-white border-l-4 ${borderColor}
                               shadow-lg rounded-md px-4 py-3 w-72`;

            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="text-xl">${icon}</div>
                    <div class="flex-1">
                        <p class="font-semibold text-slate-800">${type === 'error' ? 'Error' : 'Success'}</p>
                        <p class="text-sm text-slate-600">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()"
                            class="text-slate-500 hover:text-slate-800 leading-none">&times;</button>
                </div>
                <div class="progress-bar ${barColor} rounded-full"></div>
            `;

            container.appendChild(toast);

            setTimeout(() => toast.remove(), 3000);
        }

        function validateAndOpenConfirm() {
            const nama = document.getElementById('nama');
            const telepon = document.getElementById('telepon');
            const confirmModal = document.getElementById('confirmModal');

            let isValid = true;

            // reset state border dulu
            [nama, telepon].forEach(el => {
                el.classList.remove('border-red-500', 'focus:ring-red-500');
                el.classList.add('border-slate-300');
            });

            // cek nama wajib
            if (!nama.value.trim()) {
                isValid = false;
                nama.classList.remove('border-slate-300');
                nama.classList.add('border-red-500', 'focus:ring-red-500');
            }

            // cek telepon wajib + minimal 8 digit angka
            const telpVal = telepon.value.replace(/\D/g, ''); // buang non-angka
            if (!telpVal || telpVal.length < 8) {
                isValid = false;
                telepon.classList.remove('border-slate-300');
                telepon.classList.add('border-red-500', 'focus:ring-red-500');
            }

            if (!isValid) {
                if (typeof showToast === 'function') {
                    showToast('error', 'Nama dan nomor telepon wajib diisi dengan benar.');
                } else {
                    alert('Nama dan nomor telepon wajib diisi dengan benar.');
                }
                return;
            }

            // kalau lolos validasi -> buka modal konfirmasi
            confirmModal.classList.remove('hidden');
            confirmModal.classList.add('flex');
        }
    </script>
@endsection
