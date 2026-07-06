<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'details.vendor', 'details.items.produk'])->orderBy('id_order', 'desc')->get();
        return view('orders', compact('orders'));
    }

    public function checkout(Request $request)
    {
        $userId = Auth::id();
        $cart = session()->get('cart');

        if (empty($cart)) {
            return back()->with('error_checkout', 'Keranjang Anda masih kosong!');
        }

        DB::beginTransaction();

        try {
            $grandTotal = 0;
            foreach ($cart as $id_produk => $item) {
                $grandTotal += ($item['harga'] * $item['qty']);
            }

            $orderId = DB::table('orders')->insertGetId([
                'id_user'            => $userId, 
                'tanggal_order'      => now(),
                'total_harga_produk' => $grandTotal, 
                'total_ongkir'       => 0, 
                'grand_total'        => $grandTotal, 
                'alamat_pengiriman'  => 'Alamat Default Pembeli', 
                'created_at'         => now(),
            ], 'id_order'); 

            $pesananPerVendor = [];
            
            foreach ($cart as $id_produk => $item) {
                $produk = DB::table('produk')->where('id_produk', $id_produk)->lockForUpdate()->first();

                if (!$produk) {
                    throw new Exception("Produk " . $item['nama_produk'] . " tidak ditemukan.");
                }

                if ($produk->stok < $item['qty']) {
                    throw new Exception("Maaf, stok {$produk->nama_produk} tidak mencukupi! Sisa stok: {$produk->stok}");
                }

                DB::table('produk')->where('id_produk', $id_produk)->update([
                    'stok' => $produk->stok - $item['qty']
                ]);

                $pesananPerVendor[$produk->id_vendor][] = [
                    'id_produk' => $id_produk,
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                ];
            }

            foreach ($pesananPerVendor as $idVendor => $items) {
                $detailOrderId = DB::table('detail_order')->insertGetId([
                    'id_order'          => $orderId,
                    'id_vendor'         => $idVendor,
                    'kurir_pengiriman'  => 'Belum Memilih', 
                    'ongkir_per_vendor' => 0,              
                    'status_order'      => 'menunggu_pembayaran', 
                    'created_at'        => now(),
                ], 'id_detail_order');

                foreach ($items as $item) {
                    DB::table('item_order')->insert([
                        'id_detail_order' => $detailOrderId,
                        'id_produk'       => $item['id_produk'],
                        'jumlah_beli'     => $item['qty'],
                        'harga_saat_beli' => $item['harga'],
                        'created_at'      => now(),
                    ]);
                }
            }

            session()->forget('cart');
            DB::commit();

            return redirect('/orders')->with('success', 'Checkout berhasil! Stok telah terpotong.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error_checkout', 'Sistem membatalkan pesanan: ' . $e->getMessage());
        }
    }

    // ==========================================
    // FITUR : EKSPOR LAPORAN KE CSV / EXCEL
    // ==========================================
    public function exportCSV()
    {
        $fileName = 'Laporan_Penjualan_' . date('Y-m-d_H-i-s') . '.csv';
        
        // Mengambil data komplit hasil join relasi dari database
        $dataPenjualan = DB::table('orders')
            ->join('users', 'orders.id_user', '=', 'users.id')
            ->join('detail_order', 'orders.id_order', '=', 'detail_order.id_order')
            ->join('vendors', 'detail_order.id_vendor', '=', 'vendors.id_vendor')
            ->join('item_order', 'detail_order.id_detail_order', '=', 'item_order.id_detail_order')
            ->join('produk', 'item_order.id_produk', '=', 'produk.id_produk')
            ->select(
                'orders.id_order',
                'users.name as nama_pembeli',
                'orders.tanggal_order',
                'vendors.nama_toko',
                'produk.nama_produk',
                'item_order.jumlah_beli',
                'item_order.harga_saat_beli',
                DB::raw('(item_order.jumlah_beli * item_order.harga_saat_beli) as subtotal'),
                'detail_order.status_order'
            )
            ->orderBy('orders.id_order', 'desc')
            ->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID Order', 'Nama Pembeli', 'Tanggal Transaksi', 'Toko / Vendor', 'Nama Produk', 'Jumlah Beli', 'Harga Satuan', 'Subtotal', 'Status Pesanan'];

        $callback = function() use($dataPenjualan, $columns) {
            $file = fopen('php://output', 'w');
            
            // Tambahkan BOM (Byte Order Mark) agar Microsoft Excel tidak berantakan saat membaca huruf/angka
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Tulis Header Kolom
            fputcsv($file, $columns, ';');

            // Tulis Isi Data
            foreach ($dataPenjualan as $row) {
                fputcsv($file, [
                    $row->id_order,
                    $row->nama_pembeli,
                    $row->tanggal_order,
                    $row->nama_toko,
                    $row->nama_produk,
                    $row->jumlah_beli,
                    $row->harga_saat_beli,
                    $row->subtotal,
                    $row->status_order
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

// ==========================================
    // FITUR BARU: BERI RATING PRODUK
    // ==========================================
    public function beriRating(Request $request, $id_produk)
    {
        // Pastikan input rating valid (1 sampai 5)
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5'
        ]);

        $ratingInput = $request->rating;

        // Ambil data produk saat ini
        $produk = DB::table('produk')->where('id_produk', $id_produk)->first();

        if ($produk) {
            // Logika perhitungan rating sederhana:
            // Jika rating masih 0, langsung pakai input. Jika sudah ada, ambil nilai tengah/rata-ratanya.
            if ($produk->rating == 0 || $produk->rating == null) {
                $ratingBaru = $ratingInput;
            } else {
                $ratingBaru = ($produk->rating + $ratingInput) / 2;
            }

            // Update ke database (dibulatkan 1 angka di belakang koma, misal 4.5)
            DB::table('produk')->where('id_produk', $id_produk)->update([
                'rating' => round($ratingBaru, 1)
            ]);

            return back()->with('success', 'Terima kasih! Rating ' . $ratingInput . ' bintang berhasil diberikan untuk produk ' . $produk->nama_produk);
        }

        return back()->with('error_checkout', 'Produk tidak ditemukan!');
    }

    }