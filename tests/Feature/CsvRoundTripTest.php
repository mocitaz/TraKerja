<?php

namespace Tests\Feature;

use App\Http\Controllers\JobApplicationImportExportController;
use App\Models\JobApplication;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CsvRoundTripTest extends TestCase
{
    use RefreshDatabase;

    private function exportCsv(): string
    {
        $response = (new JobApplicationImportExportController)->exportToCsv();
        ob_start();
        $response->sendContent();
        return ob_get_clean();
    }

    public function test_export_can_be_imported_by_another_owner(): void
    {
        Setting::set('monetization_enabled', false);
        $owner = User::factory()->create();
        $other = User::factory()->create();
        JobApplication::factory()->create(['user_id' => $owner->id, 'company_name' => 'PT Contoh, "Indonesia"', 'application_status' => 'On Process', 'recruitment_stage' => 'Applied', 'career_level' => 'Full Time', 'notes' => "Baris satu\nBaris dua"]);
        $this->actingAs($owner);
        $csv = $this->exportCsv();
        $this->assertStringStartsWith("\xEF\xBB\xBF\"Company Name\",", $csv);
        $this->assertStringNotContainsString('EXPORT SUMMARY', $csv);
        $this->actingAs($other)->post('/csv/import', ['csv_file' => UploadedFile::fake()->createWithContent('export.csv', $csv)])
            ->assertSessionHasNoErrors()->assertSessionHas('success');
        $copy = JobApplication::where('user_id', $other->id)->firstOrFail();
        $this->assertSame('PT Contoh, "Indonesia"', $copy->company_name);
        $this->assertSame("Baris satu\nBaris dua", $copy->notes);
    }

    public function test_preview_reports_valid_and_failed_rows_without_writing(): void
    {
        Setting::set('monetization_enabled', false);
        $csv = "Company Name,Position,Location,Platform,Application Status,Recruitment Stage,Career Level,Application Date\nValid,Engineer,Jakarta,LinkedIn,On Process,Applied,Full Time,2026-10-08\nInvalid,Engineer,,LinkedIn,On Process,Applied,Full Time,2026-10-08\n";
        $this->actingAs(User::factory()->create())->postJson('/csv/import/preview', ['csv_file' => UploadedFile::fake()->createWithContent('import.csv', $csv)])
            ->assertOk()->assertJsonPath('valid_count', 1)->assertJsonPath('failed_count', 1)->assertJsonPath('errors.0.row', 3)
            ->assertJsonPath('rows.0.application_date', '2026-10-08');
        $this->assertDatabaseCount('job_applications', 0);
    }

    public function test_export_preserves_formula_like_and_quoted_text_on_round_trip(): void
    {
        Setting::set('monetization_enabled', false);
        $owner = User::factory()->create();
        $other = User::factory()->create();
        JobApplication::factory()->create(['user_id' => $owner->id, 'company_name' => '=SUM(1,2)', 'position' => "'Engineer", 'notes' => '0', 'career_level' => 'Full Time']);
        $this->actingAs($owner);
        $csv = $this->exportCsv();
        $this->assertStringContainsString("'=SUM(1,2)", $csv);
        $this->actingAs($other)->post('/csv/import', ['csv_file' => UploadedFile::fake()->createWithContent('export.csv', $csv)])->assertSessionHasNoErrors();
        $copy = JobApplication::where('user_id', $other->id)->firstOrFail();
        $this->assertSame('=SUM(1,2)', $copy->company_name);
        $this->assertSame("'Engineer", $copy->position);
        $this->assertSame('0', $copy->notes);
    }

    public function test_invalid_calendar_date_is_reported_and_partial_import_has_row_summary(): void
    {
        Setting::set('monetization_enabled', false);
        $csv = "Company Name,Position,Location,Platform,Application Status,Recruitment Stage,Career Level,Application Date\nValid,Engineer,Jakarta,LinkedIn,On Process,Applied,Full Time,2026-10-08\nInvalid,Engineer,Jakarta,LinkedIn,On Process,Applied,Full Time,2026-02-30\n";
        $this->actingAs(User::factory()->create())->post('/csv/import', ['csv_file' => UploadedFile::fake()->createWithContent('import.csv', $csv)])
            ->assertSessionHas('import_summary.imported_count', 1)->assertSessionHas('import_summary.failed_count', 1)
            ->assertSessionHas('import_summary.errors.0.row', 3);
        $this->assertDatabaseCount('job_applications', 1);
    }

    public function test_blank_career_level_roundtrips_and_overlong_field_is_rejected_in_preview(): void
    {
        Setting::set('monetization_enabled', false);
        $owner = User::factory()->create();
        $other = User::factory()->create();
        JobApplication::factory()->create(['user_id' => $owner->id, 'career_level' => '', 'platform_link' => '']);
        $this->actingAs($owner);
        $csv = $this->exportCsv();
        $this->actingAs($other)->post('/csv/import', ['csv_file' => UploadedFile::fake()->createWithContent('export.csv', $csv)])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('job_applications', ['user_id' => $other->id, 'career_level' => '', 'platform_link' => '']);
        $csv = "Company Name,Position,Location,Platform,Application Status,Recruitment Stage,Career Level,Application Date\n".str_repeat('x', 256).",Engineer,Jakarta,LinkedIn,On Process,Applied,Full Time,2026-10-08\n";
        $this->postJson('/csv/import/preview', ['csv_file' => UploadedFile::fake()->createWithContent('long.csv', $csv)])
            ->assertOk()->assertJsonPath('valid_count', 0)->assertJsonPath('failed_count', 1);
    }

    public function test_preview_requires_the_same_access_as_import(): void
    {
        $this->postJson('/csv/import/preview')->assertUnauthorized();
        Setting::set('monetization_enabled', true);
        $this->actingAs(User::factory()->create())->postJson('/csv/import/preview')->assertForbidden();
        Setting::set('monetization_enabled', false);
        $this->actingAs(User::factory()->create(['role' => 'admin']))->postJson('/csv/import/preview')->assertForbidden();
    }
}
