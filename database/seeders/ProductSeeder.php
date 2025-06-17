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
        // Clear existing products
        // Product::truncate(); // Uncomment if you want to clear only products

        // Ensure categories exist
        if (Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }

        // Get some existing categories to attach to products
        $weddingDressesCategory = Category::where('slug', 'wedding-dresses')->first();
        $bridalShoesCategory = Category::where('slug', 'bridal-shoes')->first();
        $weddingVeilsCategory = Category::where('slug', 'wedding-veils')->first();
        $bridalAccessoriesCategory = Category::where('slug', 'bridal-accessories')->first();

        // Create sample products
        $product1 = Product::create([
            'name' => 'Elegant Lace Wedding Dress',
            'description' => 'A beautiful lace wedding dress with a long train.',
            'price' => 1500.00,
            'image_url' => 'https://via.placeholder.com/640x480/FFB6C1?text=Wedding+Dress+1',
            'stock' => 10,
            'is_featured' => true,
        ]);
        if ($weddingDressesCategory) {
            $product1->categories()->attach($weddingDressesCategory->id);
        }

        $product2 = Product::create([
            'name' => 'Classic A-Line Bridal Gown',
            'description' => 'Timeless A-line gown made from satin fabric.',
            'price' => 1200.00,
            'image_url' => 'https://via.placeholder.com/640x480/F0F8FF?text=Wedding+Dress+2',
            'stock' => 5,
            'is_featured' => false,
        ]);
        if ($weddingDressesCategory) {
            $product2->categories()->attach($weddingDressesCategory->id);
        }

        $product3 = Product::create([
            'name' => 'Sparkling Bridal Heels',
            'description' => 'High heels adorned with glitter for a dazzling look.',
            'price' => 180.00,
            'image_url' => 'https://via.placeholder.com/640x480/DDA0DD?text=Bridal+Heels',
            'stock' => 20,
            'is_featured' => true,
        ]);
        if ($bridalShoesCategory) {
            $product3->categories()->attach($bridalShoesCategory->id);
        }

        $product4 = Product::create([
            'name' => 'Cathedral Length Wedding Veil',
            'description' => 'A grand veil perfect for a cathedral wedding.',
            'price' => 250.00,
            'image_url' => 'https://via.placeholder.com/640x480/FFFFFF?text=Wedding+Veil',
            'stock' => 15,
            'is_featured' => false,
        ]);
        if ($weddingVeilsCategory) {
            $product4->categories()->attach($weddingVeilsCategory->id);
        }

        $product5 = Product::create([
            'name' => 'Pearl Bridal Earrings',
            'description' => 'Elegant pearl earrings to complement any dress.',
            'price' => 50.00,
            'image_url' => 'https://via.placeholder.com/640x480/F0F0F0?text=Pearl+Earrings',
            'stock' => 30,
            'is_featured' => true,
        ]);
        if ($bridalAccessoriesCategory) {
            $product5->categories()->attach($bridalAccessoriesCategory->id);
        }

        // Out of stock product
        Product::create([
            'name' => 'Vintage Inspired Headpiece',
            'description' => 'A unique headpiece with a vintage flair.',
            'price' => 95.00,
            'image_url' => 'https://via.placeholder.com/640x480/D3D3D3?text=Headpiece',
            'stock' => 0, // Out of stock
            'is_featured' => false,
        ]);
    }
}