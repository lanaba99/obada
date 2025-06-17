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
        // Clear existing submissions
        // ContactSubmission::truncate(); // Uncomment if needed

        ContactSubmission::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'subject' => 'Inquiry about a dress',
            'message' => 'I would like more information about the Elegant Lace Wedding Dress. Is it available in other sizes?',
            'is_read' => false,
        ]);

        ContactSubmission::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'subject' => 'Order Status',
            'message' => 'Can you please check the status of my order #12345?',
            'is_read' => true, // Mark as read
        ]);

        ContactSubmission::create([
            'name' => 'Alice Wonderland',
            'email' => 'alice@email.com',
            'subject' => null, // Optional subject
            'message' => 'Just wanted to say how much I love your collection!',
            'is_read' => false,
        ]);
    }
}