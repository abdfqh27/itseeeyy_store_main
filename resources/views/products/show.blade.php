@extends('layouts.app')

@section('title', $product->name)
@section('header', 'Produk Detail')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-800">{{ $product->name }}</h2>
    <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md transition-all inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali Ke Produk
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Product Details -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    @if ($product->image)
                        <div class="flex-shrink-0 h-24 w-24">
                            <img class="h-24 w-24 rounded-md object-cover" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        </div>
                    @else
                        <div class="flex-shrink-0 h-24 w-24 bg-gray-200 rounded-md flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="ml-6">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500">Kategori: {{ $product->category->name }}</p>

                        @php $daysUntilExpiry = $product->getDaysUntilExpiry(); @endphp
                        <p class="text-sm text-gray-500 mt-1">
                            Kadaluarsa Dalam: <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($product->expiry_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                            @if ($daysUntilExpiry <= 30)
                                <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $daysUntilExpiry <= 0 ? 'bg-red-100 text-red-800' : ($daysUntilExpiry <= 10 ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    @if ($daysUntilExpiry <= 0)
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Kadaluarsa
                                    @else
                                        {{ $daysUntilExpiry }} {{ Str::plural('day', $daysUntilExpiry) }} left
                                    @endif
                                </span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-md font-medium text-gray-700 mb-2">Deskripsi</h4>
                    <p class="text-gray-600">{{ $product->description ?? 'Tidak ada deskripsi yang diberikan.' }}</p>
                </div>

                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="flex justify-between items-center">
                        <h4 class="text-md font-medium text-gray-700">Stok Sekarang</h4>
                        <span class="text-2xl font-bold {{ $product->stock < 10 ? 'text-red-600' : 'text-gray-700' }}">{{ $product->stock }}</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h4 class="text-md font-medium text-gray-700 mb-4">Perbaharui Stok</h4>

                    <form action="{{ route('products.update-stock', $product) }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-form-input
                                    type="number"
                                    name="quantity"
                                    id="quantity"
                                    value=""
                                    label="Kuantitas (gunakan angka negatif untuk stok habis)"
                                    required
                                />
                                <p class="mt-1 text-xs text-gray-500">Gunakan angka positif untuk menambah stok, angka negatif untuk mengurangi.</p>
                            </div>

                            <div>
                                <x-form-input
                                    type="text"
                                    name="notes"
                                    id="notes"
                                    value=""
                                    label="Catatan (Opsional)"
                                />
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-button type="submit" variant="primary">Perbaharui Stok</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Movement History -->
    <div>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h3 class="text-sm font-medium text-gray-700">Riwayat Pergerakan Stok</h3>
            </div>
            <div class="max-h-96 overflow-y-auto">
                <ul class="divide-y divide-gray-200">
                    @forelse ($product->stockMovements->sortByDesc('created_at') as $movement)
                        <li class="px-4 py-3 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $movement->type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $movement->type === 'dalam' ? '+' : '-' }}{{ $movement->quantity }}
                                    </span>
                                    @if ($movement->notes)
                                        <p class="mt-1 text-xs text-gray-500">{{ $movement->notes }}</p>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $movement->created_at->format('d M Y H:i') }}
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-3 text-sm text-gray-500 text-center">Tidak ada pergerakan stok yang tercatat</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection