<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    // Tambah Produk ke Keranjang
    public function add(Request $request)
    {
        $id_produk = $request->id_produk;
        
        // Ambil data produk beserta nama vendornya
        $produk = DB::table('produk')
            ->leftJoin('vendors', 'produk.id_vendor', '=', 'vendors.id_vendor')
            ->where('produk.id_produk', $id_produk)
            ->first();

        if(!$produk) return abort(404);

        // Hitung Harga setelah diskon
        $harga_final = $produk->harga;
        if(isset($produk->diskon) && $produk->diskon > 0) {
            $harga_final = $produk->harga - ($produk->harga * ($produk->diskon / 100));
        }

        $cart = session()->get('cart', []);

        // Jika produk sudah ada di keranjang, tambah jumlahnya
        if(isset($cart[$id_produk])) {
            $cart[$id_produk]['qty']++;
        } else {
            // Jika belum ada, masukkan data baru
            $cart[$id_produk] = [
                "nama_produk" => $produk->nama_produk,
                "qty" => 1,
                "harga" => $harga_final,
                "foto_produk" => $produk->foto_produk,
                "nama_toko" => $produk->nama_toko ?? 'Toko Tidak Diketahui'
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil masuk keranjang!');
    }

    // Tampilkan Halaman Keranjang
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('keranjang', compact('cart'));
    }

    // Hapus Produk dari Keranjang
    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }
    // (Fungsi remove yang lama biarkan saja, tambahkan kode ini di bawahnya)

    // Kurangi / Tambah Qty Produk di Keranjang
    public function updateQty(Request $request, $id)
    {
        $cart = session()->get('cart');
        
        if(isset($cart[$id])) {
            if($request->action == 'plus') {
                $cart[$id]['qty']++;
            } elseif($request->action == 'minus') {
                $cart[$id]['qty']--;
                // Jika setelah dikurangi qty menjadi 0, hapus saja sekalian dari keranjang
                if($cart[$id]['qty'] <= 0) {
                    unset($cart[$id]);
                }
            }
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }
}