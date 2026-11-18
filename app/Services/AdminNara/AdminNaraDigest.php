<?php

namespace App\Services\AdminNara;

use App\Models\AiCall;
use App\Models\CrawlRun;
use App\Models\JobSource;
use App\Models\OperationIncident;
use App\Models\Payment;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\DB;

/** "What needs attention" for the admin: plain numbers with thresholds, the same data for the chat and the Telegram digest. */
class AdminNaraDigest
{
    /** @return array<string, mixed> */
    public function data(): array
    {
        $day = now()->subDay();
        $oldestTicket = SupportTicket::where('status', 'pending')->oldest('id')->first(['id', 'subject', 'created_at']);
        $crawl = CrawlRun::where('started_at', '>=', $day)->selectRaw('count(*) as runs, sum(new_listings) as new_jobs, sum(failed_items) as failed, sum(cost_usd) as cost')->first();
        $paid = Payment::where('status', 'SUCCESS')->where('paid_at', '>=', $day);

        $data = [
            'signups_24h' => User::where('role', '!=', 'admin')->where('created_at', '>=', $day)->count(),
            'payments_success_24h' => (clone $paid)->count(),
            'revenue_24h_idr' => (int) (clone $paid)->sum('amount'),
            'payments_waiting_over_1h' => Payment::whereIn('status', ['WAITING', 'PENDING'])->where('created_at', '<', now()->subHour())->where('created_at', '>=', now()->subDays(3))->count(),
            'tickets_pending' => SupportTicket::where('status', 'pending')->count(),
            'oldest_pending_ticket' => $oldestTicket ? ['id' => $oldestTicket->id, 'subject' => mb_substr((string) $oldestTicket->subject, 0, 80), 'age_hours' => (int) $oldestTicket->created_at->diffInHours(now())] : null,
            'incidents_open' => OperationIncident::where('status', '!=', 'resolved')->selectRaw('severity, count(*) as n')->groupBy('severity')->pluck('n', 'severity')->all(),
            'sources_failing' => JobSource::where('is_active', true)->where('health', 'failing')->count(),
            'new_jobs_24h' => Vacancy::where('first_seen_at', '>=', $day)->count(),
            'crawl_failed_items_24h' => (int) $crawl->failed,
            'ai_cost_24h_usd' => round((float) AiCall::where('created_at', '>=', $day)->sum('cost_usd'), 3),
            'failed_jobs_24h' => DB::table('failed_jobs')->where('failed_at', '>=', $day)->count(),
            'suspected_scams' => Vacancy::where('scam_flag', 'suspected')->count(),
        ];
        $data['needs_attention'] = $this->attention($data);

        return $data;
    }

    /** @return array<int, string> */
    private function attention(array $d): array
    {
        $items = [];
        foreach (['critical', 'high'] as $severity) {
            if (($d['incidents_open'][$severity] ?? 0) > 0) {
                $items[] = "{$d['incidents_open'][$severity]} {$severity} incident(s) still open";
            }
        }
        if ($d['payments_waiting_over_1h'] > 0) {
            $items[] = "{$d['payments_waiting_over_1h']} payment(s) waiting for over an hour (check the provider, maybe run a sync)";
        }
        if (($d['oldest_pending_ticket']['age_hours'] ?? 0) >= 48) {
            $items[] = "Ticket #{$d['oldest_pending_ticket']['id']} has waited {$d['oldest_pending_ticket']['age_hours']} hours without a reply";
        }
        if ($d['sources_failing'] > 0) {
            $items[] = "{$d['sources_failing']} job source(s) failing";
        }
        if ($d['new_jobs_24h'] === 0) {
            $items[] = 'No new jobs in 24 hours';
        }
        if ($d['failed_jobs_24h'] > 0) {
            $items[] = "{$d['failed_jobs_24h']} failed queue job(s) in 24 hours";
        }
        if ($d['ai_cost_24h_usd'] > 5) {
            $items[] = 'AI cost over $5 in 24 hours ($'.$d['ai_cost_24h_usd'].')';
        }

        return $items;
    }

    /** The Telegram message (HTML). */
    public function message(): string
    {
        $d = $this->data();
        $lines = ['<b>Nara Admin · Ringkasan</b>', '', $d['needs_attention'] ? '<b>Perlu perhatian</b>' : '✅ Tidak ada yang mendesak.'];
        foreach ($d['needs_attention'] as $item) {
            $lines[] = '• '.e($item);
        }
        $lines[] = '';
        $lines[] = "Daftar baru 24 jam: <b>{$d['signups_24h']}</b> · Pembayaran sukses: <b>{$d['payments_success_24h']}</b> (Rp ".number_format($d['revenue_24h_idr'], 0, ',', '.').')';
        $lines[] = "Loker baru: <b>{$d['new_jobs_24h']}</b> · Tiket pending: <b>{$d['tickets_pending']}</b> · Biaya AI: <b>\${$d['ai_cost_24h_usd']}</b>";

        return implode("\n", $lines);
    }
}
