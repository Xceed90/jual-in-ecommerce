<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Vendor;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\VendorApprovedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    // Halaman Depan dengan Fitur Search & Filter Kategori (Level 1)
    public function index(Request $request)
    {
        // 1. Ambil semua list kategori untuk ditampilkan di select option filter
        // (Sesuaikan nama tabel 'kategori' jika di database kamu berbeda)
        $kategoris = DB::table('kategori')->get(); 

        // 2. Mulai query dasar untuk mengambil produk
        $query = DB::table('produk');

        // 3. FILTER KATEGORI (Hanya jalan jika user memilih kategori)
        $query->when($request->filled('kategori'), function ($q) use ($request) {
            return $q->where('id_kategori', $request->kategori);
        });

        // 4. FILTER MINIMAL HARGA
        $query->when($request->filled('min_harga'), function ($q) use ($request) {
            return $q->where('harga', '>=', $request->min_harga);
        });

        // 5. FILTER MAKSIMAL HARGA
        $query->when($request->filled('max_harga'), function ($q) use ($request) {
            return $q->where('harga', '<=', $request->max_harga);
        });

        // 6. FILTER MINIMAL RATING (Misal: Cari yang rating >= 4)
        $query->when($request->filled('rating'), function ($q) use ($request) {
            return $q->where('rating', '>=', $request->rating);
        });

        // 7. Eksekusi query produk yang sudah disaring
        $produk = $query->orderBy('id_produk', 'desc')->get();

        // 8. Kirim data produk dan kategori ke halaman view utama
        // (Ganti 'welcome' sesuai dengan nama file view katalog utamamu, misal 'index' atau 'home')
        return view('produk', compact('produk', 'kategoris'));
    
    }

    public function show($id)
    {
        // 1. Ambil data produk berdasarkan ID
        $produk = DB::table('produk')
            ->join('vendors', 'produk.id_vendor', '=', 'vendors.id_vendor') // Opsional: gabung ke vendor untuk nama toko
            ->where('id_produk', $id)
            ->first();

        if (!$produk) {
            abort(404, 'Produk tidak ditemukan');
        }

        // 2. Ambil daftar ulasan dari tabel item_order
        // Kita join sampai ke tabel users agar tahu siapa yang memberi ulasan
        $ulasanList = DB::table('item_order')
            ->join('detail_order', 'item_order.id_detail_order', '=', 'detail_order.id_detail_order')
            ->join('orders', 'detail_order.id_order', '=', 'orders.id_order')
            ->join('users', 'orders.id_user', '=', 'users.id')
            ->where('item_order.id_produk', $id)
            ->whereNotNull('item_order.ulasan') // Hanya ambil yang ada ulasannya
            ->select('users.name', 'item_order.rating_diberikan', 'item_order.ulasan', 'item_order.updated_at')
            ->orderBy('item_order.updated_at', 'desc')
            ->get();

        // 3. Kirim data ke tampilan halaman detail
        return view('produk_detail', compact('produk', 'ulasanList'));
    }

    // Fungsi Khusus Super Admin: Setujui Vendor & Kirim Email
    public function approveVendor($id)
    {
        $vendor = User::findOrFail($id);
        $vendor->status = 'approved';
        $vendor->save();

        // Kirim Email Notifikasi
        Mail::to($vendor->email)->send(new VendorApprovedMail($vendor->name));

        return redirect('/admin')->with('success', 'Akun vendor berhasil disetujui dan email telah dikirim!');
    }

    // Tampilan Dashboard Admin CRUD (Level 2)
