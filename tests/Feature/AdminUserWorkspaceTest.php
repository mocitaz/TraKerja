<?php

namespace Tests\Feature;

use App\Livewire\Admin\UserManagement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminUserWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_directory_filters_and_clear_filters_preserve_member_scope(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['name' => 'Directory Member', 'is_premium' => true, 'payment_status' => 'paid']);
        User::factory()->create(['name' => 'Other Member', 'is_premium' => false]);
        Livewire::actingAs($admin)->test(UserManagement::class)
            ->set('search', 'Directory Member')->set('filterPremium', 'premium')
            ->assertViewHas('users', fn ($users) => $users->pluck('id')->all() === [$member->id])
            ->call('resetFilters')->assertSet('search', '')->assertSet('filterPremium', 'all')
            ->assertViewHas('users', fn ($users) => $users->total() === 2);
    }

    public function test_manage_dialog_saves_profile_and_credits_separately(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['name' => 'Before edit', 'ai_credits' => 2]);
        $component = Livewire::actingAs($admin)->test(UserManagement::class)
            ->call('editUser', $member->id)->assertSet('showEditModal', true)->assertSee('Manage account')
            ->set('editName', 'Updated member')->call('updateUser')->assertHasNoErrors()->assertSet('showEditModal', false);
        $this->assertSame('Updated member', $member->fresh()->name);
        $this->assertSame(2, $member->fresh()->ai_credits);
        $component->call('editUser', $member->id)->set('editAiCredits', 7)->call('saveAiQuotas')->assertHasNoErrors();
        $this->assertSame(7, $member->fresh()->ai_credits);
    }

    public function test_duplicate_email_is_rejected_without_changing_the_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create();
        Livewire::actingAs($admin)->test(UserManagement::class)->call('editUser', $member->id)
            ->set('editEmail', $admin->email)->call('updateUser')->assertHasErrors('editEmail');
        $this->assertNotSame($admin->email, $member->fresh()->email);
    }

    public function test_actions_open_confirmation_and_cancel_without_mutation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['is_premium' => false]);
        Livewire::actingAs($admin)->test(UserManagement::class)
            ->call('openPremiumConfirmModal', $member->id)->assertSee('Grant premium access?')
            ->call('closePremiumConfirmModal')->assertSet('showPremiumConfirmModal', false)
            ->call('confirmDeleteUserModal', $member->id)->assertSee('Delete this account?')
            ->call('closeDeleteConfirmModal')->assertSet('showDeleteConfirmModal', false)
            ->call('openSendEmailModal', $member->id)->assertSee($member->email)
            ->call('closeSendEmailModal')->assertSet('showSendEmailModal', false);
        $this->assertFalse($member->fresh()->is_premium);
        $this->assertDatabaseHas('users', ['id' => $member->id]);
    }

    public function test_create_admin_checks_confirmation_and_accepts_matching_passwords(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $component = Livewire::actingAs($admin)->test(UserManagement::class)->call('openCreateAdminModal')
            ->set('newAdminName', 'New admin')->set('newAdminEmail', 'new-admin@example.test')
            ->set('newAdminPassword', 'StrongPassword123!')->set('newAdminPasswordConfirmation', 'Mismatch123!')
            ->call('createAdmin')->assertHasErrors('newAdminPassword');
        $component->set('newAdminPasswordConfirmation', 'StrongPassword123!')->call('createAdmin')
            ->assertHasNoErrors()->assertSet('showCreateAdminModal', false);
        $this->assertDatabaseHas('users', ['email' => 'new-admin@example.test', 'role' => 'admin']);
    }
}
