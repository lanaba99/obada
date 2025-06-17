<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core seeders (no dependencies or foundational)
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ContactSubmissionSeeder::class,
            PromoCodeSeeder::class, // Independent
        ]);

        // Seeders that depend on core data (Users, Products, Categories)
        $this->call([
            AddressSeeder::class,
            CartItemSeeder::class,
            ReviewSeeder::class,
            WishlistSeeder::class,
            OrderSeeder::class, // Orders create their own OrderItems now
        ]);

        // Seeders that depend on Orders being created
        $this->call([
            PaymentSeeder::class,
            // OrderItemSeeder is typically handled by OrderFactory now,
            // but if you want to create *additional* items for existing orders, call it here.
            // OrderItemSeeder::class,
        ]);
    }
}