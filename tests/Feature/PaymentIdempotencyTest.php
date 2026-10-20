<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PaymentIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Payment $payment;

    private array $transaction;

    protected function setUp(): void
    {
        parent::setUp();
        config(['pakasir.slug' => 'test-merchant', 'pakasir.api_key' => 'test-key',
            'pakasir.webhook_secret' => 'test-webhook-secret', 'pakasir.sandbox' => false]);
        $this->withHeader('X-Secret', 'test-webhook-secret');
        Mail::fake();
        Http::preventStrayRequests();
        $this->user = User::factory()->create(['ai_credits' => 0, 'cl_credits' => 0, 'photo_credits' => 0]);
        $this->payment = Payment::create([
            'user_id' => $this->user->id, 'order_id' => 'IDEMPOTENCY-1', 'amount' => 14999,
            'pakasir_txn_id' => 'txn-idempotency', 'pakasir_is_sandbox' => false,
            'status' => 'PENDING', 'notes' => 'addon_10', 'customer_name' => $this->user->name,
            'customer_email' => $this->user->email, 'customer_phone' => '0800000000',
        ]);
        $this->transaction = ['project' => 'test-merchant', 'order_id' => $this->payment->order_id,
            'amount' => 14999, 'status' => 'completed', 'txn_id' => 'txn-idempotency', 'is_sandbox' => false];
        Http::fake(['app.pakasir.com/*' => Http::response($this->transaction)]);
    }

    public static function unsuccessfulTransitions(): array
    {
        return [['markAsFailed'], ['markAsCanceled'], ['markAsExpired'], ['markAsWaiting']];
    }

    #[DataProvider('unsuccessfulTransitions')]
    public function test_success_is_terminal_even_for_a_stale_model(string $method): void
    {
        $stale = Payment::findOrFail($this->payment->id);
        $this->payment->markAsSuccess();
        $stale->$method();

        $this->assertSame('SUCCESS', $this->payment->fresh()->status);
        $this->assertNotNull($this->payment->fresh()->paid_at);
    }

    public function test_webhook_records_unique_benefits_and_does_not_regrant_after_status_reset(): void
    {
        $this->postJson('/payment/webhook', $this->transaction)->assertOk();
        $this->assertTrue(Schema::hasTable('payment_grants'));
        $this->assertDatabaseCount('payment_grants', 3);

        // A raw external write bypasses model guards; the ledger must still protect credits.
        DB::table('payments')->where('id', $this->payment->id)->update(['status' => 'FAILED']);
        $this->postJson('/payment/webhook', $this->transaction)->assertOk();

        $this->assertSame(10, $this->user->fresh()->ai_credits);
        $this->assertSame(15, $this->user->fresh()->cl_credits);
        $this->assertSame(5, $this->user->fresh()->photo_credits);
        $this->assertSame('SUCCESS', $this->payment->fresh()->status);
        $this->assertDatabaseCount('payment_grants', 3);
        Mail::assertSentCount(1);
    }

    public function test_sync_and_webhook_race_uses_the_same_settlement_guard(): void
    {
        $duringLookup = false;
        Http::fake(function () use (&$duringLookup) {
            if (! $duringLookup) {
                $duringLookup = true;
                app(\App\Services\PaymentSettlementService::class)->settle($this->payment, $this->transaction);
            }

            return Http::response($this->transaction);
        });

        $this->artisan('payments:sync-pending')->assertExitCode(0);

        $this->assertSame(10, $this->user->fresh()->ai_credits);
        $this->assertTrue(Schema::hasTable('payment_grants'));
        $this->assertDatabaseCount('payment_grants', 3);
    }

    public function test_direct_model_update_cannot_downgrade_success(): void
    {
        $stale = Payment::findOrFail($this->payment->id);
        $this->payment->markAsSuccess();
        $this->expectException(\LogicException::class);
        $stale->update(['status' => 'FAILED']);
    }

    public function test_database_rejects_duplicate_grant_for_same_payment_and_benefit(): void
    {
        $this->postJson('/payment/webhook', $this->transaction)->assertOk();
        $this->expectException(QueryException::class);
        DB::table('payment_grants')->insert(['payment_id' => $this->payment->id,
            'benefit' => 'ai_credits', 'quantity' => 10, 'granted_at' => now()]);
    }

    public function test_grant_failure_rolls_back_payment_credits_and_ledger(): void
    {
        $event = 'eloquent.creating: App\\Models\\PaymentGrant';
        Event::listen($event, function ($grant) {
            if ($grant->benefit === 'cl_credits') {
                throw new \RuntimeException('Synthetic ledger failure');
            }
        });
        try {
            $this->postJson('/payment/webhook', $this->transaction)->assertStatus(500);
        } finally {
            Event::forget($event);
        }

        $this->assertSame('PENDING', $this->payment->fresh()->status);
        $this->assertSame(0, $this->user->fresh()->ai_credits);
        $this->assertSame(0, $this->user->fresh()->cl_credits);
        $this->assertSame(0, $this->user->fresh()->photo_credits);
        $this->assertDatabaseCount('payment_grants', 0);
        Mail::assertNothingSent();
        $this->postJson('/payment/webhook', $this->transaction)->assertOk();
        $this->assertSame(10, $this->user->fresh()->ai_credits);
    }

    public function test_migration_marks_historical_success_without_granting_again(): void
    {
        $this->payment->markAsSuccess();
        $migration = require database_path('migrations/2026_10_08_000001_create_payment_grants_table.php');
        $migration->down();
        $migration->up();
        $this->assertDatabaseHas('payment_grants', ['payment_id' => $this->payment->id, 'benefit' => 'legacy_settlement']);

        DB::table('payments')->where('id', $this->payment->id)->update(['status' => 'FAILED']);
        $this->postJson('/payment/webhook', $this->transaction)->assertOk();
        $this->assertSame(0, $this->user->fresh()->ai_credits);
        $this->assertDatabaseCount('payment_grants', 1);
        Mail::assertNothingSent();
    }

    public function test_deleting_account_with_payment_preserves_existing_cascade_behavior(): void
    {
        $this->postJson('/payment/webhook', $this->transaction)->assertOk();
        $this->user->delete();
        $this->assertDatabaseMissing('payments', ['id' => $this->payment->id]);
        $this->assertDatabaseCount('payment_grants', 0);
    }

    public function test_migration_marks_old_downgraded_payment_with_paid_at(): void
    {
        $this->payment->markAsSuccess();
        DB::table('payments')->where('id', $this->payment->id)->update(['status' => 'FAILED']);
        $migration = require database_path('migrations/2026_10_08_000001_create_payment_grants_table.php');
        $migration->down();
        $migration->up();

        $this->assertDatabaseHas('payment_grants', ['payment_id' => $this->payment->id, 'benefit' => 'legacy_settlement']);
        $this->postJson('/payment/webhook', $this->transaction)->assertOk();
        $this->assertSame(0, $this->user->fresh()->ai_credits);
        $this->assertSame('SUCCESS', $this->payment->fresh()->status);
        Mail::assertNothingSent();
    }

    public function test_polling_then_webhook_grants_only_once(): void
    {
        $this->actingAs($this->user)->getJson('/payment/check-status/'.$this->payment->order_id)->assertOk();
        $this->postJson('/payment/webhook', $this->transaction)->assertOk();
        $this->assertSame(10, $this->user->fresh()->ai_credits);
        $this->assertDatabaseCount('payment_grants', 3);
        Mail::assertSentCount(1);
    }
}
