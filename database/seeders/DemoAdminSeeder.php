<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Local-only admin for reviewing the golden set and other admin pages.
 *
 * Login: reviewer.admin@trakerja.test / password
 *
 * php artisan db:seed --class=DemoAdminSeeder
 */
class DemoAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->error('DemoAdminSeeder is local-only.');

            return;
        }

        User::updateOrCreate(
            ['email' => 'reviewer.admin@trakerja.test'],
            [
                'name' => 'Golden Set Reviewer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'admin',
                'is_admin' => true,
            ],
        );

        $this->command?->info('Local admin ready: reviewer.admin@trakerja.test');
    }
}
