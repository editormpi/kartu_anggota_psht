@extends('admin.layouts.app')

@section('title', 'Tagihan')
@section('page-title', 'Manajemen Tagihan')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <p class="text-sm text-gray-500">Total: {{ $bills->total() }} tagihan ditemukan</p>
    <a href="{{ route('admin.bills.create') }}"
       class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Tagihan
    </a>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-5">
    <form method="GET" action="{{ route('admin.bills.index') }}"
          class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari anggota / uraian..."
               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">

        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
            <option value="">Semua Status</option>
            @foreach($statuses as $s)
                <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
            @endforeach
        </select>

        <select name="tahun" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
            <option value="">Semua Tahun</option>
            @foreach($tahunList as $t)
                <option value="{{ $t }}" @selected(request('tahun') == $t)>{{ $t }}</option>
            @endforeach
        </select>

        <button type="submit"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white text-sm rounded-lg transition-colors">Filter</button>
        @if(request()->anyFilled(['search','status','tahun']))
            <a href="{{ route('admin.bills.index') }}"
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm rounded-lg transition-colors text-center">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Anggota</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Tahun</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Uraian</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Nominal</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Dibayar</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Sisa</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Status</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bills as $bill)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $bill->member->full_name ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $bill->tahun }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $bill->uraian }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">Rp {{ number_format($bill->nominal, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-green-700">Rp {{ number_format($bill->dibayar, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right {{ $bill->sisa > 0 ? 'text-red-600' : 'text-gray-400' }}">
                            Rp {{ number_format($bill->sisa, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $color = match($bill->status?->value) {
                                    'Lunas'       => 'bg-green-100 text-green-700',
                                    'Sebagian'    => 'bg-amber-100 text-amber-700',
                                    'Belum Lunas' => 'bg-red-100 text-red-600',
                                    default       => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                {{ $bill->status?->label() ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.bills.edit', $bill) }}"
                                   class="p-1.5 rounded-lg text-gray-400 hover:text-primary-600 hover:bg-primary-50 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.bills.destroy', $bill) }}" method="POST"
                                      onsubmit="return confirm('Hapus tagihan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">Tidak ada tagihan ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bills->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $bills->links() }}</div>
    @endif
</div>

@endsection
