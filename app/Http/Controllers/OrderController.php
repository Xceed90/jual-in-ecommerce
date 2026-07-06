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
        // 🔒 PERBAIKAN: Tambahkan where() untuk memfilter berdasarkan user yang sedang login
        $orders = Order::with(['user', 'details.vendor', 'details.items.produk'])
                    ->where('id_user', Auth::id()) 
                    ->orderBy('id_order', 'desc')
                    ->get();
                    
        return view('orders', compact('orders'));
    }

    public function checkout(Request $request)
    {
        // 1. Ambil data cart dari session atau database
        $cartItems = session('cart') ?? []; 

        if (empty($cartItems)) {
            return redirect('/keranjang')->with('error', 'Keranjang belanja Anda masih kosong!');
        }

        // 💡 HITUNG TOTAL HARGA SECARA REAL-TIME DARI CART SEBELUM INSERT DATA
        $totalHargaProduk = 0;
        foreach ($cartItems as $id_produk => $item) {
            $totalHargaProduk += ($item['qty'] * $item['harga']);
        }

        // 2. MULAI TRY BLOCK DI SINI
        try {
            DB::beginTransaction();

            // Buat Order Baru dengan nilai kalkulasi dinamis
            $idOrderBaru = DB::table('orders')->insertGetId([
                'id_user' => auth()->user()->id,
                'tanggal_order' => now(),
                'alamat_pengiriman' => 'Alamat Default Pembeli', 
                'total_harga_produk' => $totalHargaProduk, 
                'total_ongkir' => 0, 
                'grand_total' => $totalHargaProduk, 
                'created_at' => now(),
            ], 'id_order');

        // 3. Looping Cart Items
            foreach ($cartItems as $id_produk => $item) {
                
                $produkAsli = DB::table('produk')->where('id_produk', $id_produk)->first();

                if ($produkAsli) {
                    // Masukkan ke detail order
                    $idDetailOrderBaru = DB::table('detail_order')->insertGetId([
                        'id_order' => $idOrderBaru,
                        'id_vendor' => $produkAsli->id_vendor, 
                        
                        // 💡 UBAH BAGIAN INI MENJADI KATA YANG DIIZINKAN DATABASE:
                        'status_order' => 'menunggu_pembayaran', 
                        
                        'kurir_pengiriman' => 'Belum Memilih',
                        'ongkir_per_vendor' => 0,
                        'created_at' => now(),
                    ], 'id_detail_order');

                    // Masukkan data barang ke tabel item_order
                    DB::table('item_order')->insert([
                        'id_detail_order' => $idDetailOrderBaru,
                        'id_produk' => $id_produk,
                        'jumlah_beli' => $item['qty'],
                        'harga_saat_beli' => $item['harga'],
                        'created_at' => now(),
                    ]);

                    // Perintah memotong stok produk
                    DB::table('produk')
                        ->where('id_produk', $id_produk)
                        ->decrement('stok', $item['qty']); 
                }
            }

            // 4. Bersihkan keranjang dan Commit Database
            session()->forget('cart');
            DB::commit();

            return redirect('/orders')->with('success', 'Checkout berhasil! Stok telah terpotong.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error_checkout', 'Sistem membatalkan pesanan: ' . $e->getMessage());
        }
    } // Akhir fungsi checkout

    // ==========================================
    // FITUR : EKSPOR LAPORAN KE CSV / EXCEL
    // ==========================================
    public function exportCSV()
    {
        $fileName = 'Laporan_Penjualan_' . date('Y-m-d_H-i-s') . '.csv';
        
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
            
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns, ';');

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
    } // Akhir fungsi exportCSV

    // ==========================================
    // FITUR : BERI RATING PRODUK (AKURAT & REAL-TIME)
    // ==========================================
public function beriRating(Request $request, $id_detail_order, $id_produk)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'ulasan' => 'nullable|string' // 💡 TAMBAHAN: Validasi teks ulasan
        ]);

        $ratingInput = $request->rating;

        // Update rating DAN teks ulasan ke database
        DB::table('item_order')
            ->where('id_detail_order', $id_detail_order)
            ->where('id_produk', $id_produk)
            ->update([
                'rating_diberikan' => $ratingInput,
                'ulasan' => $request->ulasan // 💡 TAMBAHAN: Simpan teks ulasannya di sini
            ]);

        $rataRataRating = DB::table('item_order')
            ->where('id_produk', $id_produk)
            ->whereNotNull('rating_diberikan')
            ->avg('rating_diberikan'); 

        DB::table('produk')->where('id_produk', $id_produk)->update([
            'rating' => round($rataRataRating, 1) 
        ]);

        return back()->with('success', 'Terima kasih! Ulasan dan rating berhasil disimpan.');
    } // Akhir fungsi beriRating

    // ==========================================
    // SIMULASI BAYAR (Ubah ke Diproses)
    // ==========================================
    public function bayarSimulasi($id_order)
    {
        DB::table('detail_order')
            ->where('id_order', $id_order)
            ->update(['status_order' => 'diproses']);

        return back()->with('success', '✅ Pembayaran berhasil disimulasikan! Pesanan sekarang sedang diproses oleh Vendor.');
    } // Akhir fungsi bayarSimulasi

    // ==========================================
    // PESANAN DITERIMA (Ubah ke Selesai)
    // ==========================================
    public function terimaPesanan($id_order)
    {
        DB::table('detail_order')
            ->where('id_order', $id_order)
            ->update(['status_order' => 'selesai']);

        return back()->with('success', '📦 Pesanan telah diterima! Transaksi selesai dan komisi telah diteruskan ke vendor.');
    } // Akhir fungsi terimaPesanan

} // AKHIR DARI KELAS OrderController