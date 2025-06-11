@extends('layouts.app')

@section('title', 'Kategori')
@section('header', 'Kategori')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-semibold text-pink-700 mb-6">Semua Kategori</h2>

    <!-- Search Bar -->
    <div class="mb-6 bg-white rounded-lg shadow-md p-4 border-l-4 border-pink-500">
        <form action="{{ route('categories.index') }}" method="GET">
            <div class="flex items-center">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-pink-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kategori..." class="pl-10 pr-4 py-2 border border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 block w-full rounded-md shadow-sm">
                </div>
                <div class="ml-4 flex">
                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md transition-all">
                        Cari
                    </button>
                    @if(request('search'))
                    <a href="{{ route('categories.index') }}" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition-all">
                        Bersihkan
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="flex justify-end gap-3 mb-4">
        <a href="{{ route('categories.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md transition-all inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Kategori
        </a>
    </div>
</div>

<!-- Notification Messages -->
@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Results for Search -->
@if(request('search'))
    <div class="mb-4 text-sm text-pink-600">
        <p>Menampilkan hasil untuk: <span class="font-semibold">"{{ request('search') }}"</span></p>
    </div>
@endif

<div class="bg-white rounded-lg shadow-md overflow-hidden border border-pink-100">
    <table class="min-w-full divide-y divide-pink-200">
        <thead class="bg-pink-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">
                    <input type="checkbox" id="select-all-checkbox" class="rounded text-pink-600 focus:ring-pink-500 h-4 w-4">
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Nama</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Deskripsi</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Jumlah Produk</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-pink-700 uppercase tracking-wider">Tindakan</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-pink-100">
            @forelse ($categories as $category)
            <tr class="hover:bg-pink-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox"
                           class="category-checkbox rounded text-pink-600 focus:ring-pink-500 h-4 w-4"
                           data-id="{{ $category->id }}"
                           {{ $category->products_count > 0 ? 'disabled' : '' }}>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-500">{{ $category->description ?? 'No description' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category->products_count > 0 ? 'bg-pink-100 text-pink-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $category->products_count }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('categories.edit', $category) }}" class="text-pink-600 hover:text-pink-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        @if ($category->products_count == 0)
                        <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Are you sure you want to delete this category?');" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-10 whitespace-nowrap text-sm text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-12 h-12 text-pink-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">
                            @if(request('search'))
                                Tidak ada kategori yang ditemukan yang cocok "{{ request('search') }}"
                            @else
                                Tidak ada kategori yang ditemukan
                            @endif
                        </p>
                        @if(request('search'))
                            <a href="{{ route('categories.index') }}" class="mt-3 text-sm text-pink-600 hover:text-pink-800">
                                Bersihkan pencarian dan tampilkan semua kategori
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-pink-200 bg-pink-50">
        {{ $categories->links() }}
    </div>
</div>

<!-- Script untuk bulk delete -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox:not([disabled])');
        const selectedCategoriesInput = document.getElementById('selected-categories-input');
        const bulkDeleteButton = document.getElementById('bulk-delete-button');

        // Function to update the hidden input and bulk delete button
        function updateBulkDelete() {
            const selectedIds = Array.from(document.querySelectorAll('.category-checkbox:checked:not([disabled])'))
                .map(checkbox => checkbox.dataset.id);

            selectedCategoriesInput.value = selectedIds.join(',');

            if (selectedIds.length === 0) {
                bulkDeleteButton.disabled = true;
                bulkDeleteButton.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                bulkDeleteButton.disabled = false;
                bulkDeleteButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        // Initialize the button state
        updateBulkDelete();

        // Select all checkbox event
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                categoryCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkDelete();
            });
        }

        // Individual checkboxes events
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Update "select all" checkbox state
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else if (document.querySelectorAll('.category-checkbox:not(:checked):not([disabled])').length === 0) {
                    selectAllCheckbox.checked = true;
                }

                updateBulkDelete();
            });
        });
    });
</script>
@endsection

