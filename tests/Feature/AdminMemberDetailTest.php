<?php

namespace Tests\Feature;

use App\Livewire\Admin\UserDetail;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class AdminMemberDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_metrics_cover_the_full_history_while_the_table_is_limited_to_eight(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create();
        JobApplication::factory()->count(12)->create(['user_id' => $member->id, 'application_status' => 'Accepted', 'status' => 'Accepted', 'application_date' => now()->subDays(3)]);
        JobApplication::factory()->count(8)->create(['user_id' => $member->id, 'application_status' => 'Rejected', 'status' => 'Rejected', 'application_date' => now()]);
        Livewire::actingAs($admin)->test(UserDetail::class, ['user' => $member])
            ->assertViewHas('totalApplications', 20)
            ->assertViewHas('acceptedApplications', 12)
            ->assertViewHas('declinedApplications', 8)
            ->assertViewHas('inProgressApplications', 0)
            ->assertViewHas('recentApplications', fn ($rows) => $rows->count() === 8 && $rows->every(fn ($row) => $row->application_status === 'Rejected'));
    }

    public function test_profile_and_quota_forms_validate_and_save_independently(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['ai_credits' => 2]);
        $component = Livewire::actingAs($admin)->test(UserDetail::class, ['user' => $member])
            ->set('email', $admin->email)->call('updateUser')->assertHasErrors('email');
        $this->assertNotSame($admin->email, $member->fresh()->email);
        $component->set('email', $member->email)->set('name', 'Updated member')->call('updateUser')->assertHasNoErrors();
        $this->assertSame('Updated member', $member->fresh()->name);
        $this->assertSame(2, $member->fresh()->ai_credits);
        $component->set('ai_credits', -1)->call('saveAiQuotas')->assertHasErrors('ai_credits');
        $component->set('ai_credits', 7)->call('saveAiQuotas')->assertHasNoErrors();
        $this->assertSame(7, $member->fresh()->ai_credits);
    }

    public function test_confirmation_can_be_cancelled_and_self_deletion_remains_blocked(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Livewire::actingAs($admin)->test(UserDetail::class, ['user' => $admin])
            ->set('showPremiumConfirmModal', true)->set('showPremiumConfirmModal', false)
            ->call('confirmDelete')->assertSet('showDeleteConfirmModal', true)
            ->call('closeDeleteConfirm')->assertSet('showDeleteConfirmModal', false)
            ->call('performDelete')->assertSet('showDeleteConfirmModal', false);
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_email_templates_use_existing_mail_classes_and_reject_unknown_templates(): void
    {
        Mail::fake();
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create();
        $component = Livewire::actingAs($admin)->test(UserDetail::class, ['user' => $member]);
        $templates = [
            'welcome' => \App\Mail\WelcomeMail::class,
            'verification_reminder' => \App\Mail\EmailVerificationReminderMail::class,
            'job_reminder' => \App\Mail\JobApplicationReminderMail::class,
            'monthly_motivation' => \App\Mail\MonthlyMotivationMail::class,
            're_engagement' => \App\Mail\ReEngagementMail::class,
            'product_update' => \App\Mail\ProductUpdateMail::class,
            'new_vibe' => \App\Mail\NewVibeMail::class,
            'ai_analyzer' => \App\Mail\AiAnalyzerFreeTrialAnnouncementMail::class,
            'ai_photo' => \App\Mail\AiPhotoAnnouncementMail::class,
            'follow_up_feature' => \App\Mail\FollowUpFeatureAnnouncementMail::class,
            'premium_granted' => \App\Mail\PremiumGrantedMail::class,
            'hiring_season' => \App\Mail\HiringSeasonAlertMail::class,
            'kemerdekaan_ri' => \App\Mail\KemerdekaanRiMail::class,
            'pahlawan' => \App\Mail\PahlawanMail::class,
            'hari_ibu' => \App\Mail\HariIbuMail::class,
            'natal' => \App\Mail\NatalMail::class,
            'tahun_baru_islam' => \App\Mail\TahunBaruIslamMail::class,
        ];
        foreach ($templates as $type => $class) {
            $component->call('openSendEmailModal')->set('emailType', $type)->call('sendEmail')->assertHasNoErrors()->assertSet('showSendEmailModal', false);
            Mail::assertSent($class, fn ($mail) => $mail->hasTo($member->email));
        }
        $component->set('emailType', 'invalid-template')->call('sendEmail')->assertHasErrors('emailType');
        Mail::assertSentCount(count($templates));
    }

    public function test_detail_page_keeps_admin_access_requirements(): void
    {
        $member = User::factory()->create();
        $this->get(route('admin.users.show', $member))->assertRedirect(route('login'));
        $this->actingAs($member)->get(route('admin.users.show', $member))->assertForbidden();
    }
}
