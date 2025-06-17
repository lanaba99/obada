<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Review::factory()->count(100)->create(); // Create 100 reviews
        Review::factory()->count(20)->good()->create(); // Create some highly rated ones
        Review::factory()->count(10)->bad()->create();  // Create some lowly rated ones
    }
}