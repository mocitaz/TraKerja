<?php
namespace Tests\Feature;

use App\Models\{User,UserResume};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileSettingsWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    private function initialPanel(string $html, string $tab): \DOMElement
    {
        $dom=new \DOMDocument; @$dom->loadHTML($html);
        return (new \DOMXPath($dom))->query('//*[@id="profile-panel-'.$tab.'"]')->item(0);
    }

    public function test_direct_section_links_and_token_success_open_security(): void
    {
        $this->actingAs(User::factory()->create());
        $response=$this->get('/profile?tab=security')->assertOk();
        $this->assertFalse($this->initialPanel($response->getContent(),'security')->hasAttribute('x-cloak'));
        $response=$this->withSession(['status'=>'token-revoked'])->get('/profile')->assertOk();
        $this->assertFalse($this->initialPanel($response->getContent(),'security')->hasAttribute('x-cloak'));
    }

    public function test_administrator_receives_the_same_accessible_deletion_dialog(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']));
        $this->get('/profile')->assertOk()->assertSee('profile-dialog-title')->assertSee('data-dialog-close="closeDialog()"',false)
            ->assertSee('profile-delete-password')->assertSee('Revoke device access?')->assertDontSee('onsubmit="return confirm',false);
    }

    public function test_resume_labels_are_escaped_and_destructive_actions_use_a_review_dialog(): void
    {
        $user=User::factory()->create();
        UserResume::create(['user_id'=>$user->id,'title'=>"Candidate's <script>CV</script>",'file_path'=>'resumes/'.$user->id.'/qa.pdf','file_name'=>'qa.pdf','file_size'=>150,'file_type'=>'application/pdf','is_primary'=>true]);
        $this->actingAs($user)->get('/profile?tab=resumes')->assertOk()->assertSee('Candidate&#039;s &lt;script&gt;CV&lt;/script&gt;',false)
            ->assertDontSee('<script>CV</script>',false)->assertSee('Keep resume')->assertSee('Resume label')->assertDontSee('onsubmit="return confirm',false);
    }

    public function test_invalid_resume_label_keeps_the_original_record_and_returns_to_resumes(): void
    {
        Storage::fake('local');$user=User::factory()->create();
        $resume=UserResume::create(['user_id'=>$user->id,'title'=>'Original CV','file_path'=>'resumes/'.$user->id.'/qa.pdf','file_name'=>'qa.pdf','file_size'=>150,'file_type'=>'application/pdf','is_primary'=>true]);
        $this->actingAs($user)->from('/profile?tab=resumes')->patch(route('user-resumes.update',$resume),['title'=>'','resume_id'=>$resume->id,'profile_dialog'=>'resume-rename'])->assertSessionHasErrors('title')->assertSessionHasInput('resume_id',$resume->id);
        $this->assertSame('Original CV',$resume->fresh()->title);
        $response=$this->get('/profile?tab=resumes')->assertOk();
        $this->assertFalse($this->initialPanel($response->getContent(),'resumes')->hasAttribute('x-cloak'));
    }

    public function test_account_deletion_with_a_remember_token_does_not_reinsert_the_account_on_logout(): void
    {
        Storage::fake('local');
        $user=User::factory()->create(['remember_token'=>str_repeat('r',60)]);
        $other=User::factory()->create();
        $this->actingAs($user)->delete('/profile',['password'=>'password'])->assertRedirect('/')->assertSessionHasNoErrors();
        $this->assertGuest();$this->assertDatabaseMissing('users',['id'=>$user->id]);$this->assertModelExists($other);
    }
}
