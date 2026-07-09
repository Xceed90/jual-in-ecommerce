<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - jual.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans antialiased min-h-screen">

    <header class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-3xl mx-auto px-4 h-14 flex items-center justify-between">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-green-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <h1 class="text-sm font-bold text-gray-800">Riwayat Pesanan</h1>
            <a href="{{ url('/') }}" class="text-sm text-green-600 font-semibold hover:text-green-700 transition">Home</a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-6">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
        @endif

        @if($orders->isEmpty())
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Belum Ada Pesanan</h3>
            <p class="text-sm text-gray-500 mb-6">Yuk, mulai belanja dari berbagai toko!</p>
            <a href="{{ url('/') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">Mulai Belanja</a>
        </div>
        @else
        <div class="space-y-4">
            @foreach($orders as $order)
            @php
                $cek_status = $order->details->first();
                $status_sekarang = $cek_status ? strtolower($cek_status->status_order) : '';
                $statusLabel = match($status_sekarang) {
                    'menunggu_pembayaran' => 'Menunggu Pembayaran',
                    'diproses' => 'Sedang Dikemas',
                    'dikirim' => 'Sedang Dikirim',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                    default => ucfirst($status_sekarang),
                };
                $statusBg = match($status_sekarang) {
                    'menunggu_pembayaran' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'diproses' => 'bg-orange-50 text-orange-600 border-orange-200',
                    'dikirim' => 'bg-blue-50 text-blue-600 border-blue-200',
                    'selesai' => 'bg-green-50 text-green-700 border-green-200',
                    'dibatalkan' => 'bg-red-50 text-red-600 border-red-200',
                    default => 'bg-gray-50 text-gray-600 border-gray-200',
                };
                $totalProduk = $order->details->sum(fn($d) => $d->items->sum('jumlah_beli'));
                $namaToko = $order->details->first()->vendor->nama_toko ?? 'Toko';
                $jumlahToko = $order->details->count();
            @endphp

            <a href="{{ route('orders.show', $order->id_order) }}" class="block bg-white rounded-lg border border-gray-200 shadow-sm hover:border-green-300 hover:shadow-md transition overflow-hidden">
                <div class="px-5 py-3 flex items-center justify-between border-b border-gray-50">
                    <div>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d M Y, H:i') }}</p>
                        <p class="text-sm font-bold text-gray-800 font-mono mt-0.5">INV-000{{ $order->id_order }}</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusBg }}">{{ $statusLabel }}</span>
                </div>

                <div class="px-5 py-4">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="text-sm font-semibold text-gray-700">
                            {{ $namaToko }}
                            @if($jumlahToko > 1)
                                <span class="text-gray-400 font-normal">+{{ $jumlahToko - 1 }} toko lainnya</span>
                            @endif
                        </span>
                    </div>

                    @php $shownItems = 0; @endphp
                    @foreach($order->details as $detail)
                        @foreach($detail->items as $item)
                            @if($shownItems < 2)
                            <div class="flex items-center gap-3 {{ $shownItems > 0 ? 'mt-2' : '' }}">
                                <div class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200 bg-gray-100 shrink-0">
                                    @if($item->produk && $item->produk->foto_produk)
                                        <img src="{{ asset('storage/produk/' . $item->produk->foto_produk) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-700 truncate">{{ $item->produk->nama_produk ?? 'Produk' }}</p>
                                    <p class="text-xs text-gray-400">x{{ $item->jumlah_beli }}</p>
                                </div>
                            </div>
                            @php $shownItems++; @endphp
                            @endif
                        @endforeach
                    @endforeach

                    @if($totalProduk > 2)
                    <p class="text-xs text-gray-400 mt-2">+{{ $totalProduk - 2 }} produk lainnya</p>
                    @endif
                </div>

                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-500">{{ $totalProduk }} produk</span>
                    <span class="text-sm font-bold text-gray-800">Total: Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </main>
</body>
</html>
