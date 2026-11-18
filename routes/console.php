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

// The Laravel scraper is frozen (TraKerja 2.0 Blueprint, Fase 0) until it is rebuilt in
// Fase 2 with cross-platform dedup and a proper closed-job verifier. The old dead-link check is no longer
// scheduled because it marks open jobs as closed when any page contains words such as
// "closed" or "expired". Run `php artisan jobs:audit` to inspect the existing data.

// Job-data engine (TraKerja 2.0). Every command is a no-op until JOB_ENGINE_SCHEDULE_ENABLED=true.
if (config('job_sources.schedule_enabled')) {
    Schedule::command('jobs:tick')->everyFifteenMinutes()->withoutOverlapping()
        ->description('Queue discovery for job sources whose adaptive interval has passed');
    Schedule::command('jobs:verify')->hourly()->withoutOverlapping(120)->runInBackground()
        ->description('Check whether stored vacancies are still open');
    Schedule::command('jobs:enrich --queue --limit=500')->everyThirtyMinutes()->withoutOverlapping()
        ->description('Queue AI enrichment for new vacancies (within the daily AI budget)');
    Schedule::command('jobs:embed --limit=1000')->hourlyAt(40)->withoutOverlapping()
        ->description('Embed new vacancies for CV match scores');
    Schedule::command('jobs:alerts')->dailyAt('07:30')->timezone('Asia/Jakarta')->withoutOverlapping()
        ->description('Send saved-search alerts for new matching vacancies');
    Schedule::command('jobs:housekeeping')->dailyAt('03:15')->timezone('Asia/Jakarta')->withoutOverlapping()
        ->description('Archive long-closed vacancies, prune logs, weekly QA sample, retry failed engine jobs');
    Schedule::command('jobs:daily-report')->dailyAt('08:00')->timezone('Asia/Jakarta')
        ->description('Daily job-engine summary to the admin Telegram channel');
    Schedule::command('jobs:dedup --no-ai')->dailyAt('04:00')->timezone('Asia/Jakarta')->withoutOverlapping()
        ->description('Merge duplicates the ingest-time matcher missed (no AI)');
}

// Job-engine watchdog: stuck queues, failed jobs, stale scrapers, verification and AI budget, alerted on
// Telegram once per problem (and again when it is resolved). Runs even with the engine schedule off, because
// the queue checks also cover the other workers.
Schedule::command('jobs:watchdog')->everyTenMinutes()->withoutOverlapping()
    ->description('Alert on stuck queues, failed jobs, stale scrapers and AI budget (Telegram)');

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

// Nara Admin digest: what needs attention, to the Telegram admin channel (only when something does).
Schedule::command('admin:nara-digest')
    ->dailyAt('08:30')
    ->timezone('Asia/Jakarta')
    ->description('Telegram digest of what needs the admin\'s attention');
