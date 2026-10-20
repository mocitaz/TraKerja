<?php

namespace Tests\Feature;

use App\Models\SupportTicket;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_support_has_an_open_form_and_only_the_owners_conversations(): void
    {
        $owner = User::factory()->create();
        $ticket = SupportTicket::create([
            'user_id' => $owner->id, 'category' => 'technical_issue', 'subject' => 'Owned request',
            'message' => 'The calendar does not show my new schedule.', 'status' => 'replied',
            'admin_reply' => 'Please refresh the calendar.', 'replied_at' => now(),
        ]);
        SupportTicket::create([
            'user_id' => User::factory()->create()->id, 'category' => 'technical_issue',
            'subject' => 'Another owners private request', 'message' => 'Private account details.', 'status' => 'pending',
        ]);

        $response = $this->actingAs($owner)->get(route('support.index'))->assertOk()
            ->assertSee('images/support/trakerja-support-cutout.png', false)
            ->assertSee('Owned request')->assertSee('Please refresh the calendar.')
            ->assertDontSee('Another owners private request')
            ->assertDontSee('class="workspace-card"', false)->assertDontSee('class="support-guide"', false)
            ->assertSee(route('support.destroy', $ticket), false);
        $document = new \DOMDocument;
        @$document->loadHTML($response->getContent());
        $this->assertSame(1, $document->getElementsByTagName('h1')->length);
        foreach (['submit', 'history'] as $panel) {
            $this->assertSame('support-'.$panel, $document->getElementById('support-tab-'.$panel)->getAttribute('aria-controls'));
            $this->assertSame('support-tab-'.$panel, $document->getElementById('support-'.$panel)->getAttribute('aria-labelledby'));
        }
    }

    public function test_request_submission_keeps_existing_fields_and_returns_the_new_conversation(): void
    {
        $this->mock(TelegramService::class)->shouldReceive('sendSupportTicket')->once()->andReturn(false);
        $owner = User::factory()->create();
        $response = $this->actingAs($owner)->post(route('support.store'), [
            'category' => 'feature_request', 'subject' => 'Calendar improvement',
            'message' => 'Please let me view all selection stages on the calendar.',
        ])->assertSessionHasNoErrors()->assertRedirect(route('support.index'));
        $ticket = SupportTicket::where('user_id', $owner->id)->sole();
        $this->assertSame('Calendar improvement', $ticket->subject);
        $this->assertSame('pending', $ticket->status);
        $response->assertSessionHas('support_ticket_id', $ticket->id);
        $this->get(route('support.index'))->assertOk()->assertSee('Calendar improvement')
            ->assertSee('Your request was sent.');
    }

    public function test_invalid_fields_are_returned_and_other_users_cannot_delete_a_request(): void
    {
        $owner = User::factory()->create();
        $ticket = SupportTicket::create([
            'user_id' => $owner->id, 'category' => 'general_feedback', 'subject' => 'Saved request',
            'message' => 'Please keep this request available.', 'status' => 'pending',
        ]);
        $other = User::factory()->create();
        $this->actingAs($other)->from(route('support.index'))->post(route('support.store'), [
            'category' => 'unknown', 'subject' => '', 'message' => 'short',
        ])->assertSessionHasErrors(['category', 'subject', 'message']);
        $this->get(route('support.index'))->assertOk()->assertSee('Please check the highlighted fields');
        $this->delete(route('support.destroy', $ticket))->assertForbidden();
        $this->assertDatabaseHas('support_tickets', ['id' => $ticket->id]);
    }
}
