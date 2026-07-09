<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - jual.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        input[type="checkbox"] { accent-color: #16a34a; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased min-h-screen">

    <header class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 h-14 flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-green-600 font-bold text-xl tracking-tight">jual<span class="text-gray-800">.in</span></a>
            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-green-600 transition">Katalog</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-green-600 transition">Pesanan Saya</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-6">
        <h1 class="text-lg font-bold text-gray-800 mb-5">Keranjang Belanja</h1>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
        @endif

        @if(count($cart) > 0)
        <div class="flex flex-col lg:flex-row gap-5">

            <div class="flex-1 space-y-4">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" id="check-all" class="w-4 h-4 rounded" checked>
                            <span class="text-sm font-semibold text-gray-700">Pilih Semua (<span id="total-items">{{ count($cart) }}</span>)</span>
                        </label>
                        <button type="button" id="btn-hapus-terpilih" class="text-sm font-semibold text-green-600 hover:text-green-700 transition hidden">Hapus</button>
                    </div>

                    @foreach($groupedByToko as $namaToko => $itemsToko)
                    <div class="border-b border-gray-100 last:border-b-0" data-toko="{{ $namaToko }}">
                        <div class="flex items-center gap-3 px-4 py-3 bg-gray-50/60">
                            <input type="checkbox" class="check-toko w-4 h-4 rounded" data-toko="{{ $namaToko }}" checked>
                            <span class="text-sm font-bold text-gray-800">{{ $namaToko }}</span>
                        </div>

                        @foreach($itemsToko as $id => $item)
                        @php
                            $subtotal = $item['harga'] * $item['qty'];
                            $stok = $item['stok'] ?? 0;
                            $habis = $stok <= 0;
                            $maksStok = $stok > 0 && $item['qty'] >= $stok;
                        @endphp
                        <div class="flex items-start gap-3 px-4 py-4 border-t border-gray-50 item-row" data-id="{{ $id }}" data-harga="{{ $item['harga'] }}" data-qty="{{ $item['qty'] }}" data-toko="{{ $namaToko }}">
                            <input type="checkbox" class="check-item w-4 h-4 rounded mt-8" name="selected_items[]" value="{{ $id }}" data-toko="{{ $namaToko }}" {{ $habis ? 'disabled' : 'checked' }}>

                            <div class="w-20 h-20 shrink-0 rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                                @if(!empty($item['foto_produk']))
                                    <img src="{{ asset('storage/produk/' . $item['foto_produk']) }}" alt="{{ $item['nama_produk'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-gray-800 leading-snug">{{ $item['nama_produk'] }}</h3>

                                @if(isset($item['diskon']) && $item['diskon'] > 0)
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-sm font-bold text-gray-900">Rp {{ number_format($item['harga'], 0, ',', '.') }}</span>
                                    <span class="text-xs text-gray-400 line-through">Rp {{ number_format($item['harga_asli'], 0, ',', '.') }}</span>
                                    <span class="text-[10px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded">{{ $item['diskon'] }}%</span>
                                </div>
                                @else
                                <p class="text-sm font-bold text-gray-900 mt-1">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                                @endif

                                @if($habis)
                                    <p class="text-xs text-red-500 font-medium mt-2">Stok habis di toko ini</p>
                                @elseif($maksStok)
                                    <p class="text-xs text-amber-600 font-medium mt-2">Stok tersisa {{ $stok }} buah</p>
                                @else
                                    <p class="text-xs text-gray-400 mt-2">Stok: {{ $stok }} buah</p>
                                @endif

                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                        <a href="{{ url('/keranjang/update-qty/'.$id.'?action=minus') }}"
                                           class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition text-lg font-medium">−</a>
                                        <span class="w-10 text-center text-sm font-semibold text-gray-800 border-x border-gray-300">{{ $item['qty'] }}</span>
                                        @if($maksStok || $habis)
                                            <span class="w-8 h-8 flex items-center justify-center text-gray-300 cursor-not-allowed text-lg font-medium">+</span>
                                        @else
                                            <a href="{{ url('/keranjang/update-qty/'.$id.'?action=plus') }}"
                                               class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition text-lg font-medium">+</a>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <a href="{{ url('/keranjang/hapus/'.$id) }}" class="text-gray-400 hover:text-red-500 transition" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </a>
                                        <span class="text-sm font-bold text-gray-800 item-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="w-full lg:w-80 shrink-0">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5 sticky top-20">
                    <h2 class="text-sm font-bold text-gray-800 mb-4">Ringkasan Belanja</h2>

                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Total (<span id="summary-count">0</span> Barang)</span>
                        <span id="summary-total" class="font-bold text-gray-900">Rp 0</span>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-3 mb-5 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition">
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            <span>Belum ada promo</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>

                    @auth
                    <form action="{{ route('checkout.prepare') }}" method="POST" id="form-checkout">
                        @csrf
                        <div id="hidden-inputs"></div>
                        <button type="submit" id="btn-beli" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition text-sm disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            Beli (0)
                        </button>
                    </form>
                    @else
                    <a href="{{ url('/login') }}" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition text-sm">
                        Login untuk Checkout
                    </a>
                    @endauth
                </div>
            </div>
        </div>

        <form action="{{ route('keranjang.hapus-terpilih') }}" method="POST" id="form-hapus" class="hidden">
            @csrf
            <div id="hapus-inputs"></div>
        </form>

        @else
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Keranjang Masih Kosong</h3>
            <p class="text-sm text-gray-500 mb-6">Yuk, cari barang menarik dari berbagai toko!</p>
            <a href="{{ url('/') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">Mulai Belanja</a>
        </div>
        @endif
    </main>

    @if(count($cart) > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('check-all');
            const checkToko = document.querySelectorAll('.check-toko');
            const checkItems = document.querySelectorAll('.check-item:not(:disabled)');
            const btnHapus = document.getElementById('btn-hapus-terpilih');
            const btnBeli = document.getElementById('btn-beli');
            const summaryTotal = document.getElementById('summary-total');
            const summaryCount = document.getElementById('summary-count');
            const hiddenInputs = document.getElementById('hidden-inputs');
            const hapusInputs = document.getElementById('hapus-inputs');
            const formHapus = document.getElementById('form-hapus');

            function getSelectedItems() {
                return Array.from(document.querySelectorAll('.check-item:checked:not(:disabled)'));
            }

            function updateSummary() {
                const selected = getSelectedItems();
                let total = 0;
                let count = 0;

                selected.forEach(cb => {
                    const row = cb.closest('.item-row');
                    const harga = parseInt(row.dataset.harga);
                    const qty = parseInt(row.dataset.qty);
                    total += harga * qty;
                    count += qty;
                });

                summaryTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
                summaryCount.textContent = count;
                btnBeli.textContent = 'Beli (' + selected.length + ')';
                btnBeli.disabled = selected.length === 0;

                hiddenInputs.innerHTML = '';
                selected.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_items[]';
                    input.value = cb.value;
                    hiddenInputs.appendChild(input);
                });

                btnHapus.classList.toggle('hidden', selected.length === 0);
            }

            function syncCheckAll() {
                const allItems = document.querySelectorAll('.check-item:not(:disabled)');
                const checkedItems = document.querySelectorAll('.check-item:checked:not(:disabled)');
                checkAll.checked = allItems.length > 0 && allItems.length === checkedItems.length;
                checkAll.indeterminate = checkedItems.length > 0 && checkedItems.length < allItems.length;
            }

            function syncCheckToko(toko) {
                const tokoItems = document.querySelectorAll('.check-item[data-toko="' + toko + '"]:not(:disabled)');
                const tokoChecked = document.querySelectorAll('.check-item[data-toko="' + toko + '"]:checked:not(:disabled)');
                const tokoCb = document.querySelector('.check-toko[data-toko="' + toko + '"]');
                if (tokoCb) {
                    tokoCb.checked = tokoItems.length > 0 && tokoItems.length === tokoChecked.length;
                    tokoCb.indeterminate = tokoChecked.length > 0 && tokoChecked.length < tokoItems.length;
                }
            }

            checkAll.addEventListener('change', function() {
                document.querySelectorAll('.check-item:not(:disabled)').forEach(cb => cb.checked = this.checked);
                checkToko.forEach(cb => cb.checked = this.checked);
                updateSummary();
            });

            checkToko.forEach(cb => {
                cb.addEventListener('change', function() {
                    const toko = this.dataset.toko;
                    document.querySelectorAll('.check-item[data-toko="' + toko + '"]:not(:disabled)').forEach(item => {
                        item.checked = this.checked;
                    });
                    syncCheckAll();
                    updateSummary();
                });
            });

            document.querySelectorAll('.check-item').forEach(cb => {
                cb.addEventListener('change', function() {
                    syncCheckToko(this.dataset.toko);
                    syncCheckAll();
                    updateSummary();
                });
            });

            btnHapus.addEventListener('click', function() {
                const selected = getSelectedItems();
                if (selected.length === 0) return;
                if (!confirm('Hapus ' + selected.length + ' produk terpilih dari keranjang?')) return;

                hapusInputs.innerHTML = '';
                selected.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_items[]';
                    input.value = cb.value;
                    hapusInputs.appendChild(input);
                });
                formHapus.submit();
            });

            updateSummary();
        });
    </script>
    @endif
</body>
</html>
