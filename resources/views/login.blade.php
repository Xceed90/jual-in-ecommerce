<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Jual.In</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInBlob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, -20px) scale(1.1); }
        }
        @keyframes fadeInBlob2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-20px, 20px) scale(1.1); }
        }
        .animate-main { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-blob-1 { animation: fadeInBlob 7s infinite alternate ease-in-out; }
        .animate-blob-2 { animation: fadeInBlob2 7s infinite alternate ease-in-out; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center relative overflow-hidden font-sans">

    <div class="absolute top-[-10%] left-[-10%] w-[400px] h-[400px] bg-blue-400/10 rounded-full blur-3xl animate-blob-1"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[400px] h-[400px] bg-orange-400/10 rounded-full blur-3xl animate-blob-2"></div>

    <div class="w-full max-w-md p-4 animate-main relative z-10">
        <div class="bg-white/80 backdrop-blur-xl border border-slate-100 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 relative">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 mb-2">
                    <span class="text-3xl font-black text-blue-600 tracking-tight">Jual<span class="text-orange-500">.In</span></span>
                    <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-100">UAS Project</span>
                </div>
                <p class="text-sm text-slate-500">Selamat datang kembali! Silakan masuk ke akunmu.</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-100 text-red-600 text-xs p-3 rounded-xl">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-1.5 group">
                    <label for="email" class="text-xs font-bold text-slate-700 block transition-colors group-focus-within:text-blue-600">Alamat E-mail</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <!-- Icon Mail Normal -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </span>
                        <input type="email" name="email" id="email" required placeholder="nama@email.com"
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all duration-300">
                    </div>
                </div>

                <div class="space-y-1.5 group">
                    <div class="flex justify-between items-center">
                        <label for="password" class="text-xs font-bold text-slate-700 block transition-colors group-focus-within:text-blue-600">Kata Sandi</label>
                        <a href="{{ url('/lupa-password') }}" class="text-xs text-blue-600 hover:underline">Lupa password?</a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </span>
                        <input type="password" name="password" id="password" required placeholder="••••••••"
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-3 pl-11 pr-12 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all duration-300">
                        
                        <!-- Toggle Show/Hide Password menggunakan desain Icon gambar ke-4 -->
                        <button type="button" 
                                onclick="const p = document.getElementById('password'); 
                                         const svgOpen = this.querySelector('.eye-open');
                                         const svgClosed = this.querySelector('.eye-closed');
                                         if(p.type === 'password') { 
                                            p.type = 'text'; 
                                            svgClosed.classList.remove('hidden'); 
                                            svgOpen.classList.add('hidden'); 
                                         } else { 
                                            p.type = 'password'; 
                                            svgOpen.classList.remove('hidden'); 
                                            svgClosed.classList.add('hidden'); 
                                         }" 
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                            
                            <!-- Mata Terbuka (Gambar 4 Kanan) -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Mata Tercoret (Gambar 4 Kiri) -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>

                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all duration-200">
                        <span class="text-xs text-slate-600 font-medium">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 active:scale-[0.98] transition-all duration-200 block text-center mt-6">
                    Masuk Sekarang 🚀
                </button>
            </form>

            <div class="text-center mt-8 pt-6 border-t border-slate-100">
                <p class="text-xs text-slate-500">Belum punya akun? 
                    <a href="{{ url('/register') }}" class="text-blue-600 font-bold hover:underline">Daftar Akun Baru</a>
                </p>
            </div>

        </div>
        
        <p class="text-center text-[11px] text-slate-400 mt-6 tracking-wide">&copy; 2026 Jual.In E-Commerce. All Rights Reserved.</p>
    </div>

</body>
</html>