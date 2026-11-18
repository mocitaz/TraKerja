<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNaraAction;
use App\Models\AdminNaraMessage;
use App\Services\AdminNara\AdminNaraActions;
use App\Services\AdminNara\AdminNaraService;
use App\Services\NaraClientStopped;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Chat endpoints of Nara Admin (admins only: the route group applies the `admin` middleware). */
class AdminNaraController extends Controller
{
    public function __construct(private AdminNaraService $nara) {}

    private function validated(Request $request): array
    {
        $data = $request->validate(['message' => 'required|string|min:1|max:1500', 'page' => 'nullable|string|max:60']);
        $data['page'] = isset(AdminNaraService::PAGES[$data['page'] ?? '']) ? $data['page'] : null;

        return $data;
    }

    /** The saved conversation, the approval cards in it and starter chips for the current page. */
    public function history(Request $request): JsonResponse
    {
        $admin = Auth::user();
        $messages = AdminNaraMessage::where('user_id', $admin->id)->latest('id')->limit(60)->get(['id', 'role', 'content'])->reverse()->values();

        return response()->json([
            'history' => $messages->map(fn ($m) => ['id' => $m->id, 'role' => $m->role, 'content' => $m->content])->all(),
            'actions' => AdminNaraService::cardsIn($admin, ...$messages->pluck('content')->all()),
            'starters' => self::starters($request->query('page')),
        ]);
    }

    public function chat(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $admin = Auth::user();
        $result = $this->nara->answer($data['message'], $admin, AdminNaraMessage::contextFor($admin), $data['page']);
        $saved = $result['success'] ? AdminNaraMessage::record($admin, $data['message'], $result['reply'], $data['page']) : null;

        return response()->json(['success' => $result['success'], 'reply' => $result['reply'], 'message_id' => $saved?->id, 'actions' => $saved ? AdminNaraService::cardsIn($admin, $result['reply']) : []]);
    }

    public function stream(Request $request): StreamedResponse
    {
        $data = $this->validated($request);
        $admin = Auth::user();
        $history = AdminNaraMessage::contextFor($admin);

        return response()->stream(function () use ($data, $admin, $history) {
            @set_time_limit(150);
            @ini_set('zlib.output_compression', 'Off');
            while (ob_get_level() > 0 && ! app()->runningUnitTests()) {
                @ob_end_flush();
            }
            ignore_user_abort(true);
            $full = '';
            try {
                $result = $this->nara->answer($data['message'], $admin, $history, $data['page'], function (string $chunk) use (&$full) {
                    $full .= $chunk;
                    echo 'data: '.json_encode(['chunk' => $chunk])."\n\n";
                    @flush();
                    if (connection_aborted()) {
                        throw new NaraClientStopped($full);
                    }
                }, function (string $status) {
                    echo 'data: '.json_encode(['status' => $status])."\n\n";
                    @flush();
                });
            } catch (NaraClientStopped $stopped) {
                $partial = trim(preg_replace('/\[[^\]]*$/', '', $stopped->partial));
                if ($partial !== '') {
                    AdminNaraMessage::record($admin, $data['message'], $partial, $data['page']);
                }

                return;
            }
            if ($result['success'] && $full !== '') {
                $saved = AdminNaraMessage::record($admin, $data['message'], $result['reply'], $data['page']);
                echo 'data: '.json_encode(['final' => $saved->content, 'id' => $saved->id, 'actions' => AdminNaraService::cardsIn($admin, $saved->content)])."\n\n";
            }
            if (! $result['success']) {
                echo "event: error\ndata: ".json_encode(['success' => false, 'message' => __('Response interrupted. Please try again.')])."\n\n";
            }
            echo "data: [DONE]\n\n";
            if (ob_get_level() > 0 && ! app()->runningUnitTests()) {
                ob_flush();
            }
            flush();
        }, 200, ['Content-Type' => 'text/event-stream', 'Cache-Control' => 'no-cache, no-transform', 'X-Accel-Buffering' => 'no', 'Connection' => 'keep-alive']);
    }

    public function clear(): JsonResponse
    {
        AdminNaraMessage::where('user_id', Auth::id())->delete();
        AdminNaraAction::where('user_id', Auth::id())->where('status', 'pending')->update(['status' => 'rejected', 'decided_at' => now()]);

        return response()->json(['success' => true]);
    }

