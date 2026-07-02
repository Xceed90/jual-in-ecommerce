<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Dashboard - jual.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 md:p-12">

    <div class="max-w-7xl mx-auto">
        <div class="mb-4">
            <a href="{{ url('/') }}" class="text-blue-600 font-bold hover:underline">← Kembali ke Toko depan</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 font-bold shadow-sm border border-green-200">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(Auth::user()->role == 'admin')
            <div class="mb-8 border-b pb-4 border-gray-300">
                <h1 class="text-3xl font-black text-purple-700">👑 Super Admin Dashboard</h1>
                <p class="text-gray-600 mt-1">Kelola perizinan vendor dan awasi produk di platform.</p>
            </div>

<div class="flex justify-between items-center mb-6 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
    <div>
        <h1 class="text-xl font-black text-gray-800">Dashboard Toko</h1>
        <p class="text-xs text-gray-400">Halo, <span class="font-bold text-blue-600">{{ auth()->user()->name }}</span> (Role: {{ auth()->user()->role }})</p>
    </div>
    
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs py-2.5 px-4 rounded-xl border border-red-200 transition">
            🚪 Log Out / Keluar Akun
        </button>
    </form>
</div>

            <div class="bg-white p-6 rounded-xl shadow-md border mb-8">
                <h2 class="text-lg font-bold text-red-600 mb-4">🚨 Menunggu Persetujuan Akun Vendor Baru</h2>
                
                @php
                    $pendingVendors = \App\Models\User::where('role', 'vendor')->where('status', 'pending')->get();
                @endphp

                @if($pendingVendors->count() > 0)
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-red-50 text-red-800 text-sm">
                                <th class="p-3 border">Nama Pemilik Toko</th>
                                <th class="p-3 border">Email</th>
                                <th class="p-3 border text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($pendingVendors as $pv)
                            <tr>
                                <td class="p-3 border font-bold">{{ $pv->name }}</td>
                                <td class="p-3 border">{{ $pv->email }}</td>
                                <td class="p-3 border text-center">
                                    <a href="{{ url('/admin/approve/'.$pv->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded shadow transition">
                                        ✅ Setujui & Kirim Email
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 italic">Saat ini tidak ada pendaftaran vendor baru.</p>
                @endif
            </div>

        @elseif(Auth::user()->role == 'vendor')
            <div class="mb-8 border-b pb-4 border-gray-300">
                <h1 class="text-3xl font-black text-orange-600">🏪 Panel Manajemen Toko</h1>
                <p class="text-gray-600 mt-1">Kelola stok dan etalase produk Anda di sini.</p>
            </div>

           <div class="bg-white p-6 rounded-xl shadow-md border mb-8">
                <h2 class="text-lg font-bold text-gray-700 mb-4">➕ Tambah Produk Baru Ke Etalase</h2>
                
                <form action="{{ url('/admin/store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    
                    <input type="text" name="nama_produk" placeholder="Nama Produk" required class="border p-3 rounded-lg text-sm">
                    <input type="number" name="harga" placeholder="Harga Asli (Rp)" required class="border p-3 rounded-lg text-sm">
                    <input type="number" name="diskon" placeholder="Diskon (%) Contoh: 10" class="border p-3 rounded-lg text-sm" min="0" max="100">
                    <input type="number" name="stok" placeholder="Jumlah Stok" required class="border p-3 rounded-lg text-sm">

                    <select name="id_kategori" required class="border p-3 rounded-lg text-sm bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>

                    @if(Auth::user()->role == 'admin')
                        <select name="id_vendor" required class="border p-3 rounded-lg text-sm bg-white">
                            <option value="">-- Pilih Toko Vendor Pemilik --</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id_vendor }}">{{ $v->nama_toko }}</option>
                            @endforeach
                        </select>
                    @else
                        <div class="border p-3 rounded-lg text-sm bg-gray-100 text-gray-500 font-semibold flex items-center">
                            🏪 Mengunci Etalase: Toko Anda Berhak Mengisi
                        </div>
                    @endif

                    <div class="col-span-1 md:col-span-3">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Upload Foto Produk (Format JPG/PNG)</label>
                        <input type="file" name="foto_produk" required class="w-full border p-2 rounded-lg text-sm bg-white">
                    </div>

                    <input type="text" name="deskripsi" placeholder="Deskripsi Singkat Produk" required class="col-span-1 md:col-span-3 border p-3 rounded-lg text-sm">

                    <button type="submit" class="col-span-1 md:col-span-3 bg-blue-600 hover:bg-blue-700 text-white font-bold p-3 rounded-lg transition shadow-md">
                        Simpan Produk 🚀
                    </button>
                </form>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md border overflow-hidden">
            <div class="p-4 bg-gray-800 text-white font-bold flex justify-between items-center">
                <span>📦 Data Seluruh Produk di Platform</span>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm">
                        <th class="p-4 border">Nama Produk</th>
                        <th class="p-4 border">Vendor</th>
                        <th class="p-4 border">Harga</th>
                        <th class="p-4 border">Stok</th>
                        <th class="p-4 border text-center">Aksi Hapus</th>
                    </tr>
                </thead>
             <tbody class="text-sm divide-y">
                    @foreach($produk as $p)
                    <tr class="hover:bg-gray-50">
                       <td class="p-4 flex items-center gap-3">
                            @if(isset($p->foto_produk))
                                <img src="{{ asset('storage/produk/' . $p->foto_produk) }}" class="w-12 h-12 object-cover rounded-lg border shadow-sm" alt="{{ $p->nama_produk }}">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-400">No Img</div>
                            @endif
                            <span class="font-bold text-gray-800">{{ $p->nama_produk }}</span>
                        </td>

                        <td class="p-4">🏪 {{ $p->vendor->nama_toko ?? 'Toko Tidak Diketahui' }}</td>

                        <td class="p-4">
                            @if(isset($p->diskon) && $p->diskon > 0)
                                <span class="text-xs line-through text-red-400 block">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                @php
                                    $hargaDiskon = $p->harga - ($p->harga * ($p->diskon / 100));
                                @endphp
                                <span class="font-bold text-green-600">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                            @else
                                <span class="font-bold text-green-600">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                            @endif
                        </td>

                        <td class="p-4">{{ $p->stok }}</td>

                        <td class="p-4 text-center">
                            <form action="{{ url('/admin/destroy/' . $p->id_produk) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold p-2 bg-red-50 hover:bg-red-100 rounded-lg transition">🗑️ Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>