<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#111827">
    <title>Login — Portal PSHT Jember</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black font-sans text-gray-100 antialiased">
    <main class="max-w-md mx-auto min-h-screen flex flex-col items-center justify-center px-6 py-10">
        <div class="w-full">
            <div class="flex flex-col items-center mb-8">
                <div class="w-20 h-20 rounded-3xl bg-white shadow-2xl grid place-items-center mb-4">
                    <span class="text-2xl font-extrabold text-gray-900 tracking-tight">PSHT</span>
                </div>
                <h1 class="text-2xl font-extrabold text-white text-center">DATABASE PORTAL</h1>
                <p class="text-sm text-gray-400 mt-1">Cabang Jember</p>
            </div>

            <div class="glass-card !bg-white p-6 shadow-2xl">
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-2xl bg-red-50 border border-red-100 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="nik" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wider">NIK</label>
                        <input
                            id="nik"
                            name="nik"
                            data-nik-input
                            value="{{ old('nik') }}"
                            placeholder="3509 1234 5678 9012"
                            class="w-full px-4 py-3 rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 font-mono tracking-wider focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"
                            required
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Password</label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="w-full pl-4 pr-12 py-3 rounded-2xl bg-gray-50 border border-gray-200 text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"
                                required
                            >
                            <button
                                type="button"
                                data-password-toggle
                                data-toggle-target="#password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 grid place-items-center text-gray-400 hover:text-gray-700"
                                aria-label="Tampilkan password"
                                aria-pressed="false"
                            >
                                <i data-lucide="eye" data-icon="show" class="w-5 h-5"></i>
                                <i data-lucide="eye-off" data-icon="hide" class="w-5 h-5 hidden"></i>
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3 rounded-2xl bg-gray-900 hover:bg-gray-800 text-white font-bold shadow-lg transition active:scale-[0.98]"
                    >
                        Masuk
                    </button>
                </form>
            </div>

            <p class="text-center text-xs text-gray-500 mt-6">
                Lupa password? Hubungi admin cabang.
            </p>
        </div>
    </main>
</body>
</html>
