<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function submitLogin(Request $request)
    {
        try {
            // 1. Validasi
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            // 2. Cek ke Database
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('dashboard'); 
            }

            // Jika gagal (email/sandi salah)
            return back()->withErrors([
                'email' => 'Email dan password salah!',
            ])->onlyInput('email');

        } catch (\Exception $e) {
            dd("TERJADI ERROR SISTEM/DATABASE:", $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}