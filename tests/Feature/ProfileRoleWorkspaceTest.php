<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileRoleWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_profile_uses_admin_navigation_without_career_preferences(): void
    {
        $admin=User::factory()->create(['role'=>'admin','auto_archive_rejected'=>true]);
        $this->actingAs($admin)->get('/profile')->assertOk()->assertSee('Admin profile')->assertSee('Admin navigation')->assertSee('Back to admin dashboard')->assertDontSee('Professional profile')->assertDontSee('Auto-archive rejected applications')->assertDontSee('profile-tab-notifications');
        $this->patch('/profile',['name'=>$admin->name,'email'=>$admin->email])->assertRedirect('/profile');
        $this->assertTrue((bool)$admin->fresh()->auto_archive_rejected);
    }

    public function test_member_profile_keeps_career_and_notification_preferences(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'user']))->get('/profile')->assertOk()->assertSee('Profile settings')->assertDontSee('Professional profile')->assertSee('Account details')->assertSee('Your profile image')->assertDontSee('profile-tab-appearance')->assertSee('Auto-archive rejected applications')->assertSee('profile-tab-notifications')->assertDontSee('Admin navigation');
    }
}
