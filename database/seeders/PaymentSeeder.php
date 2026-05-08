<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure some regular users exist to associate payments with
        $users = User::where('role', '!=', 'admin')->get();

        if ($users->isEmpty()) {
            // Create a few realistic users if none exist
            $users = collect([
                User::create([
                    'name' => 'Budi Premium TraKerja',
                    'email' => 'budi.premium@trakerja.com',
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now(),
                ]),
                User::create([
                    'name' => 'Siti Aminah',
                    'email' => 'siti.aminah@gmail.com',
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now(),
                ]),
                User::create([
                    'name' => 'Rian Hidayat',
                    'email' => 'rian.hidayat@yahoo.com',
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now(),
                ]),
                User::create([
                    'name' => 'Dewi Lestari',
                    'email' => 'dewi.lestari@outlook.com',
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now(),
                ])
            ]);
        }

        $paymentChannels = [
            [
                'code' => 'QRIS',
                'name' => 'QRIS GoPay / OVO / ShopeePay',
                'category' => 'E_WALLET'
            ],
            [
                'code' => 'BCA_VA',
                'name' => 'BCA Virtual Account',
                'category' => 'VIRTUAL_ACCOUNT'
            ],
            [
                'code' => 'MANDIRI_VA',
                'name' => 'Mandiri Virtual Account',
                'category' => 'VIRTUAL_ACCOUNT'
            ],
            [
                'code' => 'BNI_VA',
                'name' => 'BNI Virtual Account',
                'category' => 'VIRTUAL_ACCOUNT'
            ],
            [
                'code' => 'SHOPEEPAY',
                'name' => 'ShopeePay',
                'category' => 'E_WALLET'
            ],
            [
                'code' => 'OVO',
                'name' => 'OVO',
                'category' => 'E_WALLET'
            ],
        ];

        $packages = [
            [
                'type' => 'premium',
                'amount' => 149000,
                'name' => 'Premium Subscription Plan'
            ],
            [
                'type' => 'addon_10',
                'amount' => 14999,
                'name' => 'AI Analyzer Add-On Pack'
            ],
            [
                'type' => 'cl_addon_15',
                'amount' => 14999,
                'name' => 'Cover Letter Add-On Pack'
            ]
        ];

        $statuses = ['SUCCESS', 'SUCCESS', 'SUCCESS', 'PENDING', 'WAITING', 'FAILED', 'CANCELED', 'EXPIRED'];

        // Generate 25 realistic payments spread over the last 30 days
        for ($i = 0; $i < 25; $i++) {
            $user = $users->random();
            $channel = $paymentChannels[array_rand($paymentChannels)];
            $package = $packages[array_rand($packages)];
            $status = $statuses[array_rand($statuses)];

            // Spread out the creation date
            $createdAt = Carbon::now('Asia/Jakarta')->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            $paidAt = null;
            if ($status === 'SUCCESS') {
                $paidAt = (clone $createdAt)->addMinutes(rand(2, 45));
            }

            $orderId = 'ORD-' . $createdAt->format('Ymd') . '-' . strtoupper(Str::random(6));
            $yukkTxCode = 'YTX-' . $createdAt->format('Ymd') . '-' . strtoupper(Str::random(8));

            $vaNumber = null;
            if ($channel['category'] === 'VIRTUAL_ACCOUNT') {
                $vaNumber = '88012' . rand(1000000000, 9999999999);
            }

            Payment::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'yukk_transaction_code' => $yukkTxCode,
                'yukk_token' => Str::random(40),
                'amount' => $package['amount'],
                'payment_channel_code' => $channel['code'],
                'payment_channel_name' => $channel['name'],
                'payment_category' => $channel['category'],
                'va_number' => $vaNumber,
                'va_expired_at' => $channel['category'] === 'VIRTUAL_ACCOUNT' ? (clone $createdAt)->addDay() : null,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '0812' . rand(10000000, 99999999),
                'status' => $status,
                'request_at' => $createdAt,
                'paid_at' => $paidAt,
                'expired_at' => (clone $createdAt)->addDay(),
                'notes' => $package['type'],
                'metadata' => ['package_type' => $package['type']],
                'created_at' => $createdAt,
                'updated_at' => $paidAt ?? $createdAt,
            ]);
        }
    }
}
