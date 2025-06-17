<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User; // Make sure to import the User model
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure there's at least one user to link addresses to
        $userId = User::inRandomOrder()->first()->id ?? User::factory();

        return [
            'user_id' => $userId,
            'address_line1' => $this->faker->streetAddress(),
            'address_line2' => $this->faker->boolean(30) ? $this->faker->secondaryAddress() : null,
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->countryCode(),
            'type' => $this->faker->randomElement(['shipping', 'billing']),
            'is_default' => $this->faker->boolean(20),
        ];
    }

    /**
     * Indicate that the address is a shipping address.
     */
    public function shipping(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'shipping',
        ]);
    }

    /**
     * Indicate that the address is a billing address.
     */
    public function billing(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'billing',
        ]);
    }

    /**
     * Indicate that the address is a default address.
     */
    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }
}