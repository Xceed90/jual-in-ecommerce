<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - jual.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        input[type="radio"] { accent-color: #2563eb; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased min-h-screen">

    <header class="bg-white border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 h-14 flex items-center">
            <a href="{{ url('/') }}" class="text-blue-600 font-bold text-xl tracking-tight">jual<span class="text-gray-800">.in</span></a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-6">
        <div class="mb-4 flex items-center gap-4">
            <a href="{{ url('/keranjang') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 transition font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Keranjang
            </a>
            <span class="text-gray-300">|</span>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 transition font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Kembali ke Beranda
            </a>
        </div>

        @if(session('error_checkout'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error_checkout') }}</div>
        @endif

        <div class="flex flex-col lg:flex-row gap-5">

            <div class="flex-1 space-y-4">
                @foreach($groupedByToko as $namaToko => $items)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-800">{{ $namaToko }}</h2>
                    </div>

                    @foreach($items as $item)
                    <div class="flex items-start gap-4 px-5 py-4 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                        <div class="w-16 h-16 shrink-0 rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                            @if(!empty($item['foto_produk']))
                                <img src="{{ asset('storage/produk/' . $item['foto_produk']) }}" alt="{{ $item['nama_produk'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-800 leading-snug">{{ $item['nama_produk'] }}</p>
                            <p class="text-sm font-bold text-gray-900 mt-1">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-400 mt-1">Jumlah: {{ $item['qty'] }}</p>
                        </div>
                        <p class="text-sm font-bold text-gray-800 shrink-0">Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</p>
                    </div>
                    @endforeach

                    <div class="px-5 py-3 bg-gray-50 border-t border-gray-100">
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                <span>Reguler - J&T (Rp 0)</span>
                            </div>
                            <span class="text-gray-400">Estimasi tiba 3-5 hari</span>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold text-gray-800 mb-3">Alamat Pengiriman</h3>
                    <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">Alamat Default Pembeli</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-80 shrink-0 space-y-4">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5 sticky top-6">
                    <h3 class="text-sm font-bold text-gray-800 mb-4">Metode Pembayaran</h3>

                    <div class="space-y-3 mb-5">
                        <label class="flex items-center gap-3 p-3 border border-blue-500 bg-blue-50/50 rounded-lg cursor-pointer">
                            <input type="radio" name="metode_preview" value="BCA Virtual Account" checked class="w-4 h-4">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800">BCA Virtual Account</p>
                                <p class="text-xs text-gray-500">Transfer via Virtual Account BCA</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-gray-300 transition">
                            <input type="radio" name="metode_preview" value="Mandiri Virtual Account" class="w-4 h-4">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800">Mandiri Virtual Account</p>
                                <p class="text-xs text-gray-500">Transfer via Virtual Account Mandiri</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-gray-300 transition">
                            <input type="radio" name="metode_preview" value="Alfamart / Indomaret" class="w-4 h-4">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800">Alfamart / Indomaret</p>
                                <p class="text-xs text-gray-500">Bayar di gerai terdekat</p>
                            </div>
                        </label>
                    </div>

                    <div class="border-t border-gray-100 pt-4 space-y-2 mb-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Ringkasan Transaksi</p>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Total Harga ({{ $totalItem }} Barang)</span>
                            <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Total Ongkos Kirim</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-100">
                            <span>Total Tagihan</span>
                            <span id="display-total">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="button" id="btn-beli-sekarang" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition text-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Beli Sekarang
                    </button>

                    <p class="text-[10px] text-gray-400 text-center mt-3 leading-relaxed">
                        Dengan melanjutkan, Anda menyetujui Syarat & Ketentuan jual.in
                    </p>
                </div>
            </div>
        </div>
    </main>

    <div id="modal-bayar" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" id="modal-overlay"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md relative">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-800">Konfirmasi Pembayaran</h3>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Metode Pembayaran</span>
                            <span class="font-semibold text-gray-800" id="modal-metode">BCA Virtual Account</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Tagihan</span>
                            <span class="font-bold text-blue-600 text-base" id="modal-total">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Jumlah Barang</span>
                            <span class="font-semibold text-gray-800">{{ $totalItem }} item</span>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-3">
                        <p class="text-xs text-blue-700 leading-relaxed">
                            Ini adalah simulasi pembayaran. Klik <strong>Simulasi Bayar</strong> untuk memproses pesanan Anda tanpa transfer sungguhan.
                        </p>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                    <button type="button" id="btn-batal" class="flex-1 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm">Batal</button>
                    <form action="{{ route('checkout.process') }}" method="POST" class="flex-1" id="form-bayar">
                        @csrf
                        <input type="hidden" name="metode_bayar" id="input-metode" value="BCA Virtual Account">
                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition text-sm">
                            Simulasi Bayar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal-bayar');
            const btnBeli = document.getElementById('btn-beli-sekarang');
            const btnBatal = document.getElementById('btn-batal');
            const overlay = document.getElementById('modal-overlay');
            const modalMetode = document.getElementById('modal-metode');
            const inputMetode = document.getElementById('input-metode');
            const metodeRadios = document.querySelectorAll('input[name="metode_preview"]');

            function getSelectedMetode() {
                const checked = document.querySelector('input[name="metode_preview"]:checked');
                return checked ? checked.value : 'BCA Virtual Account';
            }

            metodeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    metodeRadios.forEach(r => {
                        r.closest('label').classList.remove('border-blue-500', 'bg-blue-50/50');
                        r.closest('label').classList.add('border-gray-200');
                    });
                    this.closest('label').classList.remove('border-gray-200');
                    this.closest('label').classList.add('border-blue-500', 'bg-blue-50/50');
                });
            });

            btnBeli.addEventListener('click', function() {
                const metode = getSelectedMetode();
                modalMetode.textContent = metode;
                inputMetode.value = metode;
                modal.classList.remove('hidden');
            });

            function closeModal() {
                modal.classList.add('hidden');
            }

            btnBatal.addEventListener('click', closeModal);
            overlay.addEventListener('click', closeModal);
        });
    </script>
</body>
</html>
