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
        if (Order::count() === 0) {
            $this->call(OrderSeeder::class);
        }
        if (Product::count() === 0) {
            $this->call(ProductSeeder::class);
        }

        // Create 50 additional order items randomly associated with existing orders
        OrderItem::factory()->count(50)->create();
    }
}