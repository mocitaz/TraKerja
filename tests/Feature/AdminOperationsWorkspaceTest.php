<?php

namespace Tests\Feature;

use App\Livewire\Admin\MonetizationControl;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\SupportTicket;
use App\Models\SatisfactionSurvey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminOperationsWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_six_operations_pages_render_for_admin(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']));
        foreach (['/admin/feedbacks','/admin/survey'] as $url) {
            $this->get($url)->assertOk()->assertSee('av-workspace');
        }
        $this->get('/admin/scraper')->assertNotFound();
        $this->get('/admin/settings?tab=unknown')->assertOk()->assertSee('as-workspace');
        $this->get('/admin/payments')->assertOk()->assertSee('bc-workspace');
        $this->get('/admin/monetization')->assertRedirect(route('admin.payments', ['view' => 'plans']));
    }

    public function test_ticket_search_keeps_filters_in_pagination(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']));
        $member=User::factory()->create();
        foreach (range(1,16) as $i) SupportTicket::create(['user_id'=>$member->id,'category'=>'technical_issue','subject'=>'Upload problem '.$i,'message'=>'Cannot upload my CV.','status'=>'pending']);
        SupportTicket::create(['user_id'=>$member->id,'category'=>'general_feedback','subject'=>'Other topic','message'=>'Something else.','status'=>'completed']);
        $this->get('/admin/feedbacks?search=Upload&status=pending')->assertOk()->assertViewHas('tickets',fn($tickets)=>$tickets->total()===16)->assertSee('search=Upload')->assertDontSee('Other topic');
    }

    public function test_survey_shows_real_scores_and_all_response_details(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']));
        $response=['user_id'=>User::factory()->create()->id,'feedback'=>'Make mobile navigation clearer'];
        foreach(['q1_overall','q2_navigation','q3_speed','q4_cv_builder','q5_ai_analyzer','q6_job_tracker','q7_cover_letter','q8_interviews','q9_premium','q10_recommend','q11_design','q12_cv_templates'] as $field) $response[$field]=4;
        SatisfactionSurvey::create($response);
        $this->get('/admin/survey')->assertOk()->assertViewHas('avgQ1Overall','4.0')->assertSee('Make mobile navigation clearer')->assertSee('Recommending TraKerja');
    }

    public function test_payment_search_and_export_show_the_same_orders(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']));
        $member=User::factory()->create();
        foreach (['MATCHED-ORDER','OTHER-ORDER'] as $order) {
            Payment::create(['user_id'=>$member->id,'order_id'=>$order,'amount'=>14999,'status'=>'SUCCESS','customer_name'=>$member->name,'customer_email'=>$member->email,'customer_phone'=>'0800000000']);
        }
        $this->get('/admin/payments?search=MATCHED')->assertOk()->assertViewHas('payments',fn($payments)=>$payments->total()===1)->assertSee('MATCHED-ORDER')->assertDontSee('OTHER-ORDER');
        $export=$this->get('/admin/payments/export?search=MATCHED')->assertOk()->streamedContent();
        $this->assertStringContainsString('MATCHED-ORDER',$export);
        $this->assertStringNotContainsString('OTHER-ORDER',$export);
    }

    public function test_mode_selection_is_not_saved_before_confirmation(): void
    {
        Setting::set('monetization_enabled',false);
        $admin=User::factory()->create(['role'=>'admin']);
        $component=Livewire::actingAs($admin)->test(MonetizationControl::class)->call('toggleMonetization',true)->assertSet('showConfirmation',true)->assertSee('Switch to premium access?');
        $this->assertFalse((bool)Setting::get('monetization_enabled',false));
        $component->call('cancelToggle')->assertSet('showConfirmation',false);
        $this->assertFalse((bool)Setting::get('monetization_enabled',false));
    }
}
