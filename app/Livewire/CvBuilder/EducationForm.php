<?php

namespace App\Livewire\CvBuilder;

use Livewire\Component;
use App\Models\UserEducation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EducationForm extends Component
{
    public $educations;
    public $showModal = false;
    public $editMode = false;
    public $editingId = null;
    public $showDeleteConfirm = false;
    public $deleteId = null;

    // Form fields
    public $institution_name;
    public $degree;
    public $major;
    public $gpa;
    public $location;
    public $start_date;
    public $end_date;
    public $is_current = false;
    public $description;

    // Degree types
    public $degreeTypes = [
        'SMA/SMK',
        'D3 (Diploma 3)',
        'D4 (Diploma 4)',
        'S1 (Sarjana)',
        'S2 (Magister)',
        'S3 (Doktor)',
        'Certificate',
        'Bootcamp',
        'Online Course'
    ];

    protected $listeners = ['closeAllModals' => 'closeModal'];

    protected $rules = [
        'institution_name' => 'required|string|max:255',
        'degree' => 'required|string|max:100',
        'major' => 'required|string|max:255',
        'gpa' => 'nullable|string|max:10',
        'location' => 'nullable|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after:start_date',
        'is_current' => 'boolean',
        'description' => 'nullable|string',
    ];

    public function mount()
    {
        $this->loadEducations();
    }

    public function loadEducations()
    {
        $this->educations = Auth::user()->educations()
            ->orderBy('display_order')
            ->get();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('reset-body-overflow');
    }

    public function resetForm()
    {
        $this->editMode = false;
        $this->editingId = null;
        $this->institution_name = '';
        $this->degree = '';
        $this->major = '';
        $this->gpa = '';
        $this->location = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->is_current = false;
        $this->description = '';
        $this->resetValidation();
    }

    public function edit($id)
    {
        $education = Auth::user()->educations()->findOrFail($id);

        $this->editMode = true;
        $this->editingId = $education->id;
        $this->institution_name = $education->institution_name;
        $this->degree = $education->degree;
        $this->major = $education->major;
        $this->gpa = $education->gpa;
        $this->location = $education->location;
        $this->start_date = $education->start_date;
        $this->end_date = $education->end_date;
        $this->is_current = $education->is_current;
        $this->description = $education->description;

        $this->showModal = true;
    }

    public function save()
    {
        $validator = Validator::make([
            'institution_name' => $this->institution_name,
            'degree'           => $this->degree,
            'major'            => $this->major,
            'gpa'              => $this->gpa,
            'location'         => $this->location,
            'start_date'       => $this->start_date,
            'end_date'         => $this->end_date,
            'is_current'       => $this->is_current,
            'description'      => $this->description,
        ], $this->rules, [
            'institution_name.required' => 'Institution name is required.',
            'degree.required'           => 'Degree / education level is required.',
            'major.required'            => 'Major / field of study is required.',
            'start_date.required'       => 'Start date is required.',
            'end_date.after'            => 'End date must be after start date.',
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
        if ($this->is_current) {
            $this->end_date = null;
        }

        $data = [
            'institution_name' => $this->institution_name,
            'degree' => $this->degree,
            'major' => $this->major,
            'gpa' => $this->gpa === '' ? null : $this->gpa,
            'location' => $this->location,
            'start_date' => $this->start_date === '' ? null : $this->start_date,
            'end_date' => $this->is_current ? null : ($this->end_date === '' ? null : $this->end_date),
            'is_current' => $this->is_current,
            'description' => $this->description,
        ];

        if ($this->editingId) {
            $education = Auth::user()->educations()->findOrFail($this->editingId);
            $education->update($data);
            session()->flash('message', 'Education updated successfully.');
        } else {
            $maxOrder = Auth::user()->educations()->max('display_order') ?? 0;
            $data['display_order'] = $maxOrder + 1;
            Auth::user()->educations()->create($data);
            session()->flash('message', 'Education added successfully.');
        }

        $this->closeModal();
        $this->dispatch('cv-data-updated');
        $this->loadEducations();
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

        Auth::user()->educations()->findOrFail($this->deleteId)->delete();

        $this->deleteId = null;
        $this->showDeleteConfirm = false;

        $this->dispatch('showNotification', [
            'type' => 'info',
            'title' => __('Deleted'),
            'message' => __('Education deleted successfully!'),
        ]);

        $this->dispatch('cv-data-updated');
        $this->loadEducations();
    }

    public function moveUp($id)
    {
        $education = Auth::user()->educations()->findOrFail($id);
        $previous = Auth::user()->educations()
            ->where('display_order', '<', $education->display_order)
            ->orderBy('display_order', 'desc')
            ->first();

        if ($previous) {
            $tempOrder = $education->display_order;
            $education->display_order = $previous->display_order;
            $previous->display_order = $tempOrder;
            $education->save();
            $previous->save();
        }

        $this->dispatch('cv-data-updated');
        $this->loadEducations();
    }

    public function moveDown($id)
    {
        $education = Auth::user()->educations()->findOrFail($id);
        $next = Auth::user()->educations()
            ->where('display_order', '>', $education->display_order)
            ->orderBy('display_order', 'asc')
            ->first();

        if ($next) {
            $tempOrder = $education->display_order;
            $education->display_order = $next->display_order;
            $next->display_order = $tempOrder;
            $education->save();
            $next->save();
        }

        $this->dispatch('cv-data-updated');
        $this->loadEducations();
    }

    public function render()
    {
        return view('livewire.cv-builder.education-form');
    }
}
