<?php

namespace Tests\Feature;

use App\Services\AiCopilotService;
use App\Services\TelegramService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramWebhookSecurityTest extends TestCase
{
    public function createApplication()
    {
        $app = require dirname(__DIR__, 2).'/bootstrap/app.php';
        $app->useEnvironmentPath('/dev/null');
        $this->traitsUsedByTest = array_flip(class_uses_recursive(static::class));
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'app.key' => str_repeat('t', 32),
            'cache.default' => 'array',
            'services.telegram.webhook_secret' => 'test-webhook-secret',
            'services.telegram.allowed_user_ids' => ['123'],
            'services.telegram.allowed_chat_ids' => ['-456'],
            'services.telegram.bot_token' => null,
            'services.telegram.channel_id' => null,
            'services.telegram.enabled' => false,
            'logging.default' => 'null',
        ]);
        Http::preventStrayRequests();
    }

    private function update(string $command = '/help'): array
    {
        return ['message' => [
            'from' => ['id' => 123, 'is_bot' => false, 'first_name' => 'Admin'],
            'chat' => ['id' => -456, 'type' => 'supergroup'],
            'text' => $command,
        ]];
    }

    public function test_missing_or_wrong_secret_never_reaches_the_handler(): void
    {
        $this->mock(TelegramService::class)->shouldNotReceive('handleIncomingUpdate');
        $this->postJson('/api/telegram/webhook', $this->update('/stats'))->assertForbidden();
        $this->withHeader('X-Telegram-Bot-Api-Secret-Token', 'incorrect')
            ->postJson('/api/telegram/webhook', $this->update('/ask internal data'))->assertForbidden();
    }

    public function test_missing_configuration_fails_closed(): void
    {
        config(['services.telegram.webhook_secret' => '']);
        $this->mock(TelegramService::class)->shouldNotReceive('handleIncomingUpdate');
        $this->postJson('/api/telegram/webhook', $this->update())->assertStatus(503);
        config(['services.telegram.webhook_secret' => 'invalid secret']);
        $this->postJson('/api/telegram/webhook', $this->update())->assertStatus(503);
    }

    public function test_authenticated_webhook_dispatches_and_is_throttled(): void
    {
        $this->mock(TelegramService::class)->shouldReceive('handleIncomingUpdate')
            ->with($this->update())->times(60)->andReturn(true);
        for ($i = 0; $i < 60; $i++) {
            $this->withHeader('X-Telegram-Bot-Api-Secret-Token', 'test-webhook-secret')
                ->postJson('/api/telegram/webhook', $this->update())->assertOk();
        }
        $this->postJson('/api/telegram/webhook', $this->update())->assertStatus(429)->assertHeader('Retry-After');
    }

    public function test_untrusted_senders_chats_and_channel_posts_cannot_execute_commands(): void
    {
        $this->mock(AiCopilotService::class)->shouldNotReceive('ask');
        $service = new TelegramService;
        foreach (['/stats', '/pending', '/ask internal data'] as $command) {
            $update = $this->update($command);
            $update['message']['from']['id'] = 999;
            $this->assertFalse($service->handleIncomingUpdate($update));
            $update = $this->update($command);
            $update['message']['chat']['id'] = -999;
            $this->assertFalse($service->handleIncomingUpdate($update));
            $this->assertFalse($service->handleIncomingUpdate(['channel_post' => $this->update($command)['message']]));
            $update = $this->update($command);
            $update['message']['sender_chat'] = ['id' => -456];
            $this->assertFalse($service->handleIncomingUpdate($update));
        }
    }

    public function test_empty_allowlists_and_malformed_updates_are_rejected(): void
    {
        $service = new TelegramService;
        foreach ([[], ['message' => 'invalid'], ['message' => ['text' => []]]] as $update) {
            $this->assertFalse($service->handleIncomingUpdate($update));
        }
        config(['services.telegram.allowed_user_ids' => []]);
        $this->assertFalse($service->handleIncomingUpdate($this->update('/stats')));
        config(['services.telegram.allowed_user_ids' => ['123'], 'services.telegram.allowed_chat_ids' => []]);
        $this->assertFalse($service->handleIncomingUpdate($this->update('/pending')));
    }

    public function test_bots_invalid_ids_and_non_text_payloads_are_rejected(): void
    {
        $service = new TelegramService;
        foreach ([['from', 'is_bot', true], ['from', 'id', ['123']], ['from', 'id', '123junk'],
            ['chat', 'id', ['-456']], ['chat', 'type', 'channel']] as [$section, $field, $value]) {
            $update = $this->update('/stats');
            $update['message'][$section][$field] = $value;
            $this->assertFalse($service->handleIncomingUpdate($update));
        }
        foreach ([[], ' ', str_repeat('x', 4097)] as $text) {
            $update = $this->update();
            $update['message']['text'] = $text;
            $this->assertFalse($service->handleIncomingUpdate($update));
        }
    }

    public function test_rejected_webhook_commands_are_acknowledged_without_calling_ai(): void
    {
        $this->mock(AiCopilotService::class)->shouldNotReceive('ask');
        $update = $this->update('/ask internal data');
        $update['message']['chat']['id'] = -999;
        $this->withHeader('X-Telegram-Bot-Api-Secret-Token', 'test-webhook-secret')
            ->postJson('/api/telegram/webhook', $update)->assertOk()->assertExactJson(['status' => 'ok']);
    }

    public function test_authorized_commands_work_for_webhook_and_polling_and_have_a_shared_user_limit(): void
    {
        $copilot = $this->mock(AiCopilotService::class);
        $copilot->shouldReceive('ask')->with('summary', 'Admin')->times(10)->andReturn('Safe reply');
        $service = new class extends TelegramService {
            public array $replies = [];

            public function sendMessage(string $text, ?array $inlineKeyboard = null, ?string $targetChatId = null): bool
            {
                $this->replies[] = [$text, $targetChatId];

                return true;
            }
        };
        $this->app->instance(TelegramService::class, $service);
        $this->withHeader('X-Telegram-Bot-Api-Secret-Token', 'test-webhook-secret')
            ->postJson('/api/telegram/webhook', $this->update('/ask summary'))->assertOk();
        for ($i = 0; $i < 9; $i++) {
            $this->assertTrue($service->handleIncomingUpdate($this->update('/ask summary')));
        }
        config(['services.telegram.allowed_chat_ids' => ['-456', '-789']]);
        $otherChat = $this->update('/ask summary');
        $otherChat['message']['chat']['id'] = -789;
        $this->assertFalse($service->handleIncomingUpdate($otherChat));
        $this->assertFalse($service->handleIncomingUpdate($this->update('/ask summary')));
        $this->assertCount(10, $service->replies);
        $this->assertSame(['Safe reply', '-456'], $service->replies[0]);
        $this->travel(61)->seconds();
        $copilot->shouldReceive('ask')->with('after cooldown', 'Admin')->once()->andReturn('Ready');
        $this->assertTrue($service->handleIncomingUpdate($this->update('/ask after cooldown')));
    }
}
