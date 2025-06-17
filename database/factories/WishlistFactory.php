<?php

namespace Database\Factories;

use App\Models\Wishlist;
use App\Models\User;    // Make sure User model exists
use App\Models\Product; // Make sure Product model exists
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wishlist::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure there are existing users and products
        $userId = User::inRandomOrder()->first()->id ?? User::factory();
        $productId = Product::inRandomOrder()->first()->id ?? Product::factory();

        return [
            'user_id' => $userId,
            'product_id' => $productId,
        ];
    }
}