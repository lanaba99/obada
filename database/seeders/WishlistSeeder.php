<?php

namespace Database\Seeders;

use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // To avoid unique constraint errors (user_id, product_id must be unique)
        // we can iterate through users and add unique products.
        \App\Models\User::all()->each(function ($user) {
            $products = \App\Models\Product::inRandomOrder()->limit(rand(1, 5))->get(); // Each user wishlists 1-5 random products
            foreach ($products as $product) {
                try {
                    Wishlist::factory()->create([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    // Ignore unique constraint violation if a product is already wishlisted
                    if ($e->getCode() === '23000') { // MySQL error code for integrity constraint violation
                        continue;
                    }
                    throw $e;
                }
            }
        });
    }
}