<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\{Auth,DB,Schema};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Tests\TestCase;
class ContactActivitySafetyTest extends TestCase
{
 public function createApplication(){ $app=require dirname(__DIR__,2).'/bootstrap/app.php';$app->useEnvironmentPath('/dev/null');$this->traitsUsedByTest=array_flip(class_uses_recursive(static::class));$app->make(Kernel::class)->bootstrap();return $app; }
 protected function setUp():void {parent::setUp();config(['app.key'=>str_repeat('c',32),'cache.default'=>'array','database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','logging.default'=>'null']);Schema::create('users',function(Blueprint $t){$t->id();$t->string('role')->default('user');$t->string('payment_status')->default('free');$t->timestamp('last_activity_at')->nullable();$t->timestamps();});DB::table('users')->insert(['id'=>1]);}
 public function test_activity_is_persisted_without_mass_assignment_and_throttled():void {
  Auth::setUser(User::findOrFail(1));$middleware=new \App\Http\Middleware\UpdateLastActivity;
  $middleware->handle(Request::create('/'),fn()=>response('ok'));
  $first=User::findOrFail(1)->last_activity_at;$this->assertNotNull($first);
  $this->travel(30)->seconds();$middleware->handle(Request::create('/'),fn()=>response('ok'));
  $this->assertTrue($first->equalTo(User::findOrFail(1)->last_activity_at));
  $this->travel(31)->seconds();$middleware->handle(Request::create('/'),fn()=>response('ok'));
  $this->assertTrue(User::findOrFail(1)->last_activity_at->greaterThan($first));
  $this->assertFalse((new User)->isFillable('last_activity_at'));
 }
 public function test_admin_contact_aliases_redirect_to_support_and_require_admin():void {
  $routes=app('router')->getRoutes();foreach(['admin.contact.index','admin.contact.show'] as $name){$this->assertContains('admin',$routes->getByName($name)->gatherMiddleware());}
  DB::table('users')->where('id',1)->update(['role'=>'admin']);Auth::setUser(User::findOrFail(1));
  $response=app(\App\Http\Controllers\ContactMessageController::class)->index();$this->assertSame(route('admin.feedbacks.index'),$response->getTargetUrl());
 }
 public function test_admin_support_index_returns_redirect_without_type_error():void {
  DB::table('users')->where('id',1)->update(['role'=>'admin']);Auth::setUser(User::findOrFail(1));
  $this->assertSame(route('admin.index'),app(\App\Http\Controllers\SupportTicketController::class)->index()->getTargetUrl());
 }
}
