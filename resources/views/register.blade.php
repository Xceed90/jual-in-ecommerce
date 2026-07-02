<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - jual.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-center p-4">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <h1 class="text-3xl font-black text-blue-600 text-center mb-1">jual.in</h1>
        <p class="text-center text-gray-500 mb-6">Buat akun baru Anda sekarang</p>

        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-3 rounded-lg text-sm mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ url('/register') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap / Nama Toko</label>
                <input type="text" name="name" class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-500" required placeholder="Contoh: Budi Santoso / Toko Sepatu Jaya">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Email</label>
                <input type="email" name="email" class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-500" required placeholder="name@example.com">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-500" required placeholder="Minimal 6 karakter">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-1">Mendaftar Sebagai:</label>
                <select name="role" required class="w-full border p-3 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 font-medium text-gray-700">
                    <option value="user">🛒 Pembeli (User Biasa - Langsung Aktif)</option>
                    <option value="vendor">🏪 Penjual (Vendor Toko - Butuh Persetujuan Admin)</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition shadow-md">
                Daftar Sekarang 🚀
            </button>
        </form>

        <div class="text-center mt-6 text-sm text-gray-600">
            Sudah punya akun? <a href="{{ url('/login') }}" class="text-blue-600 font-bold hover:underline">Masuk di sini</a>
        </div>
    </div>

</body>
</html>