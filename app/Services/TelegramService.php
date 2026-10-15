<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\Payment;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Throwable;

class TelegramService
{
    protected ?string $token;
    protected ?string $channelId;
    protected bool $enabled;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token');
        $this->channelId = config('services.telegram.channel_id');
        $this->enabled = (bool) config('services.telegram.enabled', false);
    }

    /**
     * Send raw HTML message to the configured channel or a specific chat ID.
     */
    public function sendMessage(string $text, ?array $inlineKeyboard = null, ?string $targetChatId = null): bool
    {
        $chatId = $targetChatId ?: $this->channelId;

        if (! $this->enabled || empty($this->token) || empty($chatId) || app()->environment('testing')) {
            return false;
        }

        try {
            $payload = [
                'chat_id'                  => $chatId,
                'text'                     => $text,
                'parse_mode'               => 'HTML',
                'disable_web_page_preview' => true,
            ];

            if ($inlineKeyboard) {
                $cleanKeyboard = [];
                foreach ($inlineKeyboard as $row) {
                    $cleanRow = [];
                    foreach ($row as $btn) {
                        if (isset($btn['url'])) {
                            if (str_starts_with($btn['url'], 'http://localhost') || str_starts_with($btn['url'], 'http://127.0.0.1')) {
                                $btn['url'] = str_replace(
                                    ['http://localhost:8000', 'http://localhost', 'http://127.0.0.1:8000', 'http://127.0.0.1'],
                                    'https://trakerja.com',
                                    $btn['url']
                                );
                            }
                        }
                        $cleanRow[] = $btn;
                    }
                    $cleanKeyboard[] = $cleanRow;
                }
                $payload['reply_markup'] = json_encode(['inline_keyboard' => $cleanKeyboard]);
            }

            $response = Http::timeout(5)
                ->asForm()
                ->post("https://api.telegram.org/bot{$this->token}/sendMessage", $payload);

            if (! $response->successful()) {
                Log::warning('Telegram API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (Throwable $e) {
            Log::error('Telegram dispatch failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Dispatch notification asynchronously (after HTTP response or immediately in CLI).
     */
    public function sendAsync(callable $callback): void
    {
        if (app()->runningInConsole() || app()->environment('testing')) {
            $callback();
            return;
        }

        dispatch(function () use ($callback) {
            try {
                $callback();
            } catch (Throwable $e) {
                Log::error('Telegram async dispatch failed: ' . $e->getMessage());
            }
        })->afterResponse();
    }

    /**
     * Mask sensitive strings (passwords, tokens, API keys) from text.
     */
    public static function sanitize(string $text): string
    {
        $patterns = [
            '/([\'"]?(?:password|passwd|pwd|secret|api_key|token|bearer|authorization)[\'"]?\s*[:=]\s*[\'"]?)([^\s\'",;&]+)/i' => '$1***REDACTED***',
            '/(Bearer\s+)[A-Za-z0-9_\-\.]{8,}/i' => '$1***REDACTED***',
            '/(mysql|pgsql|sqlite):\/\/[^@]+@/i' => '$1://***REDACTED***@',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $text) ?? $text;
    }

    /**
     * Alert: New user registration (Asynchronous).
     */
    public function sendNewUser(User $user, string $provider = 'Email', bool $async = true): bool
    {
        $action = function () use ($user, $provider) {
            $totalUsers = User::count();
            $name = htmlspecialchars($user->name ?? 'User', ENT_QUOTES, 'UTF-8');
            $email = htmlspecialchars($user->email ?? '-', ENT_QUOTES, 'UTF-8');
            $date = Carbon::now('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB';

            $text = "👤 <b>NEW USER REGISTERED</b>\n\n"
                  . "• <b>Name:</b> {$name}\n"
                  . "• <b>Email:</b> {$email}\n"
                  . "• <b>Method:</b> {$provider}\n"
                  . "• <b>Total Users:</b> " . number_format($totalUsers, 0, ',', '.') . "\n"
                  . "• <b>Time:</b> {$date}";

            $adminUrl = url('/admin/users');
            $keyboard = [
                [['text' => 'View User Management', 'url' => $adminUrl]]
            ];

            return $this->sendMessage($text, $keyboard);
        };

        if ($async) {
            $this->sendAsync($action);
            return true;
        }

        return $action();
    }

    /**
     * Alert: New customer support ticket (Asynchronous).
     */
    public function sendSupportTicket(SupportTicket $ticket, bool $async = true): bool
    {
        $action = function () use ($ticket) {
            $user = $ticket->user;
            $name = htmlspecialchars($user->name ?? $ticket->guest_name ?? 'Guest', ENT_QUOTES, 'UTF-8');
            $email = htmlspecialchars($user->email ?? $ticket->guest_email ?? '-', ENT_QUOTES, 'UTF-8');
            $subject = htmlspecialchars($ticket->subject ?? 'Support Ticket', ENT_QUOTES, 'UTF-8');
            $category = htmlspecialchars($ticket->category_label ?? ucfirst(str_replace('_', ' ', $ticket->category ?? 'General')), ENT_QUOTES, 'UTF-8');

            $previewMessage = mb_substr((string) $ticket->message, 0, 200);
            if (mb_strlen((string) $ticket->message) > 200) {
                $previewMessage .= '...';
            }
            $previewMessage = htmlspecialchars($previewMessage, ENT_QUOTES, 'UTF-8');
            $date = Carbon::now('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB';

            $text = "💬 <b>SUPPORT TICKET #{$ticket->id}</b>\n\n"
                  . "• <b>From:</b> {$name} (<code>{$email}</code>)\n"
                  . "• <b>Category:</b> {$category}\n"
                  . "• <b>Subject:</b> {$subject}\n"
                  . "• <b>Message:</b>\n<i>\"{$previewMessage}\"</i>\n\n"
                  . "• <b>Time:</b> {$date}";

            $adminUrl = url('/admin/feedbacks');
            $keyboard = [
                [['text' => 'Open Support Panel', 'url' => $adminUrl]]
            ];

            return $this->sendMessage($text, $keyboard);
        };

        if ($async) {
            $this->sendAsync($action);
            return true;
        }

        return $action();
    }

    /**
     * Alert: Successful payment transaction (Pakasir Money Alert, Asynchronous).
     */
    public function sendPaymentSuccess(Payment $payment, User $user, bool $isAddon = false, bool $async = true): bool
    {
        $action = function () use ($payment, $user, $isAddon) {
            $name = htmlspecialchars($user->name ?? 'User', ENT_QUOTES, 'UTF-8');
            $email = htmlspecialchars($user->email ?? '-', ENT_QUOTES, 'UTF-8');
            $orderId = htmlspecialchars($payment->order_id ?? '-', ENT_QUOTES, 'UTF-8');
            $amount = 'Rp ' . number_format((int) $payment->amount, 0, ',', '.');
            $channel = htmlspecialchars($payment->payment_channel_name ?? $payment->payment_channel_code ?? 'Pakasir', ENT_QUOTES, 'UTF-8');
            $package = $isAddon ? 'Add-On Credit Package' : 'Premium Pro Upgrade';
            $date = Carbon::now('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB';

            $text = "💳 <b>PAYMENT CONFIRMED</b>\n\n"
                  . "• <b>Customer:</b> {$name} (<code>{$email}</code>)\n"
                  . "• <b>Package:</b> {$package}\n"
                  . "• <b>Amount:</b> <b>{$amount}</b>\n"
                  . "• <b>Channel:</b> {$channel}\n"
                  . "• <b>Order ID:</b> <code>{$orderId}</code>\n"
                  . "• <b>Time:</b> {$date}";

            $keyboard = [
                [['text' => 'View Transactions', 'url' => url('/admin/payments')]]
            ];

            return $this->sendMessage($text, $keyboard);
        };

        if ($async) {
            $this->sendAsync($action);
            return true;
        }

        return $action();
    }

    /**
     * Alert: Security & Fraud Detection (Brute force, spoofing, anomalies).
     */
    public function sendSecurityAlert(string $title, array $details, bool $async = true): bool
    {
        $action = function () use ($title, $details) {
            $cleanTitle = htmlspecialchars(strtoupper($title), ENT_QUOTES, 'UTF-8');
            $date = Carbon::now('Asia/Jakarta')->translatedFormat('d M Y, H:i:s') . ' WIB';

            $lines = [];
            foreach ($details as $key => $value) {
                $k = htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
                $v = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
                $lines[] = "• <b>{$k}:</b> {$v}";
            }
            $detailsText = implode("\n", $lines);

            $text = "🛡️ <b>SECURITY ALERT • {$cleanTitle}</b>\n\n"
                  . "{$detailsText}\n"
                  . "• <b>Time:</b> {$date}";

            return $this->sendMessage($text);
        };

        if ($async) {
            $this->sendAsync($action);
            return true;
        }

        return $action();
    }

    /**
     * Daily recap summary report with enriched business insights (Morning 10:00 & Evening 21:00).
     */
    public function sendDailyRecap(string $period = 'morning'): bool
    {
        $now = Carbon::now('Asia/Jakarta');
        $isMorning = strtolower($period) === 'morning';

        // Time ranges
        $since = $isMorning
            ? $now->copy()->subDay()->setTime(21, 0, 0) // From 21:00 yesterday to 10:00 today
            : $now->copy()->startOfDay();                 // From 00:00 today to 21:00 today

        $totalUsers = User::count();
        $newUsers = User::where('created_at', '>=', $since)->count();
        $activeUsers = User::where('last_activity_at', '>=', $since)->count();
        $newApplications = JobApplication::where('created_at', '>=', $since)->count();
        $pendingTickets = SupportTicket::where('status', 'pending')->count();
        $resolvedTickets = SupportTicket::where('replied_at', '>=', $since)->count();

        // Top applied companies
        $topCompanies = JobApplication::where('created_at', '>=', $since)
            ->whereNotNull('company_name')
            ->where('company_name', '!=', '')
            ->selectRaw('company_name, count(*) as count')
            ->groupBy('company_name')
            ->orderByDesc('count')
            ->limit(3)
            ->pluck('company_name')
            ->toArray();
        $topCompaniesStr = ! empty($topCompanies) ? implode(', ', $topCompanies) : 'None';

        // AI Tools usage
        $aiUsage = UserActivity::whereIn('activity_type', ['ai_analyzer', 'cover_letter', 'photo_generation', 'cv_export'])
            ->where('created_at', '>=', $since)
            ->count();

        // Revenue
        $revenue = Payment::where('status', 'SUCCESS')
            ->where('paid_at', '>=', $since)
            ->sum('amount');
        $paidCount = Payment::where('status', 'SUCCESS')
            ->where('paid_at', '>=', $since)
            ->count();

        $revenueFormatted = 'Rp ' . number_format((int) $revenue, 0, ',', '.');
        $title = $isMorning ? 'MORNING BRIEFING' : 'DAILY RECAP';
        $timeLabel = $now->format('H:i') . ' WIB';

        $text = "📊 <b>{$title}</b> • {$timeLabel}\n\n"
              . "• <b>New Users:</b> +{$newUsers}\n"
              . "• <b>Active Members:</b> {$activeUsers}\n"
              . "• <b>Applications Tracked:</b> {$newApplications}\n"
              . "• <b>Top Companies:</b> {$topCompaniesStr}\n"
              . "• <b>AI Tools Generations:</b> {$aiUsage}\n"
              . "• <b>Support:</b> {$pendingTickets} pending ({$resolvedTickets} resolved)\n"
              . "• <b>Revenue:</b> {$revenueFormatted} ({$paidCount} upgrade)\n"
              . "• <b>Total Community:</b> " . number_format($totalUsers, 0, ',', '.') . " users\n"
              . "• <b>Status:</b> All systems operational";

        $keyboard = [
            [['text' => 'Open Admin Dashboard', 'url' => url('/admin')]]
        ];

        return $this->sendMessage($text, $keyboard);
    }

    /**
     * Alert: Unhandled system error 500 (with data masking & deduplication).
     */
    public function reportException(Throwable $e): bool
    {
        if (! $this->enabled) {
            return false;
        }

        // Deduplication: prevent notification storm for repeated exceptions
        $signature = md5($e->getFile() . ':' . $e->getLine() . ':' . $e->getMessage());
        $cacheKey = "tg_err_dedup_{$signature}";

        if (Cache::has($cacheKey)) {
            return false;
        }

        // Throttle for 10 minutes
        Cache::put($cacheKey, true, now()->addMinutes(10));

        $exceptionClass = class_basename($e);
        $file = str_replace(base_path() . '/', '', $e->getFile());
        $line = $e->getLine();

        // Sanitize sensitive credentials from error message
        $rawMessage = self::sanitize($e->getMessage());
        $rawMessage = mb_substr($rawMessage, 0, 250);
        $message = htmlspecialchars($rawMessage, ENT_QUOTES, 'UTF-8');

        $requestUrl = request()?->fullUrl() ?? 'CLI / Background Job';
        $requestMethod = request()?->method() ?? 'JOB';
        $requestInfo = htmlspecialchars(self::sanitize("{$requestMethod} {$requestUrl}"), ENT_QUOTES, 'UTF-8');

        $user = auth()->user();
        $userLabel = $user ? "#{$user->id} (" . htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') . ")" : 'Guest / System';
        $date = Carbon::now('Asia/Jakarta')->translatedFormat('d M Y, H:i:s') . ' WIB';

        $text = "🚨 <b>SYSTEM ERROR DETECTED</b>\n\n"
              . "• <b>Type:</b> <code>{$exceptionClass}</code>\n"
              . "• <b>Request:</b> <code>{$requestInfo}</code>\n"
              . "• <b>User:</b> {$userLabel}\n"
              . "• <b>Location:</b> <code>{$file}:{$line}</code>\n"
              . "• <b>Details:</b>\n<code>{$message}</code>\n\n"
              . "• <b>Time:</b> {$date}";

        return $this->sendMessage($text);
    }

    /**
     * Alert: Server Disk and Resource Health Check.
     */
    public function sendServerHealth(bool $force = false): bool
    {
        $diskTotal = disk_total_space('/');
        $diskFree = disk_free_space('/');
        $diskUsed = $diskTotal - $diskFree;
        $diskPercentage = round(($diskUsed / $diskTotal) * 100, 1);

        if (! $force && $diskPercentage < 85) {
            return false;
        }

        $statusBadge = $diskPercentage >= 90 ? 'CRITICAL' : ($diskPercentage >= 80 ? 'WARNING' : 'HEALTHY');
        $date = Carbon::now('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB';
        $diskTotalGb = round($diskTotal / (1024 * 1024 * 1024), 1);
        $diskUsedGb = round($diskUsed / (1024 * 1024 * 1024), 1);

        $text = "🖥 <b>SERVER HEALTH • {$statusBadge}</b>\n\n"
              . "• <b>Host:</b> " . gethostname() . "\n"
              . "• <b>Disk Usage:</b> {$diskPercentage}% ({$diskUsedGb} GB / {$diskTotalGb} GB)\n"
              . "• <b>Time:</b> {$date}";

        return $this->sendMessage($text);
    }

    /**
     * Handle incoming two-way interactive bot messages (Private Admin Assistant).
     */
    public function handleIncomingUpdate(array $update): bool
    {
        $message = $update['message'] ?? null;
        if (! is_array($message) || ! is_string($message['text'] ?? null)
            || trim($message['text']) === '' || mb_strlen($message['text'], 'UTF-8') > 4096
            || isset($message['sender_chat']) || ! is_array($message['from'] ?? null)
            || ! is_array($message['chat'] ?? null) || ($message['from']['is_bot'] ?? null) !== false
            || ! in_array($message['chat']['type'] ?? null, ['private', 'group', 'supergroup'], true)) {
            return false;
        }

        $senderId = $message['from']['id'] ?? null;
        $chatId = $message['chat']['id'] ?? null;
        if ((! is_int($senderId) && ! is_string($senderId)) || (! is_int($chatId) && ! is_string($chatId))) {
            return false;
        }
        $senderId = (string) $senderId;
        $chatId = (string) $chatId;
        $allowedUsers = array_map('strval', (array) config('services.telegram.allowed_user_ids', []));
        $allowedChats = array_map('strval', (array) config('services.telegram.allowed_chat_ids', []));
        if (! preg_match('/\A[1-9][0-9]*\z/', $senderId) || ! preg_match('/\A-?[1-9][0-9]*\z/', $chatId)
            || ! in_array($senderId, $allowedUsers, true) || ! in_array($chatId, $allowedChats, true)) {
            return false;
        }

        // Polling and webhook commands share the same sender budget across chats.
        $limitKey = 'telegram:commands:'.$senderId;
        if (RateLimiter::tooManyAttempts($limitKey, 10)) {
            return false;
        }
        RateLimiter::hit($limitKey, 60);

        $text = trim($message['text']);
        $name = $message['from']['first_name'] ?? 'Admin';
        $sender = htmlspecialchars(is_string($name) ? $name : 'Admin', ENT_QUOTES, 'UTF-8');

        // Parse command and arguments
        $parts = explode(' ', $text, 2);
        $command = strtolower($parts[0]);
        $argument = isset($parts[1]) ? trim($parts[1]) : '';

        // Strip bot username if mentioned (e.g. /stats@botname)
        if (str_contains($command, '@')) {
            $command = explode('@', $command)[0];
        }

        switch ($command) {
            case '/start':
            case '/help':
                $reply = "🤖 <b>TraKerja Admin Assistant & AI Copilot</b>\n\n"
                       . "Halo <b>{$sender}</b>, berikut daftar perintah yang tersedia:\n\n"
                       . "🧠 <b>AI COPILOT:</b>\n"
                       . "• <code>/ask [pertanyaan]</code> — Tanya apa saja ke AI Copilot seputar data & bisnis TraKerja\n\n"
                       . "📊 <b>OPERASIONAL:</b>\n"
                       . "• <code>/stats</code> — Ringkasan metrik live platform\n"
                       . "• <code>/user [email/ID]</code> — Cari profil & tracking lengkap user\n"
                       . "• <code>/pending</code> — Daftar tiket bantuan belum dibalas\n"
                       . "• <code>/recap</code> — Kirim rekap harian sekarang ke channel\n"
                       . "• <code>/health</code> — Cek status disk & kapasitas server";
                return $this->sendMessage($reply, null, $chatId);

            case '/ask':
                $answer = app(\App\Services\AiCopilotService::class)->ask($argument, $sender);
                return $this->sendMessage($answer, null, $chatId);

            case '/stats':
                $totalUsers = User::count();
                $premiumUsers = User::where('is_premium', true)->count();
                $totalApps = JobApplication::count();
                $pendingTickets = SupportTicket::where('status', 'pending')->count();
                $todayRevenue = Payment::where('status', 'SUCCESS')
                    ->where('paid_at', '>=', now()->startOfDay())
                    ->sum('amount');

                $reply = "📈 <b>LIVE PLATFORM STATS</b>\n\n"
                       . "• <b>Total Users:</b> " . number_format($totalUsers, 0, ',', '.') . "\n"
                       . "• <b>Premium Members:</b> " . number_format($premiumUsers, 0, ',', '.') . "\n"
                       . "• <b>Tracked Applications:</b> " . number_format($totalApps, 0, ',', '.') . "\n"
                       . "• <b>Today Revenue:</b> Rp " . number_format((int) $todayRevenue, 0, ',', '.') . "\n"
                       . "• <b>Pending Support:</b> {$pendingTickets} ticket(s)";
                return $this->sendMessage($reply, null, $chatId);

            case '/user':
                if (empty($argument)) {
                    return $this->sendMessage("⚠️ Format: <code>/user email@domain.com</code> atau <code>/user 123</code> (User ID)", null, $chatId);
                }

                $user = User::where('email', $argument)
                    ->orWhere('id', is_numeric($argument) ? (int) $argument : null)
                    ->first();

                if (! $user) {
                    $escapedArg = htmlspecialchars($argument, ENT_QUOTES, 'UTF-8');
                    return $this->sendMessage("❌ User dengan kata kunci <code>{$escapedArg}</code> tidak ditemukan.", null, $chatId);
                }

                $userName = htmlspecialchars($user->name ?? 'User', ENT_QUOTES, 'UTF-8');
                $userEmail = htmlspecialchars($user->email ?? '-', ENT_QUOTES, 'UTF-8');
                $role = $user->isAdmin() ? 'Admin 🛡️' : 'Job Seeker 💼';
                $isPrem = $user->isPremium() ? 'Premium Pro 👑' : 'Free Member 🌱';
                $isVerif = $user->hasVerifiedEmail() ? 'Verified ✅' : 'Unverified ⚠️';

                // Auth Provider
                $authProvider = 'Email Password';
                if (! empty($user->google_id)) {
                    $authProvider = 'Google OAuth';
                } elseif (! empty($user->linkedin_id)) {
                    $authProvider = 'LinkedIn OAuth';
                }

                // Dates
                $joinedAt = $user->created_at ? $user->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y') . ' (' . $user->created_at->diffForHumans() . ')' : '-';
                $lastActive = $user->last_activity_at 
                    ? $user->last_activity_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB'
                    : ($user->created_at ? $user->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' : '-');

                // Job Applications stats
                $totalApps = $user->jobApplications()->count();
                $onProcess = $user->jobApplications()->where('status', 'On Process')->count();
                $accepted = $user->jobApplications()->where('status', 'Accepted')->count();
                $rejected = $user->jobApplications()->where('status', 'Rejected')->count();
                $latestJob = $user->jobApplications()->latest()->first();
                $latestJobStr = $latestJob 
                    ? htmlspecialchars($latestJob->position . ' @ ' . $latestJob->company_name, ENT_QUOTES, 'UTF-8') . ' (' . $latestJob->created_at->format('d M') . ')'
                    : 'Belum ada lamaran';

                // Payments / Billing
                $totalSpent = $user->payments()->where('status', 'SUCCESS')->sum('amount');
                $totalTransactions = $user->payments()->where('status', 'SUCCESS')->count();
                $billingStr = $totalSpent > 0 
                    ? 'Rp ' . number_format((int) $totalSpent, 0, ',', '.') . " ({$totalTransactions} transaksi)"
                    : 'Rp 0';

                // AI Credits & Gamification
                $aiCredits = $user->ai_credits ?? 0;
                $clCredits = $user->cl_credits ?? 0;
                $photoCredits = $user->photo_credits ?? 0;
                $level = $user->level ?? 1;
                $xp = number_format((int) ($user->xp ?? 0), 0, ',', '.');

                // Portfolio status
                $portfolioStr = $user->is_portfolio_published && ! empty($user->portfolio_slug)
                    ? 'Published (' . htmlspecialchars($user->portfolio_slug, ENT_QUOTES, 'UTF-8') . ')'
                    : 'Draft / Belum Aktif';

                $reply = "👤 <b>USER PROFILE • #{$user->id}</b>\n\n"
                       . "• <b>Name:</b> {$userName}\n"
                       . "• <b>Email:</b> <code>{$userEmail}</code>\n"
                       . "• <b>Role:</b> {$role}\n"
                       . "• <b>Method:</b> {$authProvider}\n"
                       . "• <b>Status:</b> {$isPrem} | {$isVerif}\n"
                       . "• <b>Joined:</b> {$joinedAt}\n"
                       . "• <b>Last Active:</b> {$lastActive}\n\n"
                       . "📊 <b>JOB TRACKING</b>\n"
                       . "• <b>Total Applications:</b> <b>{$totalApps}</b>\n"
                       . "• <b>Status:</b> {$onProcess} Proses • {$accepted} Diterima • {$rejected} Ditolak\n"
                       . "• <b>Latest Job:</b> {$latestJobStr}\n\n"
                       . "💳 <b>BILLING & ASSETS</b>\n"
                       . "• <b>Total Spent:</b> {$billingStr}\n"
                       . "• <b>AI Credits:</b> {$aiCredits} Analyzer • {$clCredits} Cover Letter • {$photoCredits} Photo\n"
                       . "• <b>Gamification:</b> Level {$level} ({$xp} XP)\n"
                       . "• <b>Portfolio:</b> {$portfolioStr}";

                $keyboard = [
                    [['text' => 'Manage in Admin Panel', 'url' => url('/admin/users?search=' . urlencode($user->email))]]
                ];

                return $this->sendMessage($reply, $keyboard, $chatId);

            case '/pending':
                $pending = SupportTicket::where('status', 'pending')
                    ->latest()
                    ->limit(5)
                    ->get();

                if ($pending->isEmpty()) {
                    return $this->sendMessage("✅ Tidak ada tiket bantuan yang pending saat ini.", null, $chatId);
                }

                $lines = [];
                foreach ($pending as $ticket) {
                    $u = $ticket->user;
                    $n = htmlspecialchars($u->name ?? 'User', ENT_QUOTES, 'UTF-8');
                    $sub = htmlspecialchars($ticket->subject, ENT_QUOTES, 'UTF-8');
                    $lines[] = "• <b>#{$ticket->id}</b> dari {$n}: <i>{$sub}</i>";
                }

                $reply = "💬 <b>PENDING SUPPORT TICKETS</b>\n\n"
                       . implode("\n\n", $lines) . "\n\n"
                       . "Buka di: " . url('/admin/feedbacks');

                return $this->sendMessage($reply, null, $chatId);

            case '/recap':
                $this->sendMessage("⏳ Mengirim Daily Recap ke Channel...", null, $chatId);
                $this->sendDailyRecap('evening');
                return $this->sendMessage("✅ Daily Recap berhasil dikirim ke Channel.", null, $chatId);

            case '/health':
                $diskTotal = disk_total_space('/');
                $diskFree = disk_free_space('/');
                $diskUsed = $diskTotal - $diskFree;
                $diskPercentage = round(($diskUsed / $diskTotal) * 100, 1);
                $diskTotalGb = round($diskTotal / (1024 * 1024 * 1024), 1);
                $diskUsedGb = round($diskUsed / (1024 * 1024 * 1024), 1);

                $reply = "🖥 <b>SERVER RESOURCE HEALTH</b>\n\n"
                       . "• <b>Hostname:</b> " . gethostname() . "\n"
                       . "• <b>Disk Usage:</b> {$diskPercentage}% ({$diskUsedGb} GB / {$diskTotalGb} GB)\n"
                       . "• <b>PHP:</b> " . PHP_VERSION . "\n"
                       . "• <b>Environment:</b> " . app()->environment();

                return $this->sendMessage($reply, null, $chatId);

            default:
                return $this->sendMessage("❓ Perintah tidak dikenal. Ketik <code>/help</code> untuk melihat daftar perintah.", null, $chatId);
        }
    }
}
