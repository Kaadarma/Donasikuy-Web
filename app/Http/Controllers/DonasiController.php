<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DonasiController extends Controller
{
    /**
     * Ambil program dari ProgramController berdasarkan slug/id
     */
    private function getProgram(string $slug): array
    {
        $pc = app(ProgramController::class);

        $program = $pc->findProgram($slug); // method ini ada di ProgramController

        abort_unless($program, 404);

        return $program; // array
    }

    /**
     * Halaman pilih nominal donasi
     */
    public function nominal(Request $request, string $slug)
    {
        $program = $this->getProgram($slug);

        $nominal = $request->input('nominal', 50000);

        return view('donasi.nominal', compact('program', 'nominal'));
    }

    /**
     * Halaman input data diri donatur
     */
    public function dataDiri(Request $request, string $slug)
    {
        $program = $this->getProgram($slug);

        $nominal = $request->input('nominal', 50000);

        return view('donasi.data-diri', compact('program', 'nominal'));
    }

    /**
     * (OPSIONAL) Flow lama tanpa Midtrans
     * Kalau nanti sudah full pakai Midtrans, ini boleh kamu hapus.
     */
    public function prosesDonasi(Request $request, string $slug)
    {
        $program = $this->getProgram($slug);

        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'telepon' => 'required|string|max:30',
            'email'   => 'nullable|email',
        ]);

        $nominal       = $request->input('nominal', 50000);
        $paymentMethod = $request->input('payment_method');
        $voucherCode   = $request->input('voucher_code');

        return redirect()
            ->route('donasi.sukses')
            ->with([
                'program'       => $program,   // array
                'nominal'       => $nominal,
                'paymentMethod' => $paymentMethod,
                'voucherCode'   => $voucherCode,
                'donatur'       => $validated,
            ]);
    }

    /**
     * Halaman sukses / terima kasih (dipakai untuk Finish Redirect Midtrans)
     */
    public function sukses()
    {
        return view('donasi.sukses');
    }

    public function proses(Request $request, string $slug)
    {
        // Ambil data program sebagai array
        $program = $this->getProgram($slug);

        // 1. Validasi input dari form data diri
        $data = $request->validate([
            'nominal'        => ['required', 'integer', 'min:10000'],
            'payment_method' => ['required', 'string'], // qris, dana, gopay, dll
            'voucher_code'   => ['nullable', 'string'],
            'nama'           => ['required', 'string', 'max:100'],
            'telepon'        => ['required', 'string', 'min:8'],
            'email'          => ['nullable', 'email'],
            'is_anonymous'   => ['nullable', 'boolean'],
            'pesan'          => ['nullable', 'string', 'max:500'],
        ]);

        // 2. Generate order_id unik
        $orderId = 'DON-' . ($program['id'] ?? 'X') . '-' . Str::random(8);

        // 3. Set config Midtrans
        $this->setMidtransConfig();

        // 4. Data transaksi SNAP Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $data['nominal'],
            ],
            'customer_details' => [
                'first_name' => $data['nama'],
                'email'      => $data['email'] ?? null,
                'phone'      => $data['telepon'],
            ],
            'item_details' => [
                [
                    'id'       => $program['id'] ?? 0,
                    'price'    => $data['nominal'],
                    'quantity' => 1,
                    'name'     => substr($program['title'] ?? 'Donasi', 0, 50),
                ],
            ],
            // optional: kirim info tambahan
            'custom_field1' => $program['id'] ?? null,
            'custom_field2' => $program['title'] ?? null,
            'custom_field3' => $data['payment_method'],
        ];

        // 5. Buat transaksi SNAP
        $snap        = new \Midtrans\Snap();
        $transaction = $snap->createTransaction($params);

        // 6. Redirect ke halaman pembayaran Midtrans
        return redirect()->away($transaction->redirect_url);
    }

    /**
     * Set konfigurasi Midtrans dari config/midtrans.php
     */
    protected function setMidtransConfig(): void
    {
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds        = config('midtrans.is_3ds');
    }
}
