<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk->nama_produk }} - Detail Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom scrollbar to match Tokopedia vibe slightly */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <!-- Toast Notification -->
    <div id="toast-cart" class="fixed top-20 right-4 z-[200] transform translate-x-full opacity-0 transition-all duration-500 ease-out pointer-events-none">
        <div class="bg-white rounded-xl shadow-2xl border border-gray-100 p-4 flex items-center gap-3 min-w-[320px]">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900">Berhasil ditambahkan!</p>
                <p class="text-xs text-gray-500">Produk sudah masuk ke keranjang belanja Anda.</p>
            </div>
            <button onclick="closeToast()" class="ml-auto text-gray-400 hover:text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Navbar Sederhana --}}
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center h-16 gap-4">
                <a href="{{ url('/') }}" class="text-blue-600 font-black text-2xl tracking-tight">jual<span class="text-gray-800">.in</span></a>
                <div class="flex-1 px-8 hidden md:block">
                    <div class="relative">
                        <input type="text" placeholder="Cari di jual.in" class="w-full border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        {{-- Breadcrumb --}}
        <nav class="flex text-gray-500 text-xs font-medium mb-6 gap-2 items-center">
            <a href="{{ url('/') }}" class="hover:text-blue-600">Home</a>
            <span>/</span>
            <span class="text-blue-600 hover:text-blue-700 cursor-pointer">{{ $produk->nama_toko ?? 'Toko' }}</span>
            <span>/</span>
            <span class="text-gray-800 truncate max-w-[200px]">{{ $produk->nama_produk }}</span>
        </nav>

        {{-- Main Layout (3 Columns for Desktop) --}}
        <div class="flex flex-col lg:flex-row gap-8">
            
            {{-- Column 1: Gambar Produk --}}
            <div class="w-full lg:w-[350px] shrink-0">
                <div class="bg-white rounded-xl overflow-hidden border border-gray-200 aspect-square sticky top-24 flex items-center justify-center relative">
                    @if($produk->foto_produk)
                        <img src="{{ asset('storage/produk/' . $produk->foto_produk) }}" class="w-full h-full object-cover" alt="Foto {{ $produk->nama_produk }}">
                    @else
                        <div class="text-gray-400 flex flex-col items-center gap-2">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm font-medium">Tidak Ada Foto</span>
                        </div>
                    @endif

                    @if(isset($produk->diskon) && $produk->diskon > 0)
                        <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-black px-2 py-1 rounded-md shadow-sm">
                            -{{ $produk->diskon }}%
                        </div>
                    @endif
                </div>
            </div>

            {{-- Column 2: Detail Produk (Tengah) --}}
            <div class="w-full lg:flex-1">
                <h1 class="text-xl font-bold text-gray-900 leading-tight mb-2">{{ $produk->nama_produk }}</h1>
                
                @php
                    $totalUlasan = $ulasanList->count();
                    $rataRating = $totalUlasan > 0 ? $ulasanList->avg('rating_diberikan') : 0;
                    $terjual = \Illuminate\Support\Facades\DB::table('item_order')
                        ->join('detail_order', 'item_order.id_detail_order', '=', 'detail_order.id_detail_order')
                        ->where('item_order.id_produk', $produk->id_produk)
                        ->where('detail_order.status_order', 'selesai')
                        ->sum('item_order.jumlah_beli');
                @endphp

                <div class="flex items-center gap-3 text-sm text-gray-600 mb-4 border-b border-gray-100 pb-4">
                    <span>Terjual <span class="font-bold text-gray-800">{{ $terjual }}+</span></span>
                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <span class="font-bold text-gray-800">{{ number_format($rataRating, 1) }}</span>
                        <span class="text-gray-500">({{ $totalUlasan }} rating)</span>
                    </div>
                </div>

                <div class="mb-6">
                    @if(isset($produk->diskon) && $produk->diskon > 0)
                        @php $hargaDiskon = round($produk->harga - ($produk->harga * ($produk->diskon / 100))); @endphp
                        <h2 class="text-3xl font-black text-gray-900 mb-1">Rp{{ number_format($hargaDiskon, 0, ',', '.') }}</h2>
                        <div class="flex items-center gap-2">
                            <span class="bg-red-100 text-red-600 text-[10px] font-bold px-1.5 py-0.5 rounded">{{ $produk->diskon }}%</span>
                            <span class="text-sm text-gray-400 line-through">Rp{{ number_format($produk->harga, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <h2 class="text-3xl font-black text-gray-900">Rp{{ number_format($produk->harga, 0, ',', '.') }}</h2>
                    @endif
                </div>

                <div class="border-t border-b border-gray-100 py-4 mb-6">
                    <div class="flex flex-col gap-3 text-sm">
                        <div class="flex"><span class="w-32 text-gray-500">Kondisi</span><span class="font-medium text-gray-800">Baru</span></div>
                        <div class="flex"><span class="w-32 text-gray-500">Min. Pemesanan</span><span class="font-medium text-gray-800">1 Buah</span></div>
                        <div class="flex"><span class="w-32 text-gray-500">Etalase</span><span class="font-medium text-blue-600">{{ $produk->nama_kategori ?? 'Kategori Produk' }}</span></div>
                    </div>
                </div>

                <div class="text-gray-700 text-sm leading-relaxed pb-8">
                    <h3 class="font-bold text-base text-gray-900 mb-2">Detail Produk</h3>
                    <p class="whitespace-pre-line">{{ $produk->deskripsi ?? 'Tidak ada deskripsi detail untuk produk ini.' }}</p>
                </div>

                {{-- Bagian Toko --}}
                <div class="border-t border-gray-200 pt-6 flex items-center gap-4 pb-8">
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-base flex items-center gap-1">
                            {{ $produk->nama_toko ?? 'Toko Vendor' }}
                            <svg class="w-4 h-4 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Online Hari Ini</p>
                    </div>
                    <button class="ml-auto border border-blue-600 text-blue-600 font-bold px-4 py-1.5 rounded-lg text-sm hover:bg-blue-50 transition">
                        Follow
                    </button>
                </div>

            </div>

            {{-- Column 3: Checkout Card (Kanan - Sticky) --}}
            <div class="w-full lg:w-[320px] shrink-0">
                <div class="border border-gray-200 rounded-xl p-4 bg-white sticky top-24 shadow-[0_4px_12px_rgba(0,0,0,0.05)]">
                    <h3 class="font-bold text-gray-900 text-base mb-4">Atur jumlah dan catatan</h3>
                    
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden h-8 w-24">
                            <button type="button" id="btn-minus" class="w-8 h-full bg-white text-gray-500 hover:bg-gray-50 flex items-center justify-center font-bold text-lg">-</button>
                            <input type="text" id="qty-display" value="1" readonly class="w-8 h-full text-center text-sm font-semibold text-gray-800 border-x border-gray-300 focus:outline-none">
                            <button type="button" id="btn-plus" class="w-8 h-full bg-white text-blue-600 hover:bg-blue-50 flex items-center justify-center font-bold text-lg">+</button>
                        </div>
                        <div class="text-sm">
                            Stok: <span class="font-bold {{ $produk->stok > 10 ? 'text-gray-800' : 'text-red-600' }}">{{ $produk->stok }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-6 pt-4 border-t border-gray-100">
                        <span class="text-gray-500 text-sm">Subtotal</span>
                        <span id="subtotal-display" class="font-bold text-gray-900 text-lg">
                            Rp{{ number_format(isset($produk->diskon) && $produk->diskon > 0 ? $hargaDiskon : $produk->harga, 0, ',', '.') }}
                        </span>
                    </div>

                    @if(!Auth::check() || Auth::user()->role == 'user')
                        <div class="flex flex-col gap-2">
                            <form id="form-add-cart" action="{{ url('/keranjang/add') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                                <input type="hidden" name="qty" id="qty-input-form" value="1">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg text-sm transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                                    + Keranjang
                                </button>
                            </form>
                            <form id="form-beli-langsung" action="{{ route('checkout.buy-now') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                                <input type="hidden" name="qty" id="qty-input-buynow" value="1">
                                <button type="button" id="btn-beli-langsung" class="w-full bg-white border border-blue-600 hover:bg-blue-50 text-blue-600 font-bold py-2.5 rounded-lg text-sm transition">
                                    Beli Sekarang
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-gray-100 text-gray-500 text-xs text-center py-3 rounded-lg font-medium border border-dashed border-gray-300">
                            Mode Manajemen: Anda tidak bisa membeli.
                        </div>
                    @endif

                    <div class="flex items-center justify-center gap-6 mt-6 text-xs font-semibold text-gray-600">
                        <button onclick="alert('Fitur Chat akan segera hadir! Hubungi penjual melalui halaman toko.')" class="flex items-center gap-1.5 hover:text-blue-600 transition px-3 py-2 rounded-lg hover:bg-blue-50"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg> Chat</button>
                        <button onclick="this.querySelector('svg').classList.toggle('fill-red-500'); this.querySelector('svg').classList.toggle('text-red-500'); var s = this.querySelector('span'); s.textContent = s.textContent === 'Wishlist' ? 'Tersimpan!' : 'Wishlist';" class="flex items-center gap-1.5 hover:text-red-500 transition px-3 py-2 rounded-lg hover:bg-red-50"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg> <span>Wishlist</span></button>
                        <button onclick="if(navigator.share){navigator.share({title:'{{ $produk->nama_produk }}',url:window.location.href})}else{navigator.clipboard.writeText(window.location.href);alert('Link produk berhasil disalin!')}" class="flex items-center gap-1.5 hover:text-blue-500 transition px-3 py-2 rounded-lg hover:bg-blue-50"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg> Share</button>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ url('/') }}" class="w-full flex items-center justify-center gap-2 text-sm font-semibold text-gray-500 hover:text-blue-600 py-2 rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bagian Ulasan Pembeli (Mirip Tokopedia) --}}
        <div class="mt-16 pt-8 border-t border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-6 uppercase tracking-wide">Ulasan Pembeli</h2>
            
            <div class="flex flex-col md:flex-row gap-8">
                {{-- Box Rating Overview --}}
                <div class="w-full md:w-[400px]">
                    <div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm flex flex-col md:flex-row gap-6 items-center md:items-start">
                        
                        <div class="text-center shrink-0">
                            <div class="flex items-end justify-center gap-1 mb-1">
                                <svg class="w-8 h-8 text-yellow-400 mb-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <span class="text-4xl font-black text-gray-900 leading-none">{{ number_format($rataRating, 1) }}</span>
                                <span class="text-sm text-gray-400 font-medium pb-1">/5.0</span>
                            </div>
                            <p class="text-sm font-bold text-gray-800">
                                @if($rataRating >= 4.5) 100% pembeli merasa puas
                                @elseif($rataRating >= 4) 80% pembeli merasa puas
                                @elseif($rataRating > 0) Sebagian pembeli merasa puas
                                @else Belum ada rating
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ $totalUlasan }} rating • {{ $totalUlasan }} ulasan</p>
                        </div>

                        {{-- Progress Bars --}}
                        <div class="flex-1 w-full space-y-2">
                            @php
                                $bintangCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                                foreach($ulasanList as $u) {
                                    $b = round($u->rating_diberikan);
                                    if(isset($bintangCounts[$b])) $bintangCounts[$b]++;
                                }
                            @endphp
                            
                            @foreach([5, 4, 3, 2, 1] as $b)
                                @php 
                                    $count = $bintangCounts[$b];
                                    $percent = $totalUlasan > 0 ? ($count / $totalUlasan) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <span class="w-3 flex items-center font-bold text-gray-600 gap-0.5">
                                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        {{ $b }}
                                    </span>
                                    <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full {{ $b == 5 ? 'bg-green-500' : 'bg-gray-400' }}" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="w-6 text-right">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- List Ulasan Pilihan --}}
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-900 text-sm">ULASAN PILIHAN</h3>
                        <div class="flex items-center gap-2 text-xs text-gray-600">
                            <span>Urutkan</span>
                            <select class="border border-gray-300 rounded-lg px-2 py-1.5 outline-none focus:border-blue-500">
                                <option>Paling Membantu</option>
                                <option>Terbaru</option>
                            </select>
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-500 mb-6">Menampilkan {{ $totalUlasan }} dari {{ $totalUlasan }} ulasan</p>

                    @if($totalUlasan == 0)
                        <div class="text-center py-12 border border-dashed border-gray-300 rounded-xl bg-gray-50">
                            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <h4 class="font-bold text-gray-700 text-base">Belum ada ulasan</h4>
                            <p class="text-sm text-gray-500 mt-1">Jadilah yang pertama memberikan ulasan untuk produk ini!</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($ulasanList as $ulasan)
                                <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex text-yellow-400">
                                            @for($i = 0; $i < 5; $i++)
                                                @if($i < $ulasan->rating_diberikan)
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($ulasan->updated_at)->diffForHumans() }}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs uppercase overflow-hidden">
                                            {{ substr($ulasan->name, 0, 2) }}
                                        </div>
                                        <span class="font-bold text-gray-800 text-sm">{{ $ulasan->name }}</span>
                                    </div>

                                    <p class="text-gray-800 text-sm leading-relaxed mb-4">
                                        {{ $ulasan->ulasan }}
                                    </p>

                                    <div class="flex items-center gap-4 text-xs font-semibold text-gray-500">
                                        <button class="flex items-center gap-1 hover:text-blue-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                                            Membantu
                                        </button>
                                        <button class="flex items-center gap-1 hover:text-gray-800 transition">
                                            Lihat Balasan
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>

    {{-- Modal Konfirmasi Beli Sekarang --}}
    <div id="modal-beli-langsung" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="modal-beli-langsung-backdrop"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all">
                <div class="bg-blue-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg">Konfirmasi Beli Sekarang</h3>
                    <p class="text-blue-100 text-sm mt-0.5">Periksa pesanan Anda sebelum melanjutkan</p>
                </div>
                <div class="px-6 py-5 space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500 shrink-0">Produk</span>
                        <span class="font-semibold text-gray-800 text-right">{{ $produk->nama_produk }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Toko</span>
                        <span class="font-medium text-gray-800">{{ $produk->nama_toko ?? 'Toko Vendor' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Jumlah</span>
                        <span id="modal-qty" class="font-medium text-gray-800">1 buah</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-gray-100">
                        <span class="text-gray-500 font-medium">Total Bayar</span>
                        <span id="modal-subtotal" class="font-black text-blue-600 text-lg">
                            Rp{{ number_format(isset($produk->diskon) && $produk->diskon > 0 ? $hargaDiskon : $produk->harga, 0, ',', '.') }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 bg-gray-50 rounded-lg p-3 leading-relaxed">
                        Pesanan akan langsung diproses ke checkout. Stok produk akan terpotong setelah konfirmasi.
                    </p>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex gap-3">
                    <button type="button" id="btn-batal-beli" class="flex-1 py-2.5 rounded-lg border border-gray-300 text-gray-600 font-bold text-sm hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button type="button" id="btn-konfirmasi-beli" class="flex-1 py-2.5 rounded-lg bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 transition shadow-sm">
                        Ya, Lanjut Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Login Diperlukan --}}
    <div id="modal-login-required" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="modal-login-backdrop"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h3 class="font-bold text-gray-900 text-lg mb-2">Login Diperlukan</h3>
                <p class="text-gray-500 text-sm mb-5">Silakan masuk ke akun Anda terlebih dahulu untuk melanjutkan pembelian.</p>
                <div class="flex gap-3">
                    <button type="button" id="btn-tutup-login" class="flex-1 py-2.5 rounded-lg border border-gray-300 text-gray-600 font-bold text-sm hover:bg-gray-100 transition">Tutup</button>
                    <a href="{{ url('/login') }}" class="flex-1 py-2.5 rounded-lg bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 transition flex items-center justify-center">Login Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toast functions (global scope)
        function showToast() {
            var toast = document.getElementById('toast-cart');
            if (!toast) return;
            toast.classList.remove('translate-x-full', 'opacity-0', 'pointer-events-none');
            toast.classList.add('translate-x-0', 'opacity-100');
            setTimeout(function() { closeToast(); }, 4000);
        }

        function closeToast() {
            var toast = document.getElementById('toast-cart');
            if (!toast) return;
            toast.classList.add('translate-x-full', 'opacity-0', 'pointer-events-none');
            toast.classList.remove('translate-x-0', 'opacity-100');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const btnMinus = document.getElementById('btn-minus');
            const btnPlus = document.getElementById('btn-plus');
            const inputQtyDisplay = document.getElementById('qty-display');
            const inputQtyForm = document.getElementById('qty-input-form');
            const inputQtyBuynow = document.getElementById('qty-input-buynow');
            const subtotalElement = document.getElementById('subtotal-display');
            const modalQty = document.getElementById('modal-qty');
            const modalSubtotal = document.getElementById('modal-subtotal');

            const hargaSatuan = {{ round(isset($produk->diskon) && $produk->diskon > 0 ? $hargaDiskon : $produk->harga) }};
            const maxStok = {{ $produk->stok }};
            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

            function formatRupiah(angka) {
                return 'Rp' + new Intl.NumberFormat('id-ID').format(angka).replace(/,/g, '.');
            }

            function syncQty(qty) {
                inputQtyDisplay.value = qty;
                if (inputQtyForm) inputQtyForm.value = qty;
                if (inputQtyBuynow) inputQtyBuynow.value = qty;
                updateSubtotal(qty);
            }

            function updateSubtotal(qty) {
                const subtotal = Math.round(qty * hargaSatuan);
                subtotalElement.innerText = formatRupiah(subtotal);
                if (modalQty) modalQty.innerText = qty + ' buah';
                if (modalSubtotal) modalSubtotal.innerText = formatRupiah(subtotal);
            }

            if (btnMinus && btnPlus) {
                btnMinus.addEventListener('click', function() {
                    let currentQty = parseInt(inputQtyDisplay.value);
                    if (currentQty > 1) syncQty(currentQty - 1);
                });

                btnPlus.addEventListener('click', function() {
                    let currentQty = parseInt(inputQtyDisplay.value);
                    if (currentQty < maxStok) {
                        syncQty(currentQty + 1);
                    } else {
                        alert('Maksimal pembelian adalah stok yang tersedia (' + maxStok + ' buah)');
                    }
                });
            }

            const btnBeliLangsung = document.getElementById('btn-beli-langsung');
            const modalBeli = document.getElementById('modal-beli-langsung');
            const modalLogin = document.getElementById('modal-login-required');
            const formBeliLangsung = document.getElementById('form-beli-langsung');

            function openModal(el) { el.classList.remove('hidden'); }
            function closeModal(el) { el.classList.add('hidden'); }

            if (btnBeliLangsung) {
                btnBeliLangsung.addEventListener('click', function() {
                    if (!isLoggedIn) {
                        openModal(modalLogin);
                        return;
                    }
                    updateSubtotal(parseInt(inputQtyDisplay.value));
                    openModal(modalBeli);
                });
            }

            document.getElementById('btn-batal-beli')?.addEventListener('click', () => closeModal(modalBeli));
            document.getElementById('modal-beli-langsung-backdrop')?.addEventListener('click', () => closeModal(modalBeli));
            document.getElementById('btn-tutup-login')?.addEventListener('click', () => closeModal(modalLogin));
            document.getElementById('modal-login-backdrop')?.addEventListener('click', () => closeModal(modalLogin));

            document.getElementById('btn-konfirmasi-beli')?.addEventListener('click', function() {
                inputQtyBuynow.value = inputQtyDisplay.value;
                formBeliLangsung.submit();
            });

            // AJAX Cart submission with toast notification
            const formAddCart = document.getElementById('form-add-cart');
            if (formAddCart) {
                formAddCart.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).then(function(response) {
                        if (response.ok || response.redirected) {
                            showToast();
                        }
                    }).catch(function() {
                        showToast();
                    });
                });
            }
        });
    </script>
</body>
</html>