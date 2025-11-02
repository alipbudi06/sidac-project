<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman/view login.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses autentikasi (login).
     */
    public function authenticate(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate(
            // Aturan validasi
            [
                'Username' => ['required', 'string'],
                'Password' => ['required', 'string'],
            ],
            [
                'Username.required' => 'Username belum diinputkan.',
                'Password.required' => 'Password belum diinputkan.',
            ]
        );

        // 2. Mencoba melakukan login
        if (Auth::attempt(['Username' => $credentials['Username'], 'password' => $credentials['Password']])) {
            
            $request->session()->regenerate();

            // Arahkan ke dashboard
            return redirect()->intended(route('dashboard'));
        }

        // 3. Jika login gagal (Error E-1)
        return back()->withErrors([
            'Username' => 'Username atau Password tidak sesuai.',
        ])->onlyInput('Username');
    }
    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman login
        return redirect('/login');
    }
}