@props(['title' => 'Portal', 'back' => null])

<header class="px-5 pt-6 pb-4 flex items-center gap-3">
    @if ($back)
        <a href="{{ $back }}" class="w-10 h-10 rounded-2xl bg-white shadow-sm grid place-items-center text-gray-700 hover:bg-gray-50" aria-label="Kembali">
            <i data-lucide="chevron-left" class="w-5 h-5"></i>
        </a>
    @else
        <a href="{{ route('portal.dashboard') }}" class="w-10 h-10 rounded-2xl bg-white shadow-sm grid place-items-center text-gray-700" aria-label="Beranda">
            <i data-lucide="house" class="w-5 h-5"></i>
        </a>
    @endif
    <h1 class="text-base font-bold text-gray-800 flex-1">{{ $title }}</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-10 h-10 rounded-2xl bg-white shadow-sm grid place-items-center text-gray-700 hover:bg-red-50 hover:text-red-600 transition" aria-label="Keluar">
            <i data-lucide="log-out" class="w-5 h-5"></i>
        </button>
    </form>
</header>
