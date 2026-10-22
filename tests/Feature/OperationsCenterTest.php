<?php

namespace Tests\Feature;

use App\Http\Middleware\EnforceAccountRestriction;
use App\Livewire\Admin\OperationsCenter;
use App\Models\OperationEvent;
use App\Models\OperationIncident;
use App\Models\OperationRestriction;
use App\Models\OperationServiceCheck;
use App\Models\Payment;
use App\Models\User;
use App\Services\Operations\ServiceMonitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use Symfony\Component\Mailer\Transport\Smtp\SmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;
use Tests\TestCase;

class OperationsCenterTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_page_and_mutations_require_an_administrator(): void
    {
        $this->get('/admin/operations-center')->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->get('/admin/operations-center')->assertForbidden();
        Livewire::test(OperationsCenter::class)->assertForbidden();
        $this->actingAs($this->admin())->get('/admin/operations-center')->assertOk()->assertSee('Operations Center')->assertSee('Incident queue');
    }

    public function test_incident_creation_assignment_resolution_and_reopening_have_actor_history(): void
    {
        $admin = $this->admin();
        $member = User::factory()->create();
        $component = Livewire::actingAs($admin)->test(OperationsCenter::class)->call('newIncident')
            ->set('title', 'Gemini metadata is failing')->set('description', 'The model metadata endpoint cannot be reached.')
            ->set('severity', 'critical')->set('service', 'gemini')->set('assignedTo', $admin->id)->set('affectedUser', $member->id)
            ->call('createIncident')->assertHasNoErrors()->assertSee('Incident details');
        $incident = OperationIncident::firstOrFail();
        $this->assertSame($admin->id, $incident->assigned_to);
        $component->set('status', 'resolved')->set('note', 'Provider credentials corrected and connection verified.')->call('updateIncident')->assertHasNoErrors();
        $this->assertNotNull($incident->fresh()->resolved_at);
        $component->set('status', 'investigating')->set('note', 'Failure has returned; investigation reopened.')->call('updateIncident');
        $this->assertNull($incident->fresh()->resolved_at);
        $this->assertSame(3, OperationEvent::where('incident_id', $incident->id)->where('actor_id', $admin->id)->count());
        $this->assertSame('resolved', OperationEvent::latest('id')->first()->changes['before']['status']);
    }

    public function test_invalid_assignee_and_missing_note_cannot_change_an_incident(): void
    {
        $admin = $this->admin();
        $member = User::factory()->create();
        Livewire::actingAs($admin)->test(OperationsCenter::class)->call('newIncident')->set('title', 'Payment issue')->set('description', 'The payment callback failed validation.')
            ->set('assignedTo', $member->id)->call('createIncident')->assertHasErrors('assignedTo');
        $incident = $this->incident();
        Livewire::actingAs($admin)->test(OperationsCenter::class)->call('openIncident', $incident->id)->set('status', 'resolved')->call('updateIncident')->assertHasErrors('note');
        $this->assertSame('open', $incident->fresh()->status);
    }

    public function test_account_action_requires_confirmation_and_reason_and_revokes_api_tokens(): void
    {
        $admin = $this->admin();
        $member = User::factory()->create();
        $member->createToken('extension', ['account:read']);
        $component = Livewire::actingAs($admin)->test(OperationsCenter::class)->call('openAccount', $member->id)
            ->call('applyAccountAction')->assertHasErrors(['note', 'confirmed']);
        $this->assertDatabaseCount('operation_restrictions', 0);
        $component->set('note', 'Repeated spam was verified by support.')->set('confirmed', true)->call('applyAccountAction')->assertHasNoErrors();
        $this->assertSame(0, $member->tokens()->count());
        $this->assertTrue(OperationRestriction::blocks($member, 'account'));
        $this->assertSame('account_restrict', OperationEvent::first()->action);
        $this->assertTrue(OperationEvent::first()->created_at->isSameMinute(now()));
        $this->assertSame(1, OperationEvent::first()->changes['tokens_revoked']);
        $component->set('note', 'A duplicate active restriction is rejected.')->set('confirmed', true)->call('applyAccountAction')->assertHasErrors('scope');
        $this->assertDatabaseCount('operation_restrictions', 1);
    }

    public function test_release_is_owner_scoped_and_only_releases_the_selected_restriction(): void
    {
        $admin = $this->admin();
        $member = User::factory()->create();
        $other = User::factory()->create();
        $account = $this->restriction($member);
        $api = $this->restriction($member, 'api');
        $foreign = $this->restriction($other);
        Livewire::actingAs($admin)->test(OperationsCenter::class)->call('openAccount', $member->id)->call('prepareAccountAction', 'release', $foreign->id)->assertNotFound();
        Livewire::actingAs($admin)->test(OperationsCenter::class)->call('openAccount', $member->id)->call('prepareAccountAction', 'release', $account->id)
            ->set('note', 'Review completed; restore browser access.')->set('confirmed', true)->call('applyAccountAction')->assertHasNoErrors();
        $this->assertFalse(OperationRestriction::blocks($member, 'account'));
        $this->assertTrue(OperationRestriction::blocks($member, 'api'));
        $this->assertNull($api->fresh()->released_at);
        $this->assertSame($admin->id, $account->fresh()->released_by);
    }

    public function test_admin_accounts_cannot_be_targeted_and_locked_target_cannot_be_changed(): void
    {
        $admin = $this->admin();
        Livewire::actingAs($admin)->test(OperationsCenter::class)->call('openAccount', $admin->id)->assertStatus(422);
        $this->expectException(CannotUpdateLockedPropertyException::class);
        Livewire::actingAs($admin)->test(OperationsCenter::class)->set('memberId', $admin->id);
    }

    public function test_restriction_applies_to_existing_browser_and_api_requests_but_logout_is_allowed(): void
    {
        $member = User::factory()->create();
        $this->restriction($member);
        $this->actingAs($member)->get('/profile')->assertForbidden()->assertSee('Account access restricted');
        Route::middleware(['web', EnforceAccountRestriction::class])->get('/restriction-test', fn () => 'allowed');
        $this->getJson('/restriction-test')->assertForbidden()->assertJsonPath('message', 'Your account access is temporarily restricted. Please contact support.');
        $this->post('/logout')->assertRedirect('/');
    }

    public function test_api_only_restriction_blocks_extension_login_and_existing_tokens_without_blocking_profile(): void
    {
        $member = User::factory()->create(['password' => 'test-password']);
        $token = $member->createToken('extension', ['account:read'])->plainTextToken;
        $this->restriction($member, 'api');
        $this->postJson('/api/extension/login', ['email' => $member->email, 'password' => 'test-password'])->assertForbidden();
        $this->withToken($token)->getJson('/api/user')->assertForbidden();
        $this->actingAs($member)->get('/profile')->assertOk();
    }

    public function test_expired_and_released_restrictions_do_not_block_access(): void
    {
        $member = User::factory()->create();
        $this->restriction($member)->update(['expires_at' => now()->subMinute()]);
        $this->restriction($member)->update(['released_at' => now()]);
        $this->assertFalse(OperationRestriction::blocks($member, 'account'));
        $this->actingAs($member)->get('/profile')->assertOk();
    }

    public function test_explicit_token_revocation_records_reason_without_creating_a_restriction(): void
    {
        $member = User::factory()->create();
        $member->createToken('extension');
        Livewire::actingAs($this->admin())->test(OperationsCenter::class)->call('openAccount', $member->id)->call('prepareAccountAction', 'revoke_api')
            ->set('note', 'Member reported an unknown extension device.')->set('confirmed', true)->call('applyAccountAction')->assertHasNoErrors();
        $this->assertSame(0, $member->tokens()->count());
        $this->assertDatabaseCount('operation_restrictions', 0);
        $this->assertSame('account_revoke_api', OperationEvent::first()->action);
    }

    public function test_service_checks_are_real_bounded_redacted_and_not_run_on_page_load(): void
    {
        Http::preventStrayRequests();
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response(['name' => 'models/test'])]);
        config(['services.gemini.key' => 'test-secret', 'services.gemini.model' => 'test']);
        $component = Livewire::actingAs($this->admin())->test(OperationsCenter::class)->call('selectTab', 'services')->assertSee('No check has been run');
        Http::assertNothingSent();
        $component->call('checkServiceNow', 'gemini')->assertHasNoErrors();
        $this->assertSame('healthy', OperationServiceCheck::first()->status);
        $this->assertTrue(OperationServiceCheck::first()->created_at->isSameMinute(now()));
        $this->assertStringNotContainsString('test-secret', OperationServiceCheck::first()->toJson());
        $component->call('checkServiceNow', 'gemini')->assertHasErrors('monitor');
        Http::assertSentCount(1);
    }

    public function test_failed_check_never_stores_remote_body_or_exception_secret(): void
    {
        Http::fake(['*' => Http::response(['error' => 'secret-should-not-escape'], 401)]);
        config(['services.gemini.key' => 'test-secret']);
        $check = app(ServiceMonitor::class)->check('gemini', null);
        $this->assertSame('degraded', $check->status);
        $this->assertStringNotContainsString('secret-should-not-escape', $check->toJson());
        $this->assertStringContainsString('401', $check->summary);
    }

    public function test_unconfigured_services_and_no_transaction_are_not_marked_healthy(): void
    {
        Http::preventStrayRequests();
        config(['services.gemini.key' => '', 'mail.default' => 'array', 'pakasir.api_key' => 'key', 'pakasir.slug' => 'test']);
        $monitor = app(ServiceMonitor::class);
        $this->assertSame('unconfigured', $monitor->check('gemini', null)->status);
        $this->assertSame('unconfigured', $monitor->check('email', null)->status);
        $this->assertSame('unchecked', $monitor->check('payment', null)->status);
    }

    public function test_storage_probe_removes_only_its_own_file(): void
    {
        Storage::fake('local');
        config(['filesystems.default' => 'local']);
        Storage::disk('local')->put('member-cv.pdf', 'keep');
        $this->assertSame('healthy', app(ServiceMonitor::class)->check('storage', null)->status);
        $this->assertSame(['member-cv.pdf'], Storage::disk('local')->allFiles());
    }

    public function test_incident_context_is_kept_when_reviewing_an_affected_account(): void
    {
        $member = User::factory()->create();
        $incident = $this->incident();
        $incident->update(['affected_user_id' => $member->id]);
        Livewire::actingAs($this->admin())->test(OperationsCenter::class)
            ->call('openIncident', $incident->id)->call('openAccount', $member->id)
            ->assertSet('linkedIncident', $incident->id)->assertSet('memberId', $member->id);
    }

    public function test_summary_filters_and_service_incident_link_use_actual_records(): void
    {
        $incident = $this->incident();
        $incident->update(['severity' => 'critical']);
        OperationServiceCheck::create(['service' => 'gemini', 'status' => 'degraded', 'summary' => 'Connection check failed.', 'duration_ms' => 20]);
        Livewire::actingAs($this->admin())->test(OperationsCenter::class)->call('showSummary', 'critical')->assertSet('filter', 'unresolved')->assertSet('severityFilter', 'critical')
            ->call('newIncident', 'gemini')->assertSet('service', 'gemini')->assertSet('description', fn ($value) => str_contains($value, 'Connection check failed.'));
    }

    public function test_payment_check_reads_status_without_creating_or_mutating_a_transaction(): void
    {
        config(['pakasir.api_key' => 'test-key', 'pakasir.slug' => 'test-project', 'pakasir.sandbox' => true]);
        $member = User::factory()->create();
        $payment = Payment::create(['user_id' => $member->id, 'order_id' => 'ops-payment', 'customer_name' => 'QA Member', 'customer_email' => $member->email, 'customer_phone' => '0800000000', 'amount' => 9999, 'status' => 'PENDING', 'pakasir_txn_id' => 'txn-ops', 'pakasir_is_sandbox' => true]);
        Http::fake(['app.pakasir.com/*' => Http::sequence()->push(['txn_id' => 'txn-ops', 'status' => 'pending'])->push(['txn_id' => 'different', 'status' => 'pending'])]);
        $this->assertSame('healthy', app(ServiceMonitor::class)->check('payment', null)->status);
        Http::assertSent(fn ($request) => $request->method() === 'GET' && str_contains($request->url(), '/transaction-status/test-project/txn-ops'));
        $this->assertSame('PENDING', $payment->fresh()->status);
        $this->assertSame('degraded', app(ServiceMonitor::class)->check('payment', null)->status);
    }

    public function test_smtp_probe_opens_and_closes_a_connection_without_sending_a_message(): void
    {
        config(['mail.default' => 'smtp', 'mail.mailers.smtp.host' => 'smtp.example.test']);
        $stream = new SocketStream;
        $transport = \Mockery::mock(SmtpTransport::class);
        $transport->shouldReceive('getStream')->once()->andReturn($stream);
        $transport->shouldReceive('start')->once();
        $transport->shouldReceive('stop')->once();
        $mailer = \Mockery::mock(Mailer::class);
        $mailer->shouldReceive('getSymfonyTransport')->once()->andReturn($transport);
        Mail::shouldReceive('mailer')->once()->andReturn($mailer);
        $this->assertSame('healthy', app(ServiceMonitor::class)->check('email', null)->status);
    }

    public function test_expiry_validation_and_old_component_requests_cannot_bypass_admin_access(): void
    {
        $admin = $this->admin();
        $member = User::factory()->create();
        $component = Livewire::actingAs($admin)->test(OperationsCenter::class)->call('openAccount', $member->id)
            ->set('note', 'A past expiry must not be accepted.')->set('confirmed', true)->set('expiresAt', now()->subMinute()->format('Y-m-d H:i'))
            ->call('applyAccountAction')->assertHasErrors('expiresAt');
        $this->assertDatabaseCount('operation_restrictions', 0);
        $this->actingAs($member);
        $component->call('newIncident')->assertForbidden();
    }

    private function incident(): OperationIncident
    {
        return OperationIncident::create(['title' => 'Payment callback issue', 'description' => 'A provider callback could not be processed.', 'severity' => 'high', 'status' => 'open', 'service' => 'payment']);
    }

    private function restriction(User $member, string $scope = 'account'): OperationRestriction
    {
        return OperationRestriction::create(['user_id' => $member->id, 'scope' => $scope, 'reason' => 'Verified abuse report.']);
    }
}
