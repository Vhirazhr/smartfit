<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {

            // ✅ TAMBAHAN PENTING
            session(['admin' => true]);

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Login gagal');
    }

    public function logout()
    {
        // ✅ HAPUS SESSION
        session()->forget('admin');

        Auth::logout();
        return redirect('/admin/login');
    }
}