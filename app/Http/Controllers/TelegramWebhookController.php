<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    /**
     * Handle incoming Telegram webhook updates for the two-way assistant bot.
     */
    public function handle(Request $request, TelegramService $telegram): JsonResponse
    {
        try {
            $data = $request->all();
            if (! empty($data)) {
                $telegram->handleIncomingUpdate($data);
            }
        } catch (\Throwable $e) {
            Log::error('Telegram webhook error: ' . $e->getMessage());
        }

        return response()->json(['status' => 'ok']);
    }
}
