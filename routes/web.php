<?php

use App\Http\Controllers\AdminController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeranjangController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// --- LOGIN LOGOUT ---
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/logout', [AuthController::class, 'logout']);

// --- KATALOG (Bisa diakses siapa saja) ---
Route::get('/', [ProdukController::class, 'index']);

// Rute untuk Registrasi Akun Baru
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

// Rute Simulasi Keranjang Belanja
Route::post('/keranjang/add', [KeranjangController::class, 'add']);
Route::get('/keranjang', [KeranjangController::class, 'index']);
Route::get('/keranjang/hapus/{id}', [KeranjangController::class, 'remove']);
Route::get('/keranjang/update-qty/{id}', [KeranjangController::class, 'updateQty']);


// --- FITUR YANG BUTUH LOGIN (AMAN TERKUNCI) ---
Route::middleware('auth')->group(function () {
    

    // ==========================================
    // 1. RUTE KHUSUS SUPER ADMIN
    // ==========================================
    Route::get('/admin/approve/{id}', [ProdukController::class, 'approveVendor']);
    Route::get('/admin/vendors', [AdminController::class, 'daftarVendor']);
    Route::get('/admin/transaksi', [AdminController::class, 'semuaTransaksi']);
    Route::get('/admin/komisi', [AdminController::class, 'kelolaKomisi']);
    Route::get('/orders/export-csv', [OrderController::class, 'exportCSV']);

    
    // ==========================================
    // 2. RUTE PEMBELI (USER)
    // ==========================================
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders', [OrderController::class, 'index']);
    // 👇 UBAH BARIS INI (Tambahkan name di ujungnya) 👇
    Route::post('/orders/rating/{id_detail_order}/{id_produk}', [OrderController::class, 'beriRating'])->name('beri.rating');
    Route::post('/orders/bayar/{id_order}', [OrderController::class, 'bayarSimulasi']);
    Route::post('/orders/selesai/{id_order}', [OrderController::class, 'terimaPesanan']);

    
    // ==========================================
    // 3. RUTE DASHBOARD VENDOR (SELLER)
    // ==========================================
    // 💡 INI YANG TADI BIKIN 404, SUDAH SAYA PERBAIKI:
    Route::get('/seller/dashboard', [ProdukController::class, 'adminIndex']); 
    Route::post('/admin/store', [ProdukController::class, 'store']);
    Route::get('/admin/delete/{id}', [ProdukController::class, 'destroy']);
    Route::get('/produk/edit/{id}', [ProdukController::class, 'edit']);
    Route::post('/produk/update/{id}', [ProdukController::class, 'update']);
});


// --- FITUR LOGOUT & RESET PASSWORD ---
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Rute Lupa Password (Simulasi)
Route::get('/lupa-password', function() {
    return view('auth.lupa-password');
});

Route::post('/lupa-password', function(Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    $user = DB::table('users')->where('email', $request->email)->first();
    
    if (!$user) {
        return back()->withErrors(['email' => 'Alamat e-mail tidak terdaftar di sistem kami.']);
    }
    return view('auth.lupa-password-sukses', ['email' => $request->email]);
});

// Rute Form Password Baru
Route::get('/reset-password/{email}', function($email) {
    return view('auth.reset-password', ['email' => $email]);
});

Route::post('/reset-password', function(Illuminate\Http\Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed'
    ]);

    DB::table('users')
        ->where('email', $request->email)
        ->update(['password' => Hash::make($request->password)]);

    return redirect('/login')->with('success', 'Password berhasil diperbarui! Silakan login.');
});