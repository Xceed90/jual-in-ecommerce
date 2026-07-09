<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==========================================
    // 1. DAFTAR VENDOR
    // ==========================================
    public function daftarVendor()
    {
        // Mengambil semua user yang mendaftar sebagai vendor
        $vendors = DB::table('users')
            ->leftJoin('vendors', 'users.id', '=', 'vendors.id_user')
            ->where('users.role', 'vendor')
            ->select('users.*', 'vendors.nama_toko', 'vendors.id_vendor')
            ->orderBy('users.created_at', 'desc')
            ->get();

        return view('admin.vendors', compact('vendors'));
    }

    // ==========================================
    // 2. SEMUA TRANSAKSI (Seluruh Platform)
    // ==========================================
    public function semuaTransaksi()
    {
        // Mengambil semua order dari semua user & vendor
        $transaksis = DB::table('orders')
            ->join('users', 'orders.id_user', '=', 'users.id')
            ->select('orders.*', 'users.name as nama_pembeli')
            ->orderBy('orders.id_order', 'desc')
            ->get();

        return view('admin.transaksi', compact('transaksis'));
    }

    // ==========================================
    // 3. KELOLA KOMISI
    // ==========================================
    public function kelolaKomisi()
    {
        // Logika sederhana: Platform mengambil komisi 10% dari total penjualan setiap vendor
        // Kita hitung dari tabel detail_order (karena 1 order bisa dipecah ke beberapa vendor)
        $komisi = DB::table('detail_order')
            ->join('vendors', 'detail_order.id_vendor', '=', 'vendors.id_vendor')
            ->join('orders', 'detail_order.id_order', '=', 'orders.id_order')
            ->select(
                'vendors.nama_toko',
                'detail_order.id_detail_order',
                'orders.tanggal_order',
                'detail_order.status_order',
                // Subquery untuk menghitung total belanja per vendor
                DB::raw('(SELECT SUM(jumlah_beli * harga_saat_beli) FROM item_order WHERE item_order.id_detail_order = detail_order.id_detail_order) as total_penjualan')
            )
            ->orderBy('detail_order.id_detail_order', 'desc')
            ->get();

        return view('admin.komisi', compact('komisi'));
    }

// ==========================================
    // AKSI SUPER ADMIN: APPROVE VENDOR
    // ==========================================
    public function approveVendor($id)
    {
        DB::table('users')->where('id', $id)->update(['status' => 'approved']);
        return redirect('/admin/vendors')->with('success', 'Akses vendor berhasil disetujui!');
    }

    // ==========================================
    // AKSI SUPER ADMIN: HAPUS VENDOR
    // ==========================================
    public function hapusVendor($id)
    {
        // Menghapus data vendor dan usernya sekaligus
        DB::table('vendors')->where('id_user', $id)->delete();
        DB::table('users')->where('id', $id)->delete();
        return redirect('/admin/vendors')->with('success', 'Akun vendor berhasil dihapus permanen!');
    }

    // ==========================================
    // AKSI SUPER ADMIN: HAPUS PRODUK NAKAL
    // ==========================================
    public function hapusProduk($id)
    {
        DB::table('produk')->where('id_produk', $id)->delete();
        return back()->with('success', 'Produk berhasil diturunkan (dihapus) oleh Admin!');
    }

}