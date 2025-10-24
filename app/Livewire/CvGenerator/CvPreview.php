<?php

namespace App\Livewire\CvGenerator;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CvPreview extends Component
{
    public $showModal = false;
    public $template = 'minimal';
    public $user;
    public $experiences;
    public $educations;
    public $skills;
    public $organizations;
    public $achievements;
    public $projects;
    
    protected $listeners = ['openPreview' => 'openModal'];
    
    public function mount()
    {
        $this->loadUserData();
    }
    
    public function loadUserData()
    {
        $this->user = Auth::user();
        $this->user->load('profile'); // Load user profile untuk personal information
        $this->experiences = $this->user->experiences()->orderBy('display_order')->get();
        $this->educations = $this->user->educations()->orderBy('display_order')->get();
        $this->skills = $this->user->skills()->orderBy('display_order')->get();
        $this->organizations = $this->user->organizations()->orderBy('display_order')->get();
        $this->achievements = $this->user->achievements()->orderBy('display_order')->get();
        $this->projects = $this->user->projects()->orderBy('display_order')->get();
    }
    
    public function openModal($template = 'minimal')
    {
        $this->template = $template;
        $this->loadUserData();
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->dispatch('reset-body-overflow');
    }
    
    public function exportPdf()
    {
        // Redirect to export route
        return redirect()->route('cv.export', ['template' => $this->template]);
    }
    
    public function render()
    {
        return view('livewire.cv-generator.cv-preview');
    }
}
