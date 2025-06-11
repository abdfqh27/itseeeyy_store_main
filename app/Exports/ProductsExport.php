<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $type;

    public function __construct($type = 'all')
    {
        $this->type = $type;
    }

    public function collection()
    {
        if ($this->type === 'low_stock') {
            return Product::with('category')->where('stock', '<', 10)->get();
        }

        return Product::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Category',
            'SKU',
            'Stock',
            'Price',
            'Expiry Date',
            'Status',
            'Created At'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->category->name ?? 'N/A',
            $product->sku,
            $product->stock,
            $product->price,
            $product->expiry_date,
            $product->stock < 10 ? 'Low Stock' : 'Normal',
            $product->created_at->format('Y-m-d H:i:s')
        ];
    }
}
