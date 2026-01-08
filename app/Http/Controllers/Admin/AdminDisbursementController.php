<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisbursementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDisbursementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = DisbursementRequest::with(['program','user'])
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $disbursements = $query->paginate(10)->withQueryString();

        return view('admin.disbursements.index', compact('disbursements','status'));
    }

    public function show(DisbursementRequest $disbursement)
    {
        $disbursement->load(['program','user','items']);
        return view('admin.disbursements.show', compact('disbursement'));
    }

    public function approve(DisbursementRequest $disbursement)
    {
        if ($disbursement->status !== DisbursementRequest::STATUS_REQUESTED) {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        // optional: pastikan data rekening memang sudah ada
        if (!$disbursement->bank_name || !$disbursement->account_name || !$disbursement->account_number) {
            return back()->with('error', 'Data rekening belum lengkap.');
        }

        $disbursement->update([
            'status' => DisbursementRequest::STATUS_APPROVED,
        ]);

        return back()->with('success', 'Pencairan disetujui.');
    }


    public function reject(Request $request, DisbursementRequest $disbursement)
    {
        if ($disbursement->status !== DisbursementRequest::STATUS_REQUESTED) {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        $data = $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        $disbursement->update([
            'status' => DisbursementRequest::STATUS_REJECTED,
            'note'   => $data['note'],
        ]);

        return back()->with('success', 'Pencairan ditolak.');
    }

    public function markPaid(Request $request, DisbursementRequest $disbursement)
    {
        if ($disbursement->status !== DisbursementRequest::STATUS_APPROVED) {
            return back()->with('error', 'Hanya pencairan APPROVED yang bisa ditandai PAID.');
        }

        $data = $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'admin_note'    => 'nullable|string|max:500',
        ]);

        $path = $request->file('payment_proof')
            ->store('payment-proofs', 'public');

        $disbursement->update([
            'status'        => DisbursementRequest::STATUS_PAID,
            'paid_at'       => now(),
            'payment_proof' => $path,
            'admin_note'    => $data['admin_note'] ?? null,
        ]);

        return back()->with('success', 'Dana berhasil ditandai sudah dibayar.');
    }

}
