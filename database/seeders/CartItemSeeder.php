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
        // Clear existing cart items
        // CartItem::truncate(); // Uncomment if needed

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

        // Create cart items for Test Customer
        if ($customer && $product1) {
            CartItem::create([
                'user_id' => $customer->id,
                'product_id' => $product1->id,
                'quantity' => 1,
            ]);
        }
        if ($customer && $product3) {
            CartItem::create([
                'user_id' => $customer->id,
                'product_id' => $product3->id,
                'quantity' => 1,
            ]);
        }

        // Create cart items for Alice Smith
        if ($alice && $product2) {
            CartItem::create([
                'user_id' => $alice->id,
                'product_id' => $product2->id,
                'quantity' => 1,
            ]);
        }
        if ($alice && $product4) {
            CartItem::create([
                'user_id' => $alice->id,
                'product_id' => $product4->id,
                'quantity' => 1,
            ]);
        }

        // Create cart items for Bob Johnson
        if ($bob && $product1) {
            CartItem::create([
                'user_id' => $bob->id,
                'product_id' => $product1->id,
                'quantity' => 2,
            ]);
        }
    }
}