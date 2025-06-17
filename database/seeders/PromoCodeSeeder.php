<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon; // For date manipulation

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing promo codes
        // PromoCode::truncate(); // Uncomment if needed

        // Active fixed amount promo code
        PromoCode::create([
            'code' => 'SAVE50',
            'type' => 'fixed',
            'value' => 50.00,
            'min_order_amount' => 300.00,
            'expires_at' => Carbon::now()->addMonths(6),
            'usage_limit' => 100,
            'used_count' => 5,
            'is_active' => true,
        ]);

        // Active percentage promo code
        PromoCode::create([
            'code' => 'WEDDING10',
            'type' => 'percentage',
            'value' => 10.00,
            'min_order_amount' => 500.00,
            'expires_at' => Carbon::now()->addYear(),
            'usage_limit' => null, // No limit
            'used_count' => 0,
            'is_active' => true,
        ]);

        // Expired promo code
        PromoCode::create([
            'code' => 'OLDCODE',
            'type' => 'fixed',
            'value' => 20.00,
            'min_order_amount' => null,
            'expires_at' => Carbon::now()->subMonth(), // Expired last month
            'usage_limit' => 50,
            'used_count' => 45,
            'is_active' => false, // Set to inactive as it's expired
        ]);

        // Used up promo code
        PromoCode::create([
            'code' => 'FULLUSED',
            'type' => 'percentage',
            'value' => 5.00,
            'min_order_amount' => 100.00,
            'expires_at' => Carbon::now()->addMonths(3),
            'usage_limit' => 10,
            'used_count' => 10, // Used up
            'is_active' => false, // Set to inactive as it's used up
        ]);
    }
}