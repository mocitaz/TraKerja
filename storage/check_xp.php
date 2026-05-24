<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \App\Models\User::withCount('jobApplications')->get();
foreach($users as $user) {
    echo "User ID {$user->id}: XP = {$user->xp}, Level = {$user->level}, Jobs = {$user->job_applications_count}\n";
}
