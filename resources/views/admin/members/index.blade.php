@extends('admin.layouts.app')

@section('title', 'Manajemen Anggota')
@section('page-title', 'Manajemen Anggota')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <p class="text-sm text-gray-500">Total: {{ $members->total() }} anggota ditemukan</p>
    <a href="{{ route('admin.members.create') }}"
       class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Anggota
    </a>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-5">
    <form method="GET" action="{{ route('admin.members.index') }}"
          class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama anggota..."
               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">

        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
            <option value="">Semua Status</option>
            @foreach($statuses as $s)
                <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
            @endforeach
        </select>

        <select name="is_active" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
            <option value="">Semua Akun</option>
            <option value="1" @selected(request('is_active') === '1')>Akun Aktif</option>
            <option value="0" @selected(request('is_active') === '0')>Akun Nonaktif</option>
        </select>

        <button type="submit"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white text-sm rounded-lg transition-colors">
            Filter
        </button>
        @if(request()->anyFilled(['search','status','is_active']))
            <a href="{{ route('admin.members.index') }}"
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm rounded-lg transition-colors text-center">
                Reset
            </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">#</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Nama</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">NIK</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Tingkat</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Status</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Akun</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Login Terakhir</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($members as $member)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-400">{{ $member->id }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $member->full_name }}</td>
                        <td class="px-4 py-3 text-gray-500 font-mono text-xs">
                            @try
                                {{ $member->masked_nik }}
                            @catch (\Throwable $e)
                                ****************
                            @endtry
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $member->tingkat ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusColor = match($member->status_keanggotaan?->value) {
                                    'Aktif'       => 'bg-green-100 text-green-700',
                                    'Tidak Aktif' => 'bg-gray-100 text-gray-600',
                                    'Alumni'      => 'bg-blue-100 text-blue-700',
                                    'Berhenti'    => 'bg-red-100 text-red-600',
                                    default       => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                {{ $member->status_keanggotaan?->label() ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($member->is_active)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-600">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            {{ $member->last_login_at?->diffForHumans() ?? 'Belum pernah' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open" @click.outside="open = false"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-transition
                                     class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">

                                    <a href="{{ route('admin.members.edit', $member) }}"
                                       class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>

                                    @if(!$member->is_active)
                                        <form action="{{ route('admin.members.activate', $member) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full flex items-center gap-2 px-3 py-2 text-sm text-green-700 hover:bg-green-50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Aktifkan Akun
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.members.deactivate', $member) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full flex items-center gap-2 px-3 py-2 text-sm text-orange-700 hover:bg-orange-50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.members.reset-password', $member) }}" method="POST"
                                          onsubmit="return confirm('Reset password ke tanggal lahir?')">
                                        @csrf
                                        <button type="submit"
                                                class="w-full flex items-center gap-2 px-3 py-2 text-sm text-amber-700 hover:bg-amber-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                            </svg>
                                            Reset Password
                                        </button>
                                    </form>

                                    <div class="border-t border-gray-100 my-1"></div>

                                    <form action="{{ route('admin.members.destroy', $member) }}" method="POST"
                                          onsubmit="return confirm('Hapus anggota {{ addslashes($member->full_name) }}? Semua data terkait akan ikut terhapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm">Tidak ada anggota ditemukan</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($members->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $members->links() }}
        </div>
    @endif
</div>

@endsection
