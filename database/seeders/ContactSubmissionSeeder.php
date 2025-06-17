<?php

namespace Database\Seeders;

use App\Models\ContactSubmission;
use Illuminate\Database\Seeder;

class ContactSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContactSubmission::factory()->count(20)->create();
        ContactSubmission::factory()->count(5)->read()->create();
    }
}