<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing payments
        // Payment::truncate(); // Uncomment if needed

        // Note: The main OrderSeeder now creates payment records as part of order creation.
        // This seeder would only be used if you need to add *additional* payment records
        // for existing orders, or for independent payment records.

        // Example: Create a failed payment for a known order (if it exists)
        $order = Order::where('status', 'cancelled')->first(); // Get an existing cancelled order

        if ($order) {
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => 'FAILED-TXN-' . uniqid(),
                'amount' => $order->total_amount,
                'currency' => 'EUR',
                'method' => 'visa',
                'status' => 'failed',
                'details' => json_encode(['error' => 'Card declined']),
            ]);
        }

        // Example: Create a refunded payment for a delivered order
        $deliveredOrder = Order::where('status', 'delivered')->first();
        if ($deliveredOrder) {
            Payment::create([
                'order_id' => $deliveredOrder->id,
                'transaction_id' => 'REFUND-TXN-' . uniqid(),
                'amount' => $deliveredOrder->final_amount,
                'currency' => 'EUR',
                'method' => 'refund',
                'status' => 'refunded',
                'details' => json_encode(['reason' => 'Customer return']),
            ]);
        }
    }
}