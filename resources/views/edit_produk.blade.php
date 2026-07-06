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
            <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors group">
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
            <form action="{{ url('/produk/update/'.$produk->id_produk) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                
                <div class="col-span-1 md:col-span-2 space-y-1">
                    <label class="block text-sm font-medium text-slate-700">Nama Produk</label>
                    <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" required 
                           class="w-full border border-slate-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none">
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-slate-700">Harga Produk (Rp)</label>
                    <input type="number" name="harga" value="{{ $produk->harga }}" required 
                           class="w-full border border-slate-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none">
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-slate-700">Jumlah Stok</label>
                    <input type="number" name="stok" value="{{ $produk->stok }}" required 
                           class="w-full border border-slate-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none">
                </div>

                <div class="col-span-1 md:col-span-2 space-y-1">
                    <label class="block text-sm font-medium text-slate-700">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="4" required 
                              class="w-full border border-slate-300 px-4 py-3 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none resize-none">{{ $produk->deskripsi }}</textarea>
                </div>

                <div class="col-span-1 md:col-span-2 pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-2">
                   <a href="{{ url('/seller/dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
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
</body>
</html>