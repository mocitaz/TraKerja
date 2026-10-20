<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureUserIsPremium;
use App\Livewire\Admin\UserManagement;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use App\Services\PaymentSettlementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PremiumEntitlementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(now()->setDate(2026, 10, 8)->setTime(12, 0));
        config(['pakasir.slug' => 'test-merchant', 'pakasir.premium_duration_days' => 30]);
        Mail::fake();
        Http::preventStrayRequests();
        Setting::set('monetization_enabled', true);
    }

    private function premiumUser(array $attributes = []): User
    {
        $user = User::factory()->create(['is_premium' => true, 'payment_status' => 'paid']);
        $user->forceFill(array_replace(['premium_until' => now()->addDays(10)], $attributes))->save();
        return $user->fresh();
    }

    #[DataProvider('inactiveMemberships')]
    public function test_inactive_memberships_do_not_grant_premium_access(array $attributes): void
    {
        $user = $this->premiumUser($attributes);
        $this->assertFalse($user->isPremium());
        $this->assertFalse($user->canAccessFeature('goals'));
        $this->assertFalse(Setting::canAccess('goals', $user));
        $this->assertSame(5, $user->getFeatureLimit('cv_exports'));
        $this->assertSame(5, $user->getRemainingExports());
    }

    public static function inactiveMemberships(): array
    {
        return [
            'expired' => [['premium_until' => '2026-10-07 12:00:00']],
            'exact expiry' => [['premium_until' => '2026-10-08 12:00:00']],
            'unpaid' => [['payment_status' => 'free']],
            'revoked' => [['is_premium' => false]],
        ];
    }

    public function test_active_and_legacy_indefinite_memberships_keep_access(): void
    {
        foreach ([now()->addSecond(), null] as $expiry) {
            $user = $this->premiumUser(['premium_until' => $expiry]);
            $this->assertTrue($user->isPremium());
            $this->assertTrue(Setting::canAccess('goals', $user));
            $this->assertSame('unlimited', $user->getRemainingExports());
        }
    }

    public function test_expired_member_is_redirected_by_premium_middleware(): void
    {
        $user = $this->premiumUser(['premium_until' => now()->subSecond()]);
        $this->actingAs($user);
        $response = app(EnsureUserIsPremium::class)->handle(Request::create('/premium-only'), fn () => new Response('allowed'));
        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(route('payment.index'), $response->headers->get('Location'));
    }

    #[DataProvider('renewalExpiries')]
    public function test_verified_renewal_saves_expiry_and_does_not_extend_on_replay(string $oldExpiry, string $expected): void
    {
        $user = $this->premiumUser(['premium_until' => $oldExpiry]);
        $payment = Payment::create(['user_id' => $user->id, 'order_id' => 'PREMIUM-RENEWAL', 'amount' => 199000,
            'status' => 'PENDING', 'notes' => 'premium', 'pakasir_txn_id' => 'txn-renewal', 'pakasir_is_sandbox' => false,
            'customer_name' => $user->name, 'customer_email' => $user->email, 'customer_phone' => '0800000000']);
        $detail = ['project' => 'test-merchant', 'order_id' => $payment->order_id, 'amount' => 199000,
            'status' => 'completed', 'txn_id' => 'txn-renewal', 'is_sandbox' => false];
        $this->assertTrue(app(PaymentSettlementService::class)->settle($payment, $detail));
        $this->assertSame($expected, (string) $user->fresh()->premium_until);
        $this->assertTrue($user->fresh()->isPremium());
        $this->assertFalse(app(PaymentSettlementService::class)->settle($payment, $detail));
        $this->assertSame($expected, (string) $user->fresh()->premium_until);
    }

    public static function renewalExpiries(): array
    {
        return [
            'active preserves remaining' => ['2026-10-18 12:00:00', '2026-11-17 12:00:00'],
            'expired starts now' => ['2026-10-07 12:00:00', '2026-11-07 12:00:00'],
        ];
    }

    public function test_expiry_cannot_be_mass_assigned_by_account_updates(): void
    {
        $user = $this->premiumUser();
        $user->update(['name' => 'Updated', 'premium_until' => '2099-01-01 00:00:00']);
        $this->assertSame('2026-10-18 12:00:00', (string) $user->fresh()->premium_until);
    }

    public function test_manual_admin_grant_creates_a_dated_active_membership(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);
        $user = User::factory()->create();
        $this->actingAs($admin);
        app(UserManagement::class)->togglePremium($user->id);
        $this->assertTrue($user->fresh()->isPremium());
        $this->assertSame('2026-11-07 12:00:00', (string) $user->fresh()->premium_until);
    }
    public function test_admin_edit_grants_premium_with_matching_status_and_expiry(): void
    {
        $user = User::factory()->create();
        $component = app(UserManagement::class);
        $component->editingUserId = $user->id;
        $component->editName = $user->name;
        $component->editEmail = $user->email;
        $component->editIsPremium = true;
        $component->editIsAdmin = false;
        $component->updateUser();
        $this->assertTrue($user->fresh()->isPremium());
        $this->assertSame('2026-11-07 12:00:00', (string) $user->fresh()->premium_until);
    }

    public function test_admin_profile_edit_does_not_renew_or_pay_an_inactive_membership(): void
    {
        foreach (['paid', 'expired', 'free'] as $status) {
            $user = $this->premiumUser(['premium_until' => now()->subDays(2), 'payment_status' => $status]);
            $expiry = (string) $user->premium_until;
            $component = app(UserManagement::class);
            $component->editUser($user->id);
            $component->editName = 'Updated profile';
            $component->updateUser();
            $this->assertSame($expiry, (string) $user->fresh()->premium_until);
            $this->assertSame($status, $user->fresh()->payment_status);
            $this->assertFalse($user->fresh()->isPremium());
        }
    }

    public function test_admin_confirmation_grants_a_dated_membership_and_revokes_access(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);
        $user = User::factory()->create();
        $this->actingAs($admin);
        $component = app(UserManagement::class);
        $component->confirmTargetUserId = $user->id;
        $component->toggleManualPremium();
        $this->assertTrue($user->fresh()->isPremium());
        $this->assertSame('2026-11-07 12:00:00', (string) $user->fresh()->premium_until);
        $component->confirmTargetUserId = $user->id;
        $component->toggleManualPremium();
        $this->assertFalse($user->fresh()->isPremium());
        $this->assertNull($user->fresh()->premium_until);
    }

    public function test_created_admin_premium_membership_has_an_expiry(): void
    {
        $component = new class extends UserManagement {
            public $newAdminPassword_confirmation = 'secure-password';
        };
        $component->newAdminName = 'New Admin';
        $component->newAdminEmail = 'admin@example.test';
        $component->newAdminPassword = 'secure-password';
        $component->newAdminPasswordConfirmation = 'secure-password';
        $component->createAdmin();
        $user = User::where('email', 'admin@example.test')->firstOrFail();
        $this->assertTrue($user->isPremium());
        $this->assertSame('2026-11-07 12:00:00', (string) $user->premium_until);
    }

    public function test_expired_member_can_open_the_premium_purchase_page(): void
    {
        $user = $this->premiumUser(['premium_until' => now()->subSecond()]);
        $this->actingAs($user)->get(route('payment.premium'))->assertOk()->assertViewIs('payment.index');
    }

}
