<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users to prevent duplicates if running independently
        // User::truncate(); // Uncomment if you want to clear only users and re-seed

        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone_number' => '1234567890',
            'role' => 'admin',
            'is_approved' => true,
            'remember_token' => Str::random(10),
        ]);

        // Regular Test Customer
        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone_number' => '0987654321',
            'role' => 'customer',
            'is_approved' => true,
            'remember_token' => Str::random(10),
        ]);

        // Additional Sample Customers
        User::create([
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone_number' => '1112223333',
            'role' => 'customer',
            'is_approved' => true,
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone_number' => '4445556666',
            'role' => 'customer',
            'is_approved' => true,
            'remember_token' => Str::random(10),
        ]);

        // An unapproved user for testing the approval system
        User::create([
            'name' => 'Pending User',
            'email' => 'pending@example.com',
            'email_verified_at' => null, // Not verified
            'password' => Hash::make('password'),
            'phone_number' => '7778889999',
            'role' => 'customer',
            'is_approved' => false, // Not approved
            'remember_token' => Str::random(10),
        ]);
    }
}