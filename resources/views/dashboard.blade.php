@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="container px-4 mx-auto">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 gap-5 mt-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Products -->
        <div class="relative overflow-hidden transition-all duration-300 bg-white border border-pink-100 shadow-sm rounded-xl hover:shadow-md group">
            <div class="absolute w-24 h-24 -translate-y-1/2 rounded-full opacity-10 -right-6 -top-1 bg-primary"></div>
            <div class="p-5">
                <div class="flex items-center">
                    <div class="p-3 text-white rounded-lg bg-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">Total Produk</p>
                        <h3 class="text-2xl font-bold text-gray-700">{{ $totalProducts }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="relative overflow-hidden transition-all duration-300 bg-white border border-pink-100 shadow-sm rounded-xl hover:shadow-md group">
            <div class="absolute w-24 h-24 -translate-y-1/2 rounded-full opacity-10 -right-6 -top-1 bg-accent"></div>
            <div class="p-5">
                <div class="flex items-center">
                    <div class="p-3 text-white rounded-lg bg-accent">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">Kategori</p>
                        <h3 class="text-2xl font-bold text-gray-700">{{ $totalCategories }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Near Expiry Products -->
        <div class="relative overflow-hidden transition-all duration-300 bg-white border border-pink-100 shadow-sm rounded-xl hover:shadow-md group">
            <div class="absolute w-24 h-24 -translate-y-1/2 rounded-full opacity-10 -right-6 -top-1 bg-secondary"></div>
            <div class="p-5">
                <div class="flex items-center">
                    <div class="p-3 text-white rounded-lg bg-secondary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">Hampir Kadaluarsa</p>
                        <h3 class="text-2xl font-bold text-gray-700">{{ $nearExpiryProducts->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="relative overflow-hidden transition-all duration-300 bg-white border border-pink-100 shadow-sm rounded-xl hover:shadow-md group">
            <div class="absolute w-24 h-24 -translate-y-1/2 rounded-full opacity-10 -right-6 -top-1 bg-primary-dark"></div>
            <div class="p-5">
                <div class="flex items-center">
                    <div class="p-3 text-white rounded-lg bg-primary-dark">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">Stok Menipis</p>
                        <h3 class="text-2xl font-bold text-gray-700">{{ $lowStockProducts }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Section -->
    @if ($notifications->isNotEmpty())
    <div class="mt-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-700">Notifikasi</h2>
            <a href="#" class="px-4 py-2 text-sm font-medium text-white transition-colors rounded-lg bg-primary hover:bg-primary-dark">
                Lihat Semua
            </a>
        </div>
        <div class="overflow-hidden bg-white border border-pink-100 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-pink-100 bg-pink-50">
                <h3 class="text-sm font-medium text-gray-700">Notifikasi Terbaru</h3>
            </div>
            <ul class="divide-y divide-pink-100">
                @foreach ($notifications as $notification)
                <li class="transition-colors hover:bg-pink-50">
                    <div class="flex items-start justify-between p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 pt-1">
                                <div class="flex items-center justify-center w-8 h-8 text-white rounded-full bg-secondary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700">{{ $notification->message }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-1 text-gray-400 transition-colors rounded-full hover:bg-pink-100 hover:text-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Near Expiry Products -->
    @if ($nearExpiryProducts->isNotEmpty())
    <div class="mt-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-700">Produk Hampir Kadaluarsa</h2>
            <a href="#" class="px-4 py-2 text-sm font-medium transition-colors border rounded-lg text-primary border-primary hover:bg-primary hover:text-white">
                Lihat Semua
            </a>
        </div>
        <div class="overflow-hidden bg-white border border-pink-100 shadow-sm rounded-xl">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-pink-100">
                    <thead class="bg-pink-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Produk</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Stok</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal Kadaluarsa</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Sisa Hari</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-pink-100">
                        @foreach ($nearExpiryProducts as $product)
                        <tr class="transition-colors hover:bg-pink-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $product->category->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $product->stock }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $product->expiry_date->format('Y-m-d') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php $daysLeft = $product->getDaysUntilExpiry(); @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $daysLeft <= 10 ? 'bg-secondary text-white' : 'bg-pink-100 text-primary-dark' }}">
                                    {{ $daysLeft }} hari
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Stock Movements -->
    <div class="mt-8 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-700">Perpindahan Stok Terbaru</h2>
            <a href="{{ route('stock-movements.index') }}" class="px-4 py-2 text-sm font-medium text-white transition-colors rounded-lg bg-primary hover:bg-primary-dark">
                Lihat Semua
            </a>
        </div>
        <div class="overflow-hidden bg-white border border-pink-100 shadow-sm rounded-xl">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-pink-100">
                    <thead class="bg-pink-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Produk</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tipe</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Jumlah</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-pink-100">
                        @forelse ($recentStockMovements as $movement)
                        <tr class="transition-colors hover:bg-pink-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $movement->product->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-semibold text-white rounded-full {{ $movement->type === 'in' ? 'bg-accent' : 'bg-secondary' }}">
                                    {{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $movement->quantity }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $movement->created_at->format('Y-m-d H:i') }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-sm text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-3 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p>Tidak ada perpindahan stok terbaru</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
