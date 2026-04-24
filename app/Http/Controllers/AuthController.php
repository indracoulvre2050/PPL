<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function Register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required||unique:users',
            'password' => 'required||min:6||confirmed',
            'role' => 'required|in:admin,user',
        ]);
    
        $user=User::create($validated);
        Auth::login($user);
        return redirect()->route('admin');
    }

    public function login(Request $request)
    {
        HistoryLogger::log('Login', null, null, 'User login');
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin');
        }
        return Redirect('/')->withErrors([
            'email' => 'Email atau Password salah.',
        ])->onlyInput('email');

    }

    public function logout(Request $request)
    {
        HistoryLogger::log('Logout', null, null, 'User logout');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');

    }
}
