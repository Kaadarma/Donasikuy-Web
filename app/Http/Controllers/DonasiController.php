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
     * PROSES DONASI (PAKAI MIDTRANS SNAP TOKEN)
     */
 public function proses(Request $request, string $slug)
{
    $program = $this->getProgram($slug);

    $data = $request->validate([
        'nominal'        => ['required', 'integer', 'min:10000'],
        'payment_method' => ['required', 'string'],
        'voucher_code'   => ['nullable', 'string'],
        'nama'           => ['required', 'string', 'max:100'],
        'telepon'        => ['required', 'string', 'min:8'],
        'email'          => ['nullable', 'email'],
        'is_anonymous'   => ['nullable', 'boolean'],
        'pesan'          => ['nullable', 'string', 'max:500'],
    ]);


    $isAnonymous = $request->boolean('is_anonymous');


    $displayName = $isAnonymous ? 'Aku siapa?' : $data['nama'];

    $orderId = 'DON-' . ($program['id'] ?? 'X') . '-' . Str::random(8);

    $this->setMidtransConfig();

    $params = [
        'transaction_details' => [
            'order_id'     => $orderId,
            'gross_amount' => $data['nominal'],
        ],
        'customer_details' => [
            // kalau mau Midtrans juga lihat nama anonim:
            'first_name' => $displayName,
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
        'custom_field1' => $program['id'] ?? null,
        'custom_field2' => $program['title'] ?? null,
        'custom_field3' => $data['payment_method'],
    ];

    $snapToken = \Midtrans\Snap::getSnapToken($params);

    return view('donasi.bayar', [
        'program'      => $program,
        'data'         => $data,
        'orderId'      => $orderId,
        'snapToken'    => $snapToken,
        'displayName'  => $displayName,   
        'isAnonymous'  => $isAnonymous,   
    ]);
}


    public function prosesDonasi(Request $request, string $slug)
    {
        $program = $this->getProgram($slug);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:30',
            'email' => 'nullable|email',
        ]);

        $nominal = $request->input('nominal', 50000);
        $paymentMethod = $request->input('payment_method');
        $voucherCode = $request->input('voucher_code');

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


    public function sukses()
    {
        return view('donasi.sukses');
    }

    protected function setMidtransConfig(): void
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    }
}
