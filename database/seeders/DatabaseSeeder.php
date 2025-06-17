<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core seeders (foundational data)
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class, // Products depend on Categories
            ContactSubmissionSeeder::class,
            PromoCodeSeeder::class,
        ]);

        // Seeders that depend on core data (Users, Products, Categories)
        $this->call([
            AddressSeeder::class, // Addresses depend on Users
            CartItemSeeder::class, // Cart items depend on Users and Products
            ReviewSeeder::class, // Reviews depend on Users and Products
            WishlistSeeder::class, // Wishlists depend on Users and Products
            OrderSeeder::class, // Orders depend on Users, Products, PromoCodes and also create their own OrderItems/Payments
        ]);

        // If OrderSeeder fully handles OrderItems and Payments, you might not need to call
        // OrderItemSeeder and PaymentSeeder directly here, unless they add *additional* specific data.
        // If you keep them, ensure they handle existing data gracefully.
        // $this->call([
        //     OrderItemSeeder::class,
        //     PaymentSeeder::class,
        // ]);
    }
}