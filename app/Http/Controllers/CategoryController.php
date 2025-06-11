<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil parameter pencarian dari request
        $search = $request->input('search');
        
        // Memulai query untuk mengambil kategori
        $query = Category::withCount('products')->orderBy('name');
        
        // Menerapkan filter pencarian jika ada input pencarian
        if ($search) {
            // Menambahkan kondisi pencarian untuk kolom name dan description
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Mengambil data dengan pagination (maksimal 10 data per halaman)
        // dan menyimpan parameter pencarian di URL saat paginasi
        $categories = $query->paginate(7)->appends(['search' => $search]);
        
        // Mengirim data ke view
        return view('categories.index', compact('categories', 'search'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbaharui.');
    }

    public function destroy(Category $category)
    {
        // Check if there are products in this category
        if ($category->products()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'Tidak dapat menghapus kategori yang memiliki produk.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $categoryIds = explode(',', $request->input('selected_categories'));

        // Validasi bahwa semua kategori yang dipilih tidak memiliki produk
        $categoriesWithProducts = Category::whereIn('id', $categoryIds)
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->count();

        if ($categoriesWithProducts > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Tidak dapat menghapus kategori yang memiliki produk.');
        }

        // Delete categories
        Category::whereIn('id', $categoryIds)->delete();

        return redirect()->route('categories.index')
            ->with('success', count($categoryIds) . ' Kategori berhasil dihapus.');
    }
}