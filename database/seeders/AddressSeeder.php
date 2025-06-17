<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there are users to attach addresses to
        if (User::count() === 0) {
            User::factory()->count(10)->create(); // Create some users if none exist
        }

        $users = User::all();

        // Hardcoded example addresses to be assigned to users
        $sampleAddresses = [
            [
                'address_line1' => '123 Main St',
                'address_line2' => null,
                'city' => 'Amsterdam',
                'state' => 'NH',
                'postal_code' => '1000 AB',
                'country' => 'NL',
                'type' => 'shipping',
                'is_default' => true,
            ],
            [
                'address_line1' => '45 Koninginneweg',
                'address_line2' => 'Apt 2B',
                'city' => 'Rotterdam',
                'state' => 'ZH',
                'postal_code' => '3000 CD',
                'country' => 'NL',
                'type' => 'billing',
                'is_default' => false,
            ],
            [
                'address_line1' => '789 Elm St',
                'address_line2' => null,
                'city' => 'Utrecht',
                'state' => 'UT',
                'postal_code' => '3500 EF',
                'country' => 'NL',
                'type' => 'shipping',
                'is_default' => false,
            ],
        ];

        $users->each(function (User $user, $index) use ($sampleAddresses) {
            // Assign a few addresses to each user, cycling through sample addresses
            for ($i = 0; $i < rand(1, count($sampleAddresses)); $i++) {
                $addressData = $sampleAddresses[$i % count($sampleAddresses)];
                Address::create(array_merge($addressData, ['user_id' => $user->id]));
            }
        });
    }
}