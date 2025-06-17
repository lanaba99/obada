<?php

namespace Database\Seeders;

use App\Models\Wishlist;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing wishlists
        // Wishlist::truncate(); // Uncomment if needed

        // Ensure users and products exist
        if (User::count() === 0) {
            $this->call(UserSeeder::class);
        }
        if (Product::count() === 0) {
            $this->call(ProductSeeder::class);
        }

        $customer = User::where('email', 'customer@example.com')->first();
        $alice = User::where('email', 'alice@example.com')->first();
        $bob = User::where('email', 'bob@example.com')->first();

        $product1 = Product::where('name', 'Elegant Lace Wedding Dress')->first();
        $product2 = Product::where('name', 'Classic A-Line Bridal Gown')->first();
        $product3 = Product::where('name', 'Sparkling Bridal Heels')->first();
        $product4 = Product::where('name', 'Cathedral Length Wedding Veil')->first();
        $product5 = Product::where('name', 'Pearl Bridal Earrings')->first();

        // Create wishlist items for Test Customer
        if ($customer && $product2) {
            Wishlist::create([
                'user_id' => $customer->id,
                'product_id' => $product2->id,
            ]);
        }
        if ($customer && $product5) {
            Wishlist::create([
                'user_id' => $customer->id,
                'product_id' => $product5->id,
            ]);
        }

        // Create wishlist items for Alice Smith
        if ($alice && $product1) {
            Wishlist::create([
                'user_id' => $alice->id,
                'product_id' => $product1->id,
            ]);
        }
        if ($alice && $product3) {
            Wishlist::create([
                'user_id' => $alice->id,
                'product_id' => $product3->id,
            ]);
        }

        // Create wishlist items for Bob Johnson
        if ($bob && $product4) {
            Wishlist::create([
                'user_id' => $bob->id,
                'product_id' => $product4->id,
            ]);
        }
    }
}