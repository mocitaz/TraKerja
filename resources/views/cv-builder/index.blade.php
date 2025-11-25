<x-app-layout>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Progress Indicator -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 sm:mb-6 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3 sm:mb-4">
                    <h3 class="text-base sm:text-lg font-semibold">Profile Completion</h3>
                    <a href="{{ route('cv.generator') }}" class="w-full sm:w-auto justify-center px-3 sm:px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors duration-200 flex items-center gap-2 text-sm">
                        <i class="fas fa-file-pdf"></i>
                        Generate CV
                    </a>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
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
                    <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
                <p class="text-xs sm:text-sm text-gray-600 mt-2">{{ number_format($percentage, 0) }}% Complete</p>
            </div>

            <!-- Tabs Navigation -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6" x-data="{ activeTab: 'experiences' }" x-init="$watch('activeTab', () => { Livewire.dispatch('closeAllModals') })">
                <div class="border-b border-gray-200">
                    <!-- Mobile: Scrollable tabs -->
                    <div class="block sm:hidden">
                        <nav class="flex space-x-1 px-3 py-2 overflow-x-auto scrollbar-hide">
                            <button @click="activeTab = 'experiences'" 
                                    :class="activeTab === 'experiences' ? 'bg-primary-100 text-primary-700 border-primary-200' : 'bg-gray-50 text-gray-600 border-gray-200'"
                                    class="flex-shrink-0 px-3 py-2 rounded-lg border text-xs font-medium transition-colors">
                                <i class="fas fa-briefcase mr-1"></i>Experience
                            </button>
                            <button @click="activeTab = 'education'" 
                                    :class="activeTab === 'education' ? 'bg-primary-100 text-primary-700 border-primary-200' : 'bg-gray-50 text-gray-600 border-gray-200'"
                                    class="flex-shrink-0 px-3 py-2 rounded-lg border text-xs font-medium transition-colors">
                                <i class="fas fa-graduation-cap mr-1"></i>Education
                            </button>
                            <button @click="activeTab = 'skills'" 
                                    :class="activeTab === 'skills' ? 'bg-primary-100 text-primary-700 border-primary-200' : 'bg-gray-50 text-gray-600 border-gray-200'"
                                    class="flex-shrink-0 px-3 py-2 rounded-lg border text-xs font-medium transition-colors">
                                <i class="fas fa-star mr-1"></i>Skills
                            </button>
                            <button @click="activeTab = 'organizations'" 
                                    :class="activeTab === 'organizations' ? 'bg-primary-100 text-primary-700 border-primary-200' : 'bg-gray-50 text-gray-600 border-gray-200'"
                                    class="flex-shrink-0 px-3 py-2 rounded-lg border text-xs font-medium transition-colors">
                                <i class="fas fa-users mr-1"></i>Organizations
                            </button>
                            <button @click="activeTab = 'achievements'" 
                                    :class="activeTab === 'achievements' ? 'bg-primary-100 text-primary-700 border-primary-200' : 'bg-gray-50 text-gray-600 border-gray-200'"
                                    class="flex-shrink-0 px-3 py-2 rounded-lg border text-xs font-medium transition-colors">
                                <i class="fas fa-trophy mr-1"></i>Achievements
                            </button>
                            <button @click="activeTab = 'projects'" 
                                    :class="activeTab === 'projects' ? 'bg-primary-100 text-primary-700 border-primary-200' : 'bg-gray-50 text-gray-600 border-gray-200'"
                                    class="flex-shrink-0 px-3 py-2 rounded-lg border text-xs font-medium transition-colors">
                                <i class="fas fa-code mr-1"></i>Projects
                            </button>
                        </nav>
                    </div>
                    
                    <!-- Desktop: Full tabs -->
                    <div class="hidden sm:block">
                        <nav class="-mb-px flex space-x-6 lg:space-x-8 px-4 sm:px-6">
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
                </div>

                <!-- Tab Contents -->
                <div class="p-4 sm:p-6">
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
            <div class="bg-primary-50 border border-primary-200 rounded-lg p-3 sm:p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-primary-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-primary-800">
                            Complete your profile to generate professional CV
                        </h3>
                        <div class="mt-2 text-xs sm:text-sm text-primary-700">
                            <p>Fill in your work experience, education, skills, and other sections. Once complete, tap "Generate CV" to create your professional resume!</p>
                            @if(!auth()->user() || !(auth()->user()->is_premium && auth()->user()->payment_status === 'paid'))
                                <p class="mt-2 font-semibold">
                                    You have access to {{ auth()->user() ? auth()->user()->getCvTemplatesCount() : 1 }} CV template(s). 
                                    <a href="#" class="underline">Upgrade to Premium</a> for 4 templates and unlimited customization!
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
        
        /* Hide scrollbar for mobile tabs */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        /* Smooth scrolling for mobile tabs */
        .overflow-x-auto {
            scroll-behavior: smooth;
        }
    </style>
</x-app-layout>
