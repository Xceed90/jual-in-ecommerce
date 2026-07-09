<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ==========================================
    // FITUR : MENAMPILKAN RIWAYAT PEMBELIAN (USER)
    // ==========================================
    public function index()
    {
        $orders = Order::with(['user', 'details.vendor', 'details.items.produk'])
                    ->where('id_user', Auth::id())
                    ->orderBy('tanggal_order', 'desc')
                    ->get();

        return view('orders', compact('orders'));
    }

    // ==========================================
    // FITUR : DETAIL SATU PESANAN
    // ==========================================
    public function show($id)
    {
        $order = Order::with(['user', 'details.vendor', 'details.items.produk'])
                    ->where('id_order', $id)
                    ->where('id_user', Auth::id())
                    ->first();

        if (!$order) {
            return redirect('/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('order_detail', compact('order'));
    }

    // ==========================================
    // FITUR : HALAMAN CHECKOUT (PREVIEW)
    // ==========================================
    public function showCheckout()
    {
        $checkoutCart = session('checkout_cart', []);

        if (empty($checkoutCart)) {
            return redirect('/keranjang')->with('error', 'Pilih produk terlebih dahulu untuk checkout.');
        }

        $totalHarga = 0;
        $totalItem = 0;
        foreach ($checkoutCart as $item) {
            $totalHarga += $item['harga'] * $item['qty'];
            $totalItem += $item['qty'];
        }

        $groupedByToko = [];
        foreach ($checkoutCart as $id_produk => $item) {
            $toko = $item['nama_toko'] ?? 'Toko Tidak Diketahui';
            $groupedByToko[$toko][] = array_merge($item, ['id_produk' => $id_produk]);
        }

        return view('checkout', compact('checkoutCart', 'totalHarga', 'totalItem', 'groupedByToko'));
    }

    // ==========================================
    // FITUR : PERSIAPAN CHECKOUT DARI KERANJANG
    // ==========================================
    public function prepareCheckout(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'required|integer',
        ]);

        $cart = session('cart', []);
        $selectedIds = $request->selected_items;
        $checkoutCart = [];

        foreach ($selectedIds as $id_produk) {
            if (isset($cart[$id_produk])) {
                $produk = DB::table('produk')->where('id_produk', $id_produk)->first();
                if ($produk) {
                    if ($cart[$id_produk]['qty'] > $produk->stok) {
                        return back()->with('error', 'Stok "' . $cart[$id_produk]['nama_produk'] . '" tidak mencukupi. Sisa: ' . $produk->stok . ' buah.');
                    }
                    $checkoutCart[$id_produk] = $cart[$id_produk];
                }
            }
        }

        if (empty($checkoutCart)) {
            return back()->with('error', 'Tidak ada produk valid yang dipilih.');
        }

        session()->put('checkout_cart', $checkoutCart);

        return redirect()->route('checkout.show');
    }

    // ==========================================
    // FITUR : PROSES CHECKOUT + SIMULASI BAYAR
    // ==========================================
    public function processCheckout(Request $request)
    {
        $checkoutCart = session('checkout_cart', []);

        if (empty($checkoutCart)) {
            return redirect('/keranjang')->with('error', 'Sesi checkout telah berakhir. Silakan pilih produk kembali.');
        }

        $metodeBayar = $request->input('metode_bayar', 'BCA Virtual Account');

        $totalHargaProduk = 0;
        foreach ($checkoutCart as $id_produk => $item) {
            $produk = DB::table('produk')->where('id_produk', $id_produk)->first();
            if (!$produk) {
                return back()->with('error_checkout', 'Produk tidak ditemukan.');
            }
            if ($item['qty'] > $produk->stok) {
                return back()->with('error_checkout', 'Stok "' . $item['nama_produk'] . '" tidak mencukupi. Sisa: ' . $produk->stok . ' buah.');
            }
            $totalHargaProduk += round($item['qty'] * $item['harga']);
        }

        try {
            DB::beginTransaction();

            $idOrderBaru = DB::table('orders')->insertGetId([
                'id_user' => auth()->user()->id,
                'tanggal_order' => now(),
                'alamat_pengiriman' => 'Alamat Default Pembeli',
                'total_harga_produk' => round($totalHargaProduk),
                'total_ongkir' => 0,
                'grand_total' => round($totalHargaProduk),
                'created_at' => now(),
            ], 'id_order');

            foreach ($checkoutCart as $id_produk => $item) {
                $produkAsli = DB::table('produk')->where('id_produk', $id_produk)->first();

                if ($produkAsli) {
                    $idDetailOrderBaru = DB::table('detail_order')->insertGetId([
                        'id_order' => $idOrderBaru,
                        'id_vendor' => $produkAsli->id_vendor,
                        'status_order' => 'diproses',
                        'kurir_pengiriman' => 'Reguler - J&T',
                        'ongkir_per_vendor' => 0,
                        'created_at' => now(),
                    ], 'id_detail_order');

                    DB::table('item_order')->insert([
                        'id_detail_order' => $idDetailOrderBaru,
                        'id_produk' => $id_produk,
                        'jumlah_beli' => $item['qty'],
                        'harga_saat_beli' => round($item['harga']),
                        'created_at' => now(),
                    ]);

                    DB::table('produk')
                        ->where('id_produk', $id_produk)
                        ->decrement('stok', $item['qty']);
                }
            }

            $cart = session('cart', []);
            foreach ($checkoutCart as $id_produk => $item) {
                unset($cart[$id_produk]);
            }
            session()->put('cart', $cart);
            session()->forget('checkout_cart');

            DB::commit();

            return redirect()->route('orders.show', $idOrderBaru)
                ->with('success', 'Pembayaran via ' . $metodeBayar . ' berhasil disimulasikan! Pesanan sedang diproses.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error_checkout', 'Sistem membatalkan pesanan: ' . $e->getMessage());
        }
    }

    public function checkout(Request $request)
    {
        $cartItems = session('cart') ?? [];

        if (empty($cartItems)) {
            return redirect('/keranjang')->with('error', 'Keranjang belanja Anda masih kosong!');
        }

        session()->put('checkout_cart', $cartItems);

        return redirect()->route('checkout.show');
    }

    // ==========================================
    // FITUR : BELI LANGSUNG (Skip Keranjang)
    // ==========================================
    public function buyNow(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|integer',
            'qty' => 'required|integer|min:1',
        ]);

        $id_produk = $request->id_produk;
        $qty = (int) $request->qty;

        $produk = DB::table('produk')
            ->leftJoin('vendors', 'produk.id_vendor', '=', 'vendors.id_vendor')
            ->where('produk.id_produk', $id_produk)
            ->first();

        if (!$produk) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        if ($qty > $produk->stok) {
            return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $produk->stok . ' buah.');
        }

        $harga_final = $produk->harga;
        if (isset($produk->diskon) && $produk->diskon > 0) {
            $harga_final = round($produk->harga - ($produk->harga * ($produk->diskon / 100)));
        }

        session()->put('checkout_cart', [
            $id_produk => [
                'nama_produk' => $produk->nama_produk,
                'qty' => $qty,
                'harga' => $harga_final,
                'harga_asli' => $produk->harga,
                'diskon' => $produk->diskon ?? 0,
                'foto_produk' => $produk->foto_produk,
                'nama_toko' => $produk->nama_toko ?? 'Toko Tidak Diketahui',
                'id_vendor' => $produk->id_vendor,
                'stok' => $produk->stok,
            ],
        ]);

        return redirect()->route('checkout.show');
    }

    // ==========================================
    // FITUR : EKSPOR LAPORAN KE CSV / EXCEL
    // ==========================================
    public function exportCSV()
    {
        $fileName = 'Laporan_Penjualan_' . date('Y-m-d_H-i-s') . '.csv';

        $dataPenjualan = DB::table('orders')
            ->join('users', 'orders.id_user', '=', 'users.id')
            ->join('detail_order', 'orders.id_order', '=', 'detail_order.id_order')
            ->join('item_order', 'detail_order.id_detail_order', '=', 'item_order.id_detail_order')
            ->join('produk', 'item_order.id_produk', '=', 'produk.id_produk')
            ->join('vendors', 'produk.id_vendor', '=', 'vendors.id_vendor')
            ->select(
                'orders.id_order',
                'orders.tanggal_order',
                'users.name as nama_pembeli',
                'users.email',
                'orders.alamat_pengiriman',
                'vendors.nama_toko as vendor',
                'produk.nama_produk',
                'item_order.jumlah_beli',
                'item_order.harga_saat_beli',
                DB::raw('(item_order.jumlah_beli * item_order.harga_saat_beli) as total_harga_item'),
                'detail_order.status_order'
            )
            ->orderBy('orders.tanggal_order', 'desc')
            ->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array(
            'ID Order',
            'Tanggal Order',
            'Nama Pembeli',
            'Email Pembeli',
            'Alamat',
            'Toko / Vendor',
            'Nama Produk',
            'Qty',
            'Harga Satuan',
            'Total Harga',
            'Status Order'
        );

        $callback = function() use($dataPenjualan, $columns) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, $columns, ';');

            foreach ($dataPenjualan as $row) {
                fputcsv($file, array(
                    $row->id_order,
                    $row->tanggal_order,
                    $row->nama_pembeli,
                    $row->email,
                    $row->alamat_pengiriman,
                    $row->vendor,
                    $row->nama_produk,
                    $row->jumlah_beli,
                    $row->harga_saat_beli,
                    $row->total_harga_item,
                    $row->status_order
                ), ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ==========================================
    // FITUR : BERI RATING PRODUK (AKURAT & REAL-TIME)
    // ==========================================
    public function beriRating(Request $request, $id_detail_order, $id_produk)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'ulasan' => 'nullable|string'
        ]);

        $ratingInput = $request->rating;

        DB::table('item_order')
            ->where('id_detail_order', $id_detail_order)
            ->where('id_produk', $id_produk)
            ->update([
                'rating_diberikan' => $ratingInput,
                'ulasan' => $request->ulasan
            ]);

        $rataRataRating = DB::table('item_order')
            ->where('id_produk', $id_produk)
            ->whereNotNull('rating_diberikan')
            ->avg('rating_diberikan');

        DB::table('produk')->where('id_produk', $id_produk)->update([
            'rating' => round($rataRataRating, 1)
        ]);

        return back()->with('success', 'Terima kasih! Ulasan dan rating berhasil disimpan.');
    }

    // ==========================================
    // SIMULASI BAYAR (Ubah ke Diproses)
    // ==========================================
    public function bayarSimulasi($id_order)
    {
        $order = DB::table('orders')
            ->where('id_order', $id_order)
            ->where('id_user', Auth::id())
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan atau bukan milik Anda.');
        }

        DB::table('detail_order')
            ->where('id_order', $id_order)
            ->update([
                'status_order' => 'diproses',
                'updated_at' => now()
            ]);

        return redirect()->route('orders.show', $id_order)
            ->with('success', 'Pembayaran berhasil disimulasikan! Pesanan sekarang sedang diproses oleh Vendor.');
    }

    // ==========================================
    // PESANAN DITERIMA (Ubah ke Selesai)
    // ==========================================
    public function terimaPesanan($id_order)
    {
        $order = DB::table('orders')
            ->where('id_order', $id_order)
            ->where('id_user', Auth::id())
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan atau bukan milik Anda.');
        }

        DB::table('detail_order')
            ->where('id_order', $id_order)
            ->update([
                'status_order' => 'selesai',
                'updated_at' => now()
            ]);

        return redirect()->route('orders.show', $id_order)
            ->with('success', 'Pesanan telah diterima! Silakan berikan ulasan untuk produk Anda.');
    }

    // ==========================================
    // HALAMAN DASHBOARD LAPORAN VENDOR
    // ==========================================
    public function laporanVendor()
    {
        $id_vendor = DB::table('vendors')->where('id_user', Auth::id())->value('id_vendor');

        if (!$id_vendor) {
            return back()->with('error', 'Akun Anda bukan vendor terdaftar.');
        }

        $penjualan = DB::table('item_order')
            ->join('detail_order', 'item_order.id_detail_order', '=', 'detail_order.id_detail_order')
            ->join('orders', 'detail_order.id_order', '=', 'orders.id_order')
            ->join('produk', 'item_order.id_produk', '=', 'produk.id_produk')
            ->join('users', 'orders.id_user', '=', 'users.id')
            ->where('detail_order.id_vendor', $id_vendor)
            ->select(
                'orders.id_order',
                'users.name as nama_pembeli',
                'produk.nama_produk',
                'item_order.jumlah_beli',
                'item_order.harga_saat_beli',
                'detail_order.status_order',
                'orders.tanggal_order'
            )
            ->orderBy('orders.tanggal_order', 'desc')
            ->get();

        $totalPendapatan = 0;
        $totalTerjual = 0;
        foreach ($penjualan as $row) {
            if ($row->status_order == 'selesai' || $row->status_order == 'diproses') {
                $totalPendapatan += ($row->jumlah_beli * $row->harga_saat_beli);
            }
            $totalTerjual += $row->jumlah_beli;
        }

        return view('vendor_laporan', compact('penjualan', 'totalPendapatan', 'totalTerjual'));
    }

    // ==========================================
    // FITUR EKSPOR DATA VENDOR KE EXCEL / CSV
    // ==========================================
    public function exportLaporanVendor()
    {
        $id_vendor = DB::table('vendors')->where('id_user', Auth::id())->value('id_vendor');

        if (!$id_vendor) {
            return back()->with('error', 'Akun Anda bukan vendor terdaftar.');
        }

        $penjualan = DB::table('item_order')
            ->join('detail_order', 'item_order.id_detail_order', '=', 'detail_order.id_detail_order')
            ->join('orders', 'detail_order.id_order', '=', 'orders.id_order')
            ->join('produk', 'item_order.id_produk', '=', 'produk.id_produk')
            ->join('users', 'orders.id_user', '=', 'users.id')
            ->where('detail_order.id_vendor', $id_vendor)
            ->select(
                'orders.id_order',
                'orders.tanggal_order',
                'users.name as nama_pembeli',
                'produk.nama_produk',
                'item_order.jumlah_beli',
                'item_order.harga_saat_beli',
                'detail_order.status_order'
            )
            ->orderBy('orders.tanggal_order', 'desc')
            ->get();

        $fileName = 'Laporan_Penjualan_Vendor_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID Order', 'Tanggal', 'Nama Pembeli', 'Nama Produk', 'Jumlah Beli', 'Harga Satuan', 'Total Harga', 'Status'];

        $callback = function() use($penjualan, $columns) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, $columns, ';');

            foreach ($penjualan as $row) {
                $total = $row->jumlah_beli * $row->harga_saat_beli;

                fputcsv($file, [
                    'INV-000' . $row->id_order,
                    $row->tanggal_order,
                    $row->nama_pembeli,
                    $row->nama_produk,
                    $row->jumlah_beli,
                    $row->harga_saat_beli,
                    $total,
                    strtoupper($row->status_order)
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ==========================================
    // FITUR : ADMIN & VENDOR MANAJEMEN PESANAN
    // ==========================================
    public function updateStatusOrder(Request $request, $id)
    {
        $request->validate([
            'status_order' => 'required|in:menunggu_pembayaran,diproses,dikirim,selesai,dibatalkan'
        ]);

        $status_baru = $request->status_order;

        $detailOrder = DB::table('detail_order')->where('id_detail_order', $id)->first();

        if (!$detailOrder) {
            return back()->with('error', 'Detail Pesanan tidak ditemukan!');
        }

        if ($status_baru == 'dibatalkan' && $detailOrder->status_order != 'dibatalkan') {
            $items = DB::table('item_order')->where('id_detail_order', $id)->get();

            foreach ($items as $item) {
                DB::table('produk')
                    ->where('id_produk', $item->id_produk)
                    ->increment('stok', $item->jumlah_beli);
            }
        } elseif ($status_baru != 'dibatalkan' && $detailOrder->status_order == 'dibatalkan') {
            $items = DB::table('item_order')->where('id_detail_order', $id)->get();
            foreach ($items as $item) {
                DB::table('produk')
                    ->where('id_produk', $item->id_produk)
                    ->decrement('stok', $item->jumlah_beli);
            }
        }

        DB::table('detail_order')
            ->where('id_detail_order', $id)
            ->update([
                'status_order' => $status_baru,
                'updated_at' => now()
            ]);

        return back()->with('success', 'Berhasil! Status pesanan telah diubah menjadi ' . strtoupper($status_baru));
    }

    public function hapusPesanan($id)
    {
        $detailOrder = DB::table('detail_order')->where('id_detail_order', $id)->first();

        if (!$detailOrder) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($detailOrder->status_order != 'dibatalkan' && $detailOrder->status_order != 'selesai') {
            $items = DB::table('item_order')->where('id_detail_order', $id)->get();
            foreach ($items as $item) {
                DB::table('produk')
                    ->where('id_produk', $item->id_produk)
                    ->increment('stok', $item->jumlah_beli);
            }
        }

        DB::table('detail_order')->where('id_detail_order', $id)->delete();

        $sisaDetail = DB::table('detail_order')->where('id_order', $detailOrder->id_order)->count();
        if ($sisaDetail == 0) {
            DB::table('orders')->where('id_order', $detailOrder->id_order)->delete();
        }

        return back()->with('success', 'Pesanan dan Riwayatnya berhasil dihapus permanen! (Stok barang yang belum selesai telah dikembalikan)');
    }

    public function inputResi(Request $request, $id)
    {
        $request->validate([
            'no_resi' => 'required|string|max:100',
            'kurir'   => 'required|string|max:50'
        ]);

        DB::table('detail_order')
            ->where('id_detail_order', $id)
            ->update([
                'kurir_pengiriman' => $request->kurir . ' - ' . $request->no_resi,
                'status_order' => 'dikirim',
                'updated_at' => now()
            ]);

        return back()->with('success', 'Nomor Resi berhasil diinput dan status menjadi DIKIRIM!');
    }
}
