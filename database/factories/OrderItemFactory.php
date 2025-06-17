<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;    // Make sure to import Order model
use App\Models\Product; // Make sure to import Product model
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure there are existing orders and products
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        return [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $this->faker->numberBetween(1, 3),
            'price' => $product->price, // Use the product's current price
        ];
    }
}