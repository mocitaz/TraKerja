<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('CV Builder') }} 
                @if(is_premium(auth()->user()))
                    <span class="ml-2 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">PREMIUM</span>
                @endif
            </h2>
            <a href="{{ route('cv.generator') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                <i class="fas fa-file-pdf mr-2"></i>Generate CV
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Progress Indicator -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <h3 class="text-lg font-semibold mb-4">Profile Completion</h3>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    @php
                        $total = 0;
                        $filled = 0;
                        
                        if ($profile) $filled++;
                        $total++;
                        
                        if ($experiences->count() > 0) $filled++;
                        $total++;
                        
                        if ($educations->count() > 0) $filled++;
                        $total++;
                        
                        if ($skills->count() > 0) $filled++;
                        $total++;
                        
                        $percentage = ($filled / $total) * 100;
                    @endphp
                    <div class="bg-primary-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
                <p class="text-sm text-gray-600 mt-2">{{ number_format($percentage, 0) }}% Complete</p>
            </div>

            <!-- Tabs Navigation -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6" x-data="{ activeTab: 'experiences' }" x-init="$watch('activeTab', () => { Livewire.dispatch('closeAllModals') })">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6">
                        <button @click="activeTab = 'experiences'" 
                                :class="activeTab === 'experiences' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-briefcase mr-2"></i>Work Experience
                        </button>
                        <button @click="activeTab = 'education'" 
                                :class="activeTab === 'education' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-graduation-cap mr-2"></i>Education
                        </button>
                        <button @click="activeTab = 'skills'" 
                                :class="activeTab === 'skills' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-star mr-2"></i>Skills
                        </button>
                        <button @click="activeTab = 'organizations'" 
                                :class="activeTab === 'organizations' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-users mr-2"></i>Organizations
                        </button>
                        <button @click="activeTab = 'achievements'" 
                                :class="activeTab === 'achievements' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-trophy mr-2"></i>Achievements
                        </button>
                        <button @click="activeTab = 'projects'" 
                                :class="activeTab === 'projects' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-code mr-2"></i>Projects
                        </button>
                    </nav>
                </div>

                <!-- Tab Contents -->
                <div class="p-6">
                    <div x-show="activeTab === 'experiences'">
                        @livewire('cv-builder.experience-form')
                    </div>
                    <div x-show="activeTab === 'education'" x-cloak>
                        @livewire('cv-builder.education-form')
                    </div>
                    <div x-show="activeTab === 'skills'" x-cloak>
                        @livewire('cv-builder.skills-form')
                    </div>
                    <div x-show="activeTab === 'organizations'" x-cloak>
                        @livewire('cv-builder.organization-form')
                    </div>
                    <div x-show="activeTab === 'achievements'" x-cloak>
                        @livewire('cv-builder.achievement-form')
                    </div>
                    <div x-show="activeTab === 'projects'" x-cloak>
                        @livewire('cv-builder.project-form')
                    </div>
                </div>
            </div>

            <!-- Help Text -->
            <div class="bg-primary-50 border border-primary-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-primary-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-primary-800">
                            Complete your profile to generate professional CV
                        </h3>
                        <div class="mt-2 text-sm text-primary-700">
                            <p>Fill in your work experience, education, skills, and other sections. Once complete, click "Generate CV" to create your professional resume!</p>
                            @if(!is_premium(auth()->user()))
                                <p class="mt-2 font-semibold">
                                    You have access to {{ cv_templates_count() }} CV template(s). 
                                    <a href="#" class="underline">Upgrade to Premium</a> for 5 templates and unlimited customization!
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
