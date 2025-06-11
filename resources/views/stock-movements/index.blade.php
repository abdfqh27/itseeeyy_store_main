@extends('layouts.app')

@section('title', 'Pergerakan Stok')
@section('header', 'Pergerakan Stok')

@section('content')


<!-- Improved Filters -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="p-4 bg-pink-50 border-b">
        <h3 class="text-sm font-semibold text-pink-500">Filter Pergerakan Stok</h3>
    </div>
    <form method="GET" action="{{ route('stock-movements.index') }}" class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <x-form-input
                    type="date"
                    name="from_date"
                    id="from_date"
                    :value="request('from_date')"
                    label="Tanggal Mulai" />
            </div>
            <div>
                <x-form-input
                    type="date"
                    name="to_date"
                    id="to_date"
                    :value="request('to_date')"
                    label="Tanggal Selesai" />
            </div>
            <div>
                <x-form-select name="product_id" id="product_id" label="Product">
                    <option value="">Semua Produk</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                    @endforeach
                </x-form-select>
            </div>
            <div>
                <x-form-select name="type" id="type" label="Movement Type">
                    <option value="">Semua Tipe</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stok Masuk</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stok Keluar</option>
                </x-form-select>
            </div>
        </div>
        <div class="mt-4 flex flex-wrap gap-2 justify-end">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <x-form-input
                    type="text"
                    name="q"
                    id="q"
                    :value="request('q')"
                    placeholder="Cari catatan atau nama produk..."
                    class="pl-10" />
            </div>

            <x-button type="submit" variant="primary" class="px-6">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter
            </x-button>
            @if(request()->anyFilled(['from_date', 'to_date', 'product_id', 'type']))
            <a href="{{ route('stock-movements.index') }}" class="flex items-center px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-all shadow-sm hover:shadow">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Clear
            </a>
            @endif
        </div>
    </form>
</div>



