<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil parameter pencarian dan filter dari request
        $search = $request->input('search');
        $categoryFilter = $request->input('category_id');
        $stockFilter = $request->input('stock_filter'); // Filter stok baru: 10+, 1-10, 0
        $expiryFilter = $request->input('expiry_filter'); // Filter kadaluarsa baru: expired, 10 hari, 30 hari

        // Mengambil parameter untuk pengurutan
        $sortBy = $request->input('sort_by', 'name'); // Default: urutkan berdasarkan nama produk
        $sortDirection = $request->input('sort_direction', 'asc'); // Default: urutan ascending (A-Z)

        // Memvalidasi field pengurutan yang diizinkan untuk menghindari SQL injection
        $allowedSortFields = ['name', 'category_name', 'stock', 'expiry_date'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'name'; // Default jika field tidak valid
        }

        // Memvalidasi arah pengurutan
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc'; // Default jika arah tidak valid
        }

        // Memulai query dengan relasi category
        $query = Product::with('category');

        // Menerapkan filter pencarian jika ada input pencarian
        if ($search) {
            // Menambahkan kondisi pencarian untuk kolom name, description, dan juga nama kategori
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan kategori
        if ($categoryFilter) {
            $query->where('category_id', $categoryFilter);
        }

        // Filter berdasarkan stok
        if ($stockFilter) {
            switch ($stockFilter) {
                case '10plus':
                    $query->where('stock', '>=', 10);
                    break;
                case '1-10':
                    $query->whereBetween('stock', [1, 10]);
                    break;
                case '0':
                    $query->where('stock', 0);
                    break;
            }
        }

        // Filter berdasarkan tanggal kedaluwarsa
        if ($expiryFilter) {
            $today = Carbon::today();

            switch ($expiryFilter) {
                case 'expired':
                    // Produk yang sudah kadaluarsa (tanggal kurang dari hari ini)
                    $query->whereDate('expiry_date', '<', $today);
                    break;
                case '10days':
                    // Produk yang akan kadaluarsa dalam 10 hari ke depan
                    $query->whereDate('expiry_date', '>=', $today)
                          ->whereDate('expiry_date', '<=', $today->copy()->addDays(10));
                    break;
                case '30days':
                    // Produk yang akan kadaluarsa dalam 30 hari ke depan
                    $query->whereDate('expiry_date', '>=', $today)
                          ->whereDate('expiry_date', '<=', $today->copy()->addDays(30));
                    break;
            }
        }

        // Menerapkan pengurutan berdasarkan parameter sort_by dan sort_direction
        switch ($sortBy) {
            case 'name':
                // Mengurutkan berdasarkan nama produk
                $query->orderBy('name', $sortDirection);
                break;

            case 'category_name':
                // Mengurutkan berdasarkan nama kategori
                // Menggunakan join untuk mengurutkan berdasarkan nama kategori
                $query->join('categories', 'products.category_id', '=', 'categories.id')
                      ->orderBy('categories.name', $sortDirection)
                      ->select('products.*'); // Penting: memilih kolom dari tabel products saja
                break;

            case 'stock':
                // Mengurutkan berdasarkan jumlah stok (bisa dari terkecil ke terbesar atau sebaliknya)
                $query->orderBy('stock', $sortDirection);
                break;

            case 'expiry_date':
                // Mengurutkan berdasarkan tanggal kedaluwarsa (dari yang sebentar lagi ke yang lama atau sebaliknya)
                $query->orderBy('expiry_date', $sortDirection);
                break;
        }

        // Mengambil data dengan pagination (maksimal 7 data per halaman)
        // dan menyimpan parameter pencarian dan pengurutan di URL saat paginasi
        $products = $query->paginate(7)->appends([
            'search' => $search,
            'category_id' => $categoryFilter,
            'stock_filter' => $stockFilter,
            'expiry_filter' => $expiryFilter,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection
        ]);

        // Ambil semua kategori untuk filter dropdown
        $categories = Category::orderBy('name')->get();

        // Mengirim data ke view, termasuk parameter pencarian, filter, dan pengurutan
        return view('products.index', compact(
            'products',
            'search',
            'categories',
            'categoryFilter',
            'stockFilter',
            'expiryFilter',
            'sortBy',
            'sortDirection'
        ));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'expiry_date' => 'required|date|after:today',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product = Product::create($validated);

        // Record initial stock movement
        if ($validated['stock'] > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $validated['stock'],
                'notes' => 'Initial stock',
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dibuat.');
    }

    public function show(Product $product)
    {
        $product->load('category', 'stockMovements');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $oldStock = $product->stock;

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        // Record stock movement if stock has changed
        $newStock = $validated['stock'];
        if ($newStock != $oldStock) {
            $type = $newStock > $oldStock ? 'in' : 'out';
            $quantity = abs($newStock - $oldStock);

            StockMovement::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $quantity,
                'notes' => 'Stock adjustment via product update',
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbaharui.');
    }

    public function destroy(Product $product)
    {
        // Delete image if it exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|not_in:0',
            'notes' => 'nullable|string',
        ]);

        $type = $validated['quantity'] > 0 ? 'in' : 'out';
        $quantity = abs($validated['quantity']);

        // Check if we have enough stock for outgoing movements
        if ($type === 'out' && $product->stock < $quantity) {
            return back()->with('error', 'Stok tidak cukup tersedia.');
        }

        // Update product stock
        $product->stock = $type === 'in'
            ? $product->stock + $quantity
            : $product->stock - $quantity;
        $product->save();

        // Create stock movement record
        StockMovement::create([
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => $quantity,
            'notes' => $validated['notes'] ?? ($type === 'in' ? 'Stock added' : 'Stock removed'),
        ]);

        return back()->with('success', 'Stok berhasil diperbaharui.');
    }
}