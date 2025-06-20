<?php

namespace Database\Factories;

use App\Models\CartItem;
use App\Models\User;    // Make sure to import User model
use App\Models\Product; // Make sure to import Product model
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CartItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure there are existing users and products to link cart items to
        $userId = User::inRandomOrder()->first()->id ?? User::factory();
        $productId = Product::inRandomOrder()->first()->id ?? Product::factory();

        return [
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }
}