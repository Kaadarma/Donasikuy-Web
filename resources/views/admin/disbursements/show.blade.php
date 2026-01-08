@extends('layouts.admin-dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Detail Pencairan Dana</h1>
        <p class="text-gray-500 mt-1">Tinjau detail permintaan pencairan dana dari campaign.</p>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
            <p class="font-semibold mb-2">Ada input yang belum valid:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Detail Permintaan --}}
        <div class="lg:col-span-1 rounded-2xl bg-white shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Detail Permintaan</h2>
                    <p class="text-gray-500 text-sm mt-1">Informasi pemohon & campaign</p>
                </div>

                {{-- Status badge --}}
                @php
                    $status = $disbursement->status;
                    $badge = match($status) {
                        \App\Models\DisbursementRequest::STATUS_REQUESTED => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        \App\Models\DisbursementRequest::STATUS_APPROVED  => 'bg-blue-50 text-blue-700 border-blue-200',
                        \App\Models\DisbursementRequest::STATUS_REJECTED  => 'bg-red-50 text-red-700 border-red-200',
                        \App\Models\DisbursementRequest::STATUS_PAID      => 'bg-green-50 text-green-700 border-green-200',
                        default => 'bg-gray-50 text-gray-700 border-gray-200',
                    };
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $badge }}">
                    {{ strtoupper($status) }}
                </span>
            </div>

            <div class="mt-6 space-y-4">
                <div class="border-t border-gray-100 pt-4">
                    <p class="text-xs text-gray-500">Campaign</p>
                    <p class="font-semibold text-gray-900">{{ $disbursement->program->title ?? '-' }}</p>
                    @if(!empty($disbursement->program->slug))
                        <p class="text-sm text-gray-500">{{ $disbursement->program->slug }}</p>
                    @endif
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <p class="text-xs text-gray-500">Pemohon</p>
                    <p class="font-semibold text-gray-900">{{ $disbursement->user->name ?? '-' }}</p>
                    <p class="text-sm text-gray-500">{{ $disbursement->user->email ?? '-' }}</p>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <p class="text-xs text-gray-500">Jumlah Diminta</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($disbursement->amount) }}</p>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <p class="text-xs text-gray-500">Tanggal Pengajuan</p>
                    <p class="font-semibold text-gray-900">
                        {{ optional($disbursement->created_at)->format('d M Y') ?? '-' }}
                    </p>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <p class="text-xs text-gray-500">Catatan Pemohon</p>
                    <p class="text-gray-900">{{ $disbursement->note ?: '-' }}</p>
                </div>

                @if($disbursement->paid_at)
                <div class="border-t border-gray-100 pt-4">
                    <p class="text-xs text-gray-500">Tanggal Dibayar</p>
                    <p class="font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($disbursement->paid_at)->format('d M Y') }}
                    </p>
                </div>
                @endif
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.disbursements.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-gray-900">
                    ← Kembali ke daftar
                </a>
            </div>
        </div>

        {{-- RIGHT: Rekening + Aksi --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Rekening Tujuan --}}
            <div class="rounded-2xl bg-white shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900">Rekening Tujuan</h2>
                <p class="text-gray-500 text-sm mt-1">Pastikan data rekening sesuai sebelum menyetujui.</p>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-xl border border-gray-100 p-4">
                        <p class="text-xs text-gray-500">Bank</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $disbursement->bank_name ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-100 p-4">
                        <p class="text-xs text-gray-500">No Rekening</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $disbursement->account_number ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-100 p-4">
                        <p class="text-xs text-gray-500">Atas Nama</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $disbursement->account_name ?? '-' }}</p>
                    </div>
                </div>

                @if(!empty($disbursement->admin_note))
                    <div class="mt-4 rounded-xl border border-gray-100 bg-gray-50 p-4">
                        <p class="text-xs text-gray-500">Catatan Admin</p>
                        <p class="text-gray-900 mt-1">{{ $disbursement->admin_note }}</p>
                    </div>
                @endif
            </div>

            {{-- Aksi Admin --}}
            <div class="rounded-2xl bg-white shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900">Aksi Admin</h2>
                <p class="text-gray-500 text-sm mt-1">Setujui / tolak / tandai sudah dibayar (wajib bukti transfer).</p>

                {{-- Status: REQUESTED --}}
                @if($disbursement->status === \App\Models\DisbursementRequest::STATUS_REQUESTED)
                    <div class="mt-6 flex flex-col md:flex-row gap-3">
                        <form method="POST" action="{{ route('admin.disbursements.approve', $disbursement->id) }}">
                            @csrf
                            <button type="submit"
                                class="w-full md:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
                                Approve
                            </button>
                        </form>

                        <button type="button"
                            onclick="document.getElementById('rejectBox').classList.toggle('hidden')"
                            class="w-full md:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700">
                            Reject
                        </button>
                    </div>

                    {{-- Reject form (toggle) --}}
                    <div id="rejectBox" class="hidden mt-5 rounded-xl border border-red-100 bg-red-50 p-4">
                        <form method="POST" action="{{ route('admin.disbursements.reject', $disbursement->id) }}">
                            @csrf
                            <label class="text-sm font-semibold text-red-800">Alasan penolakan</label>
                            <textarea name="note" required rows="3"
                                class="mt-2 w-full rounded-xl border border-red-200 px-3 py-2 focus:outline-none"
                                placeholder="Contoh: Dokumen kurang lengkap / data rekening tidak valid"></textarea>

                            <div class="mt-3 flex gap-3">
                                <button type="submit"
                                    class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700">
                                    Konfirmasi Reject
                                </button>

                                <button type="button"
                                    onclick="document.getElementById('rejectBox').classList.add('hidden')"
                                    class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-white border border-red-200 text-red-700 font-semibold hover:bg-red-100">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>

                {{-- Status: APPROVED --}}
                @elseif($disbursement->status === \App\Models\DisbursementRequest::STATUS_APPROVED)
                    <div class="mt-6">
                        <button type="button"
                            onclick="document.getElementById('paidBox').classList.toggle('hidden')"
                            class="w-full md:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                            Tandai Sudah Dibayar
                        </button>

                        <p class="text-sm text-gray-500 mt-4">
                            Setelah transfer dilakukan, upload <b>bukti transfer</b> lalu konfirmasi agar status menjadi <b>PAID</b>.
                        </p>

                        {{-- Paid form (toggle) --}}
                        <div id="paidBox" class="hidden mt-5 rounded-xl border border-blue-100 bg-blue-50 p-4">
                            <form method="POST"
                                  action="{{ route('admin.disbursements.paid', $disbursement->id) }}"
                                  enctype="multipart/form-data">
                                @csrf

                                <label class="text-sm font-semibold text-blue-800">Bukti Transfer (wajib)</label>
                                <input type="file" name="payment_proof" required
                                    accept="image/*"
                                    class="mt-2 block w-full text-sm">

                                <label class="text-sm font-semibold text-blue-800 mt-4">Catatan Admin (opsional)</label>
                                <textarea name="admin_note" rows="3"
                                    class="mt-2 w-full rounded-xl border border-blue-200 px-3 py-2 focus:outline-none"
                                    placeholder="Contoh: Transfer via BNI, ref: 123456"></textarea>

                                <div class="mt-4 flex gap-3">
                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                                        Konfirmasi Dana Sudah Cair
                                    </button>

                                    <button type="button"
                                        onclick="document.getElementById('paidBox').classList.add('hidden')"
                                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-white border border-blue-200 text-blue-700 font-semibold hover:bg-blue-100">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                {{-- Status: PAID --}}
                @elseif($disbursement->status === \App\Models\DisbursementRequest::STATUS_PAID)
                    <div class="mt-6 rounded-xl border border-green-100 bg-green-50 p-4">
                        <p class="text-sm font-semibold text-green-800">Status PAID (Dana sudah dibayar)</p>
                        <p class="text-sm text-green-700 mt-1">
                            Dicatat pada: <b>{{ \Carbon\Carbon::parse($disbursement->paid_at)->format('d M Y') }}</b>
                        </p>

                        @if(!empty($disbursement->payment_proof))
                            <div class="mt-4">
                                <p class="text-xs text-green-700 font-semibold">Bukti Transfer</p>
                                <img
                                    src="{{ asset('storage/'.$disbursement->payment_proof) }}"
                                    alt="Bukti Transfer"
                                    class="mt-2 max-w-md w-full rounded-xl border border-green-200"
                                >
                            </div>
                        @else
                            <p class="text-sm text-green-700 mt-3">
                                Bukti transfer tidak ditemukan.
                            </p>
                        @endif

                        @if(!empty($disbursement->admin_note))
                            <p class="text-sm text-gray-700 mt-4">
                                <b>Catatan Admin:</b> {{ $disbursement->admin_note }}
                            </p>
                        @endif
                    </div>

                {{-- Status: REJECTED or others --}}
                @else
                    <div class="mt-6 rounded-xl border border-gray-100 bg-gray-50 p-4 text-gray-700">
                        Permintaan ini sudah diproses. Tidak ada aksi lanjutan.
                    </div>
                @endif
            </div>

            {{-- Optional: Items --}}
            @if($disbursement->items && $disbursement->items->count())
                <div class="rounded-2xl bg-white shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900">Rincian Disbursement</h2>
                    <p class="text-gray-500 text-sm mt-1">Daftar item/komponen jika ada.</p>

                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500">
                                    <th class="py-2">Deskripsi</th>
                                    <th class="py-2">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($disbursement->items as $it)
                                    <tr>
                                        <td class="py-3 text-gray-900">{{ $it->description ?? '-' }}</td>
                                        <td class="py-3 text-gray-900">Rp {{ number_format($it->amount ?? 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
