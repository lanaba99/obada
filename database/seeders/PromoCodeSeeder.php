<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PromoCode::factory()->count(10)->create(); // Create 10 active promo codes
        PromoCode::factory()->count(2)->expired()->create(); // Create a few expired ones
        PromoCode::factory()->count(2)->usageLimitReached()->create(); // Create a few used-up ones
    }
}