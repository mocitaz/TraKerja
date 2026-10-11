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
        $this->post(route('admin.email-blast.preview'), ['email_type'=>'brand_transformation'])->assertOk()->assertSee('Pembaruan Antarmuka TraKerja');
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
}
