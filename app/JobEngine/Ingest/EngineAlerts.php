<?php

namespace App\JobEngine\Ingest;

use App\Services\TelegramService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Telegram alerts for the job-data engine (scrapers, queue workers, verifier, AI budget), in the same HTML style
 * as the system error alerts:
 *
 *   🛰 <b>JOB ENGINE · WARNING</b>
 *   <b>Dealls: no successful run in 24 hours</b>
 *   • Detail: …
 *   • Time: 18 Nov 2026, 08:00 WIB
 *   [Open Engine Monitor]
 *
 * An open problem (identified by a key) is sent once, repeated every few hours while it lasts, and followed by
 * a "resolved" message when it clears, so a stuck worker at 02:00 does not produce thirty identical messages.
 */
class EngineAlerts
{
    public const CRITICAL = 'critical';

    public const WARNING = 'warning';

    public const INFO = 'info';

    public const RESOLVED = 'resolved';

    private const BADGES = [
        self::CRITICAL => ['🔴', 'CRITICAL'],
        self::WARNING => ['🟠', 'WARNING'],
        self::INFO => ['🔵', 'INFO'],
        self::RESOLVED => ['🟢', 'RESOLVED'],
    ];

    /** Hours before an alert that is still open is sent again. */
    private const REPEAT_HOURS = 6;

    public static function enabled(): bool
    {
        return (bool) config('job_sources.telegram_alerts');
    }

    /**
     * Raise (or keep) an open problem. Sent the first time and then at most every REPEAT_HOURS.
     *
     * @param  array<string, scalar|null>  $details  label => value, shown as bullets
     */
    public function raise(string $key, string $level, string $title, array $details = []): bool
    {
        $open = Cache::get($this->cacheKey($key));
        if ($open && Carbon::parse($open['sent_at'])->gt(now()->subHours(self::REPEAT_HOURS))) {
            return false;
        }
        Cache::forever($this->cacheKey($key), ['title' => $title, 'sent_at' => now()->toIso8601String(), 'since' => $open['since'] ?? now()->toIso8601String()]);
        $this->rememberOpen($key);

        if ($open) {
            $details = ['Ongoing since' => $this->wib($open['since'])] + $details;
        }

        return $this->send($level, $title, $details);
    }

    /** Close a problem that was raised before; sends a "resolved" message only if it had been sent. */
    public function resolve(string $key, ?string $title = null, array $details = []): bool
    {
        $open = Cache::pull($this->cacheKey($key));
        $this->forgetOpen($key);
        if (! $open) {
            return false;
        }
        $minutes = (int) Carbon::parse($open['since'])->diffInMinutes(now());

        return $this->send(self::RESOLVED, $title ?? $open['title'], $details + ['Duration' => $this->duration($minutes)]);
    }

    /** One-off message (no open/resolved state), e.g. a failed job. Optional throttle per key in minutes. */
    public function notify(string $level, string $title, array $details = [], ?string $throttleKey = null, int $throttleMinutes = 60): bool
    {
        if ($throttleKey && ! Cache::add('engine_alert_throttle:'.$throttleKey, true, now()->addMinutes($throttleMinutes))) {
            return false;
        }

        return $this->send($level, $title, $details);
    }

    /** Keys of the problems that are currently open (used by the watchdog to resolve them). @return array<int, string> */
    public function openKeys(): array
    {
        return array_values(Cache::get('engine_alert_open', []));
    }

    /** The HTML message, separate from sending so it can be printed and tested. */
    public function format(string $level, string $title, array $details = []): string
    {
        [$dot, $badge] = self::BADGES[$level] ?? self::BADGES[self::INFO];
        $lines = ["{$dot} <b>JOB ENGINE · {$badge}</b>", '', '<b>'.e($title).'</b>'];
        foreach ($details as $label => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $lines[] = '• <b>'.e($label).':</b> '.e((string) $value);
        }
        $lines[] = '• <b>Time:</b> '.now('Asia/Jakarta')->format('d M Y, H:i').' WIB';

        return implode("\n", $lines);
    }

    private function send(string $level, string $title, array $details): bool
    {
        $text = $this->format($level, $title, $details);
        Log::log($level === self::CRITICAL ? 'error' : ($level === self::WARNING ? 'warning' : 'info'), 'Job engine alert: '.$title, $details);
        if (! self::enabled()) {
            return false;
        }

        try {
            return app(TelegramService::class)->sendMessage($text, [[['text' => 'Open Engine Monitor', 'url' => route('admin.job-engine')]]]);
        } catch (\Throwable $e) {
            Log::warning('Job engine alert not sent: '.$e->getMessage());

            return false;
        }
    }

    private function cacheKey(string $key): string
    {
        return 'engine_alert:'.$key;
    }

    private function rememberOpen(string $key): void
    {
        $open = Cache::get('engine_alert_open', []);
        $open[$key] = $key;
        Cache::forever('engine_alert_open', $open);
    }

    private function forgetOpen(string $key): void
    {
        $open = Cache::get('engine_alert_open', []);
        unset($open[$key]);
        Cache::forever('engine_alert_open', $open);
    }

    private function wib(string $iso): string
    {
        return Carbon::parse($iso)->timezone('Asia/Jakarta')->format('d M, H:i').' WIB';
    }

    private function duration(int $minutes): string
    {
        return $minutes < 60 ? "{$minutes} min" : intdiv($minutes, 60).' h '.($minutes % 60).' min';
    }
}
