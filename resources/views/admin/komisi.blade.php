<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Kelola Komisi</title>
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
            <a href="{{ url('/admin/transaksi') }}" class="block px-4 py-3 hover:bg-gray-800 rounded-lg text-gray-300 transition">
                🛒 Semua Transaksi
            </a>
            <a href="{{ url('/admin/komisi') }}" class="block px-4 py-3 bg-blue-600 rounded-lg font-bold shadow transition text-white">
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
                <h1 class="text-3xl font-bold text-gray-800">Pendapatan & Komisi Platform</h1>
                <p class="text-gray-500 text-sm mt-1">Rincian potongan komisi (10%) dari setiap transaksi vendor.</p>
            </div>
            
            <div class="bg-white px-4 py-3 rounded-lg shadow-sm border-l-4 border-green-500">
                <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Pendapatan Platform: </span>
                <div class="text-green-600 font-black text-xl mt-1">
                    @php 
                        $total_cuan_admin = 0;
                        foreach($komisi as $k) {
                            $total_cuan_admin += ($k->total_penjualan * 0.10);
                        }
                    @endphp
                    Rp {{ number_format($total_cuan_admin, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                        <th class="p-4 border-b font-bold">No. Sub-Order</th>
                        <th class="p-4 border-b font-bold">Nama Toko</th>
                        <th class="p-4 border-b font-bold">Total Penjualan</th>
                        <th class="p-4 border-b font-bold bg-green-50 text-green-700">Komisi Admin (10%)</th>
                        <th class="p-4 border-b font-bold">Penerimaan Vendor</th>
                        <th class="p-4 border-b font-bold">Status Pesanan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($komisi as $k)
                    @php
                        // Logika Hitungan
                        $omzet = $k->total_penjualan;
                        $potongan_admin = $omzet * 0.10; // 10%
                        $pendapatan_bersih = $omzet - $potongan_admin;
                    @endphp
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="p-4 font-mono font-bold text-gray-500 text-sm">
                            ORD-{{ $k->id_detail_order }} <br>
                            <span class="text-[10px] text-gray-400 font-sans">{{ \Carbon\Carbon::parse($k->tanggal_order)->format('d M Y') }}</span>
                        </td>
                        <td class="p-4 font-bold text-blue-700">
                            🏪 {{ $k->nama_toko }}
                        </td>
                        <td class="p-4 text-gray-800 font-semibold text-sm">
                            Rp {{ number_format($omzet, 0, ',', '.') }}
                        </td>
                        <td class="p-4 bg-green-50/50">
                            <span class="font-bold text-green-600">
                                + Rp {{ number_format($potongan_admin, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-600 font-bold text-sm">
                            Rp {{ number_format($pendapatan_bersih, 0, ',', '.') }}
                        </td>
                        <td class="p-4">
                            @if($k->status_order == 'selesai')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-[10px] font-bold border border-green-200">SELESAI (CAIR)</span>
                            @else
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-[10px] font-bold border border-yellow-200 uppercase">{{ $k->status_order }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    
                    @if(count($komisi) == 0)
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400 font-medium">Belum ada transaksi vendor yang bisa dihitung komisinya.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>