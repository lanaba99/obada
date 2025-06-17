<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Order; // Make sure Order model exists
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure there's at least one order to link payments to
        $orderId = Order::inRandomOrder()->first()->id ?? Order::factory();

        return [
            'order_id' => $orderId,
            'transaction_id' => 'TXN-' . $this->faker->unique()->ean13(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => $this->faker->randomElement(['EUR', 'USD', 'GBP']),
            'method' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'details' => json_encode(['card_type' => $this->faker->creditCardType, 'last_four' => $this->faker->randomNumber(4, true)]),
        ];
    }

    /**
     * Indicate that the payment is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the payment failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
        ]);
    }
}