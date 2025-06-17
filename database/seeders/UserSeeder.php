<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_approved' => true,
        ]);

        User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'is_approved' => true,
        ]);

        User::factory()->count(20)->create();
        User::factory()->count(5)->unapproved()->create();
    }
}