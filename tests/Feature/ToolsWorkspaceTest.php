<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolsWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_support_history_is_scoped_to_the_signed_in_user(): void
    {
        $owner = User::factory()->create();
        SupportTicket::create(['user_id' => $owner->id, 'category' => 'technical_issue', 'subject' => 'Owned request', 'message' => 'Steps to reproduce the problem.', 'status' => 'pending']);
        SupportTicket::create(['user_id' => $owner->id, 'category' => 'general_feedback', 'subject' => 'Answered request', 'message' => 'A description of the issue.', 'status' => 'replied', 'admin_reply' => 'Here is the team reply.']);
        SupportTicket::create(['user_id' => User::factory()->create()->id, 'category' => 'technical_issue', 'subject' => 'Private other request', 'message' => 'Other user content.', 'status' => 'pending']);
        $this->actingAs($owner)->get(route('support.index'))->assertOk()
            ->assertSee('Owned request')->assertSee('Here is the team reply.')
            ->assertDontSee('Private other request')->assertDontSee('support-overview', false)
            ->assertSee('support-delete-title')->assertDontSee('return confirm(', false);
    }

    public function test_successful_request_opens_its_history_entry_without_sending_external_notifications(): void
    {
        $owner = User::factory()->create();
        $this->mock(\App\Services\TelegramService::class, function ($mock) {
            $mock->shouldReceive('sendSupportTicket')->once()->andReturn(true);
        });
        $this->actingAs($owner)->post(route('support.store'), [
            'category' => 'technical_issue', 'subject' => 'Cannot save application',
            'message' => 'Saving the application returns an error after completing all fields.',
        ])->assertRedirect(route('support.index'))->assertSessionHas('support_ticket_id');
        $ticket = SupportTicket::where('user_id', $owner->id)->firstOrFail();
        $this->assertSame($ticket->id, session('support_ticket_id'));
        $response = $this->get(route('support.index'))->assertOk();
        $document = new \DOMDocument();
        @$document->loadHTML($response->getContent());
        $xpath = new \DOMXPath($document);
        $this->assertSame(1, $xpath->query('//details[contains(@class, "workspace-ticket") and @open]')->length);
    }

    public function test_support_validation_keeps_the_original_fields_for_correction(): void
    {
        $this->actingAs(User::factory()->create())->from(route('support.index'))
            ->post(route('support.store'), ['category' => 'technical_issue', 'subject' => 'Needs help', 'message' => 'short'])
            ->assertRedirect(route('support.index'))->assertSessionHasErrors('message')
            ->assertSessionHasInput('subject', 'Needs help');
        $this->assertDatabaseCount('support_tickets', 0);
    }

    public function test_automation_preserves_entitlement_and_provides_template_before_upload(): void
    {
        $owner = User::factory()->create(['is_premium' => false, 'payment_status' => 'free']);
        Setting::set('monetization_enabled', true);
        $this->actingAs($owner)->get(route('automation.index', ['tab' => 'csv']))
            ->assertOk()->assertSee('Explore Premium')->assertDontSee('id="csvImportForm"', false);
        Setting::set('monetization_enabled', false);
        $this->get(route('automation.index', ['tab' => 'csv']))->assertOk()
            ->assertSee('Download CSV template')->assertSee('automation-mobile-preview')
            ->assertSee('Importing a file again adds new applications.');
        $this->get(route('automation.index'))->assertOk()->assertSee('Copy address')
            ->assertSee('Extension-tagged records')->assertSee('not a connection status.');
    }
}
