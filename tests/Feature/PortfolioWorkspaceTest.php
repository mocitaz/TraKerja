<?php
namespace Tests\Feature;

use App\Livewire\CvBuilder\PortfolioPublishModal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PortfolioWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_unpublished_sites_are_private_and_owner_can_preview_without_publishing(): void
    {
        $user = User::factory()->create(['portfolio_slug' => 'draft-owner', 'is_portfolio_published' => false]);
        $user->profile()->create(['bio' => 'Actual private summary']);
        $this->get('/@draft-owner')->assertNotFound();
        $this->get(route('portfolio.preview'))->assertRedirect(route('login'));
        $this->actingAs($user)->get(route('portfolio.preview', ['theme' => 'violet']))->assertOk()->assertSee('Private preview')->assertSee('Actual private summary');
        $this->assertFalse((bool) $user->fresh()->is_portfolio_published);
        $this->assertNotSame('violet', $user->fresh()->portfolio_theme);
        $this->actingAs(User::factory()->create())->get(route('portfolio.preview'))->assertDontSee('Actual private summary');
    }

    public function test_public_site_uses_real_content_and_safe_links_without_invented_fallbacks(): void
    {
        $user = User::factory()->create(['portfolio_slug' => 'public-owner', 'is_portfolio_published' => true]);
        $user->profile()->create(['bio' => '<script>bad()</script>Actual summary', 'phone_number' => 'PRIVATE_PHONE', 'linkedin_url' => 'javascript:alert(1)']);
        $user->projects()->create(['project_name' => 'Actual project', 'description' => 'Built something useful', 'project_url' => 'javascript:alert(1)', 'repository_url' => 'https://example.test/repo']);
        $this->get('/@public-owner')->assertOk()->assertSee('Actual project')->assertSee('https://example.test/repo', false)
            ->assertDontSee('href="javascript:', false)->assertDontSee('<script>bad()', false)->assertDontSee('PRIVATE_PHONE')
            ->assertDontSee('Creative Problem Solver')->assertDontSee('Dedicated to professional excellence');
    }

    public function test_publishing_requires_content_and_saves_only_current_user(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create(['portfolio_slug' => 'taken-name']);
        $modal = Livewire::actingAs($user)->test(PortfolioPublishModal::class)->call('openModal')
            ->set('slug', 'my-story')->call('publish')->assertHasErrors('content');
        $this->assertFalse((bool) $user->fresh()->is_portfolio_published);
        $user->skills()->create(['skill_name' => 'TypeScript', 'category' => 'Technical', 'proficiency_level' => 'Intermediate']);
        $modal->set('slug', 'taken-name')->call('publish')->assertHasErrors('slug');
        $modal->set('slug', 'my-story')->set('theme', 'violet')->call('publish')->assertHasNoErrors()->assertSet('isPublished', true);
        $this->assertSame('my-story', $user->fresh()->portfolio_slug);
        $this->assertSame('violet', $user->fresh()->portfolio_theme);
        $this->assertSame('taken-name', $other->fresh()->portfolio_slug);
        $this->get('/@my-story')->assertOk()->assertSee('TypeScript');
        $modal->call('unpublish')->assertSet('isPublished', false);
        $this->get('/@my-story')->assertNotFound();
    }

    public function test_edited_url_does_not_replace_live_link_until_saved_and_domains_are_not_claimed_connected(): void
    {
        $user = User::factory()->create(['portfolio_slug' => 'live-name', 'is_portfolio_published' => true, 'portfolio_custom_domain' => 'saved.example.test']);
        $user->profile()->create(['bio' => 'A real summary']);
        Livewire::actingAs($user)->test(PortfolioPublishModal::class)->set('slug', 'new-name')
            ->assertSeeHtml('href="'.url('/@live-name').'"')->assertDontSee('cname.trakerja.com')
            ->call('publish')->assertHasNoErrors();
        $this->assertSame('saved.example.test', $user->fresh()->portfolio_custom_domain);
        $this->actingAs($user)->get(route('cv.builder'))->assertOk()->assertSee('Open live site');
    }
}
