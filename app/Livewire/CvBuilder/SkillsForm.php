<?php

namespace App\Livewire\CvBuilder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\UserSkill;
use Livewire\Component;

class SkillsForm extends Component
{
    public $skills;
    public $showModal = false;
    public $editMode = false;
    public $skillId;
    public $showDeleteConfirm = false;
    public $deleteId = null;

    // Form fields
    public $skill_name = '';
    public $category = 'Technical';
    public $proficiency = 'Intermediate';
    public $years_of_experience = '';
    public $display_order = 0;

    public $categories = [
        'Technical',
        'Soft Skills',
        'Languages',
        'Tools & Software',
        'Frameworks',
        'Databases',
        'Other'
    ];

    public $proficiencyLevels = [
        'Beginner',
        'Intermediate',
        'Advanced',
        'Expert'
    ];

    protected $listeners = ['refreshSkills' => '$refresh', 'closeAllModals' => 'closeModal'];

    public function mount()
    {
        $this->loadSkills();
    }

    public function loadSkills()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->skills = $user->skills()
            ->orderBy('category')
            ->orderBy('display_order')
            ->get();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
        $this->display_order = $this->skills->count();
    }

    public function edit($id)
    {
        $skill = Auth::user()->skills()->findOrFail($id);

        if ($skill->user_id !== Auth::id()) {
            abort(403);
        }

        $this->skillId = $skill->id;
        $this->skill_name = $skill->skill_name;
        $this->category = $skill->category;
        $this->proficiency = $skill->proficiency_level;
        $this->years_of_experience = $skill->years_of_experience ?? '';
        $this->display_order = $skill->display_order;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $validator = Validator::make([
            'skill_name'          => $this->skill_name,
            'category'            => $this->category,
            'proficiency'         => $this->proficiency,
            'years_of_experience' => $this->years_of_experience,
        ], [
            'skill_name'          => 'required|string|max:255',
            'category'            => 'required|string|max:100',
            'proficiency'         => 'required|string|max:50',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
        ], [
            'skill_name.required' => 'Skill name is required.',
            'category.required'   => 'Category is required.',
            'proficiency.required' => 'Proficiency level is required.',
        ]);

        if ($validator->fails()) {
            $this->setErrorBag($validator->errors());
            $this->dispatch('showNotification', [
                'type'    => 'error',
                'title'   => __('Incomplete Form'),
                'message' => $validator->errors()->first(),
            ]);
            return;
        }
        $data = [
            'skill_name' => $this->skill_name,
            'category' => $this->category,
            'proficiency_level' => $this->proficiency,
            'years_of_experience' => $this->years_of_experience === '' ? null : $this->years_of_experience,
            'display_order' => $this->display_order,
        ];

        if ($this->editMode) {
            $skill = Auth::user()->skills()->findOrFail($this->skillId);
            $skill->update($data);
            $message = 'Skill updated successfully!';
        } else {
            Auth::user()->skills()->create($data);
            $message = 'Skill added successfully!';
        }

        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => __('Success'),
            'message' => $message,
        ]);

        $this->closeModal();
        $this->dispatch('cv-data-updated');
        $this->loadSkills();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete()
    {
        $this->deleteId = null;
        $this->showDeleteConfirm = false;
    }

    public function delete()
    {
        if (!$this->deleteId) return;

        $skill = Auth::user()->skills()->findOrFail($this->deleteId);

        if ($skill->user_id !== Auth::id()) {
            abort(403);
        }

        $skill->delete();

        $this->deleteId = null;
        $this->showDeleteConfirm = false;

        $this->dispatch('showNotification', [
            'type' => 'info',
            'title' => __('Deleted'),
            'message' => __('Skill deleted successfully!'),
        ]);

        $this->dispatch('cv-data-updated');
        $this->loadSkills();
    }

    public function moveUp($id)
    {
        $skill = Auth::user()->skills()->findOrFail($id);
        if ($skill->display_order > 0) {
            $swapWith = Auth::user()->skills()
                ->where('category', $skill->category)
                ->where('display_order', $skill->display_order - 1)
                ->first();

            if ($swapWith) {
                $swapWith->display_order = $skill->display_order;
                $swapWith->save();
            }

            $skill->display_order -= 1;
            $skill->save();
        }

        $this->dispatch('cv-data-updated');
        $this->loadSkills();
    }

    public function moveDown($id)
    {
        $skill = Auth::user()->skills()->findOrFail($id);
        $maxOrder = $this->skills->where('category', $skill->category)->max('display_order');

        if ($skill->display_order < $maxOrder) {
            $swapWith = Auth::user()->skills()
                ->where('category', $skill->category)
                ->where('display_order', $skill->display_order + 1)
                ->first();

            if ($swapWith) {
                $swapWith->display_order = $skill->display_order;
                $swapWith->save();
            }

            $skill->display_order += 1;
            $skill->save();
        }

        $this->dispatch('cv-data-updated');
        $this->loadSkills();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('reset-body-overflow');
    }

    private function resetForm()
    {
        $this->skillId = null;
        $this->skill_name = '';
        $this->category = 'Technical';
        $this->proficiency = 'Intermediate';
        $this->years_of_experience = '';
        $this->display_order = 0;
    }

    public function render()
    {
        return view('livewire.cv-builder.skills-form');
    }
}
