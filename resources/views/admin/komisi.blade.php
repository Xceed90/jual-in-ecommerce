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
            <a href="{{ url('/admin/vendors') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-800 rounded-lg text-gray-300 transition">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Daftar Vendor
            </a>
            <a href="{{ url('/admin/transaksi') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-800 rounded-lg text-gray-300 transition">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                Semua Transaksi
            </a>
            <a href="{{ url('/admin/komisi') }}" class="flex items-center gap-2 px-4 py-3 bg-blue-600 rounded-lg font-bold shadow transition text-white">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Kelola Komisi
            </a>
            <hr class="border-gray-700 my-4">
            <a href="{{ url('/') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-red-600 rounded-lg text-gray-400 hover:text-white transition text-sm">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                Kembali ke Toko
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
                            <svg class="w-4 h-4 inline-block mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg> {{ $k->nama_toko }}
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