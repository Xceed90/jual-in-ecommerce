<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jual-In E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                keyframes: {
                    'fade-in-up': {
                        '0%': { opacity: '0', transform: 'translateY(30px)' },
                        '100%': { opacity: '1', transform: 'translateY(0)' },
                    }
                },
                animation: {
                    // Animasi untuk judul (durasi 0.8 detik)
                    'fade-in-up': 'fade-in-up 1s ease-out forwards',
                    // Animasi untuk deskripsi dengan delay 0.3 detik agar muncul bergantian
                    'fade-in-up-delay': 'fade-in-up 0.8s ease-out 1s forwards',
                }
            }
        }
    }
</script>
</head>
<body class="bg-gray-50 font-sans antialiased">

   <nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-extrabold text-blue-600 tracking-tight">Jual<span class="text-orange-500">.In</span></span>
                    <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-100">UAS Project</span>
                </div>

                <div class="flex items-center gap-6">
                    
                    @auth
                        <div class="text-right hidden sm:block">
                            <p class="text-xs text-gray-400">Masuk sebagai:</p>
                            <p class="text-sm font-bold text-gray-800">👤 {{ auth()->user()->name }} 
                                <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-normal uppercase tracking-wider ml-1">
                                    {{ auth()->user()->role }}
                                </span>
                            </p>
                        </div>

                        @if(auth()->user()->role == 'vendor' || auth()->user()->role == 'admin')
                            <a href="{{ url('/admin') }}" class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition">Dashboard Seller</a>
                        @endif
                        
                        <form action="{{ route('logout') }}" method="POST" class="inline m-0 p-0">
                            @csrf
                            <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-700 transition">
                                🚪 Keluar
                            </button>
                        </form>
                    @endauth

                    @guest
                        <a href="{{ url('/login') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                            🔑 Masuk / Login
                        </a>
                    @endguest

                    <a href="{{ url('/keranjang') }}" class="relative group p-2 bg-gray-100 hover:bg-blue-50 rounded-full transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z" />
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center border border-white">
                            {{ session('cart') ? array_sum(array_column(session('cart'), 'qty')) : 0 }}
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

   <div class="relative bg-white overflow-hidden py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        
        <h1 class="opacity-0 animate-fade-in-up text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl">
            <span class="block">Selamat Datang di <span class="text-indigo-600">Jual-In</span></span>
            <span class="block mt-2 text-3xl sm:text-4xl text-slate-700">Jelajahi Produk Terbaik</span>
        </h1>
        
        <p class="opacity-0 animate-fade-in-up-delay mt-6 max-w-2xl mx-auto text-base text-slate-500 sm:text-lg md:text-xl md:leading-relaxed">
            Uji coba simulasi checkout dari berbagai toko vendor yang berbeda secara langsung dalam satu keranjang belanja terintegrasi.
        </p>

    </div>
    
</div>
<form action="{{ url('/') }}" method="GET" style="background: #ffffff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; margin-bottom: 30px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: flex-end;">
        
        <div style="display: flex; flex-direction: column; gap: 5px;">
            <label style="font-size: 14px; font-weight: 600; color: #374151;">📁 Kategori</label>
            <select name="kategori" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #d1d5db; font-size: 14px; outline: none;">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; flex-direction: column; gap: 5px;">
            <label style="font-size: 14px; font-weight: 600; color: #374151;">💰 Harga Min (Rp)</label>
            <input type="number" name="min_harga" value="{{ request('min_harga') }}" placeholder="Contoh: 10000" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #d1d5db; font-size: 14px; outline: none;">
        </div>

        <div style="display: flex; flex-direction: column; gap: 5px;">
            <label style="font-size: 14px; font-weight: 600; color: #374151;">💸 Harga Max (Rp)</label>
            <input type="number" name="max_harga" value="{{ request('max_harga') }}" placeholder="Contoh: 500000" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #d1d5db; font-size: 14px; outline: none;">
        </div>

        <div style="display: flex; flex-direction: column; gap: 5px;">
            <label style="font-size: 14px; font-weight: 600; color: #374151;">⭐ Minimal Rating</label>
            <select name="rating" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #d1d5db; font-size: 14px; outline: none;">
                <option value="">Semua Rating</option>
                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5) Bintang</option>
                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ Ke atas (4+)</option>
                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ Ke atas (3+)</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="flex: 2; background: #2563eb; color: white; border: none; padding: 10px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; transition: background 0.2s;">
                🔍 Cari
            </button>
            
            @if(request()->anyFilled(['kategori', 'min_harga', 'max_harga', 'rating']))
                <a href="{{ url('/') }}" style="flex: 1; text-align: center; background: #ef4444; color: white; text-decoration: none; padding: 10px; border-radius: 6px; font-weight: 600; font-size: 14px;">
                    🔄 Reset
                </a>
            @endif
        </div>

    </div>
</form>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-black text-gray-800 tracking-tight">🔥 Produk Terbaru Untuk Anda</h2>
                <p class="text-xs text-gray-400 mt-0.5">Produk segar langsung dari gudang vendor terpercaya kami</p>
            </div>
            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">Total: {{ count($produk) }} Produk</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($produk as $item)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md border border-gray-100 overflow-hidden flex flex-col group transition duration-300">
                
                <div class="relative bg-gray-100 pt-[100%] overflow-hidden">
                    @if($item->foto_produk)
                        <img src="{{ asset('storage/produk/' . $item->foto_produk) }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Foto Produk">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center bg-gray-200 text-gray-400 text-xs">Tidak Ada Foto</div>
                    @endif

                    @if(isset($item->diskon) && $item->diskon > 0)
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-sm animate-pulse">
                            KONTAN -{{ $item->diskon }}%
                        </span>
                    @endif
                </div>

                <div class="p-4 flex flex-col flex-grow">
                    <span class="text-[10px] font-bold text-blue-600 tracking-wide uppercase flex items-center gap-1 mb-1">
                        🏪 {{ $item->vendor->nama_toko ?? 'Toko Mitra' }}
                    </span>

                    <h3 class="text-sm font-bold text-gray-800 line-clamp-2 group-hover:text-blue-600 transition min-h-[40px]">
                        {{ $item->nama_produk }}
                    </h3>

                    <div class="mt-2 mb-4">
                        @if(isset($item->diskon) && $item->diskon > 0)
                            <span class="text-xs line-through text-gray-400 block">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                            @php
                                $hargaDiskon = $item->harga - ($item->harga * ($item->diskon / 100));
                            @endphp
                            <span class="text-base font-black text-green-600">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                        @else
                            <span class="text-base font-black text-green-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                        @endif
                        
                        <span class="block text-[10px] text-gray-400 mt-1 font-medium">Sisa Stok: <span class="font-bold text-gray-600">{{ $item->stok }}</span></span>
                    </div>

                    <form action="{{ url('/keranjang/add') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="id_produk" value="{{ $item->id_produk }}">
                        <button type="submit" class="w-full bg-gray-950 hover:bg-blue-600 text-white font-bold text-xs py-2.5 px-4 rounded-xl transition shadow-sm flex items-center justify-center gap-1.5 group-hover:border-transparent">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Keranjang
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </main>

</body>
</html>