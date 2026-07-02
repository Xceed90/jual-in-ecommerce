<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbarui Password - Jual.In</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center font-sans">
    <div class="w-full max-w-md p-4">
        <div class="bg-white border border-slate-100 rounded-3xl shadow-xl p-8">
            <div class="text-center mb-6">
                <h2 class="text-lg font-bold text-slate-800">Buat Kata Sandi Baru</h2>
                <p class="text-xs text-slate-500 mt-1">Untuk akun e-mail: <span class="font-bold text-slate-700">{{ $email }}</span></p>
            </div>

            @if ($errors->any())
                <div class="mb-4 bg-red-50 text-red-600 text-xs p-3 rounded-xl">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ url('/reset-password') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Kata Sandi Baru</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-blue-500 transition-all">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-blue-500 transition-all">
                </div>

                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm py-3 rounded-xl transition-all shadow-lg shadow-orange-500/20">
                    Simpan & Perbarui Password 🔐
                </button>
            </form>
        </div>
    </div>
</body>
</html>