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
            <a href="{{ url('/admin/vendors') }}" class="flex items-center gap-2 px-4 py-3 bg-blue-600 rounded-lg font-bold shadow transition">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Daftar Vendor
            </a>
            <a href="{{ url('/admin/transaksi') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-800 rounded-lg text-gray-300 transition">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                Semua Transaksi
            </a>
            <a href="{{ url('/admin/komisi') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-800 rounded-lg text-gray-300 transition">
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

        {{-- Flash Message: Sukses --}}
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-300 text-green-800 px-5 py-4 rounded-xl shadow-sm">
                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Flash Message: Error --}}
        @if(session('error'))
            <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-300 text-red-800 px-5 py-4 rounded-xl shadow-sm">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        @endif

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
                                <span class="font-semibold text-blue-700 bg-blue-100 px-3 py-1 rounded-full text-xs"><svg class="w-3.5 h-3.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg> {{ $vendor->nama_toko }}</span>
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
                                        <svg class="w-3.5 h-3.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Setujui
                                    </button>
                                </form>
                            @endif

                            <form action="{{ url('/admin/vendors/hapus/'.$vendor->id) }}" method="POST" onsubmit="return confirm('YAKIN HAPUS VENDOR INI PERMANEN? Semua data produk dan tokonya akan hilang!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow-sm text-xs font-bold transition">
                                    <svg class="w-3.5 h-3.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Hapus
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