<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Gentle Facial Cleanser',
                'category_id' => 1,
                'stock' => 25,
                'expiry_date' => Carbon::now()->addMonths(12),
                'description' => 'A gentle facial cleanser suitable for all skin types',
            ],
            [
                'name' => 'Hydrating Facial Moisturizer',
                'category_id' => 2,
                'stock' => 20,
                'expiry_date' => Carbon::now()->addMonths(18),
                'description' => 'A hydrating facial moisturizer for dry and normal skin',
            ],
            [
                'name' => 'Vitamin C Serum',
                'category_id' => 3,
                'stock' => 15,
                'expiry_date' => Carbon::now()->addDays(15), // Near expiry for testing
                'description' => 'A brightening vitamin C serum for all skin types',
            ],
            [
                'name' => 'Clay Face Mask',
                'category_id' => 4,
                'stock' => 10,
                'expiry_date' => Carbon::now()->addMonths(24),
                'description' => 'A purifying clay mask for oily and combination skin',
            ],
            [
                'name' => 'SPF 50 Sunscreen',
                'category_id' => 5,
                'stock' => 30,
                'expiry_date' => Carbon::now()->addDays(10), // Near expiry for testing
                'description' => 'A high protection SPF 50 sunscreen for face and body',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
