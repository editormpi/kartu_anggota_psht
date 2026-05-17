@extends('portal.layout')
@section('title', 'Riwayat Pembayaran')

@section('content')
    <x-portal.header title="Riwayat Pembayaran" back="{{ route('portal.dashboard') }}" />

    <section class="px-5 pb-10 space-y-3">
        @if ($payments->isEmpty())
            <div class="glass-card p-8 text-center">
                <i data-lucide="wallet" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                <p class="text-sm font-bold text-gray-400">Belum ada riwayat pembayaran.</p>
            </div>
        @else
            @php
                $totalDibayar = $payments->sum('nominal');
            @endphp
            <div class="rounded-[2rem] bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 text-white p-5 shadow-xl">
                <p class="text-xs uppercase tracking-widest font-bold text-emerald-100">Total Pembayaran</p>
                <p class="text-3xl font-extrabold mt-1">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</p>
                <p class="text-xs text-emerald-100 mt-1">{{ $payments->count() }} transaksi tercatat</p>
            </div>

            @foreach ($payments as $payment)
                <div class="glass-card p-5">
                    <div class="flex items-start gap-3">
                        <div class="menu-icon bg-emerald-50 shrink-0">
                            <i data-lucide="check-circle-2" class="w-6 h-6 text-emerald-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h3 class="font-bold text-gray-900 truncate">{{ $payment->uraian }}</h3>
                                    <p class="text-xs text-gray-500">{{ optional($payment->tanggal)->translatedFormat('d F Y') }}</p>
                                </div>
                                <p class="font-extrabold text-emerald-700 whitespace-nowrap">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</p>
                            </div>
                            @if ($payment->keterangan)
                                <p class="text-xs text-gray-500 mt-1.5">{{ $payment->keterangan }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </section>
@endsection
