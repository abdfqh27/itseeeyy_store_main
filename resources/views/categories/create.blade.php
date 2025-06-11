@extends('layouts.app')

@section('title', 'Buat Kategori')
@section('header', 'Buat Kategori')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-800">Buat Kategori Baru</h2>
    <a href="{{ route('categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md transition-all inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali Ke Kategori
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <form action="{{ route('categories.store') }}" method="POST" class="p-6">
        @csrf

        <div class="mb-4">
            <x-form-input
                type="text"
                name="name"
                id="name"
                :value="old('name')"
                label="Nama Kategori"
                required
                autofocus
            />
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <x-form-textarea
                name="description"
                id="description"
                :value="old('description')"
                label="Deskripsi (Opsional)"
                rows="4"
            />
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <x-button type="submit" variant="primary">Buat Kategori</x-button>
        </div>
    </form>
</div>
@endsection