public function adminIndex()
    {
        // 1. Ambil ID User yang sedang login (Andi = ID 5)
        $id_user_login = Auth::id(); 
        
        // 2. Cari id_vendor asli milik Andi di tabel vendors
        $data_vendor = DB::table('vendors')->where('id_user', $id_user_login)->first();
        
        // Antisipasi jika akun belum terdaftar di tabel vendors agar tidak error screen hitam
        if (!$data_vendor) {
            return "Akun Anda (" . Auth::user()->name . ") belum terdaftar di tabel 'vendors'. Pastikan data relasinya sudah ada di database.";
        }

        // Ini id_vendor asli (misal nilainya 4)
        $id_vendor = $data_vendor->id_vendor; 

        // 3. Mengambil semua daftar produk milik vendor ini
        $produk = DB::table('produk')->where('id_vendor', $id_vendor)->get();

        // 4. Hitung TOTAL PENJUALAN
        $total_penjualan = DB::table('detail_order')
            ->join('item_order', 'detail_order.id_detail_order', '=', 'item_order.id_detail_order')
            ->where('detail_order.id_vendor', $id_vendor)
            ->where('detail_order.status_order', 'selesai')
            ->sum(DB::raw('item_order.harga_saat_beli * item_order.jumlah_beli'));

        // 5. Hitung ORDER MASUK (Order Aktif)
        $order_masuk = DB::table('detail_order')
            ->where('id_vendor', $id_vendor)
            ->whereIn('status_order', [
                'menunggu pembayaran', 'menunggu_pembayaran', 
                'pending', 'diproses', 'di_proses'
            ])
            ->count();

        // 6. Cari PRODUK TERLARIS
        $produk_terlaris = DB::table('item_order')
            ->join('produk', 'item_order.id_produk', '=', 'produk.id_produk')
            ->select('produk.nama_produk', 'produk.foto_produk', DB::raw('SUM(item_order.jumlah_beli) as total_terjual'))
            ->where('produk.id_vendor', $id_vendor)
            ->groupBy('produk.id_produk', 'produk.nama_produk', 'produk.foto_produk')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        // 7. Ambil data untuk Form Tambah Produk
        $kategori = DB::table('kategori')->get();
        $vendors = DB::table('vendors')->get();

        // 8. Ambil Data Detail Pesanan Untuk Tabel
     $daftar_pesanan = DB::table('detail_order')
            ->join('orders', 'detail_order.id_order', '=', 'orders.id_order')
            ->join('users', 'orders.id_user', '=', 'users.id') 
            ->select('detail_order.*', 'orders.tanggal_order', 'orders.alamat_pengiriman', 'users.name as nama_pembeli')
            ->where('detail_order.id_vendor', $id_vendor)
            ->orderBy('orders.tanggal_order', 'desc')
            ->get();

    // 9. Ambil Semua Ulasan Produk Milik Vendor Ini (BARIS INI YANG SEBELUMNYA HILANG)
        $daftar_ulasan = DB::table('ulasan')
            ->join('produk', 'ulasan.id_produk', '=', 'produk.id_produk')
            ->join('users', 'ulasan.id_user', '=', 'users.id') 
            ->select('ulasan.*', 'produk.nama_produk', 'produk.foto_produk', 'users.name as nama_pembeli')
            ->where('produk.id_vendor', $id_vendor)
            ->orderBy('ulasan.created_at', 'desc')
            ->get();

        // PAKAI RETURN VIEW YANG INI SAJA (Di paling bawah fungsi adminIndex)
        return view('admin', compact('produk', 'total_penjualan', 'order_masuk', 'produk_terlaris', 'kategori', 'vendors', 'daftar_pesanan', 'daftar_ulasan'));
    }
     public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'id_kategori' => 'required',
            'deskripsi' => 'required',
            'diskon' => 'nullable|integer|min:0|max:100',
            'foto_produk' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        // Logika Otomatis Menentukan ID Vendor agar tidak kosong lagi:
       // Logika Otomatis Menentukan ID Vendor agar tidak kosong lagi:
        if (auth()->user()->role == 'admin') {
            // Jika admin yang tambah, ambil dari dropdown 'id_vendor'
            $id_vendor = $request->id_vendor;
        } else {
            // Jika VENDOR yang tambah, OTOMATIS cari id_vendornya di tabel vendors berdasarkan siapa yang login
            $vendor = \DB::table('vendors')->where('id_user', auth()->user()->id)->first();

            // Antisipasi jika data vendor belum dibuatkan di database
            if (!$vendor) {
                // Buat otomatis jika belum ada dan simpan ID barunya
                $id_vendor = \DB::table('vendors')->insertGetId([
                    'id_user' => auth()->user()->id,
                    'nama_toko' => auth()->user()->name,
                    'pemilik' => auth()->user()->name, // <-- Wajib ada
                ], 'id_vendor');
            } else {
                // JIKA VENDOR SUDAH ADA, baru kita ambil ID-nya dari database
                $id_vendor = $vendor->id_vendor; 
            }
        }

        // Proses Upload Foto
        $nama_file = time() . '.' . $request->foto_produk->extension();
        $request->foto_produk->storeAs('public/produk', $nama_file);

        // Simpan ke database (Sesuaikan dengan nama Model/Tabel Produkmu)
        \DB::table('produk')->insert([
            'nama_produk' => $request->nama_produk,
            'foto_produk' => $nama_file,
            'harga' => $request->harga,
            'diskon' => $request->diskon ?? 0,
            'stok' => $request->stok,
            'id_vendor' => $id_vendor, // Sudah aman terisi otomatis!
            'id_kategori' => $request->id_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect('/seller/dashboard')->with('success', 'Produk berhasil ditambahkan!');
    }

// Menampilkan Halaman Form Edit Produk
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::all();
        
        // PENGAMANAN: Pastikan yang edit adalah pemiliknya atau admin
        $user = auth()->user();
        if ($user->role != 'admin') {
            $vendorLogin = \DB::table('vendors')->where('id_user', $user->id)->first();
            if (!$vendorLogin || $produk->id_vendor != $vendorLogin->id_vendor) {
                return redirect('/admin')->withErrors(['pesan' => 'Akses ditolak! Bukan produk Anda.']);
            }
        }

        return view('edit_produk', compact('produk', 'kategori'));
    }

    // Memproses Data Update (Ini yang akan memicu Trigger!)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        // Update ke database
        \DB::table('produk')->where('id_produk', $id)->update([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            // (Foto tidak wajib diupdate dulu agar simpel)
        ]);

        return redirect('/seller/dashboard')->with('success', 'Produk berhasil diperbarui!');
    }

    // Proses Hapus Produk (Level 2)
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        
        // PENGAMANAN TAMBAHAN: Cegah Vendor nakal menghapus lewat URL
        $user = auth()->user();
        if ($user->role != 'admin') {
            $vendorLogin = \DB::table('vendors')->where('id_user', $user->id)->first();
            
            // Jika id_vendor di produk TIDAK SAMA dengan id_vendor yang login, TOLAK!
            if (!$vendorLogin || $produk->id_vendor != $vendorLogin->id_vendor) {
                return redirect('/admin')->withErrors(['pesan' => 'Akses ditolak! Anda tidak berhak menghapus produk toko lain.']);
            }
        }

        $produk->delete();

        return redirect('/seller/dashboard')->with('success', 'Produk berhasil dihapus!');
    }
}