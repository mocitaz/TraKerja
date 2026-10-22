<?php

namespace Tests\Feature;

use App\Models\SatisfactionSurvey;
use App\Models\Setting;
use App\Models\SupportTicket;
use App\Models\User;
use App\Support\SurveyQuestions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMemberVoiceTest extends TestCase
{
    use RefreshDatabase;

    private function ticket(array $attributes = []): SupportTicket
    {
        return SupportTicket::create($attributes + ['user_id'=>User::factory()->create()->id, 'subject'=>'Cannot upload a CV', 'message'=>'Please help with the upload.', 'category'=>'technical_issue', 'status'=>'pending']);
    }

    private function response(int $rating, ?string $feedback = null): SatisfactionSurvey
    {
        $attributes = ['user_id'=>User::factory()->create()->id, 'feedback'=>$feedback];
        foreach (SurveyQuestions::sections() as $section) foreach ($section['questions'] as $field=>$question) $attributes[$field]=$rating;
        return SatisfactionSurvey::create($attributes);
    }

    public function test_ticket_search_finds_member_and_guest_email_and_respects_filters(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']));
        $member=User::factory()->create(['email'=>'matching-member@example.test']);
        $ticket=$this->ticket(['user_id'=>$member->id]);
        $guest=$this->ticket(['user_id'=>null,'guest_email'=>'matching-guest@example.test','guest_name'=>'Guest tester','status'=>'on_hold']);
        $this->get('/admin/feedbacks?search=matching-member')->assertOk()->assertViewHas('tickets',fn($rows)=>$rows->modelKeys()===[$ticket->id]);
        $this->get('/admin/feedbacks?search=matching-guest&status=on_hold')->assertOk()->assertViewHas('tickets',fn($rows)=>$rows->modelKeys()===[$guest->id]);
        $this->get('/admin/feedbacks?search=matching-guest&status=pending')->assertViewHas('tickets',fn($rows)=>$rows->isEmpty())->assertSee('No matching tickets found');
    }

    public function test_ticket_detail_escapes_content_and_requires_explicit_delete_submission(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']));
        $ticket=$this->ticket(['message'=>'<script>alert("test")</script>']);
        $this->get('/admin/feedbacks?ticket='.$ticket->id)->assertOk()->assertSee('Original message')->assertSee('Keep ticket')->assertSee('Delete permanently')->assertSee('&lt;script&gt;',false)->assertDontSee('<script>alert(',false);
        $this->assertModelExists($ticket);
        $this->delete(route('admin.feedbacks.destroy',$ticket))->assertRedirect(route('admin.feedbacks.index'));
        $this->assertModelMissing($ticket);
    }

    public function test_reply_validation_keeps_the_draft_and_correct_ticket_context(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin'])); $ticket=$this->ticket();
        $this->from('/admin/feedbacks')->post(route('admin.feedbacks.reply',$ticket),['admin_reply'=>'Bad','status'=>'replied','review_ticket'=>$ticket->id])->assertSessionHasErrors('admin_reply')->assertSessionHasInput('review_ticket',$ticket->id)->assertSessionHasInput('admin_reply','Bad');
        $this->assertNull($ticket->fresh()->admin_reply);
        $this->post(route('admin.feedbacks.reply',$ticket),['admin_reply'=>'Please retry your upload.','status'=>'replied'])->assertRedirect();
        $this->assertDatabaseHas('support_tickets',['id'=>$ticket->id,'status'=>'replied','admin_reply'=>'Please retry your upload.']);
        $this->patch(route('admin.feedbacks.status',$ticket),['status'=>'completed'])->assertRedirect();
        $this->assertSame('Please retry your upload.',$ticket->fresh()->admin_reply);
    }

    public function test_survey_filters_update_the_list_averages_and_distribution_together(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']));
        $positive=$this->response(5,'Clear mobile menus'); $this->response(1); $this->response(3,'Improve the export');
        $this->get('/admin/survey?score=5&comments=written')->assertOk()->assertViewHas('totalRespondents',1)->assertViewHas('avgQ1Overall','5.0')->assertViewHas('positiveRate',100.0)->assertViewHas('writtenCount',1)->assertViewHas('responses',fn($rows)=>$rows->modelKeys()===[$positive->id])->assertSee('Clear mobile menus')->assertDontSee('Improve the export');
        $this->get('/admin/survey?comments=none')->assertViewHas('totalRespondents',1)->assertViewHas('avgQ1Overall','1.0')->assertViewHas('writtenCount',0);
        $this->get('/admin/survey?search=Improve')->assertViewHas('totalRespondents',1)->assertViewHas('avgQ1Overall','3.0');
    }

    public function test_survey_modals_include_all_twelve_questions_and_empty_scores_are_honest(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']));
        $this->get('/admin/survey')->assertOk()->assertSee('No ratings')->assertSee('No survey responses yet');
        $this->response(4,'<b>Keep this as plain text</b>');
        $response=$this->get('/admin/survey')->assertOk()->assertSee('&lt;b&gt;Keep this as plain text&lt;/b&gt;',false)->assertSee('Enable survey invitations?',false);
        foreach(SurveyQuestions::sections() as $section) foreach($section['questions'] as $question) $response->assertSee($question[1]);
    }

    public function test_survey_setting_changes_only_on_submission_and_preserves_responses(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin'])); Setting::set('survey_enabled',false); $this->response(4);
        $this->get('/admin/survey')->assertOk()->assertSee('Enable survey invitations?');
        $this->assertFalse((bool)Setting::get('survey_enabled'));
        $this->post('/admin/survey/toggle',['enabled'=>true])->assertRedirect();
        $this->assertTrue((bool)Setting::get('survey_enabled'));
        $this->post('/admin/survey/toggle',['enabled'=>false])->assertRedirect();
        $this->assertDatabaseCount('satisfaction_surveys',1);
    }

    public function test_invalid_filters_are_rejected_without_changing_data(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']));
        $this->get('/admin/feedbacks?status=invalid')->assertSessionHasErrors('status');
        $this->get('/admin/survey?score=6')->assertSessionHasErrors('score');
        $this->get('/admin/survey?comments=invalid')->assertSessionHasErrors('comments');
    }

    public function test_members_cannot_review_or_change_admin_feedback_and_survey_data(): void
    {
        $ticket=$this->ticket(); $this->actingAs(User::factory()->create());
        $this->get('/admin/feedbacks')->assertForbidden(); $this->get('/admin/survey')->assertForbidden();
        $this->post(route('admin.feedbacks.reply',$ticket),['admin_reply'=>'Unauthorized reply','status'=>'replied'])->assertForbidden();
        $this->patch(route('admin.feedbacks.status',$ticket),['status'=>'completed'])->assertForbidden();
        $this->delete(route('admin.feedbacks.destroy',$ticket))->assertForbidden();
        $this->post('/admin/survey/toggle',['enabled'=>true])->assertForbidden();
        $this->assertModelExists($ticket); $this->assertNull($ticket->fresh()->admin_reply);
    }
}
