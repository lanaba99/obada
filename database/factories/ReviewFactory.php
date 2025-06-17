<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;    // Make sure User model exists
use App\Models\Product; // Make sure Product model exists
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure there are existing users and products to link reviews to
        $userId = User::inRandomOrder()->first()->id ?? User::factory();
        $productId = Product::inRandomOrder()->first()->id ?? Product::factory();

        return [
            'user_id' => $userId,
            'product_id' => $productId,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->boolean(80) ? $this->faker->paragraph(2) : null,
        ];
    }

    /**
     * Indicate that the review has a high rating.
     */
    public function good(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->numberBetween(4, 5),
        ]);
    }

    /**
     * Indicate that the review has a low rating.
     */
    public function bad(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->numberBetween(1, 2),
        ]);
    }
}