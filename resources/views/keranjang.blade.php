<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Jual-In</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans antialiased pb-12">

    <nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
            <a href="{{ url('/') }}" class="text-blue-600 font-bold hover:underline">← Kembali Belanja</a>
            <span class="text-xl font-black text-gray-800">🛒 Keranjang Saya</span>
            
            <div class="flex items-center gap-4">
                @auth
                    <span class="text-sm font-bold text-gray-600 hidden sm:block">👤 {{ auth()->user()->name }}</span>
                @endauth
            </div>
        </div>
    </nav>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 font-bold text-sm border border-green-200">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            @if(session('cart') && count(session('cart')) > 0)
                <div class="divide-y divide-gray-100">
                    @php $totalSemua = 0; @endphp
                    
                    @foreach(session('cart') as $id => $details)
                        @php $subtotal = $details['harga'] * $details['qty']; $totalSemua += $subtotal; @endphp
                        
                        <div class="py-4 flex gap-4 items-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden border">
                                @if(isset($details['foto_produk']))
                                    <img src="{{ asset('storage/produk/' . $details['foto_produk']) }}" class="w-full h-full object-cover">
                                @endif
                            </div>

                            <div class="flex-1">
                                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded uppercase">
                                    🏪 {{ $details['nama_toko'] }}
                                </span>
                                <h3 class="font-bold text-gray-800 mt-1">{{ $details['nama_produk'] }}</h3>
                              <div class="flex items-center gap-3 mt-2">
    <a href="{{ url('/keranjang/update-qty/'.$id.'?action=minus') }}" class="w-6 h-6 flex items-center justify-center bg-gray-200 hover:bg-red-500 hover:text-white rounded text-sm font-bold transition">−</a>
    
    <span class="font-bold text-gray-800 text-sm">{{ $details['qty'] }}</span>
    
    <a href="{{ url('/keranjang/update-qty/'.$id.'?action=plus') }}" class="w-6 h-6 flex items-center justify-center bg-gray-200 hover:bg-green-500 hover:text-white rounded text-sm font-bold transition">+</a>
</div>
                            </div>

                            <div class="text-right">
                                <p class="font-black text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                <a href="{{ url('/keranjang/hapus/'.$id) }}" class="text-red-500 hover:text-red-700 text-xs font-bold mt-2 inline-block">🗑️ Hapus</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-dashed mt-4 pt-6 text-right">
                    <p class="text-gray-500 font-semibold mb-1">Total Pembayaran:</p>
                    <h2 class="text-3xl font-black text-orange-600 mb-6">Rp {{ number_format($totalSemua, 0, ',', '.') }}</h2>
                    
                    <button onclick="alert('Ini adalah simulasi! Dalam dunia nyata, ini akan diarahkan ke Payment Gateway (Midtrans/OVO/Transfer Bank) untuk membayar tagihan ke berbagai toko sekaligus.')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition">
                        🔒 Proses Pembayaran
                    </button>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-4xl mb-4">🛒</p>
                    <h3 class="text-lg font-bold text-gray-800">Keranjang Masih Kosong</h3>
                    <p class="text-gray-500 text-sm mt-2">Yuk, cari barang menarik dari berbagai vendor!</p>
                    <a href="{{ url('/') }}" class="mt-6 inline-block bg-blue-600 text-white font-bold px-6 py-2 rounded-lg">Mulai Belanja</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>