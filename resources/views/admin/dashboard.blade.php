@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">

    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Anggota</p>
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_anggota']) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ number_format($stats['anggota_aktif']) }} aktif</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Anggota Aktif</p>
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['anggota_aktif']) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">dari {{ $stats['total_anggota'] }} total</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Tagihan</p>
            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_tagihan']) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $stats['tagihan_belum_lunas'] }} belum lunas</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Belum Lunas</p>
            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['tagihan_belum_lunas']) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">tagihan perlu tindakan</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Pembayaran</p>
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($stats['total_pembayaran'], 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-0.5">semua waktu</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Sertifikat</p>
            <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_sertifikat']) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">sertifikat diterbitkan</p>
    </div>

</div>

{{-- Charts --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    {{-- Pembayaran per bulan --}}
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Pembayaran per Bulan ({{ now()->year }})</h3>
        </div>
        <div id="chart-pembayaran"></div>
    </div>

    {{-- Status anggota --}}
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-4">Status Keanggotaan</h3>
        <div id="chart-status"></div>
    </div>

</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Status tagihan --}}
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-4">Status Tagihan</h3>
        <div id="chart-tagihan"></div>
    </div>

    {{-- Quick links --}}
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('admin.members.create') }}"
               class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-primary-400 hover:bg-primary-50 transition-colors group">
                <div class="w-9 h-9 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Tambah Anggota</span>
            </a>

            <a href="{{ route('admin.bills.create') }}"
               class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-primary-400 hover:bg-primary-50 transition-colors group">
                <div class="w-9 h-9 bg-amber-100 group-hover:bg-amber-200 rounded-lg flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Buat Tagihan</span>
            </a>

            <a href="{{ route('admin.payments.create') }}"
               class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-primary-400 hover:bg-primary-50 transition-colors group">
                <div class="w-9 h-9 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Catat Pembayaran</span>
            </a>

            <a href="{{ route('admin.certificates.create') }}"
               class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-primary-400 hover:bg-primary-50 transition-colors group">
                <div class="w-9 h-9 bg-teal-100 group-hover:bg-teal-200 rounded-lg flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Tambah Sertifikat</span>
            </a>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Chart pembayaran per bulan
    new ApexCharts(document.getElementById('chart-pembayaran'), {
        series: [{
            name: 'Pembayaran (Rp)',
            data: @json($pembayaranSeries)
        }],
        chart: { type: 'area', height: 250, toolbar: { show: false }, sparkline: { enabled: false } },
        colors: ['#d97706'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
        stroke: { curve: 'smooth', width: 2 },
        xaxis: { categories: @json($bulanLabels) },
        yaxis: {
            labels: {
                formatter: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
            }
        },
        tooltip: {
            y: { formatter: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val) }
        },
        grid: { borderColor: '#f1f1f1' },
        dataLabels: { enabled: false },
    }).render();

    // Chart status anggota
    const statusLabels = @json(array_keys($statusData));
    const statusValues = @json(array_values($statusData));
    new ApexCharts(document.getElementById('chart-status'), {
        series: statusValues,
        labels: statusLabels,
        chart: { type: 'donut', height: 250 },
        colors: ['#22c55e', '#ef4444', '#3b82f6', '#f59e0b'],
        legend: { position: 'bottom' },
        plotOptions: { pie: { donut: { size: '65%' } } },
        dataLabels: { enabled: false },
    }).render();

    // Chart status tagihan
    const tagihanLabels = @json(array_keys($tagihanStatus));
    const tagihanValues = @json(array_values($tagihanStatus));
    new ApexCharts(document.getElementById('chart-tagihan'), {
        series: [{
            name: 'Jumlah',
            data: tagihanValues
        }],
        chart: { type: 'bar', height: 220, toolbar: { show: false } },
        colors: ['#d97706'],
        xaxis: { categories: tagihanLabels },
        plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#f1f1f1' },
    }).render();

});
</script>

@endsection
