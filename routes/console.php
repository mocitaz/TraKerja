<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('ai-photos:recover')->everyMinute()->withoutOverlapping();
Schedule::command('ai-studio:recover')->everyMinute()->withoutOverlapping();
Schedule::command('ai-photos:recover --cleanup')->hourly()->withoutOverlapping();
Schedule::command('data-exports:cleanup')->everyTenMinutes()->withoutOverlapping();

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

// Schedule dead-link check for job postings (Anti-Ghost Jobs)
Schedule::command('jobs:validate-dead-links --limit=100')
    ->dailyAt('03:00')
    ->timezone('Asia/Jakarta')
    ->description('Validate active job postings URLs for dead-links and footprint markers');

// Run active job scraper sources dynamically based on their configured frequency
Schedule::command('jobs:run-scraper')
    ->hourly()
    ->timezone('Asia/Jakarta')
    ->description('Run active job scraper sources dynamically based on their configured frequency');

// Telegram Admin Channel: Daily Morning Briefing (10:00 WIB)
Schedule::command('report:telegram-daily morning')
    ->dailyAt('10:00')
    ->timezone('Asia/Jakarta')
    ->description('Send morning daily recap report to Telegram admin channel');

// Telegram Admin Channel: Daily Evening Wrap-up (21:00 WIB)
Schedule::command('report:telegram-daily evening')
    ->dailyAt('21:00')
    ->timezone('Asia/Jakarta')
    ->description('Send evening daily recap report to Telegram admin channel');

// Telegram Admin Channel: Server Health & Disk Usage Check
Schedule::command('report:server-health')
    ->dailyAt('08:00')
    ->timezone('Asia/Jakarta')
    ->description('Monitor server disk health and alert to Telegram channel');
