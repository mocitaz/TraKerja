<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CvOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public static function forms(): array
    {
        return [
            'skills' => ['SkillsForm', 'skills', 'skillId', ['skill_name' => 'Original', 'category' => 'Technical', 'proficiency_level' => 'Intermediate'], ['skill_name' => 'Changed', 'proficiency' => 'Expert']],
            'experience' => ['ExperienceForm', 'experiences', 'experienceId', ['company_name' => 'Original', 'position' => 'Engineer', 'start_date' => '2025-01-01'], ['company_name' => 'Changed', 'position' => 'Lead', 'start_date' => '2025-01-01']],
            'projects' => ['ProjectForm', 'projects', 'projectId', ['project_name' => 'Original', 'start_date' => '2025-01-01'], ['project_name' => 'Changed', 'start_date' => '2025-01-01', 'is_ongoing' => true]],
            'organizations' => ['OrganizationForm', 'organizations', 'organizationId', ['organization_name' => 'Original', 'role' => 'Member', 'start_date' => '2025-01-01'], ['organization_name' => 'Changed', 'position' => 'Lead', 'start_date' => '2025-01-01', 'is_current' => true]],
            'achievements' => ['AchievementForm', 'achievements', 'achievementId', ['title' => 'Original', 'issuer' => 'Institute', 'date' => '2025-01-01'], ['title' => 'Changed', 'issuer' => 'Institute', 'issue_date' => '2025-01-01', 'credential_url' => 'https://example.test/certificate']],
            'education' => ['EducationForm', 'educations', 'editingId', ['institution_name' => 'Original', 'degree' => 'S1', 'major' => 'IT', 'start_date' => '2025-01-01'], ['institution_name' => 'Changed', 'degree' => 'S1', 'major' => 'IT', 'start_date' => '2025-01-01', 'is_current' => true]],
        ];
    }

    public static function forbiddenActions(): array
    {
        $cases = [];
        foreach (self::forms() as $name => $form) {
            foreach (['edit', 'save', 'delete', 'moveUp', 'moveDown'] as $action) $cases[$name . ' ' . $action] = [...$form, $action];
        }
        return $cases;
    }

    #[DataProvider('forbiddenActions')]
    public function test_foreign_cv_entry_cannot_be_read_or_mutated(string $form, string $relation, string $idProperty, array $row, array $fields, string $action): void
    {
        $owner = User::factory()->create();
        $attacker = User::factory()->create();
        $foreign = $owner->$relation()->create($row + ['display_order' => 1]);
        $attacker->$relation()->create($row + ['display_order' => 0]);
        $attacker->$relation()->create($row + ['display_order' => 2]);
        $before = $foreign->fresh()->getAttributes();
        $this->actingAs($attacker);
        $class = 'App\\Livewire\\CvBuilder\\' . $form;
        $component = new $class;
        $component->mount();
        foreach ($fields as $field => $value) $component->$field = $value;
        $component->editMode = true;
        $component->$idProperty = $foreign->id;
        $component->deleteId = $foreign->id;
        $denied = false;
        try {
            in_array($action, ['save', 'delete']) ? $component->$action() : $component->$action($foreign->id);
        } catch (ModelNotFoundException $e) {
            $denied = true;
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
            $denied = true;
        }
        $this->assertTrue($denied, 'Foreign entry must be rejected at the action boundary');
        $this->assertSame($before, $foreign->fresh()->getAttributes());
        $this->assertSame(0, $attacker->$relation()->orderBy('display_order')->first()->display_order);
    }

    #[DataProvider('forms')]
    public function test_owner_can_create_edit_reorder_and_delete(string $form, string $relation, string $idProperty, array $row, array $fields): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $class = 'App\\Livewire\\CvBuilder\\' . $form;
        $component = new $class;
        $component->mount();
        foreach ($fields as $field => $value) $component->$field = $value;
        $component->save();
        $entry = $user->$relation()->firstOrFail();
        $this->assertSame($user->id, $entry->user_id);
        $component->edit($entry->id);
        foreach ($fields as $field => $value) $component->$field = $value;
        $component->save();
        $this->assertSame($user->id, $entry->fresh()->user_id);
        $user->$relation()->create($row + ['display_order' => $entry->fresh()->display_order + 1]);
        $component->mount();
        $order = $entry->fresh()->display_order;
        $component->moveDown($entry->id);
        $this->assertSame($order + 1, $entry->fresh()->display_order);
        $component->moveUp($entry->id);
        $this->assertSame($order, $entry->fresh()->display_order);
        if ($relation === 'skills') $this->assertSame('Expert', $entry->fresh()->proficiency_level);
        if ($relation === 'organizations') $this->assertSame('Lead', $entry->fresh()->role);
        if ($relation === 'achievements') {
            $this->assertSame('2025-01-01', $entry->fresh()->date->format('Y-m-d'));
            $this->assertSame('https://example.test/certificate', $entry->fresh()->certificate_url);
        }
        $component->deleteId = $entry->id;
        $component->delete();
        $this->assertDatabaseMissing($entry->getTable(), ['id' => $entry->id]);
    }

    public function test_livewire_tampered_skill_id_cannot_take_foreign_entry(): void
    {
        $owner = User::factory()->create();
        $attacker = User::factory()->create();
        $skill = $owner->skills()->create(['skill_name' => 'Private skill', 'category' => 'Technical']);
        $denied = false;
        try {
            \Livewire\Livewire::actingAs($attacker)->test(\App\Livewire\CvBuilder\SkillsForm::class)
                ->set('editMode', true)->set('skillId', $skill->id)
                ->set('skill_name', 'Stolen')->call('save');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            $denied = true;
        }
        $this->assertTrue($denied);
        $this->assertSame($owner->id, $skill->fresh()->user_id);
        $this->assertSame('Private skill', $skill->fresh()->skill_name);
    }

    public function test_achievement_rejects_invalid_certificate_url(): void
    {
        $user = User::factory()->create();
        \Livewire\Livewire::actingAs($user)->test(\App\Livewire\CvBuilder\AchievementForm::class)
            ->set('title', 'Certificate')->set('issuer', 'Institute')
            ->set('issue_date', '2025-01-01')->set('credential_url', 'invalid-url')
            ->call('save')->assertHasErrors(['credential_url']);
        $this->assertSame(0, $user->achievements()->count());
    }
}
