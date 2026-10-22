<?php

namespace Tests\Feature;

use App\Livewire\Admin\Analytics;
use App\Models\{AiAnalyzerResult, AiPhoto, CoverLetter, CvTemplate, JobApplication, SupportTicket, User, UserActivity};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use Tests\TestCase;

class AdminAnalyticsWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(now('Asia/Jakarta')->setDate(2026,10,22)->setTime(15,0));
        $this->actingAs(User::factory()->create(['role'=>'admin']));
    }

    private function event(?User $user, string $type = 'login', string $status = 'success', $created = null): void
    {
        UserActivity::forceCreate(['user_id'=>$user?->id,'activity_type'=>$type,'status'=>$status,'description'=>'Synthetic analytics event','created_at'=>$created ?? now()]);
    }

    public function test_activity_uses_event_dates_instead_of_account_age_or_profile_completion(): void
    {
        $old = User::factory()->create(['created_at'=>now()->subYear()]);
        User::factory()->create();
        $this->event($old); $this->event($old);
        $this->event(auth()->user());
        $this->event(null);
        Livewire::test(Analytics::class)->call('setPeriod','7')->assertSet('report.stats.totalUsers',1)->assertSet('report.stats.activeUsers',1)
            ->call('showDetails','active')->assertViewHas('records', fn($records)=>$records->modelKeys()===[$old->id]);
    }

    public function test_period_boundaries_exclude_older_and_future_records_and_admin_accounts(): void
    {
        $start = now()->subDays(6)->startOfDay();
        $member=User::factory()->create(['created_at'=>$start]);
        User::factory()->create(['created_at'=>$start->copy()->subSecond()]);
        User::factory()->create(['created_at'=>now()->addDay()]);
        $this->event($member,'cv_export','success',$start);
        $this->event($member,'csv_export','failed');
        $this->event(auth()->user(),'csv_export');
        $this->event(null,'csv_export');
        Livewire::test(Analytics::class)->call('setPeriod','7')->assertSet('report.stats.totalUsers',1)->assertSet('report.stats.totalExports',2)
            ->assertSet('userRegistrationByDay.data.0',1)->assertSet('userGrowth.total.6',2);
    }

    public function test_application_statuses_partition_totals_and_detail_search_stays_in_the_chosen_bucket(): void
    {
        $member=User::factory()->create();
        JobApplication::factory()->create(['user_id'=>$member->id,'company_name'=>'Alpha QA','application_status'=>'Accepted']);
        JobApplication::factory()->create(['user_id'=>$member->id,'company_name'=>'Beta QA','application_status'=>'Rejected']);
        JobApplication::factory()->create(['user_id'=>auth()->id(),'company_name'=>'Admin QA','application_status'=>'Accepted']);
        $component=Livewire::test(Analytics::class)->assertSet('report.stats.totalJobApplications',2);
        $this->assertSame(2,array_sum(array_column($component->get('report.statuses'),'total')));
        $component->call('showDetails','applications','application_status','Accepted')->assertViewHas('records',fn($rows)=>$rows->total()===1)->assertSee('Alpha QA')->assertDontSee('Admin QA')
            ->set('detailSearch','Beta QA')->assertViewHas('records',fn($rows)=>$rows->total()===0)->assertSee('No matching records');
    }

    public function test_current_membership_is_explicitly_independent_of_registration_period(): void
    {
        User::factory()->create(['is_premium'=>true,'payment_status'=>'paid','premium_until'=>now()->addDay(),'created_at'=>now()->subYear()]);
        User::factory()->create(['is_premium'=>true,'payment_status'=>'paid','premium_until'=>now()->subDay()]);
        User::factory()->create(['is_premium'=>true,'payment_status'=>'free']);
        Livewire::test(Analytics::class)->call('setPeriod','7')->assertSet('report.membership.total',3)->assertSet('report.membership.premium',1)->assertSee('Current membership across all registration dates.');
    }

    public function test_details_paginate_and_reset_when_search_or_record_type_changes(): void
    {
        User::factory()->count(12)->create();
        $component=Livewire::test(Analytics::class)->call('showDetails','members')->assertViewHas('records',fn($rows)=>$rows->total()===12 && $rows->perPage()===10)
            ->call('setPage',2,'analyticsDetailsPage')->assertViewHas('records',fn($rows)=>$rows->currentPage()===2)
            ->set('detailSearch','no-such-member')->assertViewHas('records',fn($rows)=>$rows->currentPage()===1 && $rows->total()===0)
            ->call('showDetails','exports')->assertSet('detailSearch','')->assertSet('detailType','exports')
            ->call('closeDetails')->assertSet('detailsOpen',false)->assertViewHas('records',null);
    }

    public function test_refresh_reads_new_data_and_invalid_periods_return_to_the_default(): void
    {
        $component=Livewire::test(Analytics::class)->assertSet('report.stats.totalUsers',0);
        User::factory()->create();
        $component->call('loadAnalytics')->assertSet('report.stats.totalUsers',1)->assertSet('reportRevision',2)
            ->call('setPeriod','invalid')->assertSet('periodFilter','30');
    }

    public function test_empty_report_does_not_claim_zero_reply_time_or_a_peak_hour(): void
    {
        Livewire::test(Analytics::class)->assertSet('report.support.avg_resolution_hrs',null)->assertSet('report.hours.peak_hour',null)->assertSee('No replies yet')->assertSee('No recorded activity')->assertSee('No application records in this period.');
    }

    public function test_reply_averages_preserve_fractional_hours(): void
    {
        SupportTicket::forceCreate(['user_id'=>User::factory()->create()->id,'category'=>'technical_issue','subject'=>'QA reply','message'=>'QA question','status'=>'replied','created_at'=>now()->subMinutes(30),'replied_at'=>now()]);
        Livewire::test(Analytics::class)->assertSet('report.support.avg_resolution_hrs',0.5);
    }

    public function test_product_record_modals_are_searchable_without_exposing_private_payloads(): void
    {
        $user=User::factory()->create();
        AiAnalyzerResult::create(['user_id'=>$user->id,'resume_file_name'=>'Analyst QA.pdf','job_description'=>'PRIVATE-PROMPT','analysis_result'=>['private'=>'PRIVATE-RESULT']]);
        AiPhoto::create(['user_id'=>$user->id,'type'=>'enhance','original_photo_name'=>'Portrait QA.jpg']);
        CoverLetter::create(['user_id'=>$user->id,'company_name'=>'Company QA','job_title'=>'Role QA','content'=>'PRIVATE-LETTER']);
        CvTemplate::create(['user_id'=>$user->id,'name'=>'CV QA','template_key'=>'professional']);
        $component=Livewire::test(Analytics::class);
        foreach(['analysis'=>'Analyst QA','photos'=>'Portrait QA','letters'=>'Company QA','templates'=>'CV QA'] as $type=>$search) {
            $component->assertSet('report.'.$type,1)->call('showDetails',$type)->set('detailSearch',$search)
                ->assertViewHas('records',fn($rows)=>$rows->total()===1)->assertSee($search)
                ->assertDontSee('PRIVATE-PROMPT')->assertDontSee('PRIVATE-RESULT')->assertDontSee('PRIVATE-LETTER');
        }
    }

    public function test_modal_filter_columns_are_allowlisted(): void
    {
        Livewire::test(Analytics::class)->call('showDetails','applications','user_id','1')->assertStatus(422);
    }

    public function test_detail_type_cannot_be_changed_by_client_property_tampering(): void
    {
        $this->expectException(CannotUpdateLockedPropertyException::class);
        Livewire::test(Analytics::class)->set('detailType','support');
    }

    public function test_members_cannot_mount_analytics_even_outside_the_route(): void
    {
        Livewire::actingAs(User::factory()->create())->test(Analytics::class)->assertForbidden();
    }
}
