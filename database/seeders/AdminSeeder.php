<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Remove old admin account if exists
        User::where('email', 'admin@trakerja.com')->delete();
        
        // Create new admin user
        User::firstOrCreate(
            ['email' => 'Infoteknalogi@gmail.com'],
            [
                'name' => 'Infoteknalogi Admin',
                'email' => 'Infoteknalogi@gmail.com',
                'password' => Hash::make('Katakunci32.'),
                'role' => 'admin',
                'is_admin' => true,
                'is_premium' => true,
                'payment_status' => 'paid',
                'premium_purchased_at' => now(),
                'registered_phase' => 1,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: Infoteknalogi@gmail.com');
        $this->command->info('Password: Katakunci32.');
    }
}