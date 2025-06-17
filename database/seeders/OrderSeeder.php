<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() === 0) {
            User::factory()->count(10)->create();
        }
        if (Product::count() === 0) {
            Product::factory()->count(20)->create();
        }

        Order::factory()->count(30)
            ->has(\App\Models\OrderItem::factory()->count(rand(1, 5)))
            ->create();

        Order::factory()->count(10)->delivered()
            ->has(\App\Models\OrderItem::factory()->count(rand(1, 3)))
            ->create();

        Order::factory()->count(5)->cancelled()
            ->has(\App\Models\OrderItem::factory()->count(rand(1, 2)))
            ->create();
    }
}