<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - jual.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <a href="{{ url('/') }}" class="text-blue-600 hover:underline">← Kembali ke Katalog Produk</a>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-2">Simulasi Fitur Multi-Vendor</h1>
        <p class="text-gray-600 mb-8">Halaman ini membuktikan data 1 Invoice dipecah otomatis ke vendor yang berbeda.</p>

        @foreach($orders as $order)
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8">
            
            <div class="bg-gray-800 p-6 text-white flex justify-between items-center">
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-400">Nomor Invoice</p>
                    <p class="text-xl font-mono font-bold">INV-000{{ $order->id_order }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs uppercase tracking-wider text-gray-400">Pembeli</p>
                    <p class="font-bold text-lg">{{ $order->user->name }}</p>
                </div>
            </div>
            <br>
               <a href="{{ url('/orders/export-csv') }}" 
   style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; padding: 10px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.4); transition: all 0.3s ease; border: 1px solid #047857; margin-bottom: 20px;"
   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px -2px rgba(16, 185, 129, 0.6)';" 
   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(16, 185, 129, 0.4)';">
    
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
        <polyline points="14 2 14 8 20 8"></polyline>
        <path d="M12 18v-6"></path>
        <path d="M9 15l3 3 3-3"></path>
    </svg>
    
    Ekspor Laporan Penjualan
</a>
            <div class="p-6 bg-gray-50 border-b border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-500">📍 Alamat Pengiriman:</p>
                    <p class="text-gray-700 text-sm mt-1">{{ $order->alamat_pengiriman }}</p>
                </div>
                <div class="text-right flex flex-col justify-center">
                    <p class="text-sm text-gray-600">Total Belanja: <span class="font-semibold">Rp {{ number_format($order->total_harga_produk, 0, ',', '.') }}</span></p>
                    <p class="text-sm text-gray-600">Total Ongkir: <span class="font-semibold">Rp {{ number_format($order->total_ongkir, 0, ',', '.') }}</span></p>
                    <p class="text-xl font-extrabold text-blue-600 mt-1">Grand Total: Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <h3 class="text-lg font-bold text-red-500">
                    ⚡ Sistem Mendeteksi {{ $order->details->count() }} Vendor di Dalam Invoice Ini:
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($order->details as $detail)
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-5 bg-white">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs text-gray-400">Nama Vendor / Toko:</p>
                                <h4 class="text-lg font-bold text-gray-800">🏪 {{ $detail->vendor->nama_toko }}</h4>
                            </div>
                            <span class="px-2 py-1 text-xs font-bold rounded bg-yellow-100 text-yellow-800">
                                {{ strtoupper($detail->status_order) }}
                            </span>
                        </div>

                        <div class="bg-gray-50 p-2 rounded text-xs text-gray-600 mb-4 flex justify-between">
                            <p>🚚 Kurir: <strong>{{ $detail->kurir_pengiriman }}</strong></p>
                            <p>Ongkir Toko Ini: <strong>Rp {{ number_format($detail->ongkir_per_vendor, 0, ',', '.') }}</strong></p>
                        </div>

                      <div class="space-y-2">
                            @foreach($detail->items as $item)
                            <div class="flex flex-col text-sm p-3 bg-blue-50 rounded gap-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $item->produk->nama_produk }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->jumlah_beli }} x Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="font-bold text-gray-700">Rp {{ number_format($item->jumlah_beli * $item->harga_saat_beli, 0, ',', '.') }}</p>
                                </div>
                                
                                <form action="{{ url('/orders/rating/' . $item->id_produk) }}" method="POST" class="flex items-center gap-2 border-t border-blue-100 pt-2">
                                    @csrf
                                    <span class="text-xs font-semibold text-gray-600">Berikan Penilaian:</span>
                                    <select name="rating" required class="px-2 py-1 rounded border border-gray-300 text-xs outline-none focus:border-blue-500 bg-white cursor-pointer">
                                        <option value="">Pilih ⭐</option>
                                        <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                        <option value="4">⭐⭐⭐⭐ (4)</option>
                                        <option value="3">⭐⭐⭐ (3)</option>
                                        <option value="2">⭐⭐ (2)</option>
                                        <option value="1">⭐ (1)</option>
                                    </select>
                                    
                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white border-none px-3 py-1 rounded font-bold text-xs cursor-pointer transition duration-200">
                                        Kirim Rating
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
        @endforeach
    </div>

</body>
</html>