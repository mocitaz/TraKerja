<?php

namespace Tests\Feature;

use App\Livewire\JobApplicationForm;
use App\Models\JobApplication;
use App\Models\User;
use App\Support\PlatformCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Tests\TestCase;

class PlatformBrandTest extends TestCase
{
    use RefreshDatabase;

    public function test_brands_use_local_assets_and_custom_names_are_escaped_without_external_requests(): void
    {
        $this->assertSame('images/platforms/linkedin.png', PlatformCatalog::logoPath(' linkedin '));
        $this->assertSame('images/platforms/jobstreet.png', PlatformCatalog::logoPath('JobStreet'));
        $this->assertNull(PlatformCatalog::logoPath('https://untrusted.example/icon'));
        $html = Blade::render('<x-platform-brand :name="$name" />', ['name' => 'LinkedIn']);
        $this->assertStringContainsString(asset('images/platforms/linkedin.png'), $html);
        $this->assertStringNotContainsString('google.com/s2', $html);
        $custom = Blade::render('<x-platform-brand :name="$name" />', ['name' => '<script>bad</script>']);
        $this->assertStringContainsString('&lt;script&gt;', $custom);
        $this->assertStringNotContainsString('<script>', $custom);
    }

    public function test_edit_keeps_existing_platform_and_custom_platform_can_be_saved(): void
    {
        $owner = User::factory()->create();
        $job = JobApplication::factory()->create([
            'user_id' => $owner->id, 'platform' => 'LinkedIn', 'location' => 'Remote',
            'application_status' => 'On Process', 'recruitment_stage' => 'Applied',
        ]);
        $form = Livewire::actingAs($owner)->test(JobApplicationForm::class, ['jobApplication' => $job])
            ->assertSet('platform', 'LinkedIn')->assertSee(asset('images/platforms/linkedin.png'), false)
            ->call('selectPlatform', "Recruiter's portal")->assertSet('platform', 'Other')
            ->assertSet('platformOther', "Recruiter's portal")
            ->assertSee('Custom platform')->set('isRemote', true)
            ->call('save')->assertHasNoErrors();
        $this->assertSame("Recruiter's portal", $job->fresh()->platform);
    }
}
