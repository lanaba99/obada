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
        $categories = [
            'Wedding Dresses',
            'Bridesmaid Dresses',
            'Wedding Veils',
            'Bridal Shoes',
            'Bridal Accessories',
            'Groom Attire',
            'Flower Girl Dresses',
            'Mother of the Bride Dresses',
            'Wedding Jewelry',
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(
                ['name' => $categoryName],
                // Provide a fixed description or null, instead of using faker
                ['slug' => Str::slug($categoryName), 'description' => 'Browse our exquisite collection of ' . $categoryName . '.']
            );
        }

        // If you still want more random categories without Faker, you'd need to manually define more.
        // For example:
        // Category::firstOrCreate(['name' => 'Custom Category 1'], ['slug' => 'custom-category-1', 'description' => 'A custom unique category.']);
    }
}