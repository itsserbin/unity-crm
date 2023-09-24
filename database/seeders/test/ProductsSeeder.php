<?php

namespace Database\Seeders\test;

use App\Models\Catalog\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    final public function run(): void
    {
        Product::factory()->count(100)->create();
    }
}
