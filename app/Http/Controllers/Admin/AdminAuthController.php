<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminDonasikuy;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email_admin' => 'required|email',
            'password' => 'required',
        ]);

        $admin = AdminDonasikuy::where('email_admin', $request->email_admin)->first();

        if (!$admin || !Hash::check($request->password, $admin->password_admin)) {
            return back()->withErrors([
                'email_admin' => 'Email atau password salah',
            ]);
        }

        // SIMPAN SESSION ADMIN
        session([
            'admin_logged_in' => true,
            'admin_id'        => $admin->id_admin,
            'admin_name'      => $admin->nama_admin,
            'admin_email'     => $admin->email_admin,
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function logout()
    {
        session()->forget([
            'admin_logged_in',
            'admin_id',
            'admin_name',
            'admin_email',
        ]);

        return redirect()->route('admin.login');
    }
}
