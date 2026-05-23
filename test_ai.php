<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

echo "Mengirim request ke AI Server...\n";

try {
    $response = Http::timeout(60)->attach(
        'photo',
        file_get_contents(public_path('images/permata.png')),
        'permata.png'
    )->post('https://aiphoto.apitrakerja.online/enhance-photo-ai', [
        'type' => 'enhance',
        'style' => 'linkedin_pria',
        'background' => 'modern_office',
        'mode' => 'headshot'
    ]);

    echo "HTTP Status: " . $response->status() . "\n";
    echo "Response Body:\n" . $response->body() . "\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
