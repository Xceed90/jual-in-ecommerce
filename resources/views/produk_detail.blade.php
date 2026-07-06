<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk->nama_produk }} - Detail Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ url('/') }}" class="text-blue-600 hover:underline">← Kembali ke Katalog</a>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8 p-6 flex flex-col md:flex-row gap-6">
           <div class="w-full md:w-1/3 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center h-64 relative border border-gray-100">
                @if($produk->foto_produk)
                    <img src="{{ asset('storage/produk/' . $produk->foto_produk) }}" class="w-full h-full object-cover" alt="Foto {{ $produk->nama_produk }}">
                @else
                    <div class="text-gray-400 text-xs flex flex-col items-center gap-1">
                        <span>🖼️</span>
                        <span>Tidak Ada Foto</span>
                    </div>
                @endif
            </div>
            
            <div class="w-full md:w-2/3 flex flex-col justify-center">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $produk->nama_produk }}</h1>
                <p class="text-gray-500 mb-4">Toko: <span class="font-semibold text-gray-700">{{ $produk->nama_vendor ?? 'Vendor' }}</span></p>
                
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-yellow-500 text-lg">
                        @if($produk->rating)
                            @for($i = 0; $i < round($produk->rating); $i++) ⭐ @endfor
                        @else
                            <span class="text-sm text-gray-400">Belum ada rating</span>
                        @endif
                    </span>
                    @if($produk->rating)
                        <span class="text-sm font-bold text-gray-700">({{ $produk->rating }} / 5.0)</span>
                    @endif
                </div>

                <div class="text-2xl font-bold text-blue-600 mb-4">
                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                </div>
                
                <p class="text-sm text-gray-600 mb-6">Sisa Stok: <span class="font-bold">{{ $produk->stok }}</span></p>

                <form action="{{ url('/keranjang/add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition w-full md:w-auto">
                        🛒 Tambah ke Keranjang
                    </button>
                </form>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-4">Ulasan Pembeli ({{ $ulasanList->count() }})</h2>
        
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden p-6">
            @if($ulasanList->isEmpty())
                <p class="text-gray-500 text-center py-4">Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan ulasan!</p>
            @else
                <div class="flex flex-col gap-4">
                    @foreach($ulasanList as $ulasan)
                        <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-semibold text-gray-800">{{ $ulasan->name }}</span>
                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($ulasan->updated_at)->diffForHumans() }}</span>
                            </div>
                            <div class="text-yellow-500 text-xs mb-2">
                                @for($i = 0; $i < $ulasan->rating_diberikan; $i++) ⭐ @endfor
                            </div>
                            <p class="text-gray-600 text-sm italic">"{{ $ulasan->ulasan }}"</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</body>
</html>