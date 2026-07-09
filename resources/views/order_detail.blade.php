<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan INV-000{{ $order->id_order }} - jual.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .star-btn { cursor: pointer; transition: transform 0.1s; }
        .star-btn:hover { transform: scale(1.15); }
        .star-btn.active svg { fill: #f59e0b; stroke: #f59e0b; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased min-h-screen">

    @php
        $cek_status = $order->details->first();
        $status_sekarang = $cek_status ? strtolower($cek_status->status_order) : '';
        $statusLabel = match($status_sekarang) {
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'diproses' => 'Sedang Dikemas',
            'dikirim' => 'Sedang Dikirim',
            'selesai' => 'Pesanan Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($status_sekarang),
        };
        $statusColor = match($status_sekarang) {
            'menunggu_pembayaran' => 'text-amber-600',
            'diproses' => 'text-orange-500',
            'dikirim' => 'text-blue-600',
            'selesai' => 'text-green-600',
            'dibatalkan' => 'text-red-500',
            default => 'text-gray-600',
        };
        $totalProduk = $order->details->sum(fn($d) => $d->items->sum('jumlah_beli'));
    @endphp

    <header class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-2xl mx-auto px-4 h-14 flex items-center justify-between">
            <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-green-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <span class="text-sm font-semibold text-gray-800">Detail Pesanan</span>
            <a href="{{ url('/') }}" class="text-sm text-green-600 font-semibold hover:text-green-700 transition">Home</a>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 py-5 space-y-4">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">No. Pesanan</p>
                        <p class="text-sm font-bold text-gray-800 font-mono">INV-000{{ $order->id_order }}</p>
                    </div>
                    <span class="text-sm font-bold {{ $statusColor }}">{{ $statusLabel }}</span>
                </div>
                <p class="text-xs text-gray-400 mt-2">{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d M Y, H:i') }} WIB</p>
            </div>

            @if($status_sekarang == 'diproses' || $status_sekarang == 'dikirim')
            <div class="px-5 py-3 bg-green-50 border-b border-green-100 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                <p class="text-sm text-green-700 font-medium">Pengirim sedang mempersiapkan pesanan Anda</p>
            </div>
            @elseif($status_sekarang == 'menunggu_pembayaran')
            <div class="px-5 py-3 bg-amber-50 border-b border-amber-100 flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-amber-700 font-medium">Menunggu pembayaran Anda</p>
            </div>
            @elseif($status_sekarang == 'selesai')
            <div class="px-5 py-3 bg-green-50 border-b border-green-100 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-green-700 font-medium">Pesanan telah diterima dan transaksi selesai</p>
            </div>
            @endif

            @foreach($order->details as $detail)
            <div class="px-5 py-4 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-800">{{ $detail->vendor->nama_toko ?? 'Toko' }}</span>
                    </div>
                </div>

                @foreach($detail->items as $item)
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-16 h-16 shrink-0 rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                        @if($item->produk && $item->produk->foto_produk)
                            <img src="{{ asset('storage/produk/' . $item->produk->foto_produk) }}" alt="{{ $item->produk->nama_produk }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 leading-snug line-clamp-2">{{ $item->produk->nama_produk ?? 'Produk' }}</p>
                        <p class="text-sm font-bold text-gray-900 mt-1">Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-sm text-gray-500 shrink-0">x{{ $item->jumlah_beli }}</span>
                </div>
                @endforeach

                <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-100">
                    <span class="text-gray-500">{{ $detail->items->sum('jumlah_beli') }} produk</span>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span class="font-bold text-orange-500">Total Pesanan: Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-800 mb-3">Ringkasan Pembayaran</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Total Harga ({{ $totalProduk }} Barang)</span>
                    <span>Rp {{ number_format($order->total_harga_produk, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Total Ongkos Kirim</span>
                    <span>Rp {{ number_format($order->total_ongkir, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-900 pt-2 border-t border-gray-100">
                    <span>Total Pembayaran</span>
                    <span class="text-green-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-500">Alamat Pengiriman</p>
                <p class="text-sm text-gray-700 mt-0.5">{{ $order->alamat_pengiriman }}</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            @if($status_sekarang == 'menunggu_pembayaran')
            <form action="{{ url('/orders/bayar/' . $order->id_order) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition text-sm">
                    Simulasi Bayar
                </button>
            </form>
            @elseif($status_sekarang == 'diproses' || $status_sekarang == 'dikirim')
            <button type="button" id="btn-terima-pesanan" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-lg transition text-sm">
                Simulasi Pesanan Diterima
            </button>
            @endif

            <a href="{{ route('orders.index') }}" class="flex-1 text-center border border-gray-300 text-gray-700 font-semibold py-3 rounded-lg hover:bg-gray-50 transition text-sm">
                Riwayat Pesanan
            </a>
            <a href="{{ url('/') }}" class="flex-1 text-center bg-white border border-green-600 text-green-600 font-semibold py-3 rounded-lg hover:bg-green-50 transition text-sm">
                Kembali ke Home
            </a>
        </div>

        @if($status_sekarang == 'diproses' || $status_sekarang == 'dikirim')
        <form action="{{ url('/orders/selesai/' . $order->id_order) }}" method="POST" id="form-terima" class="hidden">
            @csrf
        </form>
        @endif

        <div id="section-ulasan" class="{{ $status_sekarang == 'selesai' ? '' : 'hidden' }} space-y-4">
            @foreach($order->details as $detail)
                @foreach($detail->items as $item)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-200 bg-gray-100 shrink-0">
                            @if($item->produk && $item->produk->foto_produk)
                                <img src="{{ asset('storage/produk/' . $item->produk->foto_produk) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $item->produk->nama_produk ?? 'Produk' }}</p>
                            <p class="text-xs text-gray-400">{{ $detail->vendor->nama_toko ?? 'Toko' }}</p>
                        </div>
                    </div>

                    @if(isset($item->rating_diberikan) && $item->rating_diberikan)
                    <div class="space-y-2">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $item->rating_diberikan ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                            <span class="text-sm font-semibold text-gray-700 ml-2">{{ $item->rating_diberikan }}/5</span>
                        </div>
                        @if(isset($item->ulasan) && $item->ulasan)
                            <p class="text-sm text-gray-600 bg-gray-50 rounded-lg p-3 border border-gray-100">"{{ $item->ulasan }}"</p>
                        @endif
                        <p class="text-xs text-green-600 font-medium">Ulasan telah dikirim</p>
                    </div>
                    @else
                    <form action="{{ route('beri.rating', [$detail->id_detail_order, $item->produk->id_produk]) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-2 block">Beri Penilaian</label>
                            <div class="flex items-center gap-1 star-rating" data-form="rating-{{ $detail->id_detail_order }}-{{ $item->produk->id_produk }}">
                                @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-btn" data-value="{{ $i }}">
                                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-{{ $detail->id_detail_order }}-{{ $item->produk->id_produk }}" required>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-2 block">Tulis Ulasan</label>
                            <textarea name="ulasan" rows="3" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 resize-none" placeholder="Ceritakan pengalaman Anda dengan produk ini..."></textarea>
                        </div>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                            Kirim Ulasan
                        </button>
                    </form>
                    @endif
                </div>
                @endforeach
            @endforeach
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnTerima = document.getElementById('btn-terima-pesanan');
            const formTerima = document.getElementById('form-terima');
            const sectionUlasan = document.getElementById('section-ulasan');

            if (btnTerima && formTerima && sectionUlasan) {
                btnTerima.addEventListener('click', function() {
                    if (confirm('Konfirmasi bahwa pesanan sudah Anda terima?')) {
                        sectionUlasan.classList.remove('hidden');
                        btnTerima.classList.add('hidden');
                        formTerima.submit();
                    }
                });
            }

            document.querySelectorAll('.star-rating').forEach(container => {
                const hiddenInput = document.getElementById(container.dataset.form);
                const stars = container.querySelectorAll('.star-btn');

                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const val = parseInt(this.dataset.value);
                        hiddenInput.value = val;
                        stars.forEach((s, i) => {
                            s.classList.toggle('active', i < val);
                            const svg = s.querySelector('svg');
                            if (i < val) {
                                svg.setAttribute('fill', '#f59e0b');
                                svg.setAttribute('stroke', '#f59e0b');
                            } else {
                                svg.setAttribute('fill', 'none');
                                svg.setAttribute('stroke', 'currentColor');
                            }
                        });
                    });

                    star.addEventListener('mouseenter', function() {
                        const val = parseInt(this.dataset.value);
                        stars.forEach((s, i) => {
                            const svg = s.querySelector('svg');
                            if (i < val) {
                                svg.setAttribute('fill', '#fcd34d');
                                svg.setAttribute('stroke', '#fcd34d');
                            }
                        });
                    });

                    star.addEventListener('mouseleave', function() {
                        const current = parseInt(hiddenInput.value) || 0;
                        stars.forEach((s, i) => {
                            const svg = s.querySelector('svg');
                            if (i < current) {
                                svg.setAttribute('fill', '#f59e0b');
                                svg.setAttribute('stroke', '#f59e0b');
                            } else {
                                svg.setAttribute('fill', 'none');
                                svg.setAttribute('stroke', 'currentColor');
                            }
                        });
                    });
                });
            });
        });
    </script>
</body>
</html>
