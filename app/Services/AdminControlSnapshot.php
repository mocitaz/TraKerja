<?php
namespace App\Services;

use App\Models\{User, Payment, SupportTicket, JobApplication, ScraperLogsAndMetric};

final class AdminControlSnapshot
{
    public function build(int $days = 7): array
    {
        $now = now('Asia/Jakarta');
        $start = $now->copy()->subDays($days - 1)->startOfDay();
        $previous = $start->copy()->subDays($days);
        $members = fn () => User::where('role', '!=', 'admin');
        $premium = $members()->where('is_premium', true)->where('payment_status', 'paid')
            ->where(fn ($q) => $q->whereNull('premium_until')->orWhere('premium_until', '>', $now))->count();
        $total = $members()->count();
        $newMembers = $members()->whereBetween('created_at', [$start,$now])->count();
        $previousMembers = $members()->where('created_at', '>=', $previous)->where('created_at', '<', $start)->count();
        $revenue = (int) Payment::where('status', 'SUCCESS')->whereBetween('paid_at', [$start,$now])->sum('amount');
        $previousRevenue = (int) Payment::where('status', 'SUCCESS')->where('paid_at', '>=', $previous)->where('paid_at', '<', $start)->sum('amount');
        $pending = SupportTicket::where('status','pending')->count();
        $aged = SupportTicket::where('status','pending')->where('created_at','<',$now->copy()->subHours(48))->count();
        $scrapes = ScraperLogsAndMetric::whereBetween('session_started_at', [$start,$now]);
        $failures = (int) (clone $scrapes)->sum('failed_scraped_count');
        $successes = (int) (clone $scrapes)->sum('successfully_scraped_count');
        $series = [];
        for ($i=0;$i<$days;$i++) {
            $day=$start->copy()->addDays($i);
            $series[]=['label'=>$day->format('d M'),'count'=>$members()->whereBetween('created_at',[$day,$day->copy()->endOfDay()])->count()];
        }
        $metrics = [
            'members_total'=>$total,'premium_active'=>$premium,
            'premium_share'=>$total ? round($premium/$total*100,1) : 0,
            'new_members'=>$newMembers,'previous_new_members'=>$previousMembers,
            'active_members'=>$members()->whereBetween('last_activity_at',[$start,$now])->count(),
            'revenue'=>$revenue,'previous_revenue'=>$previousRevenue,
            'pending_payments'=>Payment::whereIn('status',['PENDING','WAITING'])->count(),
            'pending_tickets'=>$pending,'aged_tickets'=>$aged,
            'applications'=>JobApplication::whereBetween('created_at',[$start,$now])->count(),
            'scrape_successes'=>$successes,'scrape_failures'=>$failures,
        ];
        $priorities=[];
        if ($aged) $priorities[]=['area'=>'Support','title'=>'Clear the oldest conversations','evidence'=>"{$aged} pending tickets are older than 48 hours.",'next'=>'Review the backlog and assign the oldest requests first.','route'=>'admin.feedbacks.index','level'=>'attention'];
        elseif ($pending) $priorities[]=['area'=>'Support','title'=>'Review incoming requests','evidence'=>"{$pending} tickets are waiting for a response.",'next'=>'Review pending tickets and record a response or status.','route'=>'admin.feedbacks.index','level'=>'review'];
        if ($failures) $priorities[]=['area'=>'Scraper','title'=>'Inspect failed extraction runs','evidence'=>"{$failures} failed extractions were recorded in this period.",'next'=>'Check source errors before retrying affected jobs. Failure counts do not establish a root cause.','route'=>'admin.scraper','level'=>'attention'];
        if ($metrics['pending_payments']) $priorities[]=['area'=>'Payments','title'=>'Check pending payment records','evidence'=>"{$metrics['pending_payments']} payment records are pending or waiting.",'next'=>'Verify provider status before deciding whether any record needs follow-up.','route'=>'admin.payments','level'=>'review'];
        if ($previousMembers > $newMembers) $priorities[]=['area'=>'Growth','title'=>'Investigate the registration slowdown','evidence'=>"{$newMembers} new members versus {$previousMembers} in the preceding {$days} days.",'next'=>'Compare acquisition channels; registration counts alone do not explain the change.','route'=>'admin.analytics','level'=>'review'];
        if (!$priorities) $priorities[]=['area'=>'Overview','title'=>'Establish the next baseline','evidence'=>'No aged support backlog, failed extractions, or pending payment records were found in this snapshot.','next'=>'Review engagement and registration trends before choosing the next experiment.','route'=>'admin.analytics','level'=>'review'];
        return ['generated_at'=>$now->toIso8601String(),'period_days'=>$days,'period_label'=>$start->format('d M').' – '.$now->format('d M Y'),
            'metrics'=>$metrics,'registrations'=>$series,'priorities'=>$priorities,
            'sources'=>[
                ['label'=>'Members','table'=>'users','definition'=>'Non-admin members; active means last recorded activity within the selected period.'],
                ['label'=>'Settled revenue','table'=>'payments','definition'=>'SUCCESS records by paid_at; soft-deleted records excluded. Gross recorded payments, not profit or net revenue.'],
                ['label'=>'Support backlog','table'=>'support_tickets','definition'=>'Current pending tickets; aged means created more than 48 hours ago.'],
                ['label'=>'Scraper results','table'=>'scraper_logs_and_metrics','definition'=>'Recorded successful/failed extraction counts by session start time; no records means no evidence, not proof of health.'],
                ['label'=>'Applications','table'=>'job_applications','definition'=>'Applications created during the selected period.'],
            ]];
    }
}
