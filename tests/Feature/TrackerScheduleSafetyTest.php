<?php
namespace Tests\Feature;

use App\Livewire\JobApplicationForm;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TrackerScheduleSafetyTest extends TestCase
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
        config(['app.key'=>str_repeat('t',32), 'cache.default'=>'array', 'database.default'=>'sqlite',
            'database.connections.sqlite.database'=>':memory:', 'logging.default'=>'null']);
        Cache::put('setting_monetization_enabled', false);
        Schema::create('users', function(Blueprint $t) { $t->id(); $t->integer('xp')->default(0); $t->integer('level')->default(1); $t->string('payment_status')->default('free'); $t->timestamps(); });
        DB::table('users')->insert(['id'=>1]);
        Auth::setUser(User::findOrFail(1));
        Schema::create('job_applications', function(Blueprint $t) {
            $t->id(); $t->integer('user_id');
            foreach(['company_name','position','location','platform','status','application_status','recruitment_stage',
                'career_level','platform_link','application_date','notes','interview_date','interview_type','interview_location','interview_notes'] as $field) $t->string($field)->nullable();
            $t->boolean('is_archived')->default(false); $t->timestamps();
        });
    }
    public function test_new_entry_clears_all_schedule_fields_and_validation_errors(): void
    {
        $form = new JobApplicationForm;
        foreach(['interview_date','interview_type','interview_location','interview_notes'] as $field) {
            $form->{$field} = 'old value'; $form->addError($field, 'old error');
        }
        $form->isEditing = true;
        $form->resetFormForNewJob('Assessment Test');
        foreach(['interview_date','interview_type','interview_location','interview_notes'] as $field) $this->assertSame('', $form->{$field});
        $this->assertTrue($form->getErrorBag()->isEmpty());
        $this->assertFalse($form->isEditing);
        $this->assertSame('Assessment Test', $form->recruitment_stage);
    }
    public static function stages(): array
    {
        return array_map(fn($stage)=>[$stage], ['HR - Interview','User - Interview','Psychotest','Assessment Test','LGD','Presentation Round']);
    }
    public function test_new_job_after_reset_cannot_inherit_a_schedule(): void
    {
        $form = new JobApplicationForm;
        $form->interview_date = '2026-10-25T10:00';
        $form->interview_type = 'Video';
        $form->interview_location = 'https://old.example.test';
        $form->interview_notes = 'Old notes';
        $form->resetFormForNewJob('HR - Interview');
        $form->company_name = 'New company'; $form->position = 'New role';
        $form->isRemote = true; $form->platform = 'LinkedIn';
        $form->save();
        $job = JobApplication::sole();
        foreach(['interview_date','interview_type','interview_location','interview_notes'] as $field) $this->assertNull($job->{$field});
    }
    #[\PHPUnit\Framework\Attributes\DataProvider('stages')]
    public function test_editing_other_fields_preserves_schedule(string $stage): void
    {
        $job = JobApplication::withoutEvents(fn () => JobApplication::create(['user_id'=>1,'company_name'=>'Company','position'=>'Role','location'=>'Remote',
            'platform'=>'LinkedIn','status'=>'Applied','application_status'=>'On Process','recruitment_stage'=>$stage,
            'application_date'=>'2026-10-01','interview_date'=>'2026-10-25 10:00:00','interview_type'=>'Video',
            'interview_location'=>'https://meet.example.test','interview_notes'=>'Prepare portfolio']));
        $form = new JobApplicationForm;
        $form->editJob($job->id);
        $form->notes = 'Updated notes';
        $form->save();
        $saved = $job->fresh();
        $this->assertSame('Updated notes', $saved->notes);
        $this->assertSame('2026-10-25 10:00:00', $saved->interview_date?->format('Y-m-d H:i:s'));
        $this->assertSame('Video', $saved->interview_type);
        $this->assertSame('https://meet.example.test', $saved->interview_location);
        $this->assertSame('Prepare portfolio', $saved->interview_notes);
        $mounted = new JobApplicationForm;
        $mounted->mount($saved);
        $this->assertSame('2026-10-25T10:00', $mounted->interview_date);
        $this->assertSame('Prepare portfolio', $mounted->interview_notes);

        $method = new \ReflectionMethod(\App\Http\Controllers\JobApplicationImportExportController::class, 'importRow');
        $result = $method->invoke(new \App\Http\Controllers\JobApplicationImportExportController, [
            'company_name'=>'Company','position'=>'Role','location'=>'Remote','platform'=>'LinkedIn',
            'application_status'=>'On Process','recruitment_stage'=>$stage,'career_level'=>'Full Time',
            'application_date'=>'2026-10-01','interview_date'=>'2026-10-25 10:00',
            'interview_type'=>'Video','interview_location'=>'https://meet.example.test','interview_notes'=>'Prepare portfolio',
        ], 1);
        $this->assertTrue($result['success']);
        $this->assertSame('2026-10-25 10:00:00', $result['data']['interview_date']->format('Y-m-d H:i:s'));
        $this->assertSame('Prepare portfolio', $result['data']['interview_notes']);
    }
}
