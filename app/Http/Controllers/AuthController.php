<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi inputan dari Vue (Ubah email menjadi name)
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // 2. Cek apakah name dan password cocok di database
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // 3. Kalau cocok, kasih respon sukses ke Vue
            return response()->json(['message' => 'Login berhasil'], 200);
        }

        // 4. Kalau gagal, tolak dengan error 401 (Pesan diubah sesuaikan dengan Nama)
        return response()->json([
            'message' => 'Nama pengguna atau kata sandi salah blay.'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Balikin ke halaman login
    }
}