    /** Run a proposed change. Only the admin who asked can, only once, and only while the proposal is fresh. */
    public function approve(AdminNaraActions $actions, int $action): JsonResponse
    {
        $admin = Auth::user();
        $proposal = AdminNaraAction::where('user_id', $admin->id)->findOrFail($action);
        if ($proposal->status !== 'pending') {
            return response()->json(['success' => false, 'action' => $proposal->card(), 'message' => __('This proposal was already handled.')], 409);
        }
        if ($proposal->isStale()) {
            $proposal->update(['status' => 'expired', 'decided_at' => now()]);

            return response()->json(['success' => false, 'action' => $proposal->card(), 'message' => __('This proposal expired. Ask Nara again.')], 410);
        }
        // Claim it first: a double click or two tabs must not run it twice.
        if (! AdminNaraAction::whereKey($proposal->id)->where('status', 'pending')->update(['status' => 'running', 'decided_at' => now()])) {
            return response()->json(['success' => false, 'message' => __('This proposal was already handled.')], 409);
        }
        $result = $actions->execute($proposal->refresh(), $admin);
        $proposal->update(['status' => $result['ok'] ? 'executed' : 'failed', 'result' => $result]);

        return response()->json(['success' => $result['ok'], 'action' => $proposal->refresh()->card(), 'message' => $result['message']]);
    }

    public function reject(int $action): JsonResponse
    {
        $proposal = AdminNaraAction::where('user_id', Auth::id())->findOrFail($action);
        if ($proposal->status === 'pending') {
            $proposal->update(['status' => 'rejected', 'decided_at' => now()]);
        }

        return response()->json(['success' => true, 'action' => $proposal->refresh()->card()]);
    }

    /** Starter cards for the page the admin is on. @return array<int, array{icon: string, text: string, hint: string}> */
    public static function starters(?string $page): array
    {
        $card = fn (string $icon, string $text, string $hint) => ['icon' => $icon, 'text' => $text, 'hint' => $hint];

        return match ($page) {
            'operations' => [$card('ph-pulse', 'Ringkas kondisi sistem sekarang', 'Layanan, insiden, antrean'), $card('ph-siren', 'Ada insiden yang masih terbuka?', 'Urut dari yang paling genting'), $card('ph-plugs', 'Service mana yang bermasalah?', 'Hasil cek layanan terakhir'), $card('ph-queue', 'Ada job antrean yang gagal?', 'Failed jobs 24 jam')],
            'job-engine', 'vacancies' => [$card('ph-plugs-connected', 'Sumber loker mana yang bermasalah?', 'Gagal atau dijeda'), $card('ph-briefcase', 'Berapa loker baru 24 jam terakhir?', 'Plus sumber teratas'), $card('ph-warning-diamond', 'Ada loker yang dicurigai scam?', 'Flag dan kualitas'), $card('ph-trend-down', 'Kenapa loker baru naik atau turun?', 'Bandingkan minggu lalu')],
            'payments' => [$card('ph-hourglass-medium', 'Ada pembayaran yang menggantung?', 'Menunggu lebih dari 1 jam'), $card('ph-currency-circle-dollar', 'Ringkas pendapatan 7 hari terakhir', 'Per status dan channel'), $card('ph-chart-line-up', 'Kenapa pendapatan naik atau turun?', 'Bandingkan periode lalu'), $card('ph-crown', 'Berapa user premium sekarang?', 'Dan yang baru minggu ini')],
            'users', 'user-activities' => [$card('ph-user-plus', 'Berapa pendaftar baru minggu ini?', 'Plus yang terverifikasi'), $card('ph-funnel', 'Berapa yang aktif membuat lamaran?', 'Corong pendaftaran'), $card('ph-magnifying-glass', 'Cari user berdasarkan email', 'Ringkasan akun lengkap'), $card('ph-pulse', 'Berapa user aktif 7 hari terakhir?', 'Aktivitas terbaru')],
            'feedbacks', 'survey' => [$card('ph-lifebuoy', 'Tiket apa yang belum dibalas?', 'Yang paling lama dulu'), $card('ph-chat-centered-text', 'Apa keluhan terbanyak di survei?', 'Rata-rata dan komentar'), $card('ph-pencil-line', 'Bantu balas tiket tertua', 'Draf untuk kamu setujui'), $card('ph-smiley', 'Seberapa puas user bulan ini?', 'Skor survei 30 hari')],
            'analytics' => [$card('ph-chart-bar', 'Apa yang berubah minggu ini?', 'Metrik utama vs minggu lalu'), $card('ph-user-plus', 'Kenapa pendaftar naik atau turun?', 'Dengan penyebab yang mungkin'), $card('ph-robot', 'Berapa biaya AI minggu ini?', 'Per fitur dan model'), $card('ph-currency-circle-dollar', 'Ringkas pendapatan bulan ini', 'Per status dan channel')],
            default => [$card('ph-list-checks', 'Apa yang perlu diperhatikan hari ini?', 'Ringkasan seluruh platform'), $card('ph-pulse', 'Ringkas kondisi sistem sekarang', 'Layanan, insiden, antrean'), $card('ph-lifebuoy', 'Tiket apa yang belum dibalas?', 'Yang paling lama dulu'), $card('ph-currency-circle-dollar', 'Ringkas pendapatan 7 hari terakhir', 'Per status dan channel')],
        };
    }
}
