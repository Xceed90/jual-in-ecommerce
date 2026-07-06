<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Semua Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

    <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-2xl shrink-0">
        <div class="p-6 text-center border-b border-gray-700">
            <h2 class="text-2xl font-black tracking-widest text-blue-400">SUPER ADMIN</h2>
            <p class="text-xs text-gray-400 mt-1">Control Panel</p>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ url('/admin/vendors') }}" class="block px-4 py-3 hover:bg-gray-800 rounded-lg text-gray-300 transition">
                👥 Daftar Vendor
            </a>
            <a href="{{ url('/admin/transaksi') }}" class="block px-4 py-3 bg-blue-600 rounded-lg font-bold shadow transition text-white">
                🛒 Semua Transaksi
            </a>
            <a href="{{ url('/admin/komisi') }}" class="block px-4 py-3 hover:bg-gray-800 rounded-lg text-gray-300 transition">
                💰 Kelola Komisi
            </a>
            <hr class="border-gray-700 my-4">
            <a href="{{ url('/') }}" class="block px-4 py-3 hover:bg-red-600 rounded-lg text-gray-400 hover:text-white transition text-sm">
                ⬅️ Kembali ke Toko
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-10 overflow-y-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Semua Transaksi Platform</h1>
                <p class="text-gray-500 text-sm mt-1">Pantau seluruh aliran pesanan dari semua toko dan pembeli.</p>
            </div>
            <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                <span class="text-gray-500 text-sm font-semibold">Total Order: </span>
                <span class="text-blue-600 font-bold text-lg">{{ count($transaksis) }} Transaksi</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                        <th class="p-4 border-b font-bold">ID Invoice</th>
                        <th class="p-4 border-b font-bold">Tanggal Order</th>
                        <th class="p-4 border-b font-bold">Nama Pembeli</th>
                        <th class="p-4 border-b font-bold">Total Harga Barang</th>
                        <th class="p-4 border-b font-bold">Total Ongkir</th>
                        <th class="p-4 border-b font-bold">Grand Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transaksis as $trx)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="p-4 font-mono font-bold text-blue-600 text-sm">
                            INV-000{{ $trx->id_order }}
                        </td>
                        <td class="p-4 text-gray-600 text-sm">
                            {{ \Carbon\Carbon::parse($trx->tanggal_order)->format('d M Y H:i') }}
                        </td>
                        <td class="p-4 font-bold text-gray-800">
                            👤 {{ $trx->nama_pembeli }}
                        </td>
                        <td class="p-4 text-gray-600 text-sm">
                            Rp {{ number_format($trx->total_harga_produk, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-gray-600 text-sm">
                            Rp {{ number_format($trx->total_ongkir, 0, ',', '.') }}
                        </td>
                        <td class="p-4">
                            <span class="font-extrabold text-green-600 bg-green-50 px-3 py-1 rounded-lg">
                                Rp {{ number_format($trx->grand_total, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if(count($transaksis) == 0)
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400 font-medium">Belum ada satupun transaksi di platform ini.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>