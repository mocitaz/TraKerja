<?php

namespace Tests\Feature;

use App\Http\Controllers\ResumeParserController;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ResumeImportSafetyTest extends TestCase
{
    public function createApplication()
    {
        $app = require dirname(__DIR__, 2).'/bootstrap/app.php';
        $app->useEnvironmentPath('/dev/null');
        $this->traitsUsedByTest = array_flip(class_uses_recursive(static::class));
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(str_repeat('r', 32)), 'cache.default' => 'array',
            'database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:',
            'logging.default' => 'null']);
        Schema::create('users', fn (Blueprint $t) => $t->id());
        Schema::create('user_experiences', function (Blueprint $t) {
            $t->id(); $t->integer('user_id'); $t->string('company_name'); $t->string('position');
            $t->string('location')->nullable(); $t->date('start_date')->nullable(); $t->date('end_date')->nullable();
            $t->boolean('is_current')->default(false); $t->text('description')->nullable();
            $t->integer('display_order')->default(1); $t->timestamps();
        });
        Schema::create('user_educations', function (Blueprint $t) {
            $t->id(); $t->integer('user_id'); $t->string('institution_name'); $t->string('degree');
            $t->string('major'); $t->date('start_date'); $t->date('end_date')->nullable();
            $t->boolean('is_current')->default(false); $t->integer('display_order')->default(1); $t->timestamps();
        });
        Schema::create('user_projects', function (Blueprint $t) {
            $t->id(); $t->integer('user_id'); $t->string('project_name'); $t->string('role')->nullable();
            $t->date('start_date')->nullable(); $t->boolean('is_ongoing')->default(false);
            $t->text('description')->nullable(); $t->integer('display_order')->default(1); $t->timestamps();
        });
        Schema::create('user_skills', function (Blueprint $t) {
            $t->id(); $t->integer('user_id'); $t->string('skill_name'); $t->string('category')->nullable();
            $t->string('proficiency_level')->nullable(); $t->integer('years_of_experience')->nullable();
            $t->integer('display_order')->default(1); $t->timestamps();
        });
        DB::table('users')->insert([['id' => 1], ['id' => 2]]);
        foreach ([1, 2] as $id) {
            DB::table('user_experiences')->insert(['user_id' => $id, 'company_name' => 'Old company', 'position' => 'Old role']);
            DB::table('user_educations')->insert(['user_id' => $id, 'institution_name' => 'Old school', 'degree' => 'Old degree',
                'major' => 'Old major', 'start_date' => '2010-01-01']);
            DB::table('user_projects')->insert(['user_id' => $id, 'project_name' => 'Old project']);
            DB::table('user_skills')->insert(['user_id' => $id, 'skill_name' => 'Old skill']);
        }
        Auth::setUser(User::findOrFail(1));
    }

    private function snapshot(): array
    {
        return array_map(fn ($table) => DB::table($table)->orderBy('id')->get()->toJson(),
            ['user_experiences', 'user_educations', 'user_projects', 'user_skills']);
    }

    private function preview(array $lines)
    {
        // A real one-page PDF fixture, parsed by the production PDF parser.
        $stream = "BT /F1 12 Tf 50 750 Td ";
        foreach ($lines as $line) $stream .= '('.$line.") Tj 0 -18 Td ";
        $stream .= 'ET';
        $objects = ['<< /Type /Catalog /Pages 2 0 R >>', '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            '<< /Length '.strlen($stream).">>\nstream\n".$stream."\nendstream"];
        $pdf = "%PDF-1.4\n"; $offsets = [0];
        foreach ($objects as $i => $object) { $offsets[] = strlen($pdf); $pdf .= ($i + 1)." 0 obj\n".$object."\nendobj\n"; }
        $xref = strlen($pdf); $pdf .= "xref\n0 6\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) $pdf .= sprintf("%010d 00000 n \n", $offset);
        $pdf .= "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n".$xref."\n%%EOF";
        $request = Request::create('/cv/import-pdf', 'POST', [], [], [
            'resume' => UploadedFile::fake()->createWithContent('resume.pdf', $pdf),
        ]);
        return app(ResumeParserController::class)->importPdf($request);
    }

    private function confirm(string $token, array $completion = [])
    {
        return app(ResumeParserController::class)->confirmImport(Request::create('/cv/import-pdf/confirm', 'POST', [
            'token' => $token, 'confirm_replace' => true, 'education_start_dates' => $completion,
        ]));
    }

    public function test_unreadable_sections_do_not_delete_existing_cv(): void
    {
        $before = $this->snapshot();
        $response = $this->preview(['My CV', 'Unrecognized heading', 'Unrecognized content']);
        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame($before, $this->snapshot());
    }

    public function test_preview_has_no_writes_and_reports_replacement_and_preserved_sections(): void
    {
        $before = $this->snapshot();
        $response = $this->preview(['SKILLS', 'PHP, Laravel']);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($before, $this->snapshot());
        $body = $response->getData(true);
        $this->assertSame('preview', $body['stage']);
        $this->assertSame(1, $body['existing_counts']['skills']);
        $this->assertCount(2, $body['data']['skills']);
        $this->assertSame([], $body['data']['experiences']);
        $this->assertNotEmpty($body['token']);
    }

    public function test_confirm_replaces_only_parsed_sections_and_is_user_scoped(): void
    {
        $preview = $this->preview(['SKILLS', 'PHP, Laravel'])->getData(true);
        $response = $this->confirm($preview['token']);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(['PHP', 'Laravel'], UserSkill::where('user_id', 1)->orderBy('display_order')->pluck('skill_name')->all());
        $this->assertSame(['Old skill'], UserSkill::where('user_id', 2)->pluck('skill_name')->all());
        foreach (['user_experiences', 'user_educations', 'user_projects'] as $table) {
            $this->assertSame(1, DB::table($table)->where('user_id', 1)->count());
        }
        $this->assertSame(409, $this->confirm($preview['token'])->getStatusCode());
    }

    public function test_insert_failure_rolls_back_every_section(): void
    {
        $preview = $this->preview(['WORK EXPERIENCE', 'New company', 'Developer Jan 2020 - Present',
            '- Built systems', 'PROJECTS', 'New project', '- Shipped app',
            'EDUCATION', 'New University', 'Bachelor of Science', 'Jan 2018 - Dec 2022',
            'SKILLS', 'PHP, Laravel'])->getData(true);
        foreach (['experiences', 'educations', 'projects', 'skills'] as $section) {
            $this->assertNotEmpty($preview['data'][$section]);
        }
        $before = $this->snapshot();
        DB::unprepared("CREATE TRIGGER fail_import BEFORE INSERT ON user_skills WHEN NEW.skill_name = 'Laravel' BEGIN SELECT RAISE(ABORT, 'forced test failure'); END");
        $this->assertSame(500, $this->confirm($preview['token'])->getStatusCode());
        $this->assertSame($before, $this->snapshot());
    }

    public function test_preview_cannot_be_confirmed_by_another_user(): void
    {
        $preview = $this->preview(['SKILLS', 'PHP'])->getData(true);
        Auth::setUser(User::findOrFail(2));
        $before = $this->snapshot();
        $this->assertSame(422, $this->confirm($preview['token'])->getStatusCode());
        $this->assertSame($before, $this->snapshot());
    }

    public function test_edits_after_preview_require_a_new_preview(): void
    {
        $preview = $this->preview(['SKILLS', 'PHP'])->getData(true);
        DB::table('user_skills')->where('user_id', 1)->update(['skill_name' => 'Edited skill']);
        $before = $this->snapshot();
        $this->assertSame(409, $this->confirm($preview['token'])->getStatusCode());
        $this->assertSame($before, $this->snapshot());
    }

    public function test_expired_preview_and_tampered_token_do_not_write(): void
    {
        $preview = $this->preview(['SKILLS', 'PHP'])->getData(true);
        $before = $this->snapshot();
        $this->travel(16)->minutes();
        $this->assertSame(422, $this->confirm($preview['token'])->getStatusCode());
        $this->assertSame(422, $this->confirm($preview['token'].'x')->getStatusCode());
        $this->assertSame($before, $this->snapshot());
    }

    public function test_pdf_without_text_never_changes_saved_cv(): void
    {
        $before = $this->snapshot();
        $this->assertSame(422, $this->preview([])->getStatusCode());
        $this->assertSame($before, $this->snapshot());
    }

    public function test_confirmation_requires_explicit_acceptance(): void
    {
        $preview = $this->preview(['SKILLS', 'PHP'])->getData(true);
        $before = $this->snapshot();
        try {
            app(ResumeParserController::class)->confirmImport(Request::create('/cv/import-pdf/confirm', 'POST', [
                'token' => $preview['token'], 'confirm_replace' => false,
            ]));
            $this->fail('Confirmation without acceptance must be rejected.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('confirm_replace', $e->errors());
        }
        $this->assertSame($before, $this->snapshot());
    }

    public function test_import_never_invents_project_skill_or_education_qualifications(): void
    {
        $preview = $this->preview(['PROJECTS', 'Inventory App', '- Tracks inventory',
            'EDUCATION', 'Example University', 'SKILLS', 'PHP, Communication'])->getData(true);
        $this->assertSame('preview', $preview['stage']);
        $project = $preview['data']['projects'][0];
        $this->assertNull($project['role']);
        $this->assertNull($project['start_date']);
        $this->assertFalse($project['is_ongoing']);
        $education = $preview['data']['educations'][0];
        $this->assertSame('', $education['degree']);
        $this->assertSame('', $education['major']);
        $this->assertNull($education['start_date']);
        $this->assertNull($education['end_date']);
        $this->assertCount(2, $preview['data']['skills']);
        foreach ($preview['data']['skills'] as $skill) {
            $this->assertNull($skill['category']);
            $this->assertNull($skill['proficiency_level']);
            $this->assertNull($skill['years_of_experience']);
        }
        $response = $this->confirm($preview['token'], ['0' => '2019-08-01']);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertNull(DB::table('user_projects')->where('user_id', 1)->value('role'));
        $this->assertNull(DB::table('user_skills')->where('user_id', 1)->value('years_of_experience'));
        $this->assertSame('2019-08-01', \App\Models\UserEducation::where('user_id', 1)->sole()->start_date->toDateString());
        $this->assertSame('', DB::table('user_educations')->where('user_id', 1)->value('major'));
    }

    public function test_missing_education_start_date_requires_user_completion_without_writes(): void
    {
        $preview = $this->preview(['EDUCATION', 'Example University'])->getData(true);
        $before = $this->snapshot();
        $response = $this->confirm($preview['token']);
        $this->assertSame(422, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['requires_completion']);
        $this->assertSame($before, $this->snapshot());
    }

    public function test_experience_location_is_not_invented(): void
    {
        $preview = $this->preview(['WORK EXPERIENCE', 'Example company', 'Designer Jan 2020 - Present'])->getData(true);
        $this->assertNull($preview['data']['experiences'][0]['location']);
        $this->assertSame('Designer', $preview['data']['experiences'][0]['position']);
        $this->assertSame(200, $this->confirm($preview['token'])->getStatusCode());
    }

    public function test_year_only_education_dates_do_not_invent_months(): void
    {
        $preview = $this->preview(['EDUCATION', 'Example University', 'Economics', '2018 - 2022'])->getData(true);
        $entry = $preview['data']['educations'][0];
        $this->assertSame('Economics', $entry['degree']);
        $this->assertSame('', $entry['major']);
        $this->assertNull($entry['start_date']);
        $this->assertNull($entry['end_date']);
        $this->assertSame(['2018 - 2022'], $preview['education_date_hints']);
        $before = $this->snapshot();
        $this->assertSame(422, $this->confirm($preview['token'], [0 => 'yesterday'])->getStatusCode());
        $this->assertSame($before, $this->snapshot());
    }

    public function test_documented_months_and_present_status_are_preserved(): void
    {
        $preview = $this->preview(['EDUCATION', 'Example University', 'Economics', 'February 2021 - Present'])->getData(true);
        $this->assertSame('2021-02-01', $preview['data']['educations'][0]['start_date']);
        $this->assertTrue($preview['data']['educations'][0]['is_current']);
        $this->assertSame(200, $this->confirm($preview['token'])->getStatusCode());
    }

    public function test_experience_without_a_position_does_not_invent_a_job_title(): void
    {
        $before = $this->snapshot();
        $response = $this->preview(['WORK EXPERIENCE', 'Example company', 'Jan 2020 - Present']);
        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame($before, $this->snapshot());
    }

    public function test_preview_from_previous_parser_version_cannot_save_old_defaults(): void
    {
        $preview = $this->preview(['SKILLS', 'PHP'])->getData(true);
        $draft = json_decode(\Illuminate\Support\Facades\Crypt::decryptString($preview['token']), true);
        unset($draft['parser_version']);
        $oldToken = \Illuminate\Support\Facades\Crypt::encryptString(json_encode($draft));
        $before = $this->snapshot();
        $this->assertSame(422, $this->confirm($oldToken)->getStatusCode());
        $this->assertSame($before, $this->snapshot());
    }
}
