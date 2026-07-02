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
                            <div class="flex justify-between items-center text-sm p-2 bg-blue-50 rounded">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $item->produk->nama_produk }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->jumlah_beli }} x Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}</p>
                                </div>
                                <p class="font-bold text-gray-700">Rp {{ number_format($item->jumlah_beli * $item->harga_saat_beli, 0, ',', '.') }}</p>
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