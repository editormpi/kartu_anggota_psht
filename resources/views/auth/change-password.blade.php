<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ubah Password — Portal PSHT Jember</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 font-sans text-gray-800 antialiased">
    <main class="max-w-md mx-auto min-h-screen flex flex-col px-5 py-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-2xl bg-amber-100 grid place-items-center">
                <i data-lucide="key-round" class="w-5 h-5 text-amber-700"></i>
            </div>
            <h1 class="text-lg font-extrabold text-gray-900">Ubah Password</h1>
        </div>

        <div class="glass-card p-6">
            <p class="text-sm text-gray-600 mb-4">
                Untuk keamanan akun, silakan ganti password default Anda.
            </p>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-2xl bg-red-50 border border-red-100 text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.change') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="current_password" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password" required
                        class="w-full px-4 py-3 rounded-2xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>

                <div>
                    <label for="new_password" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Password Baru</label>
                    <input id="new_password" name="new_password" type="password" required minlength="8"
                        class="w-full px-4 py-3 rounded-2xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter, kombinasi huruf besar, kecil, dan angka.</p>
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Konfirmasi Password Baru</label>
                    <input id="new_password_confirmation" name="new_password_confirmation" type="password" required minlength="8"
                        class="w-full px-4 py-3 rounded-2xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>

                <button type="submit" class="w-full py-3 rounded-2xl bg-gray-900 hover:bg-gray-800 text-white font-bold shadow-lg transition active:scale-[0.98]">
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </main>
</body>
</html>
