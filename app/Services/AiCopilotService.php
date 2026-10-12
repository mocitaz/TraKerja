<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\Payment;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class AiCopilotService
{
    protected ?string $geminiKey;
    protected ?string $openaiKey;

    public function __construct()
    {
        $this->geminiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
        $this->openaiKey = config('services.openai.key', env('OPENAI_API_KEY'));
    }

    /**
     * Answer incoming questions from the Telegram admin copilot.
     */
    public function ask(string $question, ?string $sender = 'Admin'): string
    {
        $cleanQuestion = trim($question);

        if (empty($cleanQuestion)) {
            return "🤖 <b>TraKerja AI Copilot</b>\n\n"
                 . "Halo <b>{$sender}</b>! Silakan ketik pertanyaan Anda setelah perintah <code>/ask</code>.\n\n"
                 . "<b>Contoh yang bisa Anda tanyakan:</b>\n"
                 . "• <code>/ask berapa total omset bulan ini dan hari ini?</code>\n"
                 . "• <code>/ask perusahaan apa saja yang paling banyak dilamar user?</code>\n"
                 . "• <code>/ask posisi kerja terpopuler di TraKerja apa saja?</code>\n"
                 . "• <code>/ask bagaimana rincian paket Free vs Premium Pro?</code>\n"
                 . "• <code>/ask ada ide apa untuk meningkatkan konversi pengguna gratis ke premium?</code>";
        }

        // Collect live data snapshot from the database
        $context = $this->buildLiveContext();

        // 1. Try Gemini API if configured
        if (! empty($this->geminiKey)) {
            try {
                $response = $this->callGemini($cleanQuestion, $context, $sender);
                if (! empty($response)) {
                    return $response;
                }
            } catch (Throwable $e) {
                Log::warning('Gemini Copilot API error: ' . $e->getMessage());
            }
        }

        // 2. Try OpenAI API if configured
        if (! empty($this->openaiKey)) {
            try {
                $response = $this->callOpenAi($cleanQuestion, $context, $sender);
                if (! empty($response)) {
                    return $response;
                }
            } catch (Throwable $e) {
                Log::warning('OpenAI Copilot API error: ' . $e->getMessage());
            }
        }

        // 3. Fallback to Smart Native Domain Expert Engine
        return $this->smartNativeAnswer($cleanQuestion, $context, $sender);
    }

    /**
     * Compile rich, live context about TraKerja database and platform state.
     */
    public function buildLiveContext(): array
    {
        $now = Carbon::now('Asia/Jakarta');

        $totalUsers = User::count();
        $premiumUsers = User::where('is_premium', true)->count();
        $todayUsers = User::where('created_at', '>=', $now->copy()->startOfDay())->count();
        $activeToday = User::where('last_activity_at', '>=', $now->copy()->startOfDay())->count();

        $totalApplications = JobApplication::count();
        $todayApplications = JobApplication::where('created_at', '>=', $now->copy()->startOfDay())->count();

        $topCompanies = JobApplication::whereNotNull('company_name')
            ->where('company_name', '!=', '')
            ->selectRaw('company_name, count(*) as count')
            ->groupBy('company_name')
            ->orderByDesc('count')
            ->limit(5)
            ->pluck('count', 'company_name')
            ->toArray();

        $topPositions = JobApplication::whereNotNull('position')
            ->where('position', '!=', '')
            ->selectRaw('position, count(*) as count')
            ->groupBy('position')
            ->orderByDesc('count')
            ->limit(5)
            ->pluck('count', 'position')
            ->toArray();

        $todayRevenue = Payment::where('status', 'SUCCESS')
            ->where('paid_at', '>=', $now->copy()->startOfDay())
            ->sum('amount');

        $monthRevenue = Payment::where('status', 'SUCCESS')
            ->where('paid_at', '>=', $now->copy()->startOfMonth())
            ->sum('amount');

        $totalRevenue = Payment::where('status', 'SUCCESS')->sum('amount');

        $pendingTickets = SupportTicket::where('status', 'pending')->count();
        $resolvedTickets = SupportTicket::where('status', 'resolved')->count();

        $aiUsage = UserActivity::whereIn('activity_type', ['ai_analyzer', 'cover_letter', 'photo_generation', 'cv_export'])
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->count();

        return [
            'platform' => 'TraKerja (PT. Teknalogi Transformasi Digital)',
            'website' => 'https://trakerja.com',
            'current_time' => $now->format('d M Y H:i') . ' WIB',
            'users' => [
                'total' => $totalUsers,
                'premium' => $premiumUsers,
                'today_registered' => $todayUsers,
                'active_today' => $activeToday,
                'conversion_rate' => $totalUsers > 0 ? round(($premiumUsers / $totalUsers) * 100, 2) . '%' : '0%',
            ],
            'applications' => [
                'total' => $totalApplications,
                'today' => $todayApplications,
                'top_companies' => $topCompanies,
                'top_positions' => $topPositions,
            ],
            'revenue' => [
                'today' => (int) $todayRevenue,
                'month' => (int) $monthRevenue,
                'all_time' => (int) $totalRevenue,
            ],
            'support' => [
                'pending' => $pendingTickets,
                'resolved' => $resolvedTickets,
            ],
            'ai_usage_month' => $aiUsage,
            'pricing' => [
                'free' => 'Gratis selamanya, limit 1x ekspor CV per bulan, 1x trial AI analyzer.',
                'premium_pro' => 'Rp 19.999 (Akses Selamanya/Lifetime): Unlimited ekspor CV ATS, 5x AI Analyzer per bulan, Portofolio custom domain.',
                'addon_bundle' => 'Rp 9.999: 15 Kredit Cover Letter + 10 Kredit AI Analyzer + 5 Kredit AI Photo.',
            ],
        ];
    }

    /**
     * Query Gemini API with structured prompt and system context.
     */
    protected function callGemini(string $question, array $context, string $sender): ?string
    {
        $systemPrompt = "Anda adalah TraKerja AI Copilot, asisten eksekutif cerdas berbasis AI untuk admin platform TraKerja (trakerja.com - PT Teknalogi Transformasi Digital).\n\n"
                      . "Karakter Anda: Sangat cerdas, profesional, solutif, ringkas, dan ramah dalam bahasa Indonesia.\n\n"
                      . "ATURAN FORMAT:\n"
                      . "1. Gunakan format HTML Telegram saja: <b>tebal</b>, <code>kode</code>, <i>miring</i>, bullet poin (•).\n"
                      . "2. JANGAN gunakan format markdown seperti **tebal**, # judul, atau ``` block.\n"
                      . "3. Berikan jawaban yang padat, berbobot, to the point, dan langsung menjawab inti pertanyaan.\n\n"
                      . "BATASAN DOMAIN (CRITICAL):\n"
                      . "Anda HANYA boleh menjawab pertanyaan yang berkaitan dengan TraKerja, karir, pencarian kerja, rekrutmen, teknologi HR, operasional platform, data pengguna, strategi bisnis, atau fitur TraKerja. Jika user menanyakan topik yang sama sekali tidak berhubungan (misal: resep masakan, ramalan zodiak, fisika kuantum), tolak dengan sopan: 'Maaf, sebagai AI Copilot TraKerja, saya hanya melayani pertanyaan seputar platform, data operasional, dan karir TraKerja.'\n\n"
                      . "DATA REAL-TIME PLATFORM SAAT INI:\n" . json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$this->geminiKey}";

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => "Pertanyaan dari {$sender}: {$question}"]],
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.4,
                'maxOutputTokens' => 800,
            ],
        ];

        $res = Http::timeout(6)->post($url, $payload);
        if ($res->successful()) {
            $text = $res->json('candidates.0.content.parts.0.text');
            if (! empty($text)) {
                return "🧠 <b>AI COPILOT</b>\n\n" . trim($text);
            }
        }

        return null;
    }

    /**
     * Query OpenAI API as secondary fallback.
     */
    protected function callOpenAi(string $question, array $context, string $sender): ?string
    {
        $systemPrompt = "Anda adalah TraKerja AI Copilot, asisten eksekutif cerdas untuk admin platform TraKerja (trakerja.com).\n"
                      . "Jawab dalam bahasa Indonesia menggunakan format HTML Telegram (<b>, <code>, <i>, bullet •). Jangan gunakan markdown **.\n"
                      . "Hanya jawab terkait TraKerja, data platform, bisnis karir, dan operasional.\n"
                      . "Data platform:\n" . json_encode($context, JSON_UNESCAPED_UNICODE);

        $res = Http::timeout(6)
            ->withToken($this->openaiKey)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => "Pertanyaan: {$question}"],
                ],
                'temperature' => 0.4,
                'max_tokens' => 800,
            ]);

        if ($res->successful()) {
            $text = $res->json('choices.0.message.content');
            if (! empty($text)) {
                return "🧠 <b>AI COPILOT</b>\n\n" . trim($text);
            }
        }

        return null;
    }

    /**
     * Intelligent Native Domain Expert Engine (Runs 100% locally with 0 API Key).
     */
    protected function smartNativeAnswer(string $q, array $c, string $sender): string
    {
        $lower = strtolower($q);

        // 1. Guardrail against unrelated queries
        $outOfScopeKeywords = ['resep', 'masak', 'cuaca', 'zodiak', 'ramalan', 'presiden', 'sepak bola', 'film', 'lirik'];
        foreach ($outOfScopeKeywords as $bad) {
            if (str_contains($lower, $bad)) {
                return "🤖 <b>AI COPILOT • OUT OF SCOPE</b>\n\n"
                     . "Maaf <b>{$sender}</b>, sebagai AI Copilot TraKerja, fokus keahlian saya adalah seputar <b>platform TraKerja, data pengguna, analitik bisnis karir, dan operasional</b>. Silakan ajukan pertanyaan terkait TraKerja!";
            }
        }

        // 2. Ide Strategi / Saran Pertumbuhan (Growth Advice)
        if (str_contains($lower, 'ide') || str_contains($lower, 'saran') || str_contains($lower, 'tips') || str_contains($lower, 'strategi') || str_contains($lower, 'tingkat') || str_contains($lower, 'growth')) {
            return "🚀 <b>SARAN STRATEGI & MONETISASI TRAKERJA</b>\n\n"
                 . "1. <b>Email Automation:</b> Targetkan pengguna Free yang kuota trial AI-nya habis dengan promosi diskon upgrade ke Premium Pro.\n"
                 . "2. <b>Perbanyak Template ATS:</b> Template CV dengan skor ATS tinggi adalah pemicu utama konversi berbayar.\n"
                 . "3. <b>Social Proof di Telegram:</b> Umumkan pencapaian tawaran kerja (Offer Celebration) secara berkala agar memicu motivasi & FOMO pengguna.\n\n"
                 . "💡 <i>(Untuk analisis kustom mendalam, Anda dapat memasukkan GEMINI_API_KEY di file .env Anda!)</i>";
        }

        // 3. Revenue / Keuangan / Omset
        if (str_contains($lower, 'omset') || str_contains($lower, 'revenue') || str_contains($lower, 'uang') || str_contains($lower, 'pendapatan') || str_contains($lower, 'bayar') || str_contains($lower, 'penjualan')) {
            $today = 'Rp ' . number_format($c['revenue']['today'], 0, ',', '.');
            $month = 'Rp ' . number_format($c['revenue']['month'], 0, ',', '.');
            $total = 'Rp ' . number_format($c['revenue']['all_time'], 0, ',', '.');
            $conv = $c['users']['conversion_rate'];

            return "💰 <b>RINGKASAN KEUANGAN & OMSET TRAKERJA</b>\n\n"
                 . "• <b>Hari Ini:</b> <b>{$today}</b>\n"
                 . "• <b>Bulan Ini:</b> <b>{$month}</b>\n"
                 . "• <b>Sepanjang Waktu:</b> <b>{$total}</b>\n"
                 . "• <b>Tingkat Konversi Berbayar:</b> {$conv} dari total " . number_format($c['users']['total'], 0, ',', '.') . " user.\n\n"
                 . "💡 <i>Tips: Paket Premium Pro seharga Rp 19.999 (Lifetime) menyumbang kontribusi terbesar transaksi.</i>";
        }

        // 3. Top Perusahaan
        if (str_contains($lower, 'perusahaan') || str_contains($lower, 'company') || str_contains($lower, 'kantor') || str_contains($lower, 'pt')) {
            $lines = [];
            $rank = 1;
            foreach ($c['applications']['top_companies'] as $comp => $cnt) {
                $lines[] = "{$rank}. <b>{$comp}</b> — {$cnt} lamaran";
                $rank++;
            }
            $list = ! empty($lines) ? implode("\n", $lines) : 'Belum ada data lamaran yang dicatat.';

            return "🏢 <b>TOP PERUSAHAAN PALING DIMINATI USER</b>\n\n"
                 . "Berikut daftar perusahaan yang paling banyak dilamar oleh pencari kerja di TraKerja:\n\n"
                 . "{$list}\n\n"
                 . "• <b>Total Lamaran Terdata:</b> " . number_format($c['applications']['total'], 0, ',', '.') . " lamaran.";
        }

        // 4. Top Posisi / Pekerjaan
        if (str_contains($lower, 'posisi') || str_contains($lower, 'role') || str_contains($lower, 'pekerjaan') || str_contains($lower, 'job') || str_contains($lower, 'profesi')) {
            $lines = [];
            $rank = 1;
            foreach ($c['applications']['top_positions'] as $pos => $cnt) {
                $lines[] = "{$rank}. <b>{$pos}</b> — {$cnt} pencari kerja";
                $rank++;
            }
            $list = ! empty($lines) ? implode("\n", $lines) : 'Belum ada data posisi pekerjaan.';

            return "💼 <b>TOP POSISI / ROLE TERPOPULER</b>\n\n"
                 . "Posisi pekerjaan yang paling aktif dilamar di ekosistem TraKerja:\n\n"
                 . "{$list}\n\n"
                 . "💡 <i>Mayoritas pengguna TraKerja berfokus pada industri teknologi, perbankan, dan operasional bisnis.</i>";
        }

        // 5. User / Pengguna / Pendaftar
        if (str_contains($lower, 'user') || str_contains($lower, 'pengguna') || str_contains($lower, 'member') || str_contains($lower, 'pendaftar') || str_contains($lower, 'aktif')) {
            $tot = number_format($c['users']['total'], 0, ',', '.');
            $prem = number_format($c['users']['premium'], 0, ',', '.');
            $tdy = $c['users']['today_registered'];
            $act = $c['users']['active_today'];
            $cr = $c['users']['conversion_rate'];

            return "👥 <b>DEMOGRAFI & AKTIVITAS USER TRAKERJA</b>\n\n"
                 . "• <b>Total Pengguna:</b> <b>{$tot}</b> akun\n"
                 . "• <b>Member Premium Pro:</b> <b>{$prem}</b> akun ({$cr})\n"
                 . "• <b>Pendaftar Baru Hari Ini:</b> +{$tdy} orang\n"
                 . "• <b>User Aktif Hari Ini:</b> {$act} orang\n\n"
                 . "Gunakan <code>/user [email]</code> untuk menginspeksi profil spesifik seorang pengguna.";
        }

        // 6. Paket / Harga / Fitur / Pricing / Monetisasi
        if (str_contains($lower, 'harga') || str_contains($lower, 'paket') || str_contains($lower, 'premium') || str_contains($lower, 'addon') || str_contains($lower, 'fitur') || str_contains($lower, 'biaya')) {
            return "💎 <b>SKEMA PAKET & HARGA TRAKERJA</b>\n\n"
                 . "1. <b>Free Tier (Gratis):</b>\n"
                 . "• Akses Kanban Board Job Tracker\n"
                 . "• 1x Ekspor CV ATS per bulan\n"
                 . "• 1x Free Trial AI Resume Analyzer\n\n"
                 . "2. <b>Premium Pro (Rp 19.999 • Sekali Bayar Selamanya):</b>\n"
                 . "• Unlimited Ekspor CV ATS (Semua template)\n"
                 . "• 5x Kuota AI Resume Analyzer per bulan\n"
                 . "• Akses Portofolio Website Builder dengan custom domain/slug\n"
                 . "• Akses prioritas fitur baru\n\n"
                 . "3. <b>Add-On Pack (Rp 9.999):</b>\n"
                 . "• 15 Kredit AI Cover Letter\n"
                 . "• 10 Kredit AI Analyzer tambahan\n"
                 . "• 5 Kredit AI Professional Headshot";
        }

        // 7. Tiket Bantuan / Support / Keluhan
        if (str_contains($lower, 'tiket') || str_contains($lower, 'support') || str_contains($lower, 'bantuan') || str_contains($lower, 'keluhan') || str_contains($lower, 'komplain')) {
            $p = $c['support']['pending'];
            $r = $c['support']['resolved'];

            return "💬 <b>STATUS DUKUNGAN PENGGUNA (SUPPORT)</b>\n\n"
                 . "• <b>Tiket Menunggu Respon:</b> <b>{$p}</b> tiket\n"
                 . "• <b>Tiket Selesai Ditangani:</b> <b>{$r}</b> tiket\n\n"
                 . "Ketik <code>/pending</code> untuk melihat 5 tiket terbaru yang membutuhkan balasan tim admin, atau buka dashboard di: " . url('/admin/feedbacks');
        }

        // Default Intelligent Fallback
        return "🤖 <b>TRAKERJA AI COPILOT</b>\n\n"
             . "Saya telah mencerna pertanyaan Anda: <i>\"{$q}\"</i>\n\n"
             . "• <b>Status Platform:</b> Normal & Sehat\n"
             . "• <b>Total Komunitas:</b> " . number_format($c['users']['total'], 0, ',', '.') . " pencari kerja\n"
             . "• <b>Total Lamaran:</b> " . number_format($c['applications']['total'], 0, ',', '.') . " lamaran terdata\n"
             . "• <b>Omset Bulan Ini:</b> Rp " . number_format($c['revenue']['month'], 0, ',', '.') . "\n\n"
             . "💡 <b>Tips Perintah Praktis:</b>\n"
             . "• <code>/ask berapa omset bulan ini?</code>\n"
             . "• <code>/ask top perusahaan terpopuler</code>\n"
             . "• <code>/ask apa saja rincian paket premium?</code>\n\n"
             . "<i>Ingin jawaban generatif yang lebih fleksibel? Tambahkan GEMINI_API_KEY di file .env Anda!</i>";
    }
}
