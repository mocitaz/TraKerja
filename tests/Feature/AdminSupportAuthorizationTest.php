<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\AdminSupportController;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminSupportAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public static function roleAndAction(): array
    {
        $cases = [];
        foreach (['guest', 'user', 'premium', 'admin'] as $role) {
            foreach (['read', 'reply', 'status', 'delete'] as $action) {
                $cases[$role.' '.$action] = [$role, $action];
            }
        }

        return $cases;
    }

    #[DataProvider('roleAndAction')]
    public function test_admin_support_http_access_matrix(string $role, string $action): void
    {
        $owner = User::factory()->create();
        $ticket = SupportTicket::create(['user_id' => $owner->id, 'category' => 'general_feedback',
            'subject' => 'Private ticket', 'message' => 'Private support content', 'status' => 'pending']);
        if ($role !== 'guest') {
            $this->actingAs(User::factory()->create([
                'role' => $role === 'admin' ? 'admin' : 'user',
                'is_premium' => $role === 'premium',
                'payment_status' => $role === 'premium' ? 'paid' : 'free',
            ]));
        }

        $response = match ($action) {
            'read' => $this->get('/admin/feedbacks'),
            'reply' => $this->post('/admin/feedbacks/'.$ticket->id.'/reply', ['admin_reply' => 'Admin response', 'status' => 'replied']),
            'status' => $this->patch('/admin/feedbacks/'.$ticket->id.'/status', ['status' => 'completed']),
            'delete' => $this->delete('/admin/feedbacks/'.$ticket->id),
        };

        if ($role === 'admin') {
            if ($action === 'read') {
                $response->assertOk()->assertSee('Private support content');
            } else {
                $response->assertRedirect(route('admin.feedbacks.index'));
                if ($action === 'delete') {
                    $this->assertDatabaseMissing('support_tickets', ['id' => $ticket->id]);
                } else {
                    $this->assertSame($action === 'reply' ? 'replied' : 'completed', $ticket->fresh()->status);
                    if ($action === 'reply') {
                        $this->assertSame('Admin response', $ticket->fresh()->admin_reply);
                    }
                }
            }
        } else {
            $role === 'guest' ? $response->assertRedirect('/login') : $response->assertForbidden();
            $this->assertSame('pending', $ticket->fresh()->status);
            $this->assertNull($ticket->fresh()->admin_reply);
        }
    }

    public static function controllerActions(): array
    {
        return [['index'], ['reply'], ['updateStatus'], ['destroy']];
    }

    #[DataProvider('controllerActions')]
    public function test_controller_itself_rejects_regular_user(string $action): void
    {
        $owner = User::factory()->create();
        $this->actingAs(User::factory()->create());
        $ticket = SupportTicket::create(['user_id' => $owner->id, 'category' => 'general_feedback',
            'subject' => 'Private ticket', 'message' => 'Private content', 'status' => 'pending']);
        $request = Request::create('/admin/feedbacks', 'POST', ['admin_reply' => 'Unauthorized reply', 'status' => 'completed']);
        $this->expectException(AuthorizationException::class);
        $controller = new AdminSupportController;
        match ($action) {
            'index' => $controller->index($request),
            'reply' => $controller->reply($request, $ticket),
            'updateStatus' => $controller->updateStatus($request, $ticket),
            'destroy' => $controller->destroy($ticket),
        };
    }

    public function test_revoked_admin_cannot_mutate_ticket_after_opening_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = SupportTicket::create(['user_id' => User::factory()->create()->id,
            'category' => 'general_feedback', 'subject' => 'Private ticket',
            'message' => 'Private content', 'status' => 'pending']);
        $this->actingAs($admin)->get('/admin/feedbacks')->assertOk();
        $admin->update(['role' => 'user', 'is_admin' => false]);
        $this->actingAs($admin->fresh())->patch('/admin/feedbacks/'.$ticket->id.'/status', ['status' => 'completed'])->assertForbidden();
        $this->assertSame('pending', $ticket->fresh()->status);
    }

    public function test_persistent_admin_middleware_rejects_livewire_after_role_revocation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/admin/settings')->assertOk();
        preg_match_all('/wire:snapshot="([^"]+)"/', $response->getContent(), $matches);
        $snapshot = null;
        foreach ($matches[1] as $encoded) {
            $json = html_entity_decode($encoded, ENT_QUOTES);
            if (json_decode($json, true)['memo']['name'] === 'admin.global-settings') {
                $snapshot = $json;
            }
        }
        $this->assertNotNull($snapshot, 'Settings component snapshot must be present');
        $admin->update(['role' => 'user', 'is_admin' => false]);
        $this->actingAs($admin->fresh())->postJson('/livewire/update', [
            'components' => [['snapshot' => $snapshot, 'updates' => [],
                'calls' => [['path' => '', 'method' => 'updateIdentity', 'params' => []]]]],
        ])->assertForbidden();
    }
}
