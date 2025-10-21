<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobApplicationExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_csv_requires_authentication(): void
    {
        $response = $this->get('/export/job-applications/csv');
        $response->assertRedirect('/login');
    }

    public function test_export_csv_returns_csv_file(): void
    {
        $user = User::factory()->create();
        
        // Create sample job applications
        JobApplication::factory()->count(3)->create([
            'user_id' => $user->id,
            'company_name' => 'Test Company',
            'position' => 'Software Engineer',
            'location' => 'Jakarta',
            'platform' => 'LinkedIn',
            'application_status' => 'On Process',
            'recruitment_stage' => 'Applied',
            'career_level' => 'Full Time',
            'platform_link' => 'https://linkedin.com/jobs/123',
            'application_date' => now()->subDays(5),
            'notes' => 'Test notes'
        ]);

        $response = $this->actingAs($user)->get('/export/job-applications/csv');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition');
        
        // Check professional filename format
        $contentDisposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('TraKerja_JobApplications_', $contentDisposition);
        $this->assertStringContainsString('.csv', $contentDisposition);
        
        // Check if CSV content contains expected headers
        $content = $response->getContent();
        $this->assertStringContainsString('Company Name', $content);
        $this->assertStringContainsString('Position', $content);
        $this->assertStringContainsString('Test Company', $content);
        $this->assertStringContainsString('Software Engineer', $content);
        $this->assertStringContainsString('TraKerja - Job Application Export', $content);
    }

    public function test_export_csv_only_includes_user_data(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        // Create job applications for both users
        JobApplication::factory()->create([
            'user_id' => $user1->id,
            'company_name' => 'User 1 Company',
            'position' => 'User 1 Position'
        ]);
        
        JobApplication::factory()->create([
            'user_id' => $user2->id,
            'company_name' => 'User 2 Company',
            'position' => 'User 2 Position'
        ]);

        $response = $this->actingAs($user1)->get('/export/job-applications/csv');

        $response->assertStatus(200);
        $content = $response->getContent();
        
        // Should only contain user1's data
        $this->assertStringContainsString('User 1 Company', $content);
        $this->assertStringNotContainsString('User 2 Company', $content);
    }

    public function test_export_stats_returns_json(): void
    {
        $user = User::factory()->create();
        
        JobApplication::factory()->count(5)->create([
            'user_id' => $user->id,
            'application_status' => 'On Process'
        ]);

        $response = $this->actingAs($user)->get('/export/job-applications/stats');

        $response->assertStatus(200);
        $response->assertJson([
            'total_applications' => 5,
            'status_breakdown' => [
                [
                    'application_status' => 'On Process',
                    'count' => 5
                ]
            ]
        ]);
    }
}
