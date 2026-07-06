<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Laporan Penjualan Vendor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">

    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">📊 Laporan Penjualan Toko</h1>
                <p class="text-sm text-gray-500">Pantau performa dagangan Anda dan unduh datanya untuk pembukuan.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ url('/') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2.5 px-5 rounded-xl text-sm transition">
                    ← Beranda
                </a>
                <a href="{{ route('vendor.laporan.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-5 rounded-xl text-sm transition flex items-center gap-2 shadow-md">
                    📥 Ekspor ke Excel (.CSV)
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Total Pendapatan Bersih</p>
                    <p class="text-3xl font-extrabold text-blue-600 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
                <div class="text-3xl bg-blue-50 p-3 rounded-xl">💰</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Total Produk Terjual</p>
                    <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalTerjual }} Unit</p>
                </div>
                <div class="text-3xl bg-gray-50 p-3 rounded-xl">📦</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50">
                <h2 class="text-lg font-bold text-gray-800">Rincian Transaksi Masuk</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 font-semibold text-xs uppercase border-b border-gray-100">
                            <th class="p-4">ID Order</th>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Pembeli</th>
                            <th class="p-4">Nama Produk</th>
                            <th class="p-4 text-center">Qty</th>
                            <th class="p-4">Harga Satuan</th>
                            <th class="p-4">Total</th>
                            <th class="p-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                        @if($penjualan->isEmpty())
                            <tr>
                                <td colspan="8" class="p-8 text-center text-gray-400 italic">Belum ada rincian transaksi produk terjual.</td>
                            </tr>
                        @else
                            @foreach($penjualan as $row)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="p-4 font-mono text-xs font-bold text-gray-700">INV-000{{ $row->id_order }}</td>
                                <td class="p-4 text-xs text-gray-400">{{ date('d M Y', strtotime($row->tanggal_order)) }}</td>
                                <td class="p-4 font-medium text-gray-800">{{ $row->nama_pembeli }}</td>
                                <td class="p-4 text-gray-700">{{ $row->nama_produk }}</td>
                                <td class="p-4 text-center font-semibold">{{ $row->jumlah_beli }}</td>
                                <td class="p-4">Rp {{ number_format($row->harga_saat_beli, 0, ',', '.') }}</td>
                                <td class="p-4 font-bold text-blue-600">Rp {{ number_format($row->jumlah_beli * $row->harga_saat_beli, 0, ',', '.') }}</td>
                                <td class="p-4 text-center">
                                    @if($row->status_order == 'selesai')
                                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Selesai</span>
                                    @elseif($row->status_order == 'diproses')
                                        <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Diproses</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">{{ $row->status_order }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>