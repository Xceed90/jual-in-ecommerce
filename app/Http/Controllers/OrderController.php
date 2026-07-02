<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DetailOrder;
use App\Models\ItemOrder;
use App\Models\Produk;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'details.vendor', 'details.items.produk'])->orderBy('id_order', 'desc')->get();
        return view('orders', compact('orders'));
    }

    // Jantung Fitur Multi-Vendor Splitting (Level 3)
    public function checkout(Request $request)
    {
        $itemsSelected = $request->input('produk', []); // Berisi array id_produk => jumlah_beli
        
        // Filter produk yang kuantitas belinya lebih dari 0
        $itemsToBuy = array_filter($itemsSelected, function($qty) {
            return $qty > 0;
        });

        if (empty($itemsToBuy)) {
            return redirect('/')->with('error', 'Pilih minimal 1 produk dengan jumlah yang benar!');
        }

        // Ambil data produk dari database dan kelompokkan berdasarkan Vendor (Toko)
        $groupedByVendor = [];
        $totalHargaProdukGlobal = 0;

        foreach ($itemsToBuy as $idProduk => $qty) {
            $produk = Produk::findOrFail($idProduk);
            $subtotal = $produk->harga * $qty;
            $totalHargaProdukGlobal += $subtotal;

            // Kelompokkan produk berdasarkan id_vendor
            $groupedByVendor[$produk->id_vendor][] = [
                'produk' => $produk,
                'qty' => $qty,
                'harga_saat_beli' => $produk->harga
            ];

            // Potong stok produk di database
            $produk->decrement('stok', $qty);
        }

        // Hitung Ongkir (Misal flat Rp 15.000 per Vendor)
        $ongkirPerVendorFlat = 15000;
        $jumlahVendor = count($groupedByVendor);
        $totalOngkirGlobal = $jumlahVendor * $ongkirPerVendorFlat;
        $grandTotalGlobal = $totalHargaProdukGlobal + $totalOngkirGlobal;

        // 1. INSERT ke tabel orders (Invoice Utama)
      $order = Order::create([
            'id_user' => Auth::id(),
            'alamat_pengiriman' => $request->alamat_pengiriman ?? 'Alamat Default Budi, Gedung Lab Basis Data',
            'total_harga_produk' => $totalHargaProdukGlobal,
            'total_ongkir' => $totalOngkirGlobal,
            'grand_total' => $grandTotalGlobal
        ]);

        // 2. KEAJAIBAN SPLITTING: Looping per Vendor
        foreach ($groupedByVendor as $idVendor => $listBarang) {
            
            // INSERT ke tabel detail_order (Pecahan Invoice per Toko)
            $detailOrder = DetailOrder::create([
                'id_order' => $order->id_order,
                'id_vendor' => $idVendor,
                'kurir_pengiriman' => 'J&T Express Super',
                'ongkir_per_vendor' => $ongkirPerVendorFlat,
                'status_order' => 'diproses'
            ]);

            // 3. INSERT ke tabel item_order (Detail barang di toko tersebut)
            foreach ($listBarang as $barang) {
                ItemOrder::create([
                    'id_detail_order' => $detailOrder->id_detail_order,
                    'id_produk' => $barang['produk']->id_produk,
                    'jumlah_beli' => $barang['qty'],
                    'harga_saat_beli' => $barang['harga_saat_beli']
                ]);
            }
        }

        return redirect('/orders')->with('success', 'Checkout Multi-Vendor Berhasil Disimulasikan!');
    }
}