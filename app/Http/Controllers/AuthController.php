<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Wajib ditambahkan
use Illuminate\Support\Facades\Hash; // Wajib ditambahkan
use Illuminate\Validation\Rules\Password; // Tambahan untuk keamanan password

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
            'email' => [
                'required', 
                'string', 
                'email:rfc,dns', 
                'max:255', 
                'unique:users',
                function ($attribute, $value, $fail) {
                    $blockedDomains = ['gnail.com', 'gamil.com', 'gmal.com', 'yaho.com', 'ymail.com', 'gmail.com.id'];
                    $domain = substr(strrchr($value, "@"), 1);
                    if (in_array(strtolower($domain), $blockedDomains)) {
                        $fail('Domain email (' . $domain . ') dicurigai sebagai salah ketik (typo). Harap periksa kembali email Anda demi keamanan.');
                    }
                },
            ],
            'password' => [
                'required', 
                'string', 
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'role' => 'required|in:user,vendor',
        ], [
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.letters' => 'Kata sandi harus mengandung huruf.',
            'password.mixed' => 'Kata sandi harus mengandung huruf besar dan huruf kecil.',
            'password.numbers' => 'Kata sandi harus mengandung angka.',
            'password.symbols' => 'Kata sandi harus mengandung simbol.',
            'email.dns' => 'Domain email tidak ditemukan atau palsu (tidak valid di internet).',
        ]);

        $statusOtomatis = ($request->role == 'vendor') ? 'pending' : 'approved';
        $namaAman = strip_tags($request->name);

        // 1. Buat akun di tabel users
        $user = User::create([
            'name' => $namaAman,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $statusOtomatis,
        ]);

        if ($request->role == 'vendor') {
            DB::table('vendors')->insert([
                'id_user' => $user->id,
                'nama_toko' => $namaAman,
                'pemilik' => $namaAman, 
            ]);

            return redirect('/login')->with('success', 'Pendaftaran Vendor berhasil! Akun Anda sedang ditinjau oleh Super Admin.');
        }

        // --- BARIS DI BAWAH INI KEMUNGKINAN TERHAPUS OLEHMU TADI ---
        return redirect('/login')->with('success', 'Pendaftaran Akun Berhasil! Silakan masuk menggunakan akun baru Anda.');
    } // <--- KURUNG KURAWAL PENUTUP FUNGSI REGISTER INI WAJIB ADA

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}