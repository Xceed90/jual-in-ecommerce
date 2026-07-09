<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jual-In E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans antialiased text-slate-800">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="bg-blue-50 text-blue-600 p-1.5 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-900 tracking-tight">Jual-In</span>
                    <span class="bg-blue-50 text-blue-600 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-blue-100 hidden sm:block">e-Commerce Pilihan Lokal</span>
                </div>

                <!-- Center Links -->
                <div class="hidden md:flex gap-8">
                    <a href="{{ url('/') }}" class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-1">Beranda</a>
                </div>

                <!-- Right Menu -->
                <div class="flex items-center gap-4">
                    
                    {{-- Keranjang Icon --}}
                    @if(!Auth::check() || Auth::user()->role == 'user')
                        <a href="{{ url('/keranjang') }}" class="relative group p-2 hover:bg-slate-100 rounded-full transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z" />
                            </svg>
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">
                                {{ session('cart') ? array_sum(array_column(session('cart'), 'qty')) : 0 }}
                            </span>
                        </a>
                    @endif

                    @auth
                        {{-- Dashboard Button for Vendor/Admin dengan SVG Profesional --}}
                        @if(Auth::user()->role == 'admin')
                            <a href="{{ url('/admin/vendors') }}" class="text-sm font-semibold text-slate-700 hover:text-blue-600 bg-white px-3 py-1.5 rounded-lg border border-slate-200 transition hidden sm:flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                Panel Admin
                            </a>
                        @elseif(Auth::user()->role == 'vendor')
                            <a href="{{ url('/seller/dashboard') }}" class="text-sm font-semibold text-slate-700 hover:text-blue-600 bg-white px-3 py-1.5 rounded-lg border border-slate-200 transition hidden sm:flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                Dashboard Seller
                            </a>
                        @endif

                        {{-- User Dropdown --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 border border-slate-200 py-1.5 px-3 rounded-full hover:bg-slate-50 transition focus:outline-none bg-white">
                                <div class="w-7 h-7 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="text-left hidden sm:block">
                                    <p class="text-xs font-semibold text-slate-800 leading-none">Halo, {{ explode(' ', Auth::user()->name)[0] }}</p>
                                    <p class="text-[10px] text-slate-500 capitalize leading-tight">{{ Auth::user()->role }}</p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div x-show="open" x-transition.opacity x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 border border-slate-100 z-50" style="display: none;">
                                @if(Auth::user()->role == 'admin')
                                    <a href="{{ url('/admin/vendors') }}" class="flex items-center gap-2 sm:hidden px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        Panel Admin
                                    </a>
                                @elseif(Auth::user()->role == 'vendor')
                                    <a href="{{ url('/seller/dashboard') }}" class="flex items-center gap-2 sm:hidden px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        Dashboard Seller
                                    </a>
                                @endif

                                @if(Auth::user()->role == 'user')
                                    <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                        Riwayat Pembelian
                                    </a>
                                    <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                        Wishlist
                                    </a>
                                @endif
                                <hr class="my-1 border-slate-100">
                                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    @guest
                        <a href="{{ url('/login') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition px-4 py-2 rounded-lg hover:bg-blue-50">Masuk</a>
                        <a href="{{ url('/register') }}" class="text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition px-4 py-2 rounded-lg shadow-sm">Daftar</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section dengan Padding Luas (Mirip Screenshot 2) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="md:w-1/2 pr-0 md:pr-10 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-800 leading-tight mb-4 tracking-tight">
                    Selamat datang di <br>
                    <span class="text-blue-600">Jual-In</span>
                </h1>
                <p class="text-slate-500 text-base md:text-lg mb-8 max-w-md leading-relaxed">
                    Temukan produk terbaik dari berbagai vendor terpercaya dalam satu keranjang belanja terintegrasi.
                </p>
                <div class="flex flex-wrap gap-3">
                    <span class="flex items-center gap-2 bg-white text-slate-600 px-4 py-2 rounded-lg border border-slate-200 text-[13px] font-medium shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        Vendor Terverifikasi
                    </span>
                    <span class="flex items-center gap-2 bg-white text-slate-600 px-4 py-2 rounded-lg border border-slate-200 text-[13px] font-medium shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd" /></svg>
                        Checkout Simulasi Cepat
                    </span>
                    <span class="flex items-center gap-2 bg-white text-slate-600 px-4 py-2 rounded-lg border border-slate-200 text-[13px] font-medium shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        Aman & Terpercaya
                    </span>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-end">
                <div class="relative w-full max-w-sm p-8 bg-white rounded-[1.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
                    <div class="flex gap-2 mb-6">
                        <div class="w-3 h-3 rounded-full bg-blue-400"></div>
                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        <div class="w-3 h-3 rounded-full bg-blue-600"></div>
                    </div>
                    <div class="flex justify-center my-8">
                        <div class="relative rounded-2xl p-4">
                            <svg class="w-24 h-24 text-blue-600 drop-shadow-md" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zm-9.83-3.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.86-7.01L19.42 4l-3.87 7H8.53L4.27 2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l.17-.25z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute top-1/2 left-0 -translate-x-1/2 -translate-y-1/2 bg-white p-3 rounded-xl shadow-lg border border-slate-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                    </div>
                    <div class="absolute top-1/3 right-0 translate-x-1/4 -translate-y-1/2 bg-white px-4 py-2 rounded-xl shadow-lg border border-slate-100 flex items-center gap-1">
                        <span class="text-yellow-400 text-sm">★</span><span class="text-yellow-400 text-sm">★</span><span class="text-yellow-400 text-sm">★</span><span class="text-yellow-400 text-sm">★</span><span class="text-yellow-400 text-sm">★</span>
                    </div>
                    <div class="absolute bottom-6 right-6 bg-white px-4 py-2 rounded-xl shadow-lg border border-slate-100">
                        <span class="text-blue-600 font-bold text-sm">Rp 250.000</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Banner Slider / Carousel -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-4 relative group" x-data="{
        activeSlide: 1,
        slides: [
            { bg: 'bg-[#313C9E]', title: 'Belanja Cerdas,<br>Hemat Maksimal!', desc: 'Dapatkan produk terbaik dari berbagai vendor terpercaya dengan harga terbaik.', cta: 'Belanja Sekarang' },
            { bg: 'bg-[#D9381E]', title: 'Flash Sale<br>Serbu 10RB', desc: 'Diskon hingga 90% untuk produk pilihan hari ini!', cta: 'Cek Sekarang' },
            { bg: 'bg-[#5B2C6F]', title: 'Gratis Ongkir<br>Ke Seluruh Indonesia', desc: 'Tanpa minimum pembelian, belanja sepuasnya tanpa beban ongkir.', cta: 'Lihat Promo' }
        ],
        next() { this.activeSlide = this.activeSlide === this.slides.length ? 1 : this.activeSlide + 1 },
        prev() { this.activeSlide = this.activeSlide === 1 ? this.slides.length : this.activeSlide - 1 },
        init() { setInterval(() => this.next(), 6000) }
    }">
        <div class="overflow-hidden rounded-2xl relative shadow-sm h-[200px] md:h-[260px]">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="activeSlide === index + 1" x-transition.opacity.duration.500ms
                    class="absolute inset-0 flex items-center p-8 md:px-16 text-white" :class="slide.bg">
                    
                    <div class="w-full md:w-2/3 z-10">
                        <h2 class="text-2xl md:text-4xl font-bold mb-3 leading-tight" x-html="slide.title"></h2>
                        <p class="text-xs md:text-sm text-white/90 mb-6 max-w-md" x-text="slide.desc"></p>
                        <a href="#" class="inline-flex items-center gap-2 bg-white text-blue-700 font-semibold px-5 py-2.5 rounded-lg text-sm hover:bg-slate-50 transition">
                            <span x-text="slide.cta"></span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </template>
        </div>

        <button @click="prev" class="absolute left-8 top-1/2 -translate-y-1/2 bg-white/90 text-slate-800 w-8 h-8 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
        </button>
        <button @click="next" class="absolute right-8 top-1/2 -translate-y-1/2 bg-white/90 text-slate-800 w-8 h-8 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
        </button>
    </div>

    <!-- Search Form Filter -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <form action="{{ url('/') }}" method="GET" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-wrap md:flex-nowrap gap-4 items-end">
            <!-- (Form sama seperti sebelumnya) -->
            <div class="w-full md:w-1/4">
                <label class="flex items-center gap-1.5 text-xs font-bold text-slate-600 mb-2">Kategori</label>
                <div class="relative">
                    <select name="kategori" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 appearance-none">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="w-full md:w-1/4">
                <label class="flex items-center gap-1.5 text-xs font-bold text-slate-600 mb-2">Harga Minimum</label>
                <input type="number" name="min_harga" value="{{ request('min_harga') }}" placeholder="10000" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div class="w-full md:w-1/4">
                <label class="flex items-center gap-1.5 text-xs font-bold text-slate-600 mb-2">Harga Maksimum</label>
                <input type="number" name="max_harga" value="{{ request('max_harga') }}" placeholder="500000" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div class="w-full md:w-1/4">
                <label class="flex items-center gap-1.5 text-xs font-bold text-slate-600 mb-2">Minimal Rating</label>
                <select name="rating" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 appearance-none">
                    <option value="">Semua Rating</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5)</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ Ke atas (4+)</option>
                </select>
            </div>
            <div class="w-full md:w-auto flex gap-2">
                <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                    Cari
                </button>
                @if(request()->anyFilled(['kategori', 'min_harga', 'max_harga', 'rating']))
                    <a href="{{ url('/') }}" class="w-full md:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-4 rounded-lg transition text-sm">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Product Section -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">Produk Terbaru untuk Anda</h2>
            </div>
            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1 transition">
                Lihat semua 
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-5 pb-24">
            @foreach($produk as $item)
            <!-- Desain Kartu Persis di Screenshot 3 -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col relative group">
                
                <div class="relative bg-slate-50 pt-[100%] overflow-hidden">
                    <a href="{{ route('produk.detail', $item->id_produk) }}"> 
                        @if($item->foto_produk)
                            <!-- Fitur fallback Data URI. Tidak akan rusak meskipun tidak ada internet -->
                            <img src="{{ asset('storage/produk/' . $item->foto_produk) }}" onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'400\' viewBox=\'0 0 400 400\'><rect width=\'400\' height=\'400\' fill=\'%23f1f5f9\'/><text x=\'50%\' y=\'50%\' font-family=\'sans-serif\' font-size=\'14\' fill=\'%2394a3b8\' text-anchor=\'middle\' dominant-baseline=\'middle\'>Tidak Ada Gambar</text></svg>';" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $item->nama_produk }}">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center bg-slate-100 text-slate-400 text-xs">Tidak Ada Gambar</div>
                        @endif 
                    </a>
                    
                    @if(isset($item->diskon) && $item->diskon > 0)
                        <!-- Desain label merah di kiri atas (Rounded normal sesuai Screenshot) -->
                        <span class="absolute top-2.5 left-2.5 bg-[#f24e4e] text-white text-[11px] font-bold px-2 py-1 rounded-md shadow-sm z-10">
                            -{{ $item->diskon }}%
                        </span>
                    @endif
                    
                    <!-- Wishlist Button di Kanan Atas -->
                    <button type="button" onclick="this.querySelector('svg').classList.toggle('text-red-500'); this.querySelector('svg').classList.toggle('text-slate-300'); this.querySelector('svg').classList.toggle('fill-current');" class="absolute top-2.5 right-2.5 bg-white rounded-full p-1.5 shadow-sm hover:bg-slate-50 transition focus:outline-none z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </button>
                </div>

                <!-- Bagian Keterangan Produk -->
                <div class="p-4 flex flex-col flex-grow">
                    <span class="text-[11px] font-normal text-slate-500 flex items-center gap-1.5 mb-1.5 truncate">
                        <!-- Icon Toko Kecil -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" /></svg>
                        {{ $item->vendor->nama_toko ?? 'Toko Mitra' }}
                    </span>

                    <h3 class="text-sm font-semibold text-slate-800 line-clamp-2 hover:text-blue-600 transition min-h-[40px] leading-snug">
                        <a href="{{ route('produk.detail', $item->id_produk) }}">
                            {{ $item->nama_produk }}
                        </a>
                    </h3>

                    @php
                        $totalReviewer = \Illuminate\Support\Facades\DB::table('item_order')
                                            ->where('id_produk', $item->id_produk)
                                            ->whereNotNull('rating_diberikan')
                                            ->count();
                        $nilaiRating = $item->rating ?? 0;
                    @endphp

                    <div class="flex items-center gap-1 mt-1.5 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <span class="text-xs font-bold text-slate-700">{{ number_format($nilaiRating, 1) }}</span>
                        <span class="text-[11px] text-slate-400">({{ $totalReviewer }} ulasan)</span>
                    </div>

                    <div class="mb-4">
                        @if(isset($item->diskon) && $item->diskon > 0)
                            <span class="text-[11px] line-through text-slate-400 block">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                            @php $hargaDiskon = $item->harga - ($item->harga * ($item->diskon / 100)); @endphp
                            <!-- Font harga disesuaikan, tidak black tapi semibold -->
                            <span class="text-[17px] font-semibold text-blue-600 block leading-tight">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                        @else
                            <span class="text-[11px] text-transparent block">Rp 0</span>
                            <span class="text-[17px] font-semibold text-blue-600 block leading-tight">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                        @endif
                        <span class="block text-[11px] text-slate-500 mt-1">Stok: {{ $item->stok }}</span>
                    </div>

                    <!-- Tombol Aksi Kanan & Kiri -->
                    @if(!Auth::check() || Auth::user()->role == 'user')
                        <div class="flex gap-2 mt-auto">
                            <form action="{{ url('/keranjang/add') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $item->id_produk }}">
                                <button type="submit" class="w-full bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 font-medium text-[11.5px] py-2 px-1 rounded-lg transition text-center flex justify-center items-center h-full">
                                    + Keranjang
                                </button>
                            </form>
                            <form action="{{ route('checkout.buy-now') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $item->id_produk }}">
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" class="w-full bg-blue-600 border border-blue-600 hover:bg-blue-700 text-white font-medium text-[11.5px] py-2 px-1 rounded-lg transition text-center flex justify-center items-center h-full">
                                    Beli Sekarang
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mt-auto">
                            @if(Auth::user()->role == 'admin')
                                <a href="{{ url('/admin/delete/' . $item->id_produk) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="block w-full text-center bg-red-50 hover:bg-red-500 text-red-600 hover:text-white border border-red-200 font-medium text-xs py-2 px-4 rounded-lg transition">
                                    Hapus Produk
                                </a>
                            @else
                                <div class="text-center py-2 px-3 bg-slate-50 text-slate-400 text-[10px] font-medium rounded-lg border border-dashed border-slate-200">
                                    Akun Manajemen
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </main>
</body>
</html>