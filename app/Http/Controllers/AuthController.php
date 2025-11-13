<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // --- 1. FITUR REGISTER ---
    public function showRegister() {
        return view('auth.auth'); // Pastikan file view-nya ada
    }

    public function register(Request $request) {
        // 1. Validasi Input (Tambahkan phone & birth_date)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            // Validasi Tambahan:
            'phone_number' => 'required|numeric', // Harus angka
            'birth_date' => 'required|date',      // Harus format tanggal
        ]);

        // 2. Simpan ke Database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            
            // Masukkan Data Baru:
            'phone_number' => $request->phone_number,
            'birth_date' => $request->birth_date,
        ]);

        // 3. Auto Login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login');
    }

    // --- 2. FITUR LOGIN ---
    public function showLogin() {
        return view('auth.auth');
    }

    public function login(Request $request) {
        // Coba login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Cek Role: admin
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }

        // Kalau gagal
        return back()->withErrors(['email' => 'Email atau password salah bro!']);
    }

    // --- 3. FITUR LOGOUT ---
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/'); // Balik ke landing page
    }
}