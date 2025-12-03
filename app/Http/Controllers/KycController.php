<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    public function index()
    {
        return view('kyc.index'); // 1 file yg berisi step 1/2/3
    }

    // STEP 1
    public function step1(Request $request)
    {
        $request->validate([
            'jenis_akun' => 'required',
            'nama' => 'required',
            'email' => 'required|email',
            'alamat' => 'required',
        ]);

        session()->put('kyc_step1', $request->all());

        return response()->json(['next' => true]);
    }

    // STEP 2
    public function step2(Request $request)
    {
        $request->validate([
            'no_hp' => 'required',
            'no_ktp' => 'required',
            'foto_ktp' => 'required|image|max:1024',
            'selfie_ktp' => 'required|image|max:1024',
            'foto_profile' => 'required|image|max:1024',
        ]);

        session()->put('kyc_step2', $request->all());

        return response()->json(['next' => true]);
    }

    // STEP 3
    public function step3(Request $request)
    {
        $request->validate([
            'bank' => 'required',
            'no_rek' => 'required',
            'nama_rek' => 'required',
            'buku_tabungan' => 'required|image|max:1024',
        ]);

        session()->put('kyc_step3', $request->all());

        return response()->json(['next' => true]);
    }

    // FINAL SUBMIT
    public function submit()
    {
        // gabungkan step
        $data = array_merge(
            session('kyc_step1'),
            session('kyc_step2'),
            session('kyc_step3'),
        );

        // TODO: simpan data ke DB

        session()->forget(['kyc_step1', 'kyc_step2', 'kyc_step3']);

        return redirect()->route('dashboard.index')->with('success', 'KYC berhasil dikirim!');
    }
}
