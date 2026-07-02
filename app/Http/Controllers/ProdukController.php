<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Vendor;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\VendorApprovedMail;

class ProdukController extends Controller
{
    // Halaman Depan dengan Fitur Search & Filter Kategori (Level 1)
    public function index(Request $request)
    {
        $query = Produk::with(['vendor', 'kategori']);

        // Jika ada pencarian kata kunci
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // Jika ada filter kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('id_kategori', $request->kategori);
        }

        $produk = $query->get();
        $allKategori = Kategori::all(); // Untuk dropdown filter

        return view('produk', compact('produk', 'allKategori'));
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
        $produk = Produk::with(['vendor', 'kategori'])->get();
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

    // Proses Hapus Produk (Level 2)
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect('/admin')->with('success', 'Produk berhasil dihapus!');
    }
}