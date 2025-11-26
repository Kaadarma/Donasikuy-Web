<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DonasiController extends Controller
{
    /**
     * Halaman pilih nominal donasi
     */
    public function nominal(Request $request)
    {
        // data program dummy
        $program = (object) [
            'title' => 'Bantu Anak-anak Kampung Cimenyan Tetap Bisa Belajar',
            'organizer' => 'odesaindonesia',
            'image_url' => 'https://via.placeholder.com/300x300',
        ];

        // kalau balik dari data diri, kita bisa isi lagi nilai nominal dari query
        $nominal = $request->input('nominal', 50000); // default 50k

        return view('donasi.nominal', compact('program', 'nominal'));
    }

    /**
     * Halaman input data diri donatur
     */
    public function dataDiri(Request $request)
    {
        $program = (object) [
            'title' => 'Bantu Anak-anak Kampung Cimenyan Tetap Bisa Belajar',
            'organizer' => 'odesaindonesia',
            'image_url' => 'https://via.placeholder.com/300x300',
        ];

        // AMBIL nominal dari halaman sebelumnya (GET / POST)
        $nominal = $request->input('nominal', 50000);

        return view('donasi.data-diri', compact('program', 'nominal'));
    }

    /**
     * Proses submit donasi dari form data diri
     */
    public function prosesDonasi(Request $request)
    {
        // Validasi data dasar
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:30',
            'email' => 'nullable|email',
            // kalau ada field lain silakan tambahkan di sini
        ]);

        // Ambil data lain yang mungkin dikirim
        $nominal = $request->input('nominal', 50000);
        $paymentMethod = $request->input('payment_method'); // dari modal metode pembayaran
        $voucherCode = $request->input('voucher_code');   // dari modal voucher

        // TODO: simpan ke database, hitung diskon voucher, buat transaksi, dsb.
        // Contoh pseudo:
        // Donation::create([...]);

        // Siapkan data untuk halaman sukses
        $program = (object) [
            'title' => 'Bantu Anak-anak Kampung Cimenyan Tetap Bisa Belajar',
            'organizer' => 'odesaindonesia',
            'image_url' => 'https://via.placeholder.com/300x300',
        ];

        // simpan di session supaya bisa diambil di halaman sukses
        return redirect()
            ->route('donasi.sukses')
            ->with([
                'program' => $program,
                'nominal' => $nominal,
                'paymentMethod' => $paymentMethod,
                'voucherCode' => $voucherCode,
                'donatur' => $validated,
            ]);
    }

    /**
     * Halaman sukses / terima kasih setelah donasi
     */
    public function sukses(Request $request)
    {
        // ambil dari session
        $program = session('program');
        $nominal = session('nominal', 50000);
        $paymentMethod = session('paymentMethod');
        $voucherCode = session('voucherCode');
        $donatur = session('donatur');

        // fallback kalau langsung akses /donasi/sukses tanpa prosesDonasi
        if (! $program) {
            $program = (object) [
                'title' => 'Bantu Anak-anak Kampung Cimenyan Tetap Bisa Belajar',
                'organizer' => 'odesaindonesia',
                'image_url' => 'https://via.placeholder.com/300x300',
            ];
        }

        return view('donasi.sukses', compact(
            'program',
            'nominal',
            'paymentMethod',
            'voucherCode',
            'donatur'
        ));
    }
}
