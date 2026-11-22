<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KawanKerjaStreamTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_stream(): void
    {
        $response = $this->post(route('kawankerja.stream'), ['message' => 'Hello']);
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_stream_route(): void
    {
        $user = User::factory()->create();

        Http::fake([
            'api.openai.com/*' => Http::response(
                "data: {\"choices\": [{\"index\": 0, \"delta\": {\"content\": \"Halo! Saya siap membantu.\"}, \"finish_reason\": null}]}\n\ndata: [DONE]\n\n",
                200,
                ['Content-Type' => 'text/event-stream']
            ),
        ]);

        $response = $this->actingAs($user)->post(route('kawankerja.stream'), [
            'message' => 'Halo HireMate',
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('text/event-stream', $response->headers->get('Content-Type'));
    }
}
