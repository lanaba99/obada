<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure users, products, and promo codes exist
        if (User::count() === 0) {
            $this->call(UserSeeder::class);
        }
        if (Product::count() === 0) {
            $this->call(ProductSeeder::class);
        }
        if (PromoCode::count() === 0) {
            $this->call(PromoCodeSeeder::class);
        }

        $customer = User::where('email', 'customer@example.com')->first();
        $alice = User::where('email', 'alice@example.com')->first();
        $bob = User::where('email', 'bob@example.com')->first();

        $product1 = Product::where('name', 'Elegant Lace Wedding Dress')->first();
        $product2 = Product::where('name', 'Classic A-Line Bridal Gown')->first();
        $product3 = Product::where('name', 'Sparkling Bridal Heels')->first();
        $product4 = Product::where('name', 'Cathedral Length Wedding Veil')->first();

        $promoCodeFixed = PromoCode::where('code', 'SAVE50')->first();
        $promoCodePercent = PromoCode::where('code', 'WEDDING10')->first();


        // --- Order 1: Completed Order with discount ---
        if ($customer && $product1 && $product3 && $promoCodeFixed) {
            $totalAmount1 = $product1->price * 1 + $product3->price * 1; // 1500 + 180 = 1680
            $discountAmount1 = $promoCodeFixed->value; // 50
            $finalAmount1 = $totalAmount1 - $discountAmount1; // 1680 - 50 = 1630

            $order = Order::create([
                'user_id' => $customer->id,
                'total_amount' => $totalAmount1,
                'discount_amount' => $discountAmount1,
                'final_amount' => $finalAmount1, // <<< Corrected: Included final_amount here
                'promo_code_id' => $promoCodeFixed->id,
                'status' => 'delivered',
                'shipping_address' => json_encode(['address_line1' => '123 Main St', 'city' => 'Amsterdam', 'postal_code' => '1000 AB', 'country' => 'NL']),
                'billing_address' => json_encode(['address_line1' => '123 Main St', 'city' => 'Amsterdam', 'postal_code' => '1000 AB', 'country' => 'NL']),
            ]);

            $order->orderItems()->create(['product_id' => $product1->id, 'quantity' => 1, 'price' => $product1->price]);
            $order->orderItems()->create(['product_id' => $product3->id, 'quantity' => 1, 'price' => $product3->price]);

            $order->payment()->create([
                'transaction_id' => 'PAY-ORDER1-XYZ',
                'amount' => $order->final_amount,
                'currency' => 'EUR',
                'method' => 'credit_card',
                'status' => 'completed',
                'details' => json_encode(['last_four' => '1111']),
            ]);
        }

        // --- Order 2: Processing Order without discount ---
        if ($alice && $product2 && $product4) {
            $totalAmount2 = $product2->price * 1 + $product4->price * 1; // 1200 + 250 = 1450
            $discountAmount2 = 0;
            $finalAmount2 = $totalAmount2 - $discountAmount2; // 1450

            $order = Order::create([
                'user_id' => $alice->id,
                'total_amount' => $totalAmount2,
                'discount_amount' => $discountAmount2,
                'final_amount' => $finalAmount2, // <<< Corrected: Included final_amount here
                'promo_code_id' => null,
                'status' => 'processing',
                'shipping_address' => json_encode(['address_line1' => '45 Koninginneweg', 'city' => 'Rotterdam', 'postal_code' => '3000 CD', 'country' => 'NL']),
                'billing_address' => json_encode(['address_line1' => '45 Koninginneweg', 'city' => 'Rotterdam', 'postal_code' => '3000 CD', 'country' => 'NL']),
            ]);

            $order->orderItems()->create(['product_id' => $product2->id, 'quantity' => 1, 'price' => $product2->price]);
            $order->orderItems()->create(['product_id' => $product4->id, 'quantity' => 1, 'price' => $product4->price]);

            $order->payment()->create([
                'transaction_id' => 'PAY-ORDER2-ABC',
                'amount' => $order->final_amount,
                'currency' => 'EUR',
                'method' => 'paypal',
                'status' => 'completed',
                'details' => json_encode(['paypal_id' => 'alice@paypal.com']),
            ]);
        }

        // --- Order 3: Cancelled Order ---
        if ($bob && $product1) {
            $totalAmount3 = $product1->price * 2; // 1500 * 2 = 3000
            $discountAmount3 = 0;
            $finalAmount3 = $totalAmount3 - $discountAmount3; // 3000

            $order = Order::create([
                'user_id' => $bob->id,
                'total_amount' => $totalAmount3,
                'discount_amount' => $discountAmount3,
                'final_amount' => $finalAmount3, // <<< Corrected: Included final_amount here
                'promo_code_id' => null,
                'status' => 'cancelled',
                'shipping_address' => json_encode(['address_line1' => '789 Elm St', 'city' => 'Utrecht', 'postal_code' => '3500 EF', 'country' => 'NL']),
                'billing_address' => json_encode(['address_line1' => '789 Elm St', 'city' => 'Utrecht', 'postal_code' => '3500 EF', 'country' => 'NL']),
            ]);

            $order->orderItems()->create(['product_id' => $product1->id, 'quantity' => 2, 'price' => $product1->price]);
            // No payment for cancelled order or payment failed record
        }

        // --- Order 4: Pending Payment with Percentage Discount ---
        if ($customer && $product1 && $promoCodePercent) {
             $totalAmount4 = $product1->price * 1; // 1500
             $discountAmount4 = $totalAmount4 * ($promoCodePercent->value / 100); // 1500 * 0.10 = 150
             $finalAmount4 = $totalAmount4 - $discountAmount4; // 1350

            $order = Order::create([
                'user_id' => $customer->id,
                'total_amount' => $totalAmount4,
                'discount_amount' => $discountAmount4,
                'final_amount' => $finalAmount4, // <<< Corrected: Included final_amount here
                'promo_code_id' => $promoCodePercent->id,
                'status' => 'pending_payment',
                'shipping_address' => json_encode(['address_line1' => '123 Main St', 'city' => 'Amsterdam', 'postal_code' => '1000 AB', 'country' => 'NL']),
                'billing_address' => json_encode(['address_line1' => '123 Main St', 'city' => 'Amsterdam', 'postal_code' => '1000 AB', 'country' => 'NL']),
            ]);
            $order->orderItems()->create(['product_id' => $product1->id, 'quantity' => 1, 'price' => $product1->price]);

            // Payment status pending
            $order->payment()->create([
                'transaction_id' => null,
                'amount' => $order->final_amount,
                'currency' => 'EUR',
                'method' => 'credit_card',
                'status' => 'pending',
                'details' => null,
            ]);
        }
    }
}