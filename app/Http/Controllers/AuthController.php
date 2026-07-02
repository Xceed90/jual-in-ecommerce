<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Wajib ditambahkan
use Illuminate\Support\Facades\Hash; // Wajib ditambahkan

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // AMAN: Cek dulu apakah status akunnya diblokir/pending
        $user = User::where('email', $request->email)->first();
        if ($user && $user->status == 'pending') {
            return back()->withErrors([
                'email' => 'Akun Vendor Anda masih dalam antrean persetujuan Super Admin. Mohon tunggu email konfirmasi ya!',
            ]);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Selamat datang, ' . Auth::user()->name);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah nih!',
        ]);
    }

    // Tampilkan Halaman Register
    public function showRegister()
    {
        return view('register');
    }

    // Proses Simpan Data Pendaftaran (Simulasi Logika Bisnis)
   public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:user,vendor',
        ]);

        $statusOtomatis = ($request->role == 'vendor') ? 'pending' : 'approved';

        // 1. Buat akun di tabel users
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $statusOtomatis,
        ]);

        // 2. TAMBAHKAN INI: Jika pendaftar adalah Vendor, otomatis daftarkan juga ke tabel vendors
        if ($request->role == 'vendor') {
            DB::table('vendors')->insert([
                'id_user' => $user->id, // menghubungkan ke akun user barusan
                'nama_toko' => $request->name,
            ]);

            return redirect('/login')->with('success', 'Pendaftaran Vendor berhasil! Akun Anda sedang ditinjau oleh Super Admin.');
        }

        return redirect('/login')->with('success', 'Pendaftaran Akun Berhasil! Silakan masuk menggunakan akun baru Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}