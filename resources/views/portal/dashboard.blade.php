@extends('portal.layout')
@section('title', 'Dashboard')

@section('content')
    <x-portal.header title="Beranda" />

    <section class="px-5">
        <div class="rounded-[2rem] bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white p-6 shadow-xl">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-3xl bg-white/10 border border-white/15 grid place-items-center overflow-hidden shrink-0">
                    @if ($member->photo_path)
                        <img src="{{ asset('storage/' . $member->photo_path) }}" alt="Foto {{ $member->full_name }}" class="w-full h-full object-cover">
                    @else
                        <i data-lucide="user-round" class="w-9 h-9 text-white/70"></i>
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-amber-300 uppercase tracking-widest font-bold">{{ $member->status_keanggotaan?->value ?? $member->status_keanggotaan }}</p>
                    <h2 class="text-lg font-extrabold leading-tight truncate">{{ $member->full_name }}</h2>
                    <p class="text-sm text-gray-300 mt-0.5">{{ $member->tingkat ?? '—' }}</p>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-2 gap-3 text-xs">
                <div class="rounded-2xl bg-white/5 border border-white/10 p-3">
                    <p class="text-gray-400">Ranting</p>
                    <p class="font-semibold text-white truncate">{{ $member->ranting ?? '—' }}</p>
                </div>
                <div class="rounded-2xl bg-white/5 border border-white/10 p-3">
                    <p class="text-gray-400">Rayon</p>
                    <p class="font-semibold text-white truncate">{{ $member->rayon ?? '—' }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="px-5 mt-6 space-y-3 pb-10">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Menu</h3>

        <x-portal.menu-button
            href="{{ route('portal.profile') }}"
            icon="id-card"
            iconBg="bg-blue-50"
            iconColor="text-blue-600"
            title="Data Anggota"
            subtitle="Identitas dan kontak"
        />

        <x-portal.menu-button
            href="{{ route('portal.certificates') }}"
            icon="award"
            iconBg="bg-amber-50"
            iconColor="text-amber-600"
            title="Sertifikat"
            subtitle="Unduh sertifikat tingkat"
        />

        <x-portal.menu-button
            href="{{ route('portal.bills') }}"
            icon="receipt"
            iconBg="bg-red-50"
            iconColor="text-red-600"
            title="Tagihan"
            subtitle="Iuran & kewajiban"
        />

        <x-portal.menu-button
            href="{{ route('portal.payment-history') }}"
            icon="wallet"
            iconBg="bg-emerald-50"
            iconColor="text-emerald-600"
            title="Riwayat Pembayaran"
            subtitle="Catatan transaksi"
        />

        <div class="menu-btn opacity-60 cursor-not-allowed" aria-disabled="true">
            <div class="menu-icon bg-gray-100">
                <i data-lucide="construction" class="w-6 h-6 text-gray-400"></i>
            </div>
            <div class="flex-1 text-left">
                <div class="font-bold text-gray-500">Jumlah JPL</div>
                <div class="text-xs text-gray-400">Fitur dalam pengembangan</div>
            </div>
        </div>

        <div class="menu-btn opacity-60 cursor-not-allowed" aria-disabled="true">
            <div class="menu-icon bg-gray-100">
                <i data-lucide="construction" class="w-6 h-6 text-gray-400"></i>
            </div>
            <div class="flex-1 text-left">
                <div class="font-bold text-gray-500">Riwayat Rayon</div>
                <div class="text-xs text-gray-400">Fitur dalam pengembangan</div>
            </div>
        </div>
    </section>
@endsection
