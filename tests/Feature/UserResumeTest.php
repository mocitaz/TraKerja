<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserResume;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserResumeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_resumes_tab_on_profile(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('profile.edit', ['tab' => 'resumes']));

        $response->assertOk();
        $response->assertSee('My Resumes');
        $response->assertSee('No resumes saved yet');
    }

    public function test_user_can_upload_a_valid_pdf_resume(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('software_engineer_cv.pdf', 350, 'application/pdf');

        $response = $this
            ->actingAs($user)
            ->post(route('user-resumes.store'), [
                'resume_file' => $file,
                'title' => 'Software Engineer 2026',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit', ['tab' => 'resumes']))
            ->assertSessionHas('status', 'resume-uploaded');

        $this->assertDatabaseHas('user_resumes', [
            'user_id' => $user->id,
            'title' => 'Software Engineer 2026',
            'file_name' => 'software_engineer_cv.pdf',
            'is_primary' => true,
        ]);

        $resume = UserResume::where('user_id', $user->id)->first();
        $this->assertNotNull($resume);
        Storage::disk('local')->assertExists($resume->file_path);
    }

    public function test_resume_upload_validates_file_extension_and_size(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();

        // Invalid mime/extension
        $badFile = UploadedFile::fake()->create('malicious.exe', 100, 'application/x-msdownload');
        $response = $this
            ->actingAs($user)
            ->post(route('user-resumes.store'), [
                'resume_file' => $badFile,
            ]);

        $response->assertSessionHasErrors(['resume_file']);
        $this->assertDatabaseCount('user_resumes', 0);

        // Oversized file (>10MB)
        $oversizedFile = UploadedFile::fake()->create('large.pdf', 11000, 'application/pdf');
        $response = $this
            ->actingAs($user)
            ->post(route('user-resumes.store'), [
                'resume_file' => $oversizedFile,
            ]);

        $response->assertSessionHasErrors(['resume_file']);
        $this->assertDatabaseCount('user_resumes', 0);
    }

    public function test_user_can_upload_multiple_resumes_and_set_primary(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();

        $file1 = UploadedFile::fake()->create('resume_v1.pdf', 200, 'application/pdf');
        $this->actingAs($user)->post(route('user-resumes.store'), [
            'resume_file' => $file1,
            'title' => 'Version 1',
        ]);

        $file2 = UploadedFile::fake()->create('resume_v2.docx', 250, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $this->actingAs($user)->post(route('user-resumes.store'), [
            'resume_file' => $file2,
            'title' => 'Version 2',
        ]);

        $this->assertDatabaseCount('user_resumes', 2);

        $resume1 = UserResume::where('title', 'Version 1')->first();
        $resume2 = UserResume::where('title', 'Version 2')->first();

        // First was default primary, second was not
        $this->assertTrue($resume1->is_primary);
        $this->assertFalse($resume2->is_primary);

        // Now set resume2 as primary
        $response = $this->actingAs($user)->post(route('user-resumes.primary', $resume2));
        $response->assertRedirect(route('profile.edit', ['tab' => 'resumes']));

        $this->assertFalse($resume1->fresh()->is_primary);
        $this->assertTrue($resume2->fresh()->is_primary);
    }

    public function test_user_can_update_resume_title(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $resume = UserResume::create([
            'user_id' => $user->id,
            'title' => 'Old Title',
            'file_path' => 'resumes/' . $user->id . '/test.pdf',
            'file_name' => 'test.pdf',
            'file_size' => 1024,
            'file_type' => 'application/pdf',
            'is_primary' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->patch(route('user-resumes.update', $resume), [
                'title' => 'Updated Tech CV 2026',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit', ['tab' => 'resumes']));

        $this->assertSame('Updated Tech CV 2026', $resume->fresh()->title);
    }

    public function test_user_can_download_their_resume(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $filePath = 'resumes/' . $user->id . '/test_download.pdf';
        Storage::disk('local')->put($filePath, 'fake content');

        $resume = UserResume::create([
            'user_id' => $user->id,
            'title' => 'Test Download',
            'file_path' => $filePath,
            'file_name' => 'test_download.pdf',
            'file_size' => 12,
            'file_type' => 'application/pdf',
            'is_primary' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('user-resumes.download', $resume));

        $response->assertOk();
    }

    public function test_user_cannot_access_or_modify_another_users_resume(): void
    {
        Storage::fake('local');

        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $filePath = 'resumes/' . $userA->id . '/user_a.pdf';
        Storage::disk('local')->put($filePath, 'user a cv');

        $resumeA = UserResume::create([
            'user_id' => $userA->id,
            'title' => 'User A CV',
            'file_path' => $filePath,
            'file_name' => 'user_a.pdf',
            'file_size' => 100,
            'file_type' => 'application/pdf',
            'is_primary' => true,
        ]);

        // User B tries to download
        $this->actingAs($userB)
            ->get(route('user-resumes.download', $resumeA))
            ->assertForbidden();

        // User B tries to update
        $this->actingAs($userB)
            ->patch(route('user-resumes.update', $resumeA), ['title' => 'Hacked'])
            ->assertForbidden();

        // User B tries to set primary
        $this->actingAs($userB)
            ->post(route('user-resumes.primary', $resumeA))
            ->assertForbidden();

        // User B tries to delete
        $this->actingAs($userB)
            ->delete(route('user-resumes.destroy', $resumeA))
            ->assertForbidden();

        $this->assertSame('User A CV', $resumeA->fresh()->title);
    }

    public function test_user_can_delete_resume_and_file_is_removed(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $filePath = 'resumes/' . $user->id . '/to_delete.pdf';
        Storage::disk('local')->put($filePath, 'delete me');

        $resume = UserResume::create([
            'user_id' => $user->id,
            'title' => 'Delete Me',
            'file_path' => $filePath,
            'file_name' => 'to_delete.pdf',
            'file_size' => 50,
            'file_type' => 'application/pdf',
            'is_primary' => true,
        ]);

        Storage::disk('local')->assertExists($filePath);

        $response = $this
            ->actingAs($user)
            ->delete(route('user-resumes.destroy', $resume));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit', ['tab' => 'resumes']))
            ->assertSessionHas('status', 'resume-deleted');

        $this->assertDatabaseMissing('user_resumes', ['id' => $resume->id]);
        Storage::disk('local')->assertMissing($filePath);
    }
}
