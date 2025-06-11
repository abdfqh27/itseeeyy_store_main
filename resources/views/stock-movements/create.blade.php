@extends('layouts.app')

@section('title', 'Pencatatan Stok')
@section('header', 'Pencatatan Stok')

@section('content')
<div class="mb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li>
                <div class="flex items-center">
                    <a href="{{ route('stock-movements.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-pink-500 md:ml-2">Pencatatan Stok</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-pink-500 md:ml-2">Pencatatan Stok</span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<div class="mt-6 mb-6 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
    <div class="p-4 bg-pink-50 border-b border-pink-100">
        <h3 class="text-sm font-semibold text-pink-500 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Informasi Pencatatan Stok
        </h3>
    </div>
    <div class="p-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 p-4 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-800">Stok Masuk</h3>
                </div>
                <p class="text-sm text-gray-600">
                    Digunakan untuk menambah jumlah stok produk dalam inventaris, seperti dari pembelian baru atau pengembalian barang.
                </p>
            </div>
            <div class="flex-1 p-4 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center text-white mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-800">Stok Keluar</h3>
                </div>
                <p class="text-sm text-gray-600">
                    Digunakan untuk mengurangi jumlah stok produk dalam inventaris, seperti untuk penjualan atau barang rusak.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Grid Container: Kiri = Stok Masuk, Kanan = Stok Keluar -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 min-h-[600px]">

    <!-- FORM STOK MASUK (KIRI) -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 flex flex-col h-full">
        <div class="p-4 bg-green-50 border-b border-green-100">
            <h3 class="text-md font-semibold text-green-600 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
                Stok Masuk
            </h3>
        </div>

        <form action="{{ route('stock-movements.store') }}" method="POST" class="p-6 flex flex-col flex-1" id="stockInForm">
            @csrf
            <input type="hidden" name="type" value="in">

            <div class="space-y-4 flex-1">
                <div>
                    <label for="product_in" class="block text-sm font-medium text-gray-700 mb-2">Produk</label>
                    <select name="product_id" id="product_in" class="block w-full rounded-lg border-gray-200 bg-gray-50 py-3 px-4 text-sm focus:border-green-400 focus:ring-green-300 focus:outline-none focus:ring focus:ring-opacity-40" required>
                        <option value="">Pilih Produk</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">
                            {{ $product->name }} (Stok: {{ $product->stock }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="quantity_in" class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <div class="flex rounded-lg bg-gray-50 border border-gray-200 overflow-hidden">
                        <button type="button" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 border-r border-gray-200 focus:outline-none transition-colors" id="decrease-qty-in">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <input
                            type="number"
                            name="quantity"
                            id="quantity_in"
                            value="1"
                            min="1"
                            class="flex-1 block w-full py-3 px-4 border-0 bg-gray-50 text-center focus:ring-0 text-sm"
                            required />
                        <button type="button" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 border-l border-gray-200 focus:outline-none transition-colors" id="increase-qty-in">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="reset" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition-all border border-gray-200 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset
                </button>
                <button type="submit" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-all flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    Stok Masuk
                </button>
            </div>
        </form>
    </div>

    <!-- FORM STOK KELUAR (KANAN) -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 flex flex-col h-full">
        <div class="p-4 bg-red-50 border-b border-red-100">
            <h3 class="text-md font-semibold text-red-600 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
                Stok Keluar
            </h3>
        </div>

        <form action="{{ route('stock-movements.store') }}" method="POST" class="p-6 flex flex-col flex-1" id="stockOutForm">
            @csrf
            <input type="hidden" name="type" value="out">

            <div class="space-y-4 flex-1">
                <div>
                    <label for="product_out" class="block text-sm font-medium text-gray-700 mb-2">Produk</label>
                    <select name="product_id" id="product_out" class="block w-full rounded-lg border-gray-200 bg-gray-50 py-3 px-4 text-sm focus:border-red-400 focus:ring-red-300 focus:outline-none focus:ring focus:ring-opacity-40" required>
                        <option value="">Pilih Produk</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">
                            {{ $product->name }} (Stok: {{ $product->stock }})
                        </option>
                        @endforeach
                    </select>
                    <div id="stock-info-out" class="mt-2 text-sm font-medium hidden">
                        <span class="px-3 py-1 bg-red-50 text-red-600 rounded-full inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                            Stok tersedia: <span id="available-stock-out" class="font-semibold ml-1">0</span>
                        </span>
                    </div>
                </div>

                <div>
                    <label for="quantity_out" class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <div class="flex rounded-lg bg-gray-50 border border-gray-200 overflow-hidden">
                        <button type="button" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 border-r border-gray-200 focus:outline-none transition-colors" id="decrease-qty-out">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <input
                            type="number"
                            name="quantity"
                            id="quantity_out"
                            value="1"
                            min="1"
                            class="flex-1 block w-full py-3 px-4 border-0 bg-gray-50 text-center focus:ring-0 text-sm"
                            required />
                        <button type="button" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 border-l border-gray-200 focus:outline-none transition-colors" id="increase-qty-out">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="quantity-warning-out" class="mt-2 hidden items-center text-sm text-red-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span>Jumlah melebihi stok yang tersedia!</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="reset" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition-all border border-gray-200 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset
                </button>
                <button type="submit" id="submit-btn-out" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-lg transition-all flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    Stok Keluar
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form Stok Masuk
        const productInSelect = document.getElementById('product_in');
        const quantityInInput = document.getElementById('quantity_in');
        const increaseInBtn = document.getElementById('increase-qty-in');
        const decreaseInBtn = document.getElementById('decrease-qty-in');

        // Form Stok Keluar
        const productOutSelect = document.getElementById('product_out');
        const quantityOutInput = document.getElementById('quantity_out');
        const stockInfoOut = document.getElementById('stock-info-out');
        const availableStockOut = document.getElementById('available-stock-out');
        const quantityWarningOut = document.getElementById('quantity-warning-out');
        const submitBtnOut = document.getElementById('submit-btn-out');
        const increaseOutBtn = document.getElementById('increase-qty-out');
        const decreaseOutBtn = document.getElementById('decrease-qty-out');

        // Stock In Functions
        increaseInBtn.addEventListener('click', function() {
            quantityInInput.value = (parseInt(quantityInInput.value) || 0) + 1;
        });

        decreaseInBtn.addEventListener('click', function() {
            const currentVal = parseInt(quantityInInput.value) || 0;
            if (currentVal > 1) {
                quantityInInput.value = currentVal - 1;
            }
        });

        // Stock Out Functions
        productOutSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const currentStock = selectedOption.dataset.stock;
                availableStockOut.textContent = currentStock;
                stockInfoOut.classList.remove('hidden');
                validateQuantityOut();
            } else {
                stockInfoOut.classList.add('hidden');
            }
        });

        function validateQuantityOut() {
            const selectedOption = productOutSelect.options[productOutSelect.selectedIndex];
            if (!selectedOption.value) return;

            const currentStock = parseInt(selectedOption.dataset.stock);
            const quantity = parseInt(quantityOutInput.value) || 0;

            if (quantity > currentStock) {
                quantityWarningOut.classList.remove('hidden');
                quantityWarningOut.classList.add('flex');
                quantityOutInput.classList.add('border-red-300', 'bg-red-50');
                submitBtnOut.disabled = true;
                submitBtnOut.classList.add('opacity-60', 'cursor-not-allowed');
            } else {
                quantityWarningOut.classList.add('hidden');
                quantityWarningOut.classList.remove('flex');
                quantityOutInput.classList.remove('border-red-300', 'bg-red-50');
                submitBtnOut.disabled = false;
                submitBtnOut.classList.remove('opacity-60', 'cursor-not-allowed');
            }
        }

        quantityOutInput.addEventListener('input', validateQuantityOut);

        increaseOutBtn.addEventListener('click', function() {
            quantityOutInput.value = (parseInt(quantityOutInput.value) || 0) + 1;
            validateQuantityOut();
        });

        decreaseOutBtn.addEventListener('click', function() {
            const currentVal = parseInt(quantityOutInput.value) || 0;
            if (currentVal > 1) {
                quantityOutInput.value = currentVal - 1;
                validateQuantityOut();
            }
        });
    });
</script>
@endsection
