<?php
// All database operations use an in-memory SQLite database with fake HTTP and mail.
$root = dirname(__DIR__, 3);
foreach (['APP_ENV'=>'testing','DB_CONNECTION'=>'sqlite','DB_DATABASE'=>':memory:','CACHE_STORE'=>'array','SESSION_DRIVER'=>'array','MAIL_MAILER'=>'array','LOG_CHANNEL'=>'null','APP_CONFIG_CACHE'=>'/private/tmp/nonexistent-trakerja-audit-config.php'] as $key=>$value) {
    putenv("$key=$value"); $_ENV[$key]=$value; $_SERVER[$key]=$value;
}
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\{DB, Auth, Http, Mail, Artisan};
use App\Models\{User, JobPosting, ScraperSource, JobApplication};
config(['database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','cache.default'=>'array','logging.default'=>'null']);
DB::purge();
if (DB::connection()->getDatabaseName() !== ':memory:') throw new RuntimeException('Isolation failed');
Mail::fake(); Http::fake(); Http::preventStrayRequests();
Artisan::call('migrate', ['--force'=>true]);
$user = User::factory()->create(); Auth::setUser($user);
$source = ScraperSource::create(['name'=>'Synthetic portal','target_domain'=>'linkedin.com','seed_url'=>'https://example.test','selectors_config'=>[]]);
$posting = JobPosting::create(['scraper_source_id'=>$source->id,'title'=>'Synthetic position','company_name'=>'Synthetic company','description'=>'Fixture','raw_url'=>'https://example.test/1','unique_hash'=>md5('fixture'),'status'=>'active']);
$explore = new App\Livewire\ExploreJobs;
$explore->trackJob($posting->id);
$job = JobApplication::where('user_id',$user->id)->firstOrFail();
$board = new App\Livewire\JobKanbanBoard;
echo 'explore_created_status='.$job->application_status.' kanban_has_status='.(in_array($job->application_status,$board->statusOptions,true)?'true':'false')."\n";
for ($i=0; $i<3; $i++) $explore->reportExpired($posting->id);
echo 'same_user_three_reports_count='.$posting->fresh()->report_dead_count.' status='.$posting->fresh()->status."\n";
$controller = new App\Http\Controllers\JobApplicationImportExportController;
$csv = $controller->exportToCsv()->getContent();
$firstLine = explode("\n",$csv)[0];
$normalize = new ReflectionMethod($controller,'normalizeHeaders');
$validate = new ReflectionMethod($controller,'validateHeaders');
$normalized = $normalize->invoke($controller,str_getcsv(ltrim($firstLine,"\xEF\xBB\xBF")));
$validation = $validate->invoke($controller,$normalized);
echo 'export_first_row_is_valid_import_header='.($validation['valid']?'true':'false')."\n";
Http::assertNothingSent();
echo "COMPLETE: fixture-only workflow proofs; no external requests.\n";
