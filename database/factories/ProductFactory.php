<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category; // Import Category model for linking
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productName = $this->faker->unique()->words(rand(2, 4), true) . ' Wedding ' . $this->faker->randomElement(['Dress', 'Veil', 'Accessory', 'Shoes']);
        $price = $this->faker->randomFloat(2, 100, 5000);

        return [
            'name' => $productName,
            'description' => $this->faker->paragraph(3),
            'price' => $price,
            'image_url' => 'https://via.placeholder.com/640x480.png/' . $this->faker->hexColor() . '?text=' . urlencode($productName), // Placeholder image
            'stock' => $this->faker->numberBetween(0, 100),
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            // Ensure there's at least one category, create if none exist
            $categories = Category::inRandomOrder()->limit(rand(1, 3))->get();
            if ($categories->isEmpty()) {
                $categories = Category::factory()->count(rand(1, 2))->create();
            }
            $product->categories()->attach($categories->pluck('id'));
        });
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }
}