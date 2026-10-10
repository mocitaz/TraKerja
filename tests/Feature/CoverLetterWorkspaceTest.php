<?php
namespace Tests\Feature;

use App\Models\CoverLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoverLetterWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_sees_actual_letter_and_actions_without_executing_content(): void
    {
        $user = User::factory()->create();
        $letter = CoverLetter::create(['user_id' => $user->id, 'job_title' => 'Actual engineer', 'company_name' => 'Actual company', 'tone' => 'professional', 'language' => 'en', 'content' => "Dear team,\n\n<script>bad()</script>\nThank you."]);
        $this->actingAs($user)->get(route('cover-letters.show', $letter))->assertOk()
            ->assertSee('Actual engineer')->assertSee('Actual company')->assertSee('English')
            ->assertSee('Download TXT')->assertSee('Print letter')->assertSee('Copy letter')
            ->assertSee('&lt;script&gt;bad()&lt;/script&gt;', false)->assertDontSee('<script>bad()</script>', false);
        $this->assertSame("Dear team,\n\n<script>bad()</script>\nThank you.", $letter->fresh()->content);
    }

    public function test_guest_and_other_account_cannot_read_letter(): void
    {
        $owner = User::factory()->create();
        $letter = CoverLetter::create(['user_id' => $owner->id, 'job_title' => 'Engineer', 'company_name' => 'Company', 'tone' => 'professional', 'language' => 'id', 'content' => 'Private content']);
        $this->get(route('cover-letters.show', $letter))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('cover-letters.show', $letter))->assertForbidden();
        $this->actingAs($owner)->get(route('cover-letters.show', $letter))->assertOk()->assertSee('Indonesian');
    }
}
