<?php

namespace Tests\Feature;

use App\Models\EmailBlastLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminEmailWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_preview_renders_custom_content_without_sending(): void
    {
        Mail::fake(); Notification::fake();
        $this->actingAs(User::factory()->create(['role'=>'admin']));
        $this->get(route('admin.email-blast'))->assertOk();
        $this->post(route('admin.email-blast.preview'), ['email_type'=>'custom','custom_subject'=>'Workspace update','custom_content'=>'A new chapter.'])->assertOk()->assertSee('A new chapter.');
        $this->post(route('admin.email-blast.preview'), ['email_type'=>'verification'])->assertOk();
        $this->post(route('admin.email-blast.preview'), ['email_type'=>'brand_transformation'])->assertOk()->assertSee(__('emails.brand_transformation.heading'));
        Mail::assertNothingSent(); Notification::assertNothingSent();
        $this->assertDatabaseCount('email_blast_logs', 0);
    }

    public function test_audience_preview_uses_active_entitlement_and_excludes_admins(): void
    {
        Mail::fake(); $this->actingAs(User::factory()->create(['role'=>'admin']));
        $active = User::factory()->create(['is_premium'=>true,'payment_status'=>'paid','premium_until'=>now()->addDays(5)]);
        $expired = User::factory()->create(['is_premium'=>true,'payment_status'=>'paid','premium_until'=>now()->subDay()]);
        $this->postJson(route('admin.email-blast.init'), ['email_type'=>'welcome','target_user'=>'premium'])->assertOk()->assertJsonPath('total',1)->assertJsonPath('users.0.id',$active->id);
        $this->postJson(route('admin.email-blast.init'), ['email_type'=>'welcome','target_user'=>'free'])->assertOk()->assertJsonPath('total',1)->assertJsonPath('users.0.id',$expired->id);
        Mail::assertNothingSent();
    }

    public function test_history_filters_campaigns_and_reports_success_counts(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']));
        EmailBlastLog::create(['email_type'=>'welcome','target_audience'=>'all','total_target'=>10,'success_count'=>8,'failed_count'=>2]);
        EmailBlastLog::create(['email_type'=>'custom','target_audience'=>'free','total_target'=>3,'success_count'=>3,'failed_count'=>0]);
        $this->get(route('admin.email-blast.history',['audience'=>'free']))->assertOk()->assertViewHas('logs',fn ($logs)=>$logs->total()===1)->assertViewHas('totalSuccess',11);
    }
    public function test_history_filters_outcomes_and_searches_recorded_template_and_subject(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $complete = EmailBlastLog::create(['email_type' => 'custom', 'template_name' => 'Weekly check-in', 'subject' => 'One small step', 'language' => 'id', 'target_audience' => 'all', 'total_target' => 4, 'success_count' => 4, 'failed_count' => 0]);
        EmailBlastLog::create(['email_type' => 'welcome', 'target_audience' => 'new', 'total_target' => 4, 'success_count' => 2, 'failed_count' => 1, 'failed_details' => [['email' => 'member@example.test', 'error' => '<script>alert(1)</script>']]]);
        $this->get(route('admin.email-blast.history', ['search' => 'small step']))->assertOk()
            ->assertViewHas('logs', fn ($logs) => $logs->total() === 1 && $logs->first()->id === $complete->id)
            ->assertSee('Weekly check-in')->assertViewHas('totalSuccess', 6);
        foreach (['complete', 'issues', 'partial'] as $status) {
            $this->get(route('admin.email-blast.history', ['status' => $status]))->assertOk()->assertViewHas('logs', fn ($logs) => $logs->total() === 1);
        }
        $this->get(route('admin.email-blast.history', ['status' => 'issues']))->assertOk()->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_campaign_log_keeps_message_details_and_rejects_impossible_totals(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $data = ['email_type' => 'custom', 'template_name' => 'Check-in', 'subject' => 'Next steps', 'language' => 'id', 'banner' => 'next-step', 'target_audience' => 'all', 'total_target' => 3, 'success_count' => 1, 'failed_count' => 1];
        $this->postJson(route('admin.email-blast.store-log'), $data)->assertOk();
        $this->assertDatabaseHas('email_blast_logs', ['subject' => 'Next steps', 'language' => 'id', 'banner' => 'next-step']);
        $this->postJson(route('admin.email-blast.store-log'), array_replace($data, ['success_count' => 3, 'failed_count' => 1]))->assertUnprocessable()->assertJsonValidationErrors('total_target');
        $this->assertDatabaseCount('email_blast_logs', 1);
    }

}
