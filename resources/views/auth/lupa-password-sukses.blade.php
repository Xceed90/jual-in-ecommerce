<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Terkirim (Simulasi) - Jual.In</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center font-sans">
    <div class="w-full max-w-md p-4">
        <div class="bg-white border border-slate-100 rounded-3xl shadow-xl p-8 text-center space-y-4">
            <div class="text-4xl">📩</div>
            <h2 class="text-lg font-bold text-slate-800">Tautan Berhasil Dibuat!</h2>
            <p class="text-xs text-slate-500 leading-relaxed">
                Sistem mendeteksi email <strong class="text-slate-800">{{ $email }}</strong> aktif. Karena ini adalah lingkungan pengujian UAS, link reset dikirimkan langsung ke layar ini:
            </p>
            
            <div class="bg-blue-50 border border-blue-100 p-4 rounded-2xl my-4 text-left">
                <p class="text-[10px] uppercase font-bold text-blue-500 tracking-wider mb-1">Log Sistem Mailer:</p>
                <p class="text-xs text-slate-600 font-mono break-all">To: {{ $email }}<br>Subject: Reset Your Password</p>
                
                <a href="{{ url('/reset-password/'.$email) }}" 
                   class="mt-3 block text-center bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs py-2 rounded-xl transition-all">
                   🔗 Klik Sini untuk Simulasi Buka Link Email
                </a>
            </div>

            <p class="text-[11px] text-slate-400">Tekan tombol di atas untuk melanjutkan proses pembuatan kata sandi baru.</p>
        </div>
    </div>
</body>
</html>