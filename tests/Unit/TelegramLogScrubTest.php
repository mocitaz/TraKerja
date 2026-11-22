<?php

namespace Tests\Unit;

use App\Services\TelegramService;
use Tests\TestCase;

/** A failed Telegram call never writes the bot token to the log (cURL errors quote the full URL). */
class TelegramLogScrubTest extends TestCase
{
    public function test_the_bot_token_is_removed_from_error_text(): void
    {
        config(['services.telegram.bot_token' => '123456:SECRET-token_value']);
        $scrub = new \ReflectionMethod(TelegramService::class, 'scrub');

        $clean = $scrub->invoke(new TelegramService, 'cURL error 28: timed out for https://api.telegram.org/bot123456:SECRET-token_value/sendMessage and /bot999:OTHER-token/x');

        $this->assertStringNotContainsString('SECRET-token_value', $clean);
        $this->assertStringNotContainsString('OTHER-token', $clean);
        $this->assertStringContainsString('api.telegram.org/bot***/sendMessage', $clean);
    }
}
