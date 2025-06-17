<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing reviews
        // Review::truncate(); // Uncomment if needed

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

        // Create sample reviews, respecting unique constraint (user_id, product_id)
        if ($customer && $product1) {
            Review::create([
                'user_id' => $customer->id,
                'product_id' => $product1->id,
                'rating' => 5,
                'comment' => 'Absolutely stunning dress! Highly recommend it.',
            ]);
        }

        if ($customer && $product3) {
            Review::create([
                'user_id' => $customer->id,
                'product_id' => $product3->id,
                'rating' => 4,
                'comment' => 'Beautiful heels, a bit uncomfortable after a few hours.',
            ]);
        }

        if ($alice && $product2) {
            Review::create([
                'user_id' => $alice->id,
                'product_id' => $product2->id,
                'rating' => 5,
                'comment' => 'A truly classic and elegant gown. Perfect!',
            ]);
        }

        if ($bob && $product1) {
            Review::create([
                'user_id' => $bob->id,
                'product_id' => $product1->id,
                'rating' => 3,
                'comment' => 'Nice dress, but the lace details were not what I expected from the photos.',
            ]);
        }

        // Add more unique reviews as needed
    }
}