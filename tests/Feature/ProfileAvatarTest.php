<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\AvatarCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileAvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_saves_a_catalog_avatar_that_does_not_change_on_profile_updates(): void
    {
        $this->post('/register', [
            'name' => 'New Member', 'email' => 'new-member@example.test',
            'password' => 'Password123!', 'password_confirmation' => 'Password123!',
        ])->assertSessionHasNoErrors();
        $user = User::where('email', 'new-member@example.test')->firstOrFail();
        $this->assertArrayHasKey($user->avatar_id ?? '', AvatarCatalog::all());
        $avatar = $user->avatar_id;
        $user->update(['name' => 'Updated Member']);
        $this->assertSame($avatar, $user->fresh()->avatar_id);
        $this->assertStringEndsWith('/images/avatars/'.$avatar.'.svg', $user->fresh()->avatar_url);
    }

    public function test_user_without_a_photo_gets_a_stable_local_avatar(): void
    {
        $user = User::factory()->create(['logo' => null]);
        $this->assertStringContainsString('/images/avatars/', $user->avatar_url);
        $this->assertSame($user->avatar_url, $user->fresh()->avatar_url);
        $this->actingAs($user)->get(route('profile.edit'))->assertOk()->assertSee('Choose an avatar');
    }

    public function test_selecting_an_avatar_replaces_only_the_actors_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('logos/old.png', 'old photo');
        $user = User::factory()->create(['logo' => 'logos/old.png']);
        $other = User::factory()->create(['avatar_id' => 'amber']);

        $this->actingAs($user)->patch(route('profile.avatar.update'), ['avatar_id' => 'rose', 'user_id' => $other->id])
            ->assertRedirect(route('profile.edit'))->assertSessionHasNoErrors();
        $this->assertSame('rose', $user->fresh()->avatar_id);
        $this->assertNull($user->fresh()->logo);
        $this->assertSame('amber', $other->fresh()->avatar_id);
        Storage::disk('public')->assertMissing('logos/old.png');
        $this->assertStringEndsWith('/images/avatars/rose.svg', $user->fresh()->avatar_url);
    }

    public function test_avatar_catalog_rejects_paths_and_guests(): void
    {
        $this->patchJson(route('profile.avatar.update'), ['avatar_id' => 'rose'])->assertUnauthorized();
        $user = User::factory()->create();
        $originalAvatar = $user->avatar_id;
        $this->actingAs($user)->patchJson(route('profile.avatar.update'), ['avatar_id' => '../../secret'])
            ->assertUnprocessable()->assertJsonValidationErrors('avatar_id');
        $this->assertSame($originalAvatar, $user->fresh()->avatar_id);
    }

    public function test_uploaded_photo_takes_precedence_and_removal_restores_selected_avatar(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['avatar_id' => 'rose', 'logo' => null]);
        $this->actingAs($user)->post(route('profile.photo.update'), ['logo' => UploadedFile::fake()->image('portrait.png')])
            ->assertRedirect(route('profile.edit'));
        $path = $user->fresh()->logo;
        Storage::disk('public')->assertExists($path);
        $this->assertStringContainsString('/storage/', $user->fresh()->avatar_url);
        $this->delete(route('profile.photo.destroy'))->assertRedirect(route('profile.edit'));
        Storage::disk('public')->assertMissing($path);
        $this->assertSame('rose', $user->fresh()->avatar_id);
        $this->assertStringEndsWith('/images/avatars/rose.svg', $user->fresh()->avatar_url);
    }

    public function test_missing_photo_falls_back_and_catalog_assets_are_valid_svg(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['logo' => 'logos/missing.png', 'avatar_id' => 'rose']);
        $this->assertStringEndsWith('/images/avatars/rose.svg', $user->avatar_url);
        foreach (AvatarCatalog::all() as $id => $label) {
            $path = public_path("images/avatars/{$id}.svg");
            $this->assertFileExists($path);
            $svg = simplexml_load_file($path);
            $this->assertSame('svg', $svg->getName());
        }
    }
}
