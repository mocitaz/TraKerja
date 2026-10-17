<?php

namespace App\Livewire\CvBuilder;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class MainBuilder extends Component
{
    public string $section = 'details';
    public ?string $headline = '';
    public ?string $phone_number = '';
    public ?string $domicile = '';
    public ?string $bio = '';
    public ?string $linkedin_url = '';
    public ?string $website_url = '';

    public function mount(): void
    {
        abort_unless(Auth::check(), 401);
        $profile = Auth::user()->profile;
        foreach (['headline', 'phone_number', 'domicile', 'bio', 'linkedin_url', 'website_url'] as $field) {
            $this->$field = $profile?->$field ?? '';
        }
    }

    public function selectSection(string $section): void
    {
        abort_unless(in_array($section, ['details', 'experiences', 'education', 'skills', 'projects', 'organizations', 'achievements'], true), 422);
        $this->section = $section;
    }

    public function saveDetails(): void
    {
        $data = $this->validate([
            'headline' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'domicile' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'linkedin_url' => 'nullable|url:http,https|max:255',
            'website_url' => 'nullable|url:http,https|max:255',
        ]);
        Auth::user()->profile()->updateOrCreate(['user_id' => Auth::id()], $data);
        $this->dispatch('cv-data-updated');
        $this->dispatch('showNotification', ['type' => 'success', 'title' => __('CV details saved'), 'message' => __('Your profile and CV now use these details.')]);
    }

    #[On('cv-data-updated')]
    public function refreshSections(): void {}

    public function render()
    {
        $user = Auth::user()->fresh();
        $sections = [
            ['key' => 'experiences', 'relation' => 'experiences', 'label' => 'Experience'],
            ['key' => 'education', 'relation' => 'educations', 'label' => 'Education'],
            ['key' => 'skills', 'relation' => 'skills', 'label' => 'Skills'],
            ['key' => 'projects', 'relation' => 'projects', 'label' => 'Projects'],
            ['key' => 'organizations', 'relation' => 'organizations', 'label' => 'Organizations'],
            ['key' => 'achievements', 'relation' => 'achievements', 'label' => 'Achievements'],
        ];
        $user->loadCount(array_column($sections, 'relation'));
        foreach ($sections as &$item) {
            $item['count'] = $user->{$item['relation'].'_count'};
        }
        unset($item);
        return view('livewire.cv-builder.main-builder', compact('user', 'sections'));
    }
}
