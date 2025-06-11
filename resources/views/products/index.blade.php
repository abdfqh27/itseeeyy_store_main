
@extends('layouts.app')

@section('title', 'Produk')
@section('header', 'Produk')

@section('content')
<div class="bg-pink-50 p-6 rounded-lg shadow-md mb-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-pink-800 mb-4 md:mb-0">
            <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
            </svg>
            Produk Manajemen
        </h2>

        <!-- Search Bar -->
        <div class="w-full md:w-1/3 mb-4 md:mb-0">
            <form action="{{ route('products.index') }}" method="GET" class="flex">
                <div class="relative flex-grow">
                    <input type="text"
                           name="search"
                           value="{{ $search ?? '' }}"
                           placeholder="Cari Produk..."
                           class="w-full px-4 py-2 rounded-l-lg border border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500">
                    @if($search)
                    <a href="{{ route('products.index') }}" class="absolute right-12 top-2.5 text-gray-400 hover:text-pink-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                    @endif
                </div>
                <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-r-lg transition duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Add Product Button -->
        <a href="{{ route('products.create') }}" class="btn bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-300 shadow-md transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Produk
        </a>
    </div>

    <!-- Search Results Stats -->
    @if($search)
    <div class="mb-4 text-sm text-pink-700 italic">
        <p>Showing results for: "{{ $search }}" ({{ $products->total() }} {{ Str::plural('product', $products->total()) }} found)</p>
    </div>
    @endif

    <!-- Enhanced Filter Options -->
    <div class="mt-4 bg-white p-4 rounded-lg border border-pink-200 shadow-sm">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="flex flex-wrap items-center gap-2 mb-3 md:mb-0">
                <span class="text-pink-700 font-medium">Filters:</span>

                <!-- Category Filter Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="px-3 py-1.5 border border-pink-300 rounded-lg bg-pink-50 hover:bg-pink-100 text-pink-700 flex items-center text-sm">
                        <span>Kategori</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white z-10" x-cloak>
                        <div class="py-1 rounded-md bg-white shadow-xs">
                            <!-- Filter will be implemented using the existing category model -->
                            <a href="{{ route('products.index', ['sort_by' => $sortBy, 'sort_direction' => $sortDirection]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100">Semua Kategori</a>
                            @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                                <a href="{{ route('products.index', ['category_id' => $category->id, 'sort_by' => $sortBy, 'sort_direction' => $sortDirection, 'search' => $search ?? '']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 {{ request('category_id') == $category->id ? 'bg-pink-50 font-medium' : '' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Stock Filter -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="px-3 py-1.5 border border-pink-300 rounded-lg bg-pink-50 hover:bg-pink-100 text-pink-700 flex items-center text-sm">
                        <span>Stok</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white z-10" x-cloak>
                        <div class="py-1 rounded-md bg-white shadow-xs">
                            <a href="{{ route('products.index', ['sort_by' => $sortBy, 'sort_direction' => $sortDirection, 'search' => $search ?? '']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100">Semua</a>
                            <a href="{{ route('products.index', ['stock_status' => 'in_stock', 'sort_by' => $sortBy, 'sort_direction' => $sortDirection, 'search' => $search ?? '']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 {{ request('stock_status') == 'in_stock' ? 'bg-pink-50 font-medium' : '' }}">
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></span>Stok Dalam (10+)
                            </a>
                            <a href="{{ route('products.index', ['stock_status' => 'low_stock', 'sort_by' => $sortBy, 'sort_direction' => $sortDirection, 'search' => $search ?? '']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 {{ request('stock_status') == 'low_stock' ? 'bg-pink-50 font-medium' : '' }}">
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-yellow-500 mr-2"></span>Stok Kurang (1-10)
                            </a>
                            <a href="{{ route('products.index', ['stock_status' => 'out_of_stock', 'sort_by' => $sortBy, 'sort_direction' => $sortDirection, 'search' => $search ?? '']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 {{ request('stock_status') == 'out_of_stock' ? 'bg-pink-50 font-medium' : '' }}">
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></span>Tidak Ada Stok (0)
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Expiry Filter -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="px-3 py-1.5 border border-pink-300 rounded-lg bg-pink-50 hover:bg-pink-100 text-pink-700 flex items-center text-sm">
                        <span>Kadaluarsa</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white z-10" x-cloak>
                        <div class="py-1 rounded-md bg-white shadow-xs">
                            <a href="{{ route('products.index', ['sort_by' => $sortBy, 'sort_direction' => $sortDirection, 'search' => $search ?? '']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100">Semua</a>
                            <a href="{{ route('products.index', ['expiry_status' => 'expired', 'sort_by' => $sortBy, 'sort_direction' => $sortDirection, 'search' => $search ?? '']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 {{ request('expiry_status') == 'expired' ? 'bg-pink-50 font-medium' : '' }}">
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></span>Kadaluarsa
                            </a>
                            <a href="{{ route('products.index', ['expiry_status' => 'expiring_soon', 'sort_by' => $sortBy, 'sort_direction' => $sortDirection, 'search' => $search ?? '']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 {{ request('expiry_status') == 'expiring_soon' ? 'bg-pink-50 font-medium' : '' }}">
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-orange-500 mr-2"></span>Kadaluarsa Dalam (10 hari)
                            </a>
                            <a href="{{ route('products.index', ['expiry_status' => 'expiring_month', 'sort_by' => $sortBy, 'sort_direction' => $sortDirection, 'search' => $search ?? '']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 {{ request('expiry_status') == 'expiring_month' ? 'bg-pink-50 font-medium' : '' }}">
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-yellow-500 mr-2"></span>Kedaluwarsa dalam 30 hari
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Active Filters -->
                @if(request('category_id') || request('stock_status') || request('expiry_status'))
                <div class="ml-2 flex flex-wrap gap-2 items-center">
                    @if(request('category_id'))
                        @php $category = \App\Models\Category::find(request('category_id')); @endphp
                        <div class="flex items-center bg-pink-100 text-pink-800 px-3 py-1 rounded-full text-xs">
                            <span>Category: {{ $category ? $category->name : 'Unknown' }}</span>
                            <a href="{{ route('products.index', array_merge(request()->except('category_id'), ['sort_by' => $sortBy, 'sort_direction' => $sortDirection])) }}" class="ml-1 text-pink-600 hover:text-pink-800">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </div>
                    @endif

                    @if(request('stock_status'))
                        <div class="flex items-center bg-pink-100 text-pink-800 px-3 py-1 rounded-full text-xs">
                            <span>Stock: {{ ucfirst(str_replace('_', ' ', request('stock_status'))) }}</span>
                            <a href="{{ route('products.index', array_merge(request()->except('stock_status'), ['sort_by' => $sortBy, 'sort_direction' => $sortDirection])) }}" class="ml-1 text-pink-600 hover:text-pink-800">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </div>
                    @endif

                    @if(request('expiry_status'))
                        <div class="flex items-center bg-pink-100 text-pink-800 px-3 py-1 rounded-full text-xs">
                            <span>Expiry: {{ ucfirst(str_replace('_', ' ', request('expiry_status'))) }}</span>
                            <a href="{{ route('products.index', array_merge(request()->except('expiry_status'), ['sort_by' => $sortBy, 'sort_direction' => $sortDirection])) }}" class="ml-1 text-pink-600 hover:text-pink-800">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </div>
                    @endif

                    @if(request('category_id') || request('stock_status') || request('expiry_status'))
                        <a href="{{ route('products.index', ['search' => $search ?? '', 'sort_by' => $sortBy, 'sort_direction' => $sortDirection]) }}" class="text-xs text-pink-600 hover:text-pink-800 underline">Bersihkan Semua Filter</a>
                    @endif
                </div>
                @endif
            </div>

            <!-- Sort Options -->
            <div class="flex items-center bg-pink-50 rounded-lg px-3 py-1.5 border border-pink-200">
                <span class="text-pink-700 text-sm mr-2">Urutkan Berdasarkan:</span>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center text-pink-800 text-sm font-medium hover:text-pink-600">
                        <span>
                            @if($sortBy == 'name')
                                Nama
                            @elseif($sortBy == 'category_name')
                                Kategori
                            @elseif($sortBy == 'stock')
                                Stok
                            @elseif($sortBy == 'expiry_date')
                                Tanggal Kadaluarsa
                            @endif
                        </span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($sortDirection == 'asc')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            @endif
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white z-10" x-cloak>
                        <div class="py-1 rounded-md bg-white shadow-xs">
                            <a href="{{ route('products.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'name', 'sort_direction' => $sortBy == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 {{ $sortBy == 'name' ? 'bg-pink-50 font-medium' : '' }}">
                                Nama
                                @if($sortBy == 'name')
                                    <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($sortDirection == 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </a>
                            <a href="{{ route('products.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'category_name', 'sort_direction' => $sortBy == 'category_name' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 {{ $sortBy == 'category_name' ? 'bg-pink-50 font-medium' : '' }}">
                                Kategori
                                @if($sortBy == 'category_name')
                                    <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($sortDirection == 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </a>
                            <a href="{{ route('products.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'stock', 'sort_direction' => $sortBy == 'stock' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 {{ $sortBy == 'stock' ? 'bg-pink-50 font-medium' : '' }}">
                                Stok
                                @if($sortBy == 'stock')
                                    <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($sortDirection == 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </a>
                            <a href="{{ route('products.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'expiry_date', 'sort_direction' => $sortBy == 'expiry_date' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100 {{ $sortBy == 'expiry_date' ? 'bg-pink-50 font-medium' : '' }}">
                                Tanggal Kadaluarsa
                                @if($sortBy == 'expiry_date')
                                    <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($sortDirection == 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden border border-pink-100">
    <!-- Table Header -->
    <div class="bg-gradient-to-r from-pink-500 to-pink-400 text-white px-6 py-4">
        <h3 class="text-lg font-semibold">Semua Produk</h3>
    </div>

    <table class="min-w-full divide-y divide-pink-100">
        <thead class="bg-pink-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Produk</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Kategori</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Stok</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Tanggal Kadaluarsa</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-pink-700 uppercase tracking-wider">Tindakan</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-pink-100">
            @forelse ($products as $product)
            <tr class="hover:bg-pink-50 transition duration-150">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        @if ($product->image)
                            <div class="flex-shrink-0 h-12 w-12">
                                <img class="h-12 w-12 rounded-full object-cover border-2 border-pink-200" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            </div>
                        @else
                            <div class="flex-shrink-0 h-12 w-12 bg-pink-100 rounded-full flex items-center justify-center">
                                <svg class="h-6 w-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                            @if($product->description)
                                <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($product->description, 60) }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full bg-pink-100 text-pink-800">{{ $product->category->name }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        @if($product->stock > 10)
                            <span class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></span>
                        @elseif($product->stock > 0)
                            <span class="h-2.5 w-2.5 rounded-full bg-yellow-500 mr-2"></span>
                        @else
                            <span class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></span>
                        @endif
                        <div class="text-sm text-gray-900 font-medium">{{ $product->stock }}</div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $daysUntilExpiry = $product->getDaysUntilExpiry();
                    @endphp
                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($product->expiry_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</div>

                    @if ($daysUntilExpiry <= 30)
                        <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-full text-xs font-medium
                            {{ $daysUntilExpiry <= 0 ? 'bg-red-100 text-red-800' : ($daysUntilExpiry <= 10 ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">
                            @if ($daysUntilExpiry <= 0)
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Expired
                            @else
                                {{ $daysUntilExpiry }} {{ Str::plural('day', $daysUntilExpiry) }} left
                            @endif
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        <a href="{{ route('products.show', $product) }}" class="text-pink-600 hover:text-pink-900 bg-pink-50 p-1.5 rounded-full hover:bg-pink-100 transition duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('products.edit', $product) }}" class="text-purple-600 hover:text-purple-900 bg-purple-50 p-1.5 rounded-full hover:bg-purple-100 transition duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline-block" x-data="{ confirmDelete: false }">
                            @csrf
                            @method('DELETE')
                            <button
                                type="button"
                                @click="confirmDelete = true"
                                class="text-red-600 hover:text-red-900 bg-red-50 p-1.5 rounded-full hover:bg-red-100 transition duration-300"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>

                            <!-- Delete Confirmation Modal -->
                            <div x-show="confirmDelete"
                                class="fixed inset-0 z-50 overflow-y-auto"
                                aria-labelledby="modal-title"
                                role="dialog"
                                aria-modal="true"
                                x-cloak>

                                <!-- Background overlay -->
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" x-show="confirmDelete"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    @click="confirmDelete = false"></div>

                                <!-- Modal panel -->
                                <div class="flex items-end sm:items-center justify-center min-h-screen p-4 text-center sm:p-0">
                                    <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full"
                                        x-show="confirmDelete"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="sm:flex sm:items-start">
                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                </div>
                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                        Delete Product
                                                    </h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">
                                                            Apakah Anda yakin ingin menghapus produk "{{ $product->name }}"? Tindakan ini tidak dapat dibatalkan.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                Hapus
                                            </button>
                                            <button type="button" @click="confirmDelete = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                Batalkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-10 whitespace-nowrap text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-pink-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-gray-500 text-lg">Produk Tidak Ditemukan</span>
                        @if($search)
                            <p class="text-gray-400 text-sm mt-1">Coba ubah kueri pencarian Anda atau</p>
                            <a href="{{ route('products.index') }}" class="text-pink-600 hover:text-pink-800 mt-3 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Bersihkan Pencarian
                            </a>
                        @else
                            <p class="text-gray-400 text-sm mt-1">Mulailah dengan menambahkan produk baru</p>
                            <a href="{{ route('products.create') }}" class="mt-4 px-4 py-2 bg-pink-600 text-white rounded hover:bg-pink-700 transition duration-300 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Produk
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Enhanced Pagination -->
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-pink-100 bg-pink-50">
        <div class="flex justify-between items-center">
            <div class="text-sm text-pink-700">
                Tampilkan {{ $products->firstItem() ?? 0 }} ke {{ $products->lastItem() ?? 0 }} dari{{ $products->total() }} produk
            </div>
            <div>
                {{ $products->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md"
     x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 4000)"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="flex items-center">
        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif
@endsection
