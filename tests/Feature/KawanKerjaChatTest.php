<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KawanKerjaChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_kawankerja_endpoints(): void
    {
        $this->postJson(route('kawankerja.chat'), ['message' => 'Halo'])
            ->assertUnauthorized();

        $this->getJson(route('kawankerja.history'))
            ->assertUnauthorized();

        $this->postJson(route('kawankerja.clear'))
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_chat_and_receive_reply(): void
    {
        config(['services.gemini.key' => 'fake-test-key']);

        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Halo! Saya KawanKerja. Ada yang bisa dibantu?']
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson(route('kawankerja.chat'), [
                'message' => 'Halo KawanKerja',
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'reply' => 'Halo! Saya KawanKerja. Ada yang bisa dibantu?',
        ]);

        // Verify history endpoint returns the conversation
        $historyRes = $this->actingAs($user)->getJson(route('kawankerja.history'));
        $historyRes->assertOk();
        $this->assertCount(2, $historyRes->json('history'));
    }

    public function test_user_can_clear_chat_history(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->withSession([
            'kawankerja_history' => [
                ['role' => 'user', 'content' => 'Hai'],
                ['role' => 'model', 'content' => 'Halo'],
            ]
        ]);

        $response = $this->actingAs($user)->postJson(route('kawankerja.clear'));
        $response->assertOk();
        $response->assertJson(['success' => true]);

        $historyRes = $this->actingAs($user)->getJson(route('kawankerja.history'));
        $this->assertEmpty($historyRes->json('history'));
    }

    public function test_chat_validates_message_presence_and_length(): void
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user)->postJson(route('kawankerja.chat'), [
            'message' => '',
        ]);
        $res->assertUnprocessable();

        $oversized = str_repeat('a', 1001);
        $resOver = $this->actingAs($user)->postJson(route('kawankerja.chat'), [
            'message' => $oversized,
        ]);
        $resOver->assertUnprocessable();
    }

    public function test_assistant_bubble_includes_copy_action_attributes(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('kawankerja-copy-btn');
    }

    public function test_chat_view_includes_action_card_routes_and_deep_navigation_support(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('actionRoutes');
        $response->assertSee('kawankerja-action-card');
    }
}
