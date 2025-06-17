<?php

namespace Database\Factories;

use App\Models\PromoCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PromoCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PromoCode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['fixed', 'percentage']);
        $value = ($type === 'fixed') ? $this->faker->randomFloat(2, 5, 50) : $this->faker->numberBetween(5, 25); // percentage

        return [
            'code' => Str::upper(Str::random(10)),
            'type' => $type,
            'value' => $value,
            'min_order_amount' => $this->faker->boolean(50) ? $this->faker->randomFloat(2, 50, 200) : null,
            'expires_at' => $this->faker->boolean(70) ? $this->faker->dateTimeBetween('now', '+1 year') : null,
            'usage_limit' => $this->faker->boolean(50) ? $this->faker->numberBetween(10, 1000) : null,
            'used_count' => 0,
            'is_active' => $this->faker->boolean(90),
        ];
    }

    /**
     * Indicate that the promo code is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the promo code has reached its usage limit.
     */
    public function usageLimitReached(): static
    {
        return $this->state(fn (array $attributes) => [
            'usage_limit' => 10,
            'used_count' => 10,
            'is_active' => false,
        ]);
    }
}