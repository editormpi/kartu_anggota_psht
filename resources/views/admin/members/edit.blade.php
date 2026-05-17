@extends('admin.layouts.app')

@section('title', 'Edit Anggota')
@section('page-title', 'Edit Anggota: ' . $member->full_name)

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.members.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke daftar anggota
    </a>
</div>

@if($errors->any())
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4">
        <p class="text-sm font-medium text-red-700 mb-2">Terdapat kesalahan:</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li class="text-sm text-red-600">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.members.update', $member) }}">
    @csrf @method('PUT')
    @include('admin.members._form')
</form>

@endsection
