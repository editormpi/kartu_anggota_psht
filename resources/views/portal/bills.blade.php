@extends('portal.layout')
@section('title', 'Tagihan')

@section('content')
    <x-portal.header title="Tagihan" back="{{ route('portal.dashboard') }}" />

    <section class="px-5 pb-10 space-y-3">
        @if ($bills->isEmpty())
            <div class="glass-card p-8 text-center">
                <i data-lucide="receipt" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                <p class="text-sm font-bold text-gray-400">Belum ada tagihan tercatat.</p>
            </div>
        @else
            @php
                $totalSisa = $bills->sum('sisa');
            @endphp
            <div class="rounded-[2rem] bg-gradient-to-br from-red-500 via-red-600 to-rose-700 text-white p-5 shadow-xl">
                <p class="text-xs uppercase tracking-widest font-bold text-red-100">Total Sisa Tagihan</p>
                <p class="text-3xl font-extrabold mt-1">Rp {{ number_format($totalSisa, 0, ',', '.') }}</p>
            </div>

            @foreach ($bills as $bill)
                @php
                    $statusValue = $bill->status?->value ?? $bill->status;
                    $statusStyle = match ($statusValue) {
                        'Lunas' => 'bg-emerald-100 text-emerald-700',
                        'Sebagian' => 'bg-amber-100 text-amber-700',
                        default => 'bg-red-100 text-red-700',
                    };
                @endphp
                <div class="glass-card p-5">
                    <div class="flex items-start gap-3">
                        <div class="menu-icon bg-red-50 shrink-0">
                            <i data-lucide="receipt-text" class="w-6 h-6 text-red-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h3 class="font-bold text-gray-900 truncate">{{ $bill->uraian }}</h3>
                                    <p class="text-xs text-gray-500">Tahun {{ $bill->tahun }}</p>
                                </div>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $statusStyle }}">{{ $statusValue }}</span>
                            </div>

                            <dl class="mt-3 grid grid-cols-3 gap-2 text-xs">
                                <div class="rounded-2xl bg-gray-50 p-2">
                                    <dt class="text-gray-500">Nominal</dt>
                                    <dd class="font-bold text-gray-900">Rp {{ number_format($bill->nominal, 0, ',', '.') }}</dd>
                                </div>
                                <div class="rounded-2xl bg-gray-50 p-2">
                                    <dt class="text-gray-500">Dibayar</dt>
                                    <dd class="font-bold text-emerald-700">Rp {{ number_format($bill->dibayar, 0, ',', '.') }}</dd>
                                </div>
                                <div class="rounded-2xl bg-gray-50 p-2">
                                    <dt class="text-gray-500">Sisa</dt>
                                    <dd class="font-bold text-red-700">Rp {{ number_format($bill->sisa, 0, ',', '.') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </section>
@endsection
