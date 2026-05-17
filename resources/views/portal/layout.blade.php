<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#111827">
    <title>@yield('title', 'Portal') — PSHT Jember</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 font-sans text-gray-800 antialiased">
    <div class="max-w-md mx-auto min-h-screen flex flex-col">
        @yield('content')
        <div class="pb-[env(safe-area-inset-bottom)]"></div>
    </div>

    @if (session('success'))
        <div class="fixed bottom-6 inset-x-4 mx-auto max-w-sm bg-emerald-600 text-white text-sm font-medium px-4 py-3 rounded-2xl shadow-lg" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="fixed bottom-6 inset-x-4 mx-auto max-w-sm bg-amber-500 text-white text-sm font-medium px-4 py-3 rounded-2xl shadow-lg" role="status">
            {{ session('warning') }}
        </div>
    @endif
</body>
</html>
