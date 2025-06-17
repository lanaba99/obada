<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing order items
        // OrderItem::truncate(); // Uncomment if needed

        // Note: The main OrderSeeder now creates order items as part of order creation.
        // This seeder would only be used if you need to add *additional* items to existing orders,
        // or if you are not calling OrderSeeder for some reason.

        // Example of adding one more item to a known order (if it exists)
        $order = Order::where('status', 'processing')->first(); // Get an existing processing order
        $product = Product::where('name', 'Pearl Bridal Earrings')->first(); // A specific product

        if ($order && $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }
    }
}