<?php

namespace Tests\Feature;

use App\Livewire\Admin\MonetizationControl;
use App\Models\{Payment, Setting, User};
use App\Services\YukkPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\TestCase;

class AdminBillingWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp(); Cache::flush();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    private function payment(array $attributes = []): Payment
    {
        $member = User::factory()->create();
        return Payment::create($attributes + ['user_id' => $member->id, 'order_id' => 'ORDER-'.$member->id, 'amount' => 10000, 'status' => 'SUCCESS', 'customer_name' => $member->name, 'customer_email' => $member->email, 'customer_phone' => '0800000000']);
    }

    public function test_payments_and_monetization_share_one_canonical_page_and_navigation_entry(): void
    {
        $response = $this->get(route('admin.payments'))->assertOk()->assertSee('billing-panel-transactions', false)->assertSee('billing-panel-plans', false)->assertSee('Premium base price')->assertDontSee('Feature Access Matrix');
        $dom = new \DOMDocument; @$dom->loadHTML($response->getContent()); $xpath = new \DOMXPath($dom);
        $this->assertSame(1, $xpath->query('//*[@id="admin-sidebar"]//a[@href="'.route('admin.payments').'"]')->length);
        $this->assertSame(0, $xpath->query('//*[@id="admin-sidebar"]//a[@href="'.route('admin.monetization').'"]')->length);
        $this->get(route('admin.monetization'))->assertRedirect(route('admin.payments', ['view' => 'plans']));
    }

    public function test_environment_status_totals_and_csv_follow_the_same_filters(): void
    {
        $live = $this->payment(['order_id' => 'LIVE-MATCH', 'pakasir_is_sandbox' => false, 'amount' => 12000]);
        $this->payment(['order_id' => 'SANDBOX-OTHER', 'pakasir_is_sandbox' => true, 'amount' => 90000]);
        $this->payment(['order_id' => 'UNKNOWN-OTHER', 'pakasir_is_sandbox' => null, 'amount' => 5000]);
        $this->payment(['order_id' => 'PENDING-OTHER', 'pakasir_is_sandbox' => false, 'status' => 'PENDING', 'amount' => 50000]);
        $filters = ['environment' => 'live', 'status' => 'SUCCESS'];
        $this->get(route('admin.payments', $filters))->assertOk()
            ->assertViewHas('payments', fn ($rows) => $rows->total() === 1 && $rows->first()->id === $live->id)
            ->assertViewHas('stats', fn ($stats) => $stats['total'] === 1 && $stats['success'] === 1 && $stats['total_revenue'] === 12000)
            ->assertSee('LIVE-MATCH')->assertDontSee('SANDBOX-OTHER');
        $csv = $this->get(route('admin.payments.export', $filters))->assertOk()->streamedContent();
        $this->assertStringContainsString('LIVE-MATCH', $csv); $this->assertStringNotContainsString('SANDBOX-OTHER', $csv);
        $this->get(route('admin.payments', ['environment' => 'unknown']))->assertOk()->assertViewHas('payments', fn ($rows) => $rows->total() === 1);
    }

    public function test_search_supports_customer_snapshots_and_pakasir_transaction_ids_and_dates_are_validated(): void
    {
        $payment = $this->payment(['customer_name' => 'Recorded buyer', 'pakasir_txn_id' => 'PK-SEARCH-KEY']);
        foreach (['Recorded buyer', 'PK-SEARCH-KEY'] as $search) $this->get(route('admin.payments', ['search' => $search]))->assertOk()->assertViewHas('payments', fn ($rows) => $rows->total() === 1 && $rows->first()->id === $payment->id);
        $this->get(route('admin.payments', ['date_to' => now()->format('Y-m-d')]))->assertOk();
        $this->get(route('admin.payments', ['date_from' => '2026-10-20', 'date_to' => '2026-10-19']))->assertSessionHasErrors('date_to');
        $this->get(route('admin.payments', ['per_page' => 100000]))->assertSessionHasErrors('per_page');
    }

    public function test_detail_exposes_review_fields_without_gateway_tokens_or_full_member_data(): void
    {
        $payment = $this->payment(['yukk_token' => 'private-gateway-token', 'pakasir_is_sandbox' => true]);
        $this->getJson(route('admin.payments.show', $payment->id))->assertOk()->assertJsonPath('payment.can_cancel', false)
            ->assertJsonPath('payment.pakasir_is_sandbox', true)->assertJsonMissingPath('payment.yukk_token')->assertJsonMissingPath('user.password')->assertJsonMissingPath('user.remember_token');
    }

    public function test_price_uses_checkout_default_and_preserves_existing_orders(): void
    {
        $payment = $this->payment(['amount' => 14999]);
        Livewire::test(MonetizationControl::class)->assertSet('premiumPrice', 9999)
            ->set('premiumPrice', -1)->call('updatePremiumPrice')->assertHasErrors('premiumPrice')
            ->set('premiumPrice', 1.5)->call('updatePremiumPrice')->assertHasErrors('premiumPrice')
            ->set('premiumPrice', 25000)->call('updatePremiumPrice')->assertHasNoErrors();
        $this->assertSame(25000, Setting::get('premium_price'));
        $this->assertSame(14999, $payment->fresh()->amount);
    }

    public function test_access_mode_requires_confirmation_and_paid_membership_counts_exclude_expired_accounts(): void
    {
        Setting::set('monetization_enabled', false);
        User::factory()->create(['is_premium' => true, 'payment_status' => 'paid', 'premium_until' => now()->addDay()]);
        User::factory()->create(['is_premium' => true, 'payment_status' => 'paid', 'premium_until' => now()->subDay()]);
        User::factory()->create(['is_premium' => true, 'payment_status' => 'unpaid', 'premium_until' => null]);
        $component = Livewire::test(MonetizationControl::class)->assertSet('premiumUsers', 1)->assertSet('totalUsers', 3);
        $component->call('confirmToggle'); $this->assertFalse(Setting::get('monetization_enabled'));
        $component->call('toggleMonetization', true)->assertSet('showConfirmation', true)->call('cancelToggle');
        $this->assertFalse(Setting::get('monetization_enabled'));
        $component->call('toggleMonetization', true)->call('confirmToggle')->assertSet('showConfirmation', false);
        $this->assertTrue(Setting::get('monetization_enabled'));
    }

    public function test_cancellation_keeps_existing_gateway_guards_and_never_cancels_settled_payments(): void
    {
        $settled = $this->payment(['yukk_transaction_code' => 'YUKK-SETTLED']);
        $this->postJson(route('admin.payments.cancel', $settled->id))->assertStatus(400);
        $pending = $this->payment(['status' => 'PENDING']);
        $this->postJson(route('admin.payments.cancel', $pending->id))->assertStatus(400);
        $pending->update(['yukk_transaction_code' => 'YUKK-PENDING']);
        $this->mock(YukkPaymentService::class, fn ($mock) => $mock->shouldReceive('cancelVATransaction')->once()->with('YUKK-PENDING')->andReturn(['success' => true]));
        $this->postJson(route('admin.payments.cancel', $pending->id))->assertOk()->assertJsonPath('success', true);
        $this->assertSame('CANCELED', $pending->fresh()->status); $this->assertSame('SUCCESS', $settled->fresh()->status);
    }

    public function test_members_cannot_access_billing_data_or_plan_settings(): void
    {
        $payment = $this->payment(); $this->actingAs(User::factory()->create());
        $this->get(route('admin.payments'))->assertForbidden(); $this->getJson(route('admin.payments.show', $payment->id))->assertForbidden();
        Livewire::test(MonetizationControl::class)->assertForbidden();
    }
}
