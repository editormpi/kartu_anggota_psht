@extends('portal.layout')
@section('title', 'Data Anggota')

@section('content')
    <x-portal.header title="Data Anggota" back="{{ route('portal.dashboard') }}" />

    <section class="px-5 pb-10 space-y-4">
        <div class="glass-card p-5">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-amber-100 to-amber-200 grid place-items-center overflow-hidden shrink-0">
                    @if ($member->photo_path)
                        <img src="{{ asset('storage/' . $member->photo_path) }}" alt="" class="w-full h-full object-cover">
                    @else
                        <i data-lucide="user-round" class="w-8 h-8 text-amber-700"></i>
                    @endif
                </div>
                <div class="min-w-0">
                    <h2 class="font-extrabold text-gray-900 truncate">{{ $member->full_name }}</h2>
                    <p class="text-sm text-gray-500">{{ $member->tingkat ?? '—' }}</p>
                </div>
            </div>
        </div>

        @php
            $sections = [
                'Identitas' => [
                    ['label' => 'Tempat, Tanggal Lahir', 'value' => trim(($member->tempat_lahir ?? '') . ', ' . optional($member->tanggal_lahir)->format('d/m/Y'), ', ')],
                    ['label' => 'Jenis Kelamin', 'value' => $member->jenis_kelamin === 'L' ? 'Laki-laki' : ($member->jenis_kelamin === 'P' ? 'Perempuan' : '—')],
                    ['label' => 'Weton', 'value' => $member->weton],
                    ['label' => 'Agama', 'value' => $member->agama],
                    ['label' => 'Pekerjaan', 'value' => $member->pekerjaan],
                ],
                'Keanggotaan' => [
                    ['label' => 'Status', 'value' => $member->status_keanggotaan?->value ?? $member->status_keanggotaan],
                    ['label' => 'Tanggal Keanggotaan', 'value' => optional($member->tanggal_keanggotaan)->format('d/m/Y')],
                    ['label' => 'Tingkat', 'value' => $member->tingkat],
                    ['label' => 'Ranting', 'value' => $member->ranting],
                    ['label' => 'Rayon', 'value' => $member->rayon],
                    ['label' => 'Tempat Latihan', 'value' => $member->tempat_latihan],
                ],
                'Kontak' => [
                    ['label' => 'No. HP', 'value' => $member->hp],
                    ['label' => 'Alamat', 'value' => $member->alamat],
                ],
            ];
        @endphp

        @foreach ($sections as $sectionTitle => $items)
            <div class="glass-card p-5">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">{{ $sectionTitle }}</h3>
                <dl class="divide-y divide-gray-100">
                    @foreach ($items as $item)
                        <div class="py-2.5 flex gap-4">
                            <dt class="text-sm text-gray-500 w-40 shrink-0">{{ $item['label'] }}</dt>
                            <dd class="text-sm font-semibold text-gray-900 flex-1 break-words">{{ $item['value'] ?: '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        @endforeach
    </section>
@endsection
