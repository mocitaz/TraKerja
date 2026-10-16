<?php

namespace Tests\Feature;

use App\Jobs\ProcessAiPhoto;
use App\Models\AiPhoto;
use App\Models\Setting;
use App\Models\User;
use App\Services\AiPhotoProcessingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AiPhotoProcessingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        Storage::fake('local');
        Http::preventStrayRequests();
        Setting::set('monetization_enabled', true);
    }

    private function submit(User $user, ?string $key = null): AiPhoto
    {
        return app(AiPhotoProcessingService::class)->submit($user, UploadedFile::fake()->image('portrait.png'), ['type' => 'remove_bg'], $key ?? (string) Str::uuid());
    }

    public function test_http_submission_reserves_credit_and_returns_queued_request_without_provider_call(): void
    {
        Http::fake(['*' => Http::response(['error' => 'provider must not be called'], 500)]);
        $user = User::factory()->create(['photo_credits' => 1]);
        $this->actingAs($user)->post('/ai-photo/process', ['photo' => UploadedFile::fake()->image('portrait.png'), 'type' => 'remove_bg', 'request_key' => (string) Str::uuid()])
            ->assertRedirect('/ai-photo/1');
        $this->assertDatabaseHas('ai_photos', ['user_id' => $user->id, 'status' => 'queued', 'credit_state' => 'reserved']);
        $this->assertSame(0, $user->fresh()->photo_credits);
        Http::assertNothingSent();
        Queue::assertPushed(ProcessAiPhoto::class, 1);
    }

    public function test_studio_async_photo_submission_returns_the_owned_result_url(): void
    {
        $user = User::factory()->create(['photo_credits' => 1]);
        $this->actingAs($user)->postJson('/ai-photo/process', ['photo' => UploadedFile::fake()->image('portrait.png'), 'type' => 'remove_bg', 'request_key' => (string) Str::uuid()])
            ->assertAccepted()->assertJsonPath('success', true)->assertJsonPath('status', 'queued')->assertJsonPath('url', route('ai-photo.show', 1));
        $this->assertSame(0, $user->fresh()->photo_credits);
        Http::assertNothingSent();
    }

    public function test_duplicate_submission_and_stale_user_cannot_reserve_twice(): void
    {
        $user = User::factory()->create(['photo_credits' => 1]);
        $key = (string) Str::uuid();
        $first = $this->submit($user, $key);
        $this->assertSame($first->id, $this->submit($user, $key)->id);
        $this->actingAs($user)->postJson('/ai-photo/process', ['photo' => UploadedFile::fake()->image('other.png'), 'type' => 'enhance', 'request_key' => (string) Str::uuid()])->assertUnprocessable();
        $this->assertDatabaseCount('ai_photos', 1);
        $this->assertSame(0, $user->fresh()->photo_credits);
        Queue::assertPushed(ProcessAiPhoto::class, 1);
    }

    public function test_worker_settles_success_once_and_deletes_private_source(): void
    {
        $photo = $this->submit(User::factory()->create(['photo_credits' => 1]));
        Http::fake(['*' => Http::response(['success' => true, 'result_url' => 'https://example.test/result.png'])]);
        $job = new ProcessAiPhoto($photo->id);
        $job->handle(app(AiPhotoProcessingService::class));
        $job->handle(app(AiPhotoProcessingService::class));
        $this->assertDatabaseHas('ai_photos', ['id' => $photo->id, 'status' => 'completed', 'credit_state' => 'consumed', 'result_url' => 'https://example.test/result.png']);
        Http::assertSentCount(1);
        Storage::disk('local')->assertMissing($photo->source_path);
        $this->assertSame(0, $photo->user->fresh()->photo_credits);
    }

    public function test_provider_failure_refunds_once_and_explicit_retry_uses_new_reservation(): void
    {
        $user = User::factory()->create(['photo_credits' => 1]);
        $photo = $this->submit($user);
        Http::fake(['*' => Http::response(['error' => 'unavailable'], 503)]);
        $job = new ProcessAiPhoto($photo->id);
        $job->handle(app(AiPhotoProcessingService::class));
        $job->failed(new \RuntimeException('duplicate failure'));
        $this->assertDatabaseHas('ai_photos', ['id' => $photo->id, 'status' => 'failed', 'credit_state' => 'refunded']);
        $this->assertSame(1, $user->fresh()->photo_credits);
        $key = (string) Str::uuid();
        $this->actingAs($user)->post('/ai-photo/'.$photo->id.'/retry', ['request_key' => $key])->assertRedirect('/ai-photo/2');
        $this->post('/ai-photo/'.$photo->id.'/retry', ['request_key' => $key])->assertRedirect('/ai-photo/2');
        $this->assertDatabaseCount('ai_photos', 2);
        $this->assertSame(0, $user->fresh()->photo_credits);
        Http::assertSentCount(1);
    }

    public function test_result_persistence_failure_refunds_the_reservation(): void
    {
        $user = User::factory()->create(['photo_credits' => 1]);
        $photo = $this->submit($user);
        Http::fake(['*' => Http::response(['success' => true, 'result_url' => 'https://example.test/result.png'])]);
        AiPhoto::saving(function (AiPhoto $model) {
            if ($model->status === 'completed') throw new \RuntimeException('simulated result write failure');
        });
        try {
            (new ProcessAiPhoto($photo->id))->handle(app(AiPhotoProcessingService::class));
            $this->assertSame(1, $user->fresh()->photo_credits);
            $this->assertDatabaseHas('ai_photos', ['id' => $photo->id, 'status' => 'failed', 'credit_state' => 'refunded']);
        } finally {
            AiPhoto::flushEventListeners();
        }
    }

    public function test_abandoned_processing_is_refunded_without_another_provider_call(): void
    {
        $user = User::factory()->create(['photo_credits' => 1]);
        $photo = $this->submit($user);
        $photo->forceFill(['status' => 'processing', 'processing_token' => (string) Str::uuid(), 'processing_expires_at' => now()->subMinute()])->save();
        $this->artisan('ai-photos:recover')->assertSuccessful();
        $this->artisan('ai-photos:recover')->assertSuccessful();
        $this->assertSame(1, $user->fresh()->photo_credits);
        $this->assertDatabaseHas('ai_photos', ['id' => $photo->id, 'status' => 'failed']);
        Http::assertNothingSent();
    }

    public function test_unmetered_request_never_refunds_a_credit_it_did_not_reserve(): void
    {
        Setting::set('monetization_enabled', false);
        $user = User::factory()->create(['photo_credits' => 0]);
        $photo = $this->submit($user);
        Setting::set('monetization_enabled', true);
        Http::fake(['*' => Http::response([], 500)]);
        (new ProcessAiPhoto($photo->id))->handle(app(AiPhotoProcessingService::class));
        $this->assertSame(0, $user->fresh()->photo_credits);
        $this->assertDatabaseHas('ai_photos', ['id' => $photo->id, 'credit_state' => 'none', 'status' => 'failed']);
    }

    public function test_status_and_retry_are_owner_scoped_and_invalid_submission_does_not_charge(): void
    {
        $user = User::factory()->create(['photo_credits' => 1]);
        $photo = $this->submit($user);
        $this->actingAs(User::factory()->create())->getJson('/ai-photo/'.$photo->id.'/status')->assertNotFound();
        $this->post('/ai-photo/'.$photo->id.'/retry', ['request_key' => (string) Str::uuid()])->assertNotFound();
        $this->actingAs($user)->getJson('/ai-photo/'.$photo->id.'/status')->assertOk()->assertJsonPath('status', 'queued');
        $this->postJson('/ai-photo/process', ['photo' => UploadedFile::fake()->image('portrait.png'), 'type' => 'remove_bg'])->assertUnprocessable();
        $this->assertDatabaseCount('ai_photos', 1);
    }

    public function test_private_status_page_displays_pending_state_without_result_image(): void
    {
        $user = User::factory()->create(['photo_credits' => 1]);
        $photo = $this->submit($user);
        $this->actingAs($user)->get('/ai-photo/'.$photo->id)->assertOk()->assertViewIs('ai-studio.index')
            ->assertSee('Your photo is in the queue.')->assertDontSee('alt="AI Photo Result"', false);
        $this->getJson('/ai-photo/'.$photo->id.'/status')->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_initial_record_failure_rolls_back_credit_and_removes_source(): void
    {
        $user = User::factory()->create(['photo_credits' => 1]);
        AiPhoto::creating(fn () => throw new \RuntimeException('simulated request insert failure'));
        try {
            try {
                $this->submit($user);
                $this->fail('Insert should have failed');
            } catch (\RuntimeException $exception) {
                $this->assertSame('simulated request insert failure', $exception->getMessage());
            }
            $this->assertSame(1, $user->fresh()->photo_credits);
            $this->assertDatabaseCount('ai_photos', 0);
            $this->assertSame([], Storage::disk('local')->allFiles());
        } finally {
            AiPhoto::flushEventListeners();
        }
    }

    public function test_dispatch_failure_persists_failed_request_and_refunds_once(): void
    {
        $this->mock(\Illuminate\Contracts\Bus\Dispatcher::class)->shouldReceive('dispatch')->once()->andThrow(new \RuntimeException('queue unavailable'));
        $user = User::factory()->create(['photo_credits' => 1]);
        $photo = $this->submit($user);
        $this->assertSame('failed', $photo->status);
        $this->assertSame('refunded', $photo->credit_state);
        $this->assertSame(1, $user->fresh()->photo_credits);
        $this->assertDatabaseCount('ai_photos', 1);
        Http::assertNothingSent();
    }

    public function test_recovery_preserves_active_lease_and_cleans_only_expired_terminal_sources(): void
    {
        $user = User::factory()->create(['photo_credits' => 2]);
        $photo = $this->submit($user);
        $photo->forceFill(['status' => 'processing', 'created_at' => now()->subMinutes(31), 'processing_token' => (string) Str::uuid(), 'processing_expires_at' => now()->addMinute()])->save();
        app(AiPhotoProcessingService::class)->expire($photo->id);
        $old = $this->submit($user);
        app(AiPhotoProcessingService::class)->fail($old->id, 'failed');
        $old->forceFill(['updated_at' => now()->subDays(8)])->save();
        $this->artisan('ai-photos:recover --cleanup')->assertSuccessful();
        $this->assertSame('processing', $photo->fresh()->status);
        Storage::disk('local')->assertExists($photo->source_path);
        Storage::disk('local')->assertMissing($old->source_path);
        $this->assertSame(1, $user->fresh()->photo_credits);
        Http::assertNothingSent();
    }

    public function test_credit_helper_uses_current_balance_instead_of_stale_model(): void
    {
        $user = User::factory()->create(['photo_credits' => 1]);
        $stale = User::find($user->id);
        $this->assertTrue($user->incrementPhotoCount());
        $this->assertFalse($stale->incrementPhotoCount());
        $this->assertSame(0, $user->fresh()->photo_credits);
    }

    public function test_recovery_does_not_accumulate_duplicate_queue_jobs(): void
    {
        $photo = $this->submit(User::factory()->create(['photo_credits' => 1]));
        $photo->forceFill(['created_at' => now()->subMinutes(3)])->save();
        $this->artisan('ai-photos:recover')->assertSuccessful();
        $this->artisan('ai-photos:recover')->assertSuccessful();
        Queue::assertPushed(ProcessAiPhoto::class, 1);
    }

    public function test_expired_queued_request_is_refunded_and_orphan_source_is_cleaned(): void
    {
        $user = User::factory()->create(['photo_credits' => 1]);
        $photo = $this->submit($user);
        $this->travel(31)->minutes();
        $this->artisan('ai-photos:recover')->assertSuccessful();
        $this->assertSame('failed', $photo->fresh()->status);
        $this->assertSame(1, $user->fresh()->photo_credits);
        $user->delete();
        touch(Storage::disk('local')->path($photo->source_path), now()->subDays(8)->timestamp);
        $this->artisan('ai-photos:recover --cleanup')->assertSuccessful();
        Storage::disk('local')->assertMissing($photo->source_path);
        Http::assertNothingSent();
    }
}
