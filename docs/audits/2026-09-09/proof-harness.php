<?php
// Synthetic fixtures only. Never connects to the configured application database.
$root = '/Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja';
foreach (['APP_ENV'=>'testing','DB_CONNECTION'=>'sqlite','DB_DATABASE'=>':memory:','CACHE_STORE'=>'array','SESSION_DRIVER'=>'array','MAIL_MAILER'=>'array','LOG_CHANNEL'=>'null','APP_CONFIG_CACHE'=>'/private/tmp/nonexistent-trakerja-audit-config.php'] as $key=>$value) { putenv("$key=$value"); $_ENV[$key]=$value; $_SERVER[$key]=$value; }
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\{DB,Schema,Auth,Http,Mail};
use Illuminate\Database\Schema\Blueprint;
use App\Models\{User,UserSkill,SupportTicket,Payment};
config(['database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','cache.default'=>'array','logging.default'=>'null']);
DB::purge();
if(DB::connection()->getDatabaseName() !== ':memory:') throw new RuntimeException('Isolation failed');
Mail::fake(); Http::fake(); Http::preventStrayRequests();
Schema::create('users',function(Blueprint $t){$t->id();$t->string('name');$t->string('email');$t->string('password')->nullable();$t->string('role')->default('user');$t->boolean('is_admin')->default(false);$t->boolean('is_premium')->default(false);$t->string('payment_status')->default('free');$t->integer('ai_credits')->default(0);$t->integer('cl_credits')->default(0);$t->integer('photo_credits')->default(0);$t->timestamp('premium_until')->nullable();$t->timestamps();});
Schema::create('user_skills',function(Blueprint $t){$t->id();$t->integer('user_id');$t->string('skill_name');$t->string('category');$t->string('proficiency_level')->default('Intermediate');$t->integer('years_of_experience')->nullable();$t->integer('display_order')->default(0);$t->timestamps();});
Schema::create('support_tickets',function(Blueprint $t){$t->id();$t->integer('user_id');$t->string('subject');$t->text('message');$t->string('status')->default('pending');$t->timestamps();});
Schema::create('payments',function(Blueprint $t){$t->id();$t->integer('user_id');$t->string('order_id');$t->integer('amount');$t->string('status');$t->string('notes');$t->text('webhook_data')->nullable();$t->timestamp('paid_at')->nullable();$t->softDeletes();$t->timestamps();});
Schema::create('webhook_logs',function(Blueprint $t){$t->id();foreach(['provider','transaction_id','reference_id','event_type','payload','status','notes'] as $f)$t->text($f)->nullable();$t->timestamps();});
Schema::create('settings',function(Blueprint $t){$t->id();$t->string('key');$t->text('value')->nullable();$t->timestamps();});
$a=User::create(['name'=>'Synthetic A','email'=>'a@example.test']);$b=User::create(['name'=>'Synthetic B','email'=>'b@example.test']);Auth::setUser($a);
$ticket=SupportTicket::create(['user_id'=>$b->id,'subject'=>'Synthetic','message'=>'Synthetic']);
$request=Illuminate\Http\Request::create('/admin/feedbacks/'.$ticket->id.'/status','PATCH',['status'=>'completed']);
(new App\Http\Controllers\Admin\AdminSupportController)->updateStatus($request,$ticket);
echo 'ticket_other_user_status='.$ticket->fresh()->status."\n";
$skill=UserSkill::create(['user_id'=>$b->id,'skill_name'=>'Original','category'=>'Technical']);
$component=new App\Livewire\CvBuilder\SkillsForm;
$component->editMode=true;$component->skillId=$skill->id;$component->skill_name='Synthetic replacement';$component->proficiency='Expert';
$component->save();
echo 'skill_owner_after_save='.$skill->fresh()->user_id.' attacker='.$a->id.' proficiency='.$skill->fresh()->proficiency_level."\n";
$pay=Payment::create(['user_id'=>$a->id,'order_id'=>'AUDIT-SYNTHETIC','amount'=>14999,'status'=>'PENDING','notes'=>'addon_10']);
$controller=new App\Http\Controllers\PaymentController;
foreach(['completed','failed','completed'] as $i=>$status){
 $r=Illuminate\Http\Request::create('/payment/webhook','POST',['order_id'=>$pay->order_id,'amount'=>14999,'status'=>$status,'transaction_id'=>'synthetic-'.$i]);
 $response=$controller->webhook($r);
 echo 'unsigned_webhook_'.$status.' http='.$response->getStatusCode().' payment='.$pay->fresh()->status.' credits='.$a->fresh()->ai_credits."\n";
}
Http::assertNothingSent();echo "payment_provider_requests=0\n";
Http::fake(['http://127.0.0.1:9999/*'=>Http::response('<title>Synthetic internal service</title>',200)]);
$r=Illuminate\Http\Request::create('/jobs/scrape-url','POST',['url'=>'http://127.0.0.1:9999/synthetic']);
$response=(new App\Http\Controllers\JobScraperController)->scrapeUrl($r);
Http::assertSent(fn($r)=>str_starts_with($r->url(),'http://127.0.0.1:9999/'));
echo 'scraper_private_target_sent_to_fake=true http='.$response->getStatusCode()."\n";
try{(new App\Http\Controllers\ContactMessageController)->index();}catch(Throwable $e){echo 'contact_index_error='.$e->getMessage()."\n";}
$a->forceFill(['is_premium'=>true,'payment_status'=>'paid','premium_until'=>now()->subDay()]);echo 'expired_premium_isPremium='.($a->isPremium()?'true':'false')."\n";
echo 'premium_until_fillable='.($a->isFillable('premium_until')?'true':'false')."\n";
$router=$app->make('router');foreach(['/admin/feedbacks','/admin/scraper','/api/extension/login'] as $path){$r=Illuminate\Http\Request::create($path,str_contains($path,'login')?'POST':'GET');$route=$router->getRoutes()->match($r);echo $path.' middleware='.json_encode($router->gatherRouteMiddleware($route))."\n";}
echo "COMPLETE: synthetic memory database, fake HTTP and mail; controller-level proofs, not full browser tests.\n";
