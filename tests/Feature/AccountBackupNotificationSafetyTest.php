<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\{Storage,Schema,DB,Cache,Process};
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class AccountBackupNotificationSafetyTest extends TestCase
{
    public function createApplication()
    {
        $app=require dirname(__DIR__,2).'/bootstrap/app.php'; $app->useEnvironmentPath('/dev/null');
        $this->traitsUsedByTest=array_flip(class_uses_recursive(static::class)); $app->make(Kernel::class)->bootstrap(); return $app;
    }
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key'=>str_repeat('n',32),'cache.default'=>'array','database.default'=>'sqlite',
            'database.connections.sqlite.database'=>':memory:','logging.default'=>'null']);
        Schema::create('users',function(Blueprint $t){$t->id();$t->boolean('email_notifications_enabled')->default(true);
            $t->boolean('notify_interview_reminders')->default(true);$t->boolean('notify_goal_achieved')->default(true);
            $t->string('email')->default('fixture@example.test');});
        Schema::create('user_resumes',function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->string('file_path');$t->timestamps();});
        DB::table('users')->insert(['id'=>1]);
        Cache::put('setting_monetization_enabled',false);
        Storage::fake('local');
    }
    public function test_account_cleanup_removes_private_files_and_is_repeatable(): void
    {
        Storage::disk('local')->put('resumes/1/cv.pdf','private fixture');
        Storage::disk('local')->put('resumes/2/cv.pdf','other owner');
        DB::table('user_resumes')->insert(['user_id'=>1,'file_path'=>'resumes/1/cv.pdf']);
        $service=app(\App\Services\AccountResumeCleanup::class);
        $service->clean(User::findOrFail(1)); $service->clean(User::findOrFail(1));
        Storage::disk('local')->assertMissing('resumes/1/cv.pdf');
        Storage::disk('local')->assertExists('resumes/2/cv.pdf');
    }
    public function test_account_controller_cleans_files_before_cascading_owner_rows(): void
    {
        Storage::disk('local')->put('resumes/1/cv.pdf','private fixture');
        DB::table('user_resumes')->insert(['user_id'=>1,'file_path'=>'resumes/1/cv.pdf']);
        $user=User::findOrFail(1);
        \Illuminate\Support\Facades\Auth::setUser($user);
        $request=\Mockery::mock(\Illuminate\Http\Request::class);
        $request->shouldReceive('validateWithBag')->with('userDeletion',['password'=>['required','current_password']])->once()->andReturn([]);
        $request->shouldReceive('user')->andReturn($user);
        $session=\Mockery::mock(); $session->shouldReceive('invalidate')->once(); $session->shouldReceive('regenerateToken')->once();
        $request->shouldReceive('session')->andReturn($session);
        app(\App\Http\Controllers\ProfileController::class)->destroy($request);
        $this->assertSame(0, DB::table('users')->count());
        $this->assertSame(0, DB::table('user_resumes')->count());
        Storage::disk('local')->assertMissing('resumes/1/cv.pdf');
    }
    public function test_notification_policy_respects_master_switch_and_each_preference(): void
    {
        $user=User::findOrFail(1);
        $this->assertTrue($user->wantsEmailNotification('interview'));
        $this->assertTrue($user->wantsEmailNotification('goal_achieved'));
        $user->email_notifications_enabled=false;
        $this->assertFalse($user->wantsEmailNotification('interview'));
        $this->assertFalse($user->wantsEmailNotification('goal_achieved'));
        $user->email_notifications_enabled=true; $user->notify_goal_achieved=false;
        $this->assertFalse($user->wantsEmailNotification('goal_achieved'));
        $this->assertTrue($user->wantsEmailNotification('interview'));
        $user->notify_interview_reminders=false;
        $this->assertFalse($user->wantsEmailNotification('interview'));
        $this->assertFalse($user->wantsEmailNotification('unknown'));
    }
    public static function failedDumps(): array
    {
        return [[1,''],[1,"CREATE TABLE test (id int);\n-- Dump completed on today\n"],[0,''],[0,'-- Table: users'],[0,'CREATE TABLE test (id int);']];
    }
    #[\PHPUnit\Framework\Attributes\DataProvider('failedDumps')]
    public function test_failed_or_incomplete_dump_never_produces_download(int $exit,string $output): void
    {
        config(['database.default'=>'mysql','database.connections.mysql'=>['host'=>'db.test','port'=>3306,'database'=>'fixture','username'=>'fixture','password'=>'synthetic-test-value']]);
        Process::fake(['*'=>Process::result(output:$output,exitCode:$exit)]);
        $this->expectException(\RuntimeException::class);
        app(\App\Services\DatabaseBackup::class)->create();
    }
    public function test_complete_dump_is_preserved_exactly(): void
    {
        config(['database.default'=>'mysql','database.connections.mysql'=>['host'=>'db.test','port'=>3306,'database'=>'fixture','username'=>'fixture','password'=>'synthetic-test-value']]);
        $sql="-- MySQL dump\nCREATE TABLE `users` (`id` int);\nINSERT INTO `users` VALUES (1);\n-- Dump completed on 2026-10-15\n";
        Process::fake(['*'=>Process::result(output:$sql,exitCode:0)]);
        $path=app(\App\Services\DatabaseBackup::class)->create();
        try {$this->assertSame($sql,file_get_contents($path));} finally {unlink($path);}
        Process::assertRan(fn($process)=>is_array($process->command) && !str_contains(implode(' ',$process->command),'synthetic-test-value'));
    }

    public function test_cleanup_failure_preserves_owner_and_resume_rows_for_retry(): void
    {
        DB::table('user_resumes')->insert(['user_id'=>1,'file_path'=>'resumes/1/cv.pdf']);
        $disk=\Mockery::mock();
        $disk->shouldReceive('exists')->with('resumes/1/cv.pdf')->andReturn(true);
        $disk->shouldReceive('delete')->with('resumes/1/cv.pdf')->andReturn(false);
        Storage::shouldReceive('disk')->with('local')->andReturn($disk);
        try {
            app(\App\Services\AccountResumeCleanup::class)->clean(User::findOrFail(1));
            $this->fail('Failed storage deletion must stop account deletion.');
        } catch (\RuntimeException $e) {
            $this->assertSame(1, DB::table('users')->count());
            $this->assertSame(1, DB::table('user_resumes')->count());
        }
    }

    public function test_resume_cleanup_never_deletes_another_owners_file(): void
    {
        Storage::disk('local')->put('resumes/2/cv.pdf','other owner');
        DB::table('user_resumes')->insert(['user_id'=>1,'file_path'=>'resumes/2/cv.pdf']);
        try {
            app(\App\Services\AccountResumeCleanup::class)->clean(User::findOrFail(1));
            $this->fail('A different owner path must be rejected.');
        } catch (\RuntimeException $e) {
            Storage::disk('local')->assertExists('resumes/2/cv.pdf');
        }
    }

    public function test_goal_achievement_delivery_obeys_preferences(): void
    {
        Schema::create('user_goals',function(Blueprint $t){$t->id();$t->integer('user_id');$t->boolean('is_achieved')->default(false);$t->timestamps();});
        \Illuminate\Support\Facades\Mail::fake();
        $user=User::findOrFail(1);
        foreach ([[false,true,false],[true,false,false],[true,true,true]] as [$master,$preference,$send]) {
            DB::table('users')->where('id',1)->update(['email_notifications_enabled'=>$master,'notify_goal_achieved'=>$preference]);
            DB::table('user_goals')->insert(['user_id'=>1,'is_achieved'=>false]);
            $goal=\App\Models\UserGoal::latest('id')->first();
            $goal->update(['is_achieved'=>true]);
        }
        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\GoalAchievedMail::class,1);
    }

    public function test_interview_command_and_model_delivery_obey_master_switch(): void
    {
        Schema::create('job_applications',function(Blueprint $t){$t->id();$t->integer('user_id');$t->string('company_name');$t->dateTime('interview_date');});
        DB::table('job_applications')->insert(['user_id'=>1,'company_name'=>'Fixture',
            'interview_date'=>now('Asia/Jakarta')->addDay()->startOfDay()->addHours(10)]);
        \Illuminate\Support\Facades\Mail::fake();
        DB::table('users')->where('id',1)->update(['email_notifications_enabled'=>false]);
        $this->artisan('interviews:send-reminders')->assertSuccessful();
        $this->assertFalse(\App\Models\JobApplication::sole()->sendInterviewReminder());
        \Illuminate\Support\Facades\Mail::assertNothingSent();
        DB::table('users')->where('id',1)->update(['email_notifications_enabled'=>true]);
        $this->artisan('interviews:send-reminders')->assertSuccessful();
        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\InterviewReminderMail::class,1);
    }
}
