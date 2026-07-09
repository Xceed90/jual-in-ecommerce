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

        $produk = DB::table('produk')
            ->leftJoin('vendors', 'produk.id_vendor', '=', 'vendors.id_vendor')
            ->where('produk.id_produk', $id_produk)
            ->first();

        if(!$produk) return abort(404);

        $harga_final = $produk->harga;
        if(isset($produk->diskon) && $produk->diskon > 0) {
            $harga_final = round($produk->harga - ($produk->harga * ($produk->diskon / 100)));
        }

        $qty = $request->has('qty') ? (int) $request->qty : 1;

        $cart = session()->get('cart', []);

        $qtyBaru = isset($cart[$id_produk]) ? $cart[$id_produk]['qty'] + $qty : $qty;

        if ($qtyBaru > $produk->stok) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $produk->stok . ' buah.');
        }

        if(isset($cart[$id_produk])) {
            $cart[$id_produk]['qty'] = $qtyBaru;
            $cart[$id_produk]['stok'] = $produk->stok;
        } else {
            $cart[$id_produk] = [
                "nama_produk" => $produk->nama_produk,
                "qty" => $qty,
                "harga" => $harga_final,
                "harga_asli" => $produk->harga,
                "diskon" => $produk->diskon ?? 0,
                "foto_produk" => $produk->foto_produk,
                "nama_toko" => $produk->nama_toko ?? 'Toko Tidak Diketahui',
                "id_vendor" => $produk->id_vendor,
                "stok" => $produk->stok,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil masuk keranjang!');
    }

    // Tampilkan Halaman Keranjang
    public function index()
    {
        $cart = session()->get('cart', []);

        foreach ($cart as $id_produk => $item) {
            $produk = DB::table('produk')->where('id_produk', $id_produk)->first();
            if ($produk) {
                $cart[$id_produk]['stok'] = $produk->stok;
                if ($cart[$id_produk]['qty'] > $produk->stok) {
                    $cart[$id_produk]['qty'] = $produk->stok;
                }
            } else {
                unset($cart[$id_produk]);
            }
        }

        session()->put('cart', $cart);

        $groupedByToko = [];
        foreach ($cart as $id_produk => $item) {
            $toko = $item['nama_toko'] ?? 'Toko Tidak Diketahui';
            $groupedByToko[$toko][$id_produk] = $item;
        }

        return view('keranjang', compact('cart', 'groupedByToko'));
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

    // Hapus Banyak Produk Sekaligus
    public function removeSelected(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'required|integer',
        ]);

        $cart = session()->get('cart', []);

        foreach ($request->selected_items as $id) {
            if (isset($cart[$id])) {
                unset($cart[$id]);
            }
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk terpilih berhasil dihapus dari keranjang.');
    }

    // Kurangi / Tambah Qty Produk di Keranjang
    public function updateQty(Request $request, $id)
    {
        $cart = session()->get('cart');

        if(isset($cart[$id])) {
            $produk = DB::table('produk')->where('id_produk', $id)->first();

            if (!$produk) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                return redirect()->back()->with('error', 'Produk tidak lagi tersedia.');
            }

            if($request->action == 'plus') {
                if ($cart[$id]['qty'] >= $produk->stok) {
                    return redirect()->back()->with('error', 'Stok maksimal tercapai. Sisa stok: ' . $produk->stok . ' buah.');
                }
                $cart[$id]['qty']++;
            } elseif($request->action == 'minus') {
                $cart[$id]['qty']--;
                if($cart[$id]['qty'] <= 0) {
                    unset($cart[$id]);
                }
            }

            if (isset($cart[$id])) {
                $cart[$id]['stok'] = $produk->stok;
            }

            session()->put('cart', $cart);
        }
        return redirect()->back();
    }
}
