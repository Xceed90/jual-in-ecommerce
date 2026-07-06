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
        $user = auth()->user();
        
        if ($user->role == 'admin') {
            // Jika Super Admin: Bisa melihat SEMUA produk
            $produk = Produk::with(['vendor', 'kategori'])->get();
        } else {
            // Jika Vendor: HANYA bisa melihat produk miliknya sendiri
            // 1. Cari data vendor milik user yang sedang login
            $vendorLogin = \DB::table('vendors')->where('id_user', $user->id)->first();
            
            if ($vendorLogin) {
                // 2. Filter produk berdasarkan id_vendor
                $produk = Produk::with(['vendor', 'kategori'])
                                ->where('id_vendor', $vendorLogin->id_vendor)
                                ->get();
            } else {
                // Jika profil vendor belum terbuat, tampilkan data kosong dulu
                $produk = collect(); 
            }
        }
        
        $vendors = Vendor::all();
        $kategori = Kategori::all();

        return view('admin', compact('produk', 'vendors', 'kategori'));
    }

    // Proses Tambah Produk Baru (Level 2)
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

        return redirect('/admin')->with('success', 'Produk baru berhasil ditambahkan!');
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

        return redirect('/admin')->with('success', 'Produk berhasil diubah!');
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

        return redirect('/admin')->with('success', 'Produk berhasil dihapus!');
    }
}