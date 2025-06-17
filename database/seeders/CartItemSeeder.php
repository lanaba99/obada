<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure necessary data exists
        if (User::count() === 0) {
            User::factory()->count(10)->create();
        }
        if (Product::count() === 0) {
            Product::factory()->count(20)->create();
        }

        // Populate cart items, avoiding duplicates per user/product
        User::all()->each(function ($user) {
            // Each user adds 1-5 unique products to their cart
            $products = Product::inRandomOrder()->limit(rand(1, 5))->get();
            foreach ($products as $product) {
                try {
                    CartItem::factory()->create([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'quantity' => rand(1, 3),
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    // Ignore unique constraint violation (product already in cart)
                    if ($e->getCode() === '23000') {
                        continue;
                    }
                    throw $e;
                }
            }
        });
    }
}