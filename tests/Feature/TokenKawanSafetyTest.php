<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\{Cache,DB,Schema,Hash};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Tests\TestCase;
class TokenKawanSafetyTest extends TestCase
{
 public function createApplication(){ $app=require dirname(__DIR__,2).'/bootstrap/app.php';$app->useEnvironmentPath('/dev/null');$this->traitsUsedByTest=array_flip(class_uses_recursive(static::class));$app->make(Kernel::class)->bootstrap();return $app; }
 protected function setUp():void {parent::setUp();config(['app.key'=>str_repeat('k',32),'cache.default'=>'array','database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','logging.default'=>'null']);Schema::create('users',function(Blueprint $t){$t->id();$t->string('password');$t->string('remember_token')->nullable();$t->string('payment_status')->default('free');$t->timestamp('email_verified_at')->nullable();$t->timestamps();});DB::table('users')->insert(['id'=>1,'password'=>Hash::make('OldPassword1!'),'email_verified_at'=>now()]);Schema::create('personal_access_tokens',function(Blueprint $t){$t->id();$t->morphs('tokenable');$t->string('name');$t->string('token',64);$t->text('abilities')->nullable();$t->timestamp('last_used_at')->nullable();$t->timestamp('expires_at')->nullable();$t->timestamps();}); }
 public function test_password_rotation_revokes_tokens_and_changes_password_atomically():void {
  $user=User::findOrFail(1);$token=$user->createToken('Chrome extension')->plainTextToken;
  app(\App\Services\PasswordTokenRotation::class)->update($user,'NewPassword2!');
  $this->assertTrue(Hash::check('NewPassword2!', $user->fresh()->password));
  $this->assertNull(\Laravel\Sanctum\PersonalAccessToken::findToken($token));
 }
 private function request():Request {$r=Request::create('/kawankerja/chat','POST',[],[],[],['REMOTE_ADDR'=>'192.0.2.1']);$r->setUserResolver(fn()=>User::findOrFail(1));return $r;}
 public function test_provider_routes_register_admission_control():void {
  foreach(['kawankerja.chat','kawankerja.stream'] as $name){
   $this->assertContains(\App\Http\Middleware\LimitKawanKerja::class,app('router')->getRoutes()->getByName($name)->gatherMiddleware());
  }
 }
 public function test_shared_chat_stream_limits_block_parallel_work_and_release_after_stream():void {
  $middleware=new \App\Http\Middleware\LimitKawanKerja;
  $stream=$middleware->handle($this->request(),fn()=>response()->stream(fn()=>null));
  $blocked=$middleware->handle($this->request(),fn()=>response('must not execute'));
  $this->assertSame(429,$blocked->getStatusCode());
  ($stream->getCallback())();
  $this->assertSame(200,$middleware->handle($this->request(),fn()=>response('ok'))->getStatusCode());
 }
 public function test_account_budget_and_unverified_account_block_provider_work():void {
  $middleware=new \App\Http\Middleware\LimitKawanKerja;
  for($i=0;$i<10;$i++) $this->assertSame(200,$middleware->handle($this->request(),fn()=>response('ok'))->getStatusCode());
  $this->assertSame(429,$middleware->handle($this->request(),fn()=>response('wrong'))->getStatusCode());
  DB::table('users')->where('id',1)->update(['email_verified_at'=>null]);
  try{$middleware->handle($this->request(),fn()=>response('wrong'));$this->fail('Must reject unverified account');}catch(\Symfony\Component\HttpKernel\Exception\HttpException $e){$this->assertSame(403,$e->getStatusCode());}
 }
 public function test_failed_partial_stream_never_appends_fallback():void {
  $service=new class extends \App\Services\KawanKerjaService {
   public int $calls=0;
   public function __construct(){ $this->apiKey='fixture';$this->primaryModel='primary';$this->fallbackModel='fallback'; }
   public function buildUserContext(User $user):array{return [];}
   protected function executeStreamRequest(string $model,array $payload,callable $onChunk):?string {$this->calls++;$onChunk('Partial primary');return null;}
  };
  $chunks=[];$result=$service->chatStream('Hello',User::findOrFail(1),[],function($chunk)use(&$chunks){$chunks[]=$chunk;});
  $this->assertFalse($result['success']);$this->assertSame(['Partial primary'],$chunks);$this->assertSame(1,$service->calls);
 }
 public function test_budget_is_shared_over_the_day_and_exception_releases_concurrency_lock():void {
  $middleware=new \App\Http\Middleware\LimitKawanKerja;
  try{$middleware->handle($this->request(),fn()=>throw new \RuntimeException('fixture'));}catch(\RuntimeException $e){}
  $this->assertSame(200,$middleware->handle($this->request(),fn()=>response('ok'))->getStatusCode());
  for($i=2;$i<60;$i++){
   $this->travel(61)->seconds();
   $this->assertSame(200,$middleware->handle($this->request(),fn()=>response('ok'))->getStatusCode());
  }
  $this->travel(61)->seconds();
  $this->assertSame(429,$middleware->handle($this->request(),fn()=>response('wrong'))->getStatusCode());
 }
 public function test_device_revocation_is_owner_scoped():void {
  $user=User::findOrFail(1);$token=$user->createToken('Owned device')->accessToken;
  DB::table('users')->insert(['id'=>2,'password'=>Hash::make('Fixture2!')]);
  $request=Request::create('/profile/api-tokens/'.$token->id,'DELETE');$request->setUserResolver(fn()=>User::findOrFail(2));
  try{app(\App\Http\Controllers\ApiTokenController::class)->destroy($request,$token->id);$this->fail('Other owner token must not be revoked');}
  catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){$this->assertNotNull($token->fresh());}
  $request->setUserResolver(fn()=>$user);
  app(\App\Http\Controllers\ApiTokenController::class)->destroy($request,$token->id);
  $this->assertNull($token->fresh());
 }
}
