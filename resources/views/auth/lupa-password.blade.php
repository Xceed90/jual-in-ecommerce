<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Jual.In</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-main { animation: fadeInUp 0.5s ease forwards; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center font-sans">
    <div class="w-full max-w-md p-4 animate-main">
        <div class="bg-white border border-slate-100 rounded-3xl shadow-xl p-8">
            <div class="text-center mb-6">
                <span class="text-2xl font-black text-blue-600">Jual<span class="text-orange-500">.In</span></span>
                <h2 class="text-lg font-bold text-slate-800 mt-4">Pulihkan Kata Sandi</h2>
                <p class="text-xs text-slate-500 mt-1">Masukkan email akunmu untuk mendapatkan tautan pemulihan simulasi.</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 bg-red-50 text-red-600 text-xs p-3 rounded-xl">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ url('/lupa-password') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Alamat E-mail</label>
                    <input type="email" name="email" required placeholder="nama@email.com"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-blue-500 transition-all">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3 rounded-xl transition-all">
                    Kirim Tautan Pemulihan 📨
                </button>
            </form>
            
            <div class="text-center mt-6 pt-4 border-t border-slate-100">
                <a href="{{ url('/login') }}" class="text-xs text-blue-600 font-bold hover:underline">← Kembali ke Login</a>
            </div>
        </div>
    </div>
</body>
</html>