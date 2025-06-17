<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing categories
        // Category::truncate(); // Uncomment if you want to clear only categories

        $categories = [
            ['name' => 'Wedding Dresses', 'description' => 'Our exquisite collection of wedding gowns.'],
            ['name' => 'Bridesmaid Dresses', 'description' => 'Stylish dresses for your bridal party.'],
            ['name' => 'Wedding Veils', 'description' => 'Elegant veils to complete your bridal look.'],
            ['name' => 'Bridal Shoes', 'description' => 'Comfortable and fashionable shoes for your big day.'],
            ['name' => 'Bridal Accessories', 'description' => 'Jewelry, headpieces, and more.'],
            ['name' => 'Groom Attire', 'description' => 'Suits and tuxedos for the groom and groomsmen.'],
            ['name' => 'Flower Girl Dresses', 'description' => 'Adorable dresses for the youngest members of the bridal party.'],
            ['name' => 'Mother of the Bride Dresses', 'description' => 'Sophisticated outfits for mothers.'],
            ['name' => 'Wedding Jewelry', 'description' => 'Rings, necklaces, earrings for brides and guests.'],
            ['name' => 'Honeymoon Outfits', 'description' => 'Stylish and comfortable wear for your honeymoon.'],
            ['name' => 'Reception Dresses', 'description' => 'Change into something comfortable and chic for your reception.'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['name' => $categoryData['name']],
                ['slug' => Str::slug($categoryData['name']), 'description' => $categoryData['description']]
            );
        }
    }
}