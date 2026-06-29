<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password123')]
        );

        // Seed admin user
        $this->call(AdminSeeder::class);

        // Seed dummy users with job applications
        $this->call(DummyPremiumUserSeeder::class);

        // Seed payment records
        $this->call(PaymentSeeder::class);

        // Seed customer support tickets & feedbacks
        $this->call(SupportTicketSeeder::class);

        // Seed default scraper sources
        $this->call(ScraperSourceSeeder::class);
    }
}
