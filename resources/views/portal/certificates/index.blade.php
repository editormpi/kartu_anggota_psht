@extends('portal.layout')
@section('title', 'Sertifikat')

@section('content')
    <x-portal.header title="Sertifikat" back="{{ route('portal.dashboard') }}" />

    <section class="px-5 pb-10 space-y-3">
        @if ($certificates->isEmpty())
            <div class="glass-card p-8 text-center">
                <i data-lucide="award" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                <p class="text-sm font-bold text-gray-400">Belum ada sertifikat tercatat.</p>
            </div>
        @else
            @foreach ($certificates as $cert)
                <div class="rounded-[2rem] bg-gradient-to-br from-amber-50 via-yellow-50 to-amber-100 p-5 shadow-md border border-amber-200/50">
                    <div class="flex items-start gap-3">
                        <div class="menu-icon bg-amber-200/60 shrink-0">
                            <i data-lucide="award" class="w-6 h-6 text-amber-700"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-extrabold text-amber-900 truncate">{{ $cert->jenis }}</h3>
                            <p class="text-xs text-amber-700 font-mono">{{ $cert->nomor }}</p>
                            <p class="text-xs text-amber-700 mt-0.5">{{ $cert->tanggal->translatedFormat('d F Y') }}</p>

                            <a
                                href="{{ route('portal.certificates.download', $cert) }}"
                                class="mt-3 inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-amber-700 hover:bg-amber-800 text-white text-sm font-bold shadow transition active:scale-[0.98]"
                            >
                                <i data-lucide="download" class="w-4 h-4"></i>
                                Unduh PDF
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </section>
@endsection
