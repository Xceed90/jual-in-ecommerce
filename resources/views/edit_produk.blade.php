<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - jual.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-indigo-100 selection:text-indigo-900">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-indigo-600 text-white rounded-lg flex items-center justify-center font-bold text-xl leading-none">j.</div>
                    <span class="font-bold text-xl tracking-tight text-slate-900">jual.in <span class="font-medium text-slate-400 text-sm ml-1">Workspace</span></span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100 uppercase tracking-wide">
                        Mode Edit
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="mb-6">
            <a href="{{ url('/seller/dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l-7-7m7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Edit Detail Produk</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui informasi produk Anda. Perubahan akan langsung diterapkan pada etalase toko.</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
            <!-- PENTING: Tambahkan enctype multipart/form-data agar form bisa mengirim file -->
            <form action="{{ url('/produk/update/'.$produk->id_produk) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                
                <!-- BAGIAN FOTO PRODUK SESUAI SCREENSHOT -->
                <div class="col-span-1 md:col-span-2 space-y-3 p-5 bg-slate-50 border border-slate-200 rounded-xl @error('foto_produk') border-red-200 bg-red-50/30 @enderror">
                    <label class="block text-sm font-semibold @error('foto_produk') text-red-700 @else text-slate-700 @enderror">Foto Produk</label>
                    <div class="flex flex-col sm:flex-row items-start gap-6">
                        <div class="w-32 h-32 shrink-0 bg-white border @error('foto_produk') border-red-300 @else border-slate-200 @enderror rounded-lg overflow-hidden flex items-center justify-center shadow-sm">
                            @if($produk->foto_produk)
                                <img id="preview-gambar" src="{{ asset('storage/produk/' . $produk->foto_produk) }}?v={{ time() }}" alt="Preview" class="w-full h-full object-cover">
                            @else
                                <img id="preview-gambar" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                <span id="placeholder-teks" class="text-xs text-slate-400">Tidak ada foto</span>
                            @endif
                        </div>
                        
                        <div class="space-y-2 flex-1 w-full mt-2 sm:mt-0">
                            <input type="file" name="foto_produk" id="foto_produk" accept="image/*"
                                   class="block w-full text-sm text-slate-500
                                          file:mr-4 file:py-2.5 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-semibold
                                          @error('foto_produk') 
                                              file:bg-red-100 file:text-red-700 hover:file:bg-red-200
                                          @else 
                                              file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 
                                          @enderror
                                          cursor-pointer transition-colors outline-none">
                            <p class="text-xs @error('foto_produk') text-red-500 font-medium @else text-slate-500 @enderror leading-relaxed">
                                *Biarkan kosong jika tidak ingin mengubah gambar. (Maks: 3MB, Format: JPG/PNG)
                            </p>
                            
                            <!-- PESAN ERROR VALIDASI UX WRITING TAMPIL DI SINI -->
                            @error('foto_produk')
                                <div class="mt-3 p-3.5 rounded-lg bg-red-50 border border-red-200 flex items-start gap-3 animate-pulse">
                                    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <p class="text-sm text-red-700 font-medium leading-relaxed">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2 space-y-1">
                    <label class="block text-sm font-medium text-slate-700">Nama Produk</label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required 
                           class="w-full border border-slate-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none">
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-slate-700">Harga Produk (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga', $produk->harga) }}" required 
                           class="w-full border border-slate-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none">
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-slate-700">Jumlah Stok</label>
                    <input type="number" name="stok" value="{{ old('stok', $produk->stok) }}" required 
                           class="w-full border border-slate-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none">
                </div>

                <div class="col-span-1 md:col-span-2 space-y-1">
                    <label class="block text-sm font-medium text-slate-700">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="4" required 
                              class="w-full border border-slate-300 px-4 py-3 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none resize-none">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                </div>

                <div class="col-span-1 md:col-span-2 pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-2">
                    <a href="{{ url('/seller/dashboard') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-xl transition-all shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </main>

    <!-- Script untuk Live Preview Gambar -->
    <script>
        document.getElementById('foto_produk').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImg = document.getElementById('preview-gambar');
                    const placeholderTeks = document.getElementById('placeholder-teks');
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    if (placeholderTeks) placeholderTeks.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>