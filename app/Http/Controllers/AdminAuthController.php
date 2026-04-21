<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // 🔥 CEK KE TABEL ADMINS
        $admin = Admin::where('email', $credentials['email'])->first();

        if ($admin && Hash::check($credentials['password'], $admin->password)) {

            // ✅ SESSION SESUAI LOGIKA KAMU
            session(['admin' => true]);

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Login gagal');
    }

    public function logout()
    {
        session()->forget('admin');
        Auth::logout();

        return redirect('/admin/login');
    }
}