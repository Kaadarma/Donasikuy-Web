<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// ganti dengan model KYC punyamu
use App\Models\KycProfile;

class KycController extends Controller
{
    /**
     * Helper ambil data KYC dari session.
     */
    protected function getSessionData()
    {
        return session('kyc', []);
    }

    /**
     * Helper simpan/merge data KYC ke session.
     */
    protected function putSessionData(array $data)
    {
        $current = $this->getSessionData();
        session(['kyc' => array_merge($current, $data)]);
    }

    /* =======================
     * BAGIAN 1 - INFORMASI DASAR
     * ======================= */

    public function step1()
    {
        $kyc = $this->getSessionData();

        return view('kyc.step1', compact('kyc'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'account_type'    => 'required|in:individu,organisasi',
            'entity_name'     => 'required|string|max:255',
            'entity_email'    => 'required|email',
            'entity_address'  => 'required|string',
        ]);

        $this->putSessionData($validated);

        return redirect()->route('kyc.step2');
    }

    /* =======================
     * BAGIAN 2
     * (isi sendiri field-nya sesuai form)
     * ======================= */

    public function step2()
    {
        $kyc = $this->getSessionData();

        return view('kyc.step2', compact('kyc'));
    }

    public function storeStep2(Request $request)
    {
        // TODO: ganti sesuai field form Bagian 2
        // contoh sederhana (tanpa validasi dulu):
        $data = $request->except(['_token']);

        $this->putSessionData($data);

        return redirect()->route('kyc.step3');
    }

    /* =======================
     * BAGIAN 3 - IDENTITAS PEMEGANG AKUN
     * ======================= */

    public function step3()
    {
        $kyc = $this->getSessionData();

        return view('kyc.step3', compact('kyc'));
    }

    public function storeStep3(Request $request)
    {
        $validated = $request->validate([
            'holder_phone'   => 'required|string|max:20',
            'holder_ktp'     => 'required|string|max:30',
            'ktp_photo'      => 'required|image|max:1024',      // 1MB
            'selfie_ktp'     => 'required|image|max:1024',
            'profile_photo'  => 'required|image|max:1024',
        ]);

        // handle upload file
        if ($request->hasFile('ktp_photo')) {
            $validated['ktp_photo_path'] = $request->file('ktp_photo')
                ->store('kyc/ktp', 'public');
        }

        if ($request->hasFile('selfie_ktp')) {
            $validated['selfie_ktp_path'] = $request->file('selfie_ktp')
                ->store('kyc/selfie', 'public');
        }

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo_path'] = $request->file('profile_photo')
                ->store('kyc/profile', 'public');
        }

        // kita gak simpan file object, cuma path-nya
        unset($validated['ktp_photo'], $validated['selfie_ktp'], $validated['profile_photo']);

        $this->putSessionData($validated);

        return redirect()->route('kyc.step4');
    }

    /* =======================
     * BAGIAN 4 - INFO PENCAIRAN DANA
     * ======================= */

    public function step4()
    {
        $kyc = $this->getSessionData();

        return view('kyc.step4', compact('kyc'));
    }

    public function storeStep4(Request $request)
    {
        $validated = $request->validate([
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_name'   => 'required|string|max:100',
            'book_photo'     => 'required|image|max:1024',      // 1MB
        ]);

        if ($request->hasFile('book_photo')) {
            $validated['book_photo_path'] = $request->file('book_photo')
                ->store('kyc/book', 'public');
        }
        unset($validated['book_photo']);

        // gabung semua data dari semua step
        $allData = array_merge(
            $this->getSessionData(),
            $validated,
            ['user_id' => Auth::id()]
        );

        // === SIMPAN KE DATABASE ===
        // ganti KycProfile dengan model dan kolom punyamu
        KycProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $allData
        );

        // bersihkan session
        session()->forget('kyc');

        return redirect()
            ->route('kyc.completed')
            ->with('success', 'Data verifikasi KYC berhasil dikirim.');
    }

    /* =======================
     * HALAMAN SELESAI (OPSIONAL)
     * ======================= */

    public function completed()
    {
        return view('kyc.completed');
    }
}
