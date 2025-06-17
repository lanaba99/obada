<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }

        Product::factory()->count(50)->create();
        Product::factory()->count(10)->featured()->create();
        Product::factory()->count(5)->outOfStock()->create();
    }
}