<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Daftar Vendor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

    <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-2xl">
        <div class="p-6 text-center border-b border-gray-700">
            <h2 class="text-2xl font-black tracking-widest text-blue-400">SUPER ADMIN</h2>
            <p class="text-xs text-gray-400 mt-1">Control Panel</p>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ url('/admin/vendors') }}" class="block px-4 py-3 bg-blue-600 rounded-lg font-bold shadow transition">
                👥 Daftar Vendor
            </a>
            <a href="{{ url('/admin/transaksi') }}" class="block px-4 py-3 hover:bg-gray-800 rounded-lg text-gray-300 transition">
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

    <main class="flex-1 p-10">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Daftar Vendor Terdaftar</h1>
                <p class="text-gray-500 text-sm mt-1">Pantau semua user yang memiliki akses berjualan.</p>
            </div>
            <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                <span class="text-gray-500 text-sm font-semibold">Total Vendor: </span>
                <span class="text-blue-600 font-bold text-lg">{{ count($vendors) }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                        <th class="p-4 border-b font-bold">ID User / Vendor</th>
                        <th class="p-4 border-b font-bold">Nama Pemilik</th>
                        <th class="p-4 border-b font-bold">Nama Toko</th>
                        <th class="p-4 border-b font-bold">Email Akses</th>
                        <th class="p-4 border-b font-bold">Tanggal Gabung</th>
                        <th class="p-4 border-b font-bold">Status</th>
                        <th class="p-4 border-b font-bold text-center">Aksi (Admin Only)</th> </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    
                    @foreach($vendors as $vendor)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="p-4 text-gray-500 font-mono text-sm">
                            #U-{{ $vendor->id }} <br> 
                            <span class="text-xs text-blue-400">{{ $vendor->id_vendor ? '#V-'.$vendor->id_vendor : 'Belum buka toko' }}</span>
                        </td>
                        <td class="p-4 font-bold text-gray-800">{{ $vendor->name }}</td>
                        <td class="p-4">
                            @if($vendor->nama_toko)
                                <span class="font-semibold text-blue-700 bg-blue-100 px-3 py-1 rounded-full text-xs">🏪 {{ $vendor->nama_toko }}</span>
                            @else
                                <span class="text-gray-400 italic text-sm">Belum diatur</span>
                            @endif
                        </td>
                        <td class="p-4 text-gray-600 text-sm">{{ $vendor->email }}</td>
                        <td class="p-4 text-gray-500 text-sm">{{ \Carbon\Carbon::parse($vendor->created_at)->format('d M Y') }}</td>
                        <td class="p-4">
                            @if(($vendor->status ?? 'pending') == 'approved')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold border border-green-200">APPROVED</span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-bold border border-yellow-200">PENDING</span>
                            @endif
                        </td>
                        
                        <td class="p-4 flex justify-center gap-2">
                            
                            @if(($vendor->status ?? 'pending') != 'approved')
                                <form action="{{ url('/admin/vendors/approve/'.$vendor->id) }}" method="POST" onsubmit="return confirm('Setujui vendor ini untuk berjualan?');">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded shadow-sm text-xs font-bold transition">
                                        ✅ Setujui
                                    </button>
                                </form>
                            @endif

                            <form action="{{ url('/admin/vendors/hapus/'.$vendor->id) }}" method="POST" onsubmit="return confirm('YAKIN HAPUS VENDOR INI PERMANEN? Semua data produk dan tokonya akan hilang!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow-sm text-xs font-bold transition">
                                    🗑️ Hapus
                                </button>
                            </form>
                            
                        </td>
                    </tr>
                    @endforeach
                    @if(count($vendors) == 0)
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-400">Belum ada vendor yang mendaftar di sistem ini.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>