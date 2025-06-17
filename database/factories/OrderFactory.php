<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;       // Import User model
use App\Models\PromoCode; // Import PromoCode model
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure there's at least one user to link orders to
        $userId = User::inRandomOrder()->first()->id ?? User::factory();

        $totalAmount = $this->faker->randomFloat(2, 50, 2000);
        $discountAmount = 0;
        $promoCodeId = null;

        // Optionally apply a promo code
        if ($this->faker->boolean(30)) { // 30% chance to apply a promo code
            $promoCode = PromoCode::inRandomOrder()->where('is_active', true)->whereNull('expires_at')->first();
            if ($promoCode) {
                $promoCodeId = $promoCode->id;
                if ($promoCode->type === 'fixed') {
                    $discountAmount = $promoCode->value;
                } else { // percentage
                    $discountAmount = $totalAmount * ($promoCode->value / 100);
                }
                $discountAmount = min($discountAmount, $totalAmount * 0.5); // Cap discount at 50% of total
            }
        }

        $finalAmount = $totalAmount - $discountAmount;
        if ($finalAmount < 0) $finalAmount = 0; // Ensure final amount is not negative

        return [
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'final_amount' => $finalAmount,
            'discount_amount' => $discountAmount,
            'promo_code_id' => $promoCodeId,
            'status' => $this->faker->randomElement(['pending_payment', 'processing', 'shipped', 'delivered', 'cancelled']),
            'shipping_address' => json_encode([ // Storing as JSON, or you can link to an Address model
                'address_line1' => $this->faker->streetAddress(),
                'city' => $this->faker->city(),
                'postal_code' => $this->faker->postcode(),
                'country' => $this->faker->country(),
            ]),
            'billing_address' => json_encode([ // Storing as JSON, or you can link to an Address model
                'address_line1' => $this->faker->streetAddress(),
                'city' => $this->faker->city(),
                'postal_code' => $this->faker->postcode(),
                'country' => $this->faker->country(),
            ]),
        ];
    }

    /**
     * Indicate that the order is completed.
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
        ]);
    }

    /**
     * Indicate that the order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}