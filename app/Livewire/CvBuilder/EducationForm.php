<?php

namespace App\Livewire\CvBuilder;

use Livewire\Component;
use App\Models\UserEducation;
use Illuminate\Support\Facades\Auth;

class EducationForm extends Component
{
    public $educations;
    public $showModal = false;
    public $editMode = false;
    public $editingId = null;
    
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
        $this->educations = UserEducation::where('user_id', Auth::id())
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
        $education = UserEducation::where('user_id', Auth::id())->findOrFail($id);
        
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
        $this->validate();

        if ($this->is_current) {
            $this->end_date = null;
        }

        $data = [
            'user_id' => Auth::id(),
            'institution_name' => $this->institution_name,
            'degree' => $this->degree,
            'major' => $this->major,
            'gpa' => $this->gpa,
            'location' => $this->location,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_current' => $this->is_current,
            'description' => $this->description,
        ];

        if ($this->editingId) {
            $education = UserEducation::where('user_id', Auth::id())->findOrFail($this->editingId);
            $education->update($data);
            session()->flash('message', 'Education updated successfully.');
        } else {
            $maxOrder = UserEducation::where('user_id', Auth::id())->max('display_order') ?? 0;
            $data['display_order'] = $maxOrder + 1;
            UserEducation::create($data);
            session()->flash('message', 'Education added successfully.');
        }

        $this->closeModal();
        $this->loadEducations();
    }

    public function delete($id)
    {
        UserEducation::where('user_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('message', 'Education deleted successfully.');
        $this->loadEducations();
    }

    public function moveUp($id)
    {
        $education = UserEducation::where('user_id', Auth::id())->findOrFail($id);
        $previous = UserEducation::where('user_id', Auth::id())
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

        $this->loadEducations();
    }

    public function moveDown($id)
    {
        $education = UserEducation::where('user_id', Auth::id())->findOrFail($id);
        $next = UserEducation::where('user_id', Auth::id())
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

        $this->loadEducations();
    }

    public function render()
    {
        return view('livewire.cv-builder.education-form');
    }
}
