<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\KycSubmission;

class EnsureKycVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // kalau belum login, biarin lewat ke middleware 'auth' dulu
        if (!$user) {
            return redirect()->route('login');
        }

        // cari data KYC user ini
        $kyc = KycSubmission::where('user_id', $user->id)->first();

        // kalau belum pernah submit atau status bukan 'approved'
        if (!$kyc || $kyc->status !== 'approved') {
            return redirect()->route('kyc.required');
        }

        return $next($request);
    }
}
