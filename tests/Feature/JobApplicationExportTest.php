<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobApplicationExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_csv_export_requires_authentication(): void
    {
        $this->get(route('csv.export'))->assertRedirect(route('login'));
    }

    public function test_export_is_data_only_and_has_importable_header(): void
    {
        $user = User::factory()->create();
        JobApplication::factory()->count(3)->create(['user_id' => $user->id, 'company_name' => 'Test Company', 'position' => 'Software Engineer']);
        $response = $this->actingAs($user)->get(route('csv.export'))->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('TraKerja_Data_', $response->headers->get('Content-Disposition'));
        $csv = $response->streamedContent();
        $file = fopen('php://temp', 'r+');
        fwrite($file, substr($csv, 3));
        rewind($file);
        $header = fgetcsv($file, 0, ',', '"', '');
        $this->assertSame(['Company Name', 'Position', 'Location', 'Platform', 'Application Status', 'Recruitment Stage', 'Career Level', 'Application Date', 'Platform Link', 'Notes', 'Interview Date', 'Interview Type', 'Interview Location', 'Interview Notes', 'CSV Encoding'], $header);
        $rows = [];
        while (($row = fgetcsv($file, 0, ',', '"', '')) !== false) $rows[] = $row;
        fclose($file);
        $this->assertCount(3, $rows);
        $this->assertSame('Test Company', $rows[0][0]);
        $this->assertSame('Software Engineer', $rows[0][1]);
        $this->assertSame('trakerja-v1', $rows[0][14]);
        $this->assertStringNotContainsString('EXPORT SUMMARY', $csv);
    }

    public function test_export_includes_only_the_current_owner(): void
    {
        $owner = User::factory()->create();
        JobApplication::factory()->create(['user_id' => $owner->id, 'company_name' => 'Owner Company']);
        JobApplication::factory()->create(['company_name' => 'Other Company']);
        $csv = $this->actingAs($owner)->get(route('csv.export'))->assertOk()->streamedContent();
        $this->assertStringContainsString('Owner Company', $csv);
        $this->assertStringNotContainsString('Other Company', $csv);
    }

    public function test_inactive_legacy_export_and_stats_routes_stay_unavailable(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get('/export/job-applications/csv')->assertNotFound();
        $this->get('/export/job-applications/stats')->assertNotFound();
        $this->actingAs(User::factory()->create(['role' => 'admin']))->get(route('csv.export'))->assertForbidden();
    }
}