<!-- Stock Movements Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-pink-50">
            <tr>
                <th scope="col" class="px-6 py-5 text-left text-xs font-semibold text-pink-500 uppercase tracking-wider">Tanggal & Waktu</th>
                <th scope="col" class="px-6 py-5 text-left text-xs font-semibold text-pink-500  uppercase tracking-wider">Produk</th>
                <th scope="col" class="px-6 py-5 text-left text-xs font-semibold text-pink-500  uppercase tracking-wider">Kategori</th>
                <th scope="col" class="px-6 py-5 text-left text-xs font-semibold text-pink-500  uppercase tracking-wider">Tipe</th>
                <th scope="col" class="px-6 py-5 text-left text-xs font-semibold text-pink-500  uppercase tracking-wider">Jumlah</th>
                <!-- <th scope="col" class="px-6 py-5 text-left text-xs font-semibold text-pink-500  uppercase tracking-wider">Catatan</th> -->
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($stockMovements as $movement)
            <tr class="hover:bg-pink-50 transition-colors {{ $loop->first && request()->has('highlight') ? 'bg-yellow-50 animate-pulse' : '' }} cursor-pointer"
                data-product-id="{{ $movement->product_id }}"
                data-product-name="{{ $movement->product->name }}"
                data-product-category="{{ $movement->product->category->name }}"
                data-product-description="{{ $movement->product->description ?? 'Tidak ada deskripsi yang tersedia' }}"
                data-product-stock="{{ $movement->product->current_stock ?? $movement->product->stock }}"
                data-product-notes="{{ $movement->notes ?? 'Tidak ada catatan yang tersedia' }}"
                data-product-sku="{{ $movement->product->sku ?? 'N/A' }}"
                onclick="showProductDetail(this)">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $movement->created_at->format('Y-m-d') }}</div>
                    <div class="text-sm text-gray-500">{{ $movement->created_at->format('H:i:s') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $movement->product->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">{{ $movement->product->category->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $movement->type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $movement->type === 'in' ? 'Stok Masuk' : 'Stok Keluar' }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="text-sm text-gray-900">{{ $movement->quantity }}</div>
                        @if($movement->type === 'in')
                        <svg class="w-4 h-4 ml-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                        @else
                        <svg class="w-4 h-4 ml-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                        @endif
                    </div>
                </td>
                <!-- <td class="px-6 py-4">
                    <div class="text-sm text-gray-500">{{ $movement->notes ?? '-' }}</div>
                </td> -->
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex flex-col items-center">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-lg font-medium text-gray-500">Tidak ada catatan pergerakan stok</p>
                        <p class="text-gray-400 mt-1">Coba sesuaikan filter Anda atau tambahkan pergerakan stok baru</p>
                        <a href="{{ route('stock-movements.create') }}" class="mt-4 btn-primary inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Catat Pergerakan Stok
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Informasi Hasil -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start text-sm text-gray-600">
                <span class="text-center sm:text-left">
                    Menampilkan {{ $stockMovements->firstItem() ?? 0 }} sampai {{ $stockMovements->lastItem() ?? 0 }} dari {{ $stockMovements->total() }} hasil
                </span>
                <form method="GET" action="{{ route('stock-movements.index') }}" class="mt-2 sm:mt-0 sm:inline-flex sm:ml-4">
                    <select name="per_page" id="per_page" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                        onchange="this.form.submit()">
                        @foreach([15, 30, 50, 100] as $perPage)
                        <option value="{{ $perPage }}" {{ request('per_page', 15) == $perPage ? 'selected' : '' }}>
                            {{ $perPage }} per halaman
                        </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                    <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                    <input type="hidden" name="product_id" value="{{ request('product_id') }}">
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                </form>
            </div>

            <!-- Link Navigasi Halaman -->
            @if ($stockMovements->hasPages())
            <div class="flex items-center justify-center space-x-1">
                <!-- Tombol Halaman Sebelumnya -->
                <a href="{{ $stockMovements->previousPageUrl() }}"
                    class="{{ $stockMovements->onFirstPage() ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white hover:bg-gray-50 text-gray-700' }} px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150 border">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <!-- Nomor Halaman (Desktop) -->
                <div class="hidden md:flex space-x-1">
                    @foreach ($stockMovements->getUrlRange(max(1, $stockMovements->currentPage() - 2), min($stockMovements->lastPage(), $stockMovements->currentPage() + 2)) as $page => $url)
                    <a href="{{ $url }}"
                        class="{{ $page == $stockMovements->currentPage() ? 'bg-pink-500 hover:bg-pink-600 text-white' : 'bg-white hover:bg-gray-50 text-gray-700' }} px-4 py-2 rounded-md text-sm font-medium transition-colors duration-150 border">
                        {{ $page }}
                    </a>
                    @endforeach
                </div>

                <!-- Indikator Halaman Saat Ini (Mobile) -->
                <span class="md:hidden px-4 py-2 rounded-md text-sm font-medium bg-white border flex items-center">
                    <span class="font-bold text-pink-500">{{ $stockMovements->currentPage() }}</span>
                    <span class="mx-1 text-gray-500">/</span>
                    <span>{{ $stockMovements->lastPage() }}</span>
                </span>

                <!-- Tombol Halaman Selanjutnya -->
                <a href="{{ $stockMovements->nextPageUrl() }}"
                    class="{{ !$stockMovements->hasMorePages() ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white hover:bg-gray-50 text-gray-700' }} px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150 border">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Detail Produk -->
<div id="productDetailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 overflow-hidden transform transition-all">
        <div class="bg-pink-50 px-6 py-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-medium text-pink-500" id="modalProductName">Detail Produk</h3>
            <button onclick="closeProductModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="px-6 py-4">
            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-1">Kategori</p>
                <p class="font-medium" id="modalProductCategory">Memuat...</p>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-1">Deskripsi</p>
                <p id="modalProductDescription">Memuat...</p>
            </div>

            <!-- <div class="mb-4">
                <p class="text-sm text-gray-500 mb-1">Catatan</p>
                <p id="modalProductNotes">Memuat...</p>
            </div> -->

            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-1">Stok Saat Ini</p>
                <p class="font-medium" id="modalProductStock">0</p>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-3 flex justify-between items-center border-t">
            <a href="#" id="viewProductLink" class="text-pink-500 hover:text-pink-700 text-sm font-medium transition-colors">
                Lihat Detail Lengkap
            </a>
            <button onclick="closeProductModal()" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>



@endsection
<a href="{{ route('stock-movements.create') }}" class="fixed bottom-6 right-6 z-50 p-4 bg-pink-500 hover:bg-pink-600 text-white rounded-full shadow-lg transition-all">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
    </svg>
</a>

<script>
    function showProductDetail(element) {
        const productId = element.getAttribute('data-product-id');
        const name = element.getAttribute('data-product-name');
        const category = element.getAttribute('data-product-category');
        const description = element.getAttribute('data-product-description');
        const stock = element.getAttribute('data-product-stock');
        const sku = element.getAttribute('data-product-sku');
        const notes = element.getAttribute('data-product-notes') || 'No notes available';

        document.getElementById('modalProductName').textContent = name;
        document.getElementById('modalProductCategory').textContent = category;
        document.getElementById('modalProductDescription').textContent = description;
        document.getElementById('modalProductStock').textContent = stock;
        document.getElementById('modalProductNotes').textContent = notes;

        document.getElementById('viewProductLink').href = `/products/${productId}`;

        document.getElementById('productDetailModal').classList.remove('hidden');

        if (event) {
            event.stopPropagation();
        }
    }

    function closeProductModal() {
        document.getElementById('productDetailModal').classList.add('hidden');
    }

    document.addEventListener('click', function(event) {
        const modal = document.getElementById('productDetailModal');
        const modalContent = modal.querySelector('div');

        if (modal && !modal.classList.contains('hidden') && !modalContent.contains(event.target)) {
            closeProductModal();
        }
    });

    document.querySelector('#productDetailModal > div').addEventListener('click', function(event) {
        event.stopPropagation();
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeProductModal();
        }
    });
</script>