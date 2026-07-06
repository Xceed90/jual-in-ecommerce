<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Dashboard - jual.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-indigo-100 selection:text-indigo-900">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-indigo-600 text-white rounded-lg flex items-center justify-center font-bold text-xl leading-none">j.</div>
                    <span class="font-bold text-xl tracking-tight text-slate-900">jual.in <span class="font-medium text-slate-400 text-sm ml-1">Workspace</span></span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}" class="text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                        &larr; Lihat Toko Depan
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-8 font-medium shadow-sm border border-emerald-100 flex items-center gap-3 animate-pulse">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Halo, {{ auth()->user()->name }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-sm text-slate-500">Selamat datang kembali di panel manajemen.</p>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100 uppercase tracking-wide">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="mt-4 md:mt-0">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 bg-white hover:bg-rose-50 text-rose-600 font-semibold text-sm py-2 px-4 rounded-lg border border-slate-200 hover:border-rose-200 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar Sesi
                </button>
            </form>
        </div>

        @if(Auth::user()->role == 'admin')
            
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Persetujuan Akun Vendor</h2>
                    <p class="text-sm text-slate-500">Tinjau dan setujui pendaftaran toko baru.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-12">
                @php
                    $pendingVendors = \App\Models\User::where('role', 'vendor')->where('status', 'pending')->get();
                @endphp

                @if($pendingVendors->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                    <th class="p-4 px-6">Nama Pemilik Toko</th>
                                    <th class="p-4 px-6">Email Terdaftar</th>
                                    <th class="p-4 px-6 text-right">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @foreach($pendingVendors as $pv)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="p-4 px-6 font-semibold text-slate-800">{{ $pv->name }}</td>
                                    <td class="p-4 px-6 text-slate-500">{{ $pv->email }}</td>
                                    <td class="p-4 px-6 text-right">
                                        <a href="{{ url('/admin/approve/'.$pv->id) }}" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2 rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Setujui Akun
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-base font-semibold text-slate-800">Tidak ada pengajuan tertunda</h3>
                        <p class="text-sm text-slate-500 mt-1">Semua vendor telah ditinjau dan disetujui.</p>
                    </div>
                @endif
            </div>

        @elseif(Auth::user()->role == 'vendor')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-center gap-5 hover:shadow-md transition">
                    <div class="bg-emerald-100 p-4 rounded-2xl text-emerald-600 border border-emerald-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
                        <h3 class="text-2xl font-black text-slate-800 mt-1">Rp {{ number_format($total_penjualan ?? 0, 0, ',', '.') }}</h3>
                        <p class="text-[11px] text-emerald-500 font-bold mt-1">✅ Saldo Tersedia</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-center gap-5 hover:shadow-md transition">
                    <div class="bg-indigo-100 p-4 rounded-2xl text-indigo-600 border border-indigo-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Order Aktif</p>
                        <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $order_masuk ?? 0 }} <span class="text-sm font-bold text-slate-400">Pesanan</span></h3>
                        <p class="text-[11px] text-indigo-500 font-bold mt-1">📦 Perlu Diproses</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 h-full flex flex-col">
                    <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                        🔥 Produk Terlaris Anda
                    </h4>
                    <ul class="space-y-3 flex-1">
                        @if(isset($produk_terlaris) && count($produk_terlaris) > 0)
                            @foreach($produk_terlaris as $top)
                            <li class="flex items-center justify-between border-b border-slate-100 pb-2 last:border-0 last:pb-0">
                                <div class="flex items-center gap-3">
                                    @if($top->foto_produk)
                                        <img src="{{ asset('storage/produk/' . $top->foto_produk) }}" class="w-10 h-10 rounded-lg object-cover shadow-sm border border-slate-200">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-[9px] font-medium text-slate-400 border border-slate-200">No Pic</div>
                                    @endif
                                    <span class="text-xs font-semibold text-slate-700 line-clamp-1 max-w-[100px]">{{ $top->nama_produk }}</span>
                                </div>
                                <span class="text-[10px] font-black text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-md shadow-sm">
                                    {{ $top->total_terjual }} Terjual
                                </span>
                            </li>
                            @endforeach
                        @else
                            <li class="text-xs text-slate-400 italic text-center py-4 bg-slate-50 rounded-xl border border-slate-100">Belum ada penjualan.</li>
                        @endif
                    </ul>
                </div>

            </div>
            <div class="mb-6">
                </ul>
                </div>

            </div>

            <div class="mb-6 flex items-center justify-between mt-8">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">📦 Daftar Pesanan Masuk</h2>
                    <p class="text-sm text-slate-500">Pantau dan proses pesanan dari pelanggan Anda.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-12">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                <th class="p-4 px-6">Tanggal & ID</th>
                                <th class="p-4 px-6">Nama Pembeli</th>
                                <th class="p-4 px-6">Alamat Pengiriman</th>
                                <th class="p-4 px-6">Status Pesanan</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($daftar_pesanan as $pesanan)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="p-4 px-6">
                                    <span class="block font-bold text-slate-800">#ORD-{{ $pesanan->id_order }}</span>
                                    <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($pesanan->tanggal_order)->format('d M Y, H:i') }}</span>
                                </td>
                                <td class="p-4 px-6 font-semibold text-slate-700">
                                    👤 {{ $pesanan->nama_pembeli }}
                                </td>
                                <td class="p-4 px-6 text-slate-600 text-xs max-w-xs truncate">
                                    {{ $pesanan->alamat_pengiriman }}
                                </td>
                                <td class="p-4 px-6">
                                    @if($pesanan->status_order == 'selesai')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">✅ Selesai</span>
                                    @elseif($pesanan->status_order == 'diproses')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">⚙️ Diproses</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">⏳ {{ ucfirst(str_replace('_', ' ', $pesanan->status_order)) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-400 italic">
                                    Belum ada pesanan masuk ke toko Anda.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-900">Tambahkan Produk Baru</h2>
                <p class="text-sm text-slate-500">Isi detail produk untuk ditampilkan di etalase toko Anda.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 mb-12">
                <form action="{{ url('/admin/store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">Nama Produk</label>
                        <input type="text" name="nama_produk" placeholder="Misal: Sepatu Sneakers Pria" required class="w-full border border-slate-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">Harga Asli (Rp)</label>
                        <input type="number" name="harga" placeholder="Misal: 250000" required class="w-full border border-slate-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">Diskon Produk (%)</label>
                        <input type="number" name="diskon" placeholder="Opsional (0-100)" class="w-full border border-slate-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none" min="0" max="100">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">Jumlah Stok</label>
                        <input type="number" name="stok" placeholder="Stok tersedia" required class="w-full border border-slate-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">Kategori</label>
                        <select name="id_kategori" required class="w-full border border-slate-300 px-4 py-2.5 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none">
                            <option value="" disabled selected>Pilih kategori yang sesuai</option>
                            @foreach($kategori as $k)
                                <!-- KEMBALIKAN KODE OPTION INI: -->
                                <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">Status Vendor</label>
                      @if(Auth::user()->role == 'admin')
                            <select name="id_vendor" required class="w-full border border-slate-300 px-4 py-2.5 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                                <option value="" disabled selected>Pilih Toko Vendor Pemilik</option>
                                @foreach($vendors as $v)
                                    <option value="{{ $v->id_vendor }}">{{ $v->nama_toko }}</option>
                                @endforeach
                            </select>
                        @else
                            <div class="w-full border border-slate-200 bg-slate-50 text-slate-500 px-4 py-2.5 rounded-lg text-sm flex items-center cursor-not-allowed">
                                Terkunci untuk Toko Anda
                            </div>
                        @endif
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-1">
                        <label class="block text-sm font-medium text-slate-700">Upload Foto Produk</label>
                        <div class="mt-1 flex flex-col items-center justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-indigo-400 transition-colors bg-slate-50/50 relative min-h-[180px]">
                            
                            <div id="preview-container" class="hidden absolute inset-0 w-full h-full bg-white rounded-xl overflow-hidden flex items-center justify-center z-10 p-2">
                                <img id="image-preview" src="#" alt="Draf Foto Produk" class="w-full h-full object-contain rounded-lg">
                                <button type="button" onclick="resetUpload()" class="absolute top-3 right-3 bg-slate-900/90 hover:bg-rose-600 text-white py-1.5 px-3 rounded-lg text-xs font-semibold transition-colors shadow-md z-20 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Ganti Foto
                                </button>
                            </div>

                            <div id="upload-instruction" class="space-y-1 text-center transition-opacity duration-200">
                                <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-slate-600 justify-center">
                                    <label class="relative cursor-pointer bg-transparent rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Pilih file gambar</span>
                                        <input type="file" id="foto_produk" name="foto_produk" required class="sr-only" onchange="previewImage(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-slate-500">PNG, JPG, GIF hingga 2MB</p>
                            </div>

                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-1">
                        <label class="block text-sm font-medium text-slate-700">Deskripsi Singkat</label>
                        <textarea name="deskripsi" placeholder="Tuliskan spesifikasi atau keunggulan produk di sini..." required rows="3" class="w-full border border-slate-300 px-4 py-3 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none resize-none"></textarea>
                    </div>

                    <div class="col-span-1 md:col-span-2 pt-2">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 px-4 rounded-xl transition-all shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 flex justify-center items-center gap-2">
                            Simpan ke Etalase
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <div class="mb-6 mt-12 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Inventaris Produk</h2>
                <p class="text-sm text-slate-500">Kelola semua produk yang tersedia di sistem.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                            <th class="p-4 px-6">Informasi Produk</th>
                            <th class="p-4 px-6">Toko / Vendor</th>
                            <th class="p-4 px-6">Harga Final</th>
                            <th class="p-4 px-6 text-center">Stok</th>
                            <th class="p-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        @foreach($produk as $p)
                        <tr class="hover:bg-slate-50/70 transition-colors group">
                            
                            <td class="p-4 px-6">
                                <div class="flex items-center gap-4">
                                    @if(isset($p->foto_produk))
                                        <div class="w-14 h-14 rounded-xl border border-slate-200 overflow-hidden flex-shrink-0 bg-slate-50">
                                            <img src="{{ asset('storage/produk/' . $p->foto_produk) }}" class="w-full h-full object-cover" alt="{{ $p->nama_produk }}">
                                        </div>
                                    @else
                                        <div class="w-14 h-14 rounded-xl border border-slate-200 flex items-center justify-center bg-slate-50 flex-shrink-0">
                                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="block font-semibold text-slate-900">{{ $p->nama_produk }}</span>
                                        <span class="text-xs text-slate-400 mt-0.5 block">ID: {{ $p->id_produk ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="p-4 px-6 text-slate-600">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $p->vendor->nama_toko ?? 'Internal' }}
                                </div>
                            </td>

                            <td class="p-4 px-6">
                                @if(isset($p->diskon) && $p->diskon > 0)
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2">
                                            @php
                                                $hargaDiskon = $p->harga - ($p->harga * ($p->diskon / 100));
                                            @endphp
                                            <span class="font-bold text-slate-900">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-600 border border-rose-200">
                                                -{{ $p->diskon }}%
                                            </span>
                                        </div>
                                        <span class="text-xs line-through text-slate-400 mt-0.5">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                    </div>
                                @else
                                    <span class="font-bold text-slate-900">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                @endif
                            </td>
                                
                            <td class="p-4 px-6 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold {{ $p->stok > 0 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                    {{ $p->stok }} Tersedia
                                </span>
                            </td>
                            <td class="p-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ url('/produk/edit/' . $p->id_produk) }}" 
                                class="inline-flex items-center justify-center text-amber-500 hover:text-white bg-transparent hover:bg-amber-500 font-medium text-sm p-2 rounded-lg transition-all border border-transparent hover:border-amber-600">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </a>

                                <a href="{{ url('/admin/delete/' . $p->id_produk) }}" 
                                onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini permanen?');" 
                                class="inline-flex items-center justify-center text-rose-500 hover:text-white bg-transparent hover:bg-rose-500 font-medium text-sm p-2 rounded-lg transition-all border border-transparent hover:border-rose-600">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </a>
                            </div>
                        </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</body>
<script>
        function previewImage(input) {
            const container = document.getElementById('preview-container');
            const preview = document.getElementById('image-preview');
            const instruction = document.getElementById('upload-instruction');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                // Proses membaca file gambar dari lokal komputer
                reader.onload = function(e) {
                    preview.src = e.target.result; // Pasang gambar ke tag <img>
                    container.classList.remove('hidden'); // Tampilkan draf foto
                    instruction.classList.add('opacity-0'); // Sembunyikan teks instruksi biar rapi
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function resetUpload() {
            const input = document.getElementById('foto_produk');
            const container = document.getElementById('preview-container');
            const instruction = document.getElementById('upload-instruction');
            
            input.value = ""; // Reset isi input file menjadi kosong kembali
            container.classList.add('hidden'); // Sembunyikan draf foto
            instruction.classList.remove('opacity-0'); // Munculkan kembali teks instruksi bawaan
        }
    </script>
</html>