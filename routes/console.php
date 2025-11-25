<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule interview reminders to be sent daily at 9 AM
Schedule::command('interviews:send-reminders')
    ->dailyAt('09:00')
    ->timezone('Asia/Jakarta')
    ->description('Send email reminders for interviews happening in 24 hours');

// Schedule email verification reminders for users who haven't verified (min 3 days old)
// Will send max 3 reminders with 3 days interval between each reminder
Schedule::command('email:send-verification-reminders --min-days=3 --max-reminders=3 --reminder-interval=3')
    ->dailyAt('10:00')
    ->timezone('Asia/Jakarta')
    ->description('Send verification reminders to users who registered at least 3 days ago but haven\'t verified');

// Schedule archive old declined and not processed jobs
// Runs daily to ensure any old data gets archived automatically
Schedule::command('jobs:archive-old-declined')
    ->dailyAt('02:00')
    ->timezone('Asia/Jakarta')
    ->description('Archive old job applications with Declined status or Not Processed stage');
