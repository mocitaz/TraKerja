<div>
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" 
             x-data="{ closing: false }" 
             x-init="document.body.style.overflow = 'hidden'"
             @keydown.escape.window="closing = true; document.body.style.overflow = 'auto'; $wire.closeModal()">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" 
                 @click="closing = true; document.body.style.overflow = 'auto'; $wire.closeModal()"></div>
            
            <!-- Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col">
                    
                    <!-- Modal Header -->
                    <div class="sticky top-0 z-10 bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">CV Preview</h3>
                                    <p class="text-white/80 text-sm">Review your CV before downloading</p>
                                </div>
                            </div>
                            <button type="button"
                                    @click="closing = true; document.body.style.overflow = 'auto'; $wire.closeModal()" 
                                    class="text-white/80 hover:text-white transition p-2 hover:bg-white/10 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Preview Content (Scrollable) -->
                    <div class="flex-1 overflow-y-auto p-8 bg-gray-50">
                        <div class="max-w-4xl mx-auto bg-white shadow-lg" style="min-height: 1000px;">
                            <!-- CV Content - Matches PDF Export Exactly -->
                            <div class="p-12" style="font-family: Arial, sans-serif; font-size: 10pt; line-height: 1.4; color: #000;">
                                
                                <!-- Header -->
                                <div class="mb-5">
                                    <h1 style="font-size: 18pt; font-weight: bold; margin-bottom: 8px; text-transform: uppercase;">{{ strtoupper($user->name) }}</h1>
                                    <div style="font-size: 9pt; color: #333; margin-bottom: 3px; line-height: 1.3;">
                                        @php
                                            $contactParts = [];
                                            if($user->profile && $user->profile->phone_number) {
                                                $contactParts[] = $user->profile->phone_number;
                                            }
                                            $contactParts[] = $user->email;
                                            if($user->profile && $user->profile->linkedin_url) {
                                                $contactParts[] = $user->profile->linkedin_url;
                                            }
                                            if($user->profile && $user->profile->github_url) {
                                                $contactParts[] = $user->profile->github_url;
                                            }
                                            echo implode(' | ', $contactParts);
                                        @endphp
                                    </div>
                                    @if($user->profile && $user->profile->domicile)
                                        <div style="font-size: 9pt; color: #333; line-height: 1.3;">{{ $user->profile->domicile }}</div>
                                    @endif
                                </div>

                                <!-- Profile Summary (Bio) -->
                                @if($user->profile && $user->profile->bio)
                                    <div class="mb-4" style="text-align: justify; line-height: 1.5;">
                                        {{ $user->profile->bio }}
                                    </div>
                                @endif

                                <!-- Work Experience -->
                                @if($experiences && $experiences->count() > 0)
                                    <div class="mb-5">
                                        <div style="font-size: 11pt; font-weight: bold; margin-bottom: 8px; padding-bottom: 3px; border-bottom: 2px solid #000; text-transform: uppercase;">Work Experiences</div>
                                        @foreach($experiences as $exp)
                                            <div class="mb-3">
                                                <div class="flex justify-between items-start mb-1">
                                                    <div>
                                                        <span style="font-weight: bold;">{{ $exp->company_name }}</span>
                                                        @if($exp->location)
                                                            <span> - {{ $exp->location }}</span>
                                                        @endif
                                                    </div>
                                                    <div style="text-align: right; white-space: nowrap; margin-left: 1rem;">
                                                        {{ $exp->start_date ? $exp->start_date->format('M Y') : '' }} - 
                                                        {{ $exp->is_current ? 'Present' : ($exp->end_date ? $exp->end_date->format('M Y') : '') }}
                                                    </div>
                                                </div>
                                                <div style="font-style: italic; margin-bottom: 3px;">{{ $exp->position }}</div>
                                                @if($exp->description)
                                                    <ul style="margin-left: 15px; margin-top: 3px;">
                                                        @foreach(explode("\n", $exp->description) as $point)
                                                            @if(trim($point))
                                                                <li style="margin-bottom: 2px; line-height: 1.4;">{{ trim($point, "• -") }}</li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Education -->
                                @if($educations && $educations->count() > 0)
                                    <div class="mb-5">
                                        <div style="font-size: 11pt; font-weight: bold; margin-bottom: 8px; padding-bottom: 3px; border-bottom: 2px solid #000; text-transform: uppercase;">Education Level</div>
                                        @foreach($educations as $edu)
                                            <div class="mb-3">
                                                <div class="flex justify-between items-start mb-1">
                                                    <div>
                                                        <span style="font-weight: bold;">{{ $edu->institution_name }}</span>
                                                        @if($edu->location)
                                                            <span> - {{ $edu->location }}</span>
                                                        @endif
                                                    </div>
                                                    <div style="text-align: right; white-space: nowrap; margin-left: 1rem;">
                                                        {{ $edu->start_date ? $edu->start_date->format('M Y') : '' }} - 
                                                        {{ $edu->is_current ? 'Present' : ($edu->end_date ? $edu->end_date->format('M Y') : '') }}
                                                    </div>
                                                </div>
                                                <div style="font-style: italic; margin-bottom: 2px;">
                                                    {{ $edu->degree }}{{ $edu->major ? ' of ' . $edu->major : '' }}{{ $edu->gpa ? ', ' . $edu->gpa : '' }}
                                                </div>
                                                @if($edu->description)
                                                    <ul style="margin-left: 15px; margin-top: 3px;">
                                                        @foreach(explode("\n", $edu->description) as $point)
                                                            @if(trim($point))
                                                                <li style="margin-bottom: 2px; line-height: 1.4;">{{ trim($point, "• -") }}</li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Skills, Achievements & Other Experience -->
                                @if(($skills && $skills->count() > 0) || ($projects && $projects->count() > 0) || ($achievements && $achievements->count() > 0))
                                    <div class="mb-5">
                                        <div style="font-size: 11pt; font-weight: bold; margin-bottom: 8px; padding-bottom: 3px; border-bottom: 2px solid #000; text-transform: uppercase;">Skills, Achievements & Other Experience</div>
                                        
                                        <!-- Skills by Category -->
                                        @foreach($skills->groupBy('category') as $category => $categorySkills)
                                            <div class="mb-2">
                                                <strong>{{ $category }} ({{ $categorySkills->first()->years_of_experience ?? date('Y') }}):</strong>
                                                @foreach($categorySkills as $skill){{ $skill->skill_name }}{{ !$loop->last ? ', ' : '' }}@endforeach
                                            </div>
                                        @endforeach
                                        
                                        <!-- Projects -->
                                        @foreach($projects as $project)
                                            <div class="mb-2">
                                                <strong>Projects ({{ $project->start_date ? $project->start_date->format('Y') : date('Y') }}):</strong>
                                                {{ $project->description ?? $project->project_name }}
                                            </div>
                                        @endforeach
                                        
                                        <!-- Achievements -->
                                        @foreach($achievements as $achievement)
                                            <div class="mb-2">
                                                <strong>{{ $achievement->title }}:</strong>
                                                {{ $achievement->description ?? $achievement->issuer }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Watermark for Free Users -->
                                @if(!$user->is_premium)
                                    <div class="text-center mt-8 pt-4" style="border-top: 1px solid #ccc; color: #999; font-size: 8pt;">
                                        Generated by TraKerja - Contact admin to remove watermark
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer (Sticky) -->
                    <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 rounded-b-2xl flex items-center justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            <p class="flex items-center gap-2 text-emerald-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Unlimited exports for all users
                            </p>
                        </div>
                        <div class="flex gap-3">
                            <button type="button"
                                    @click="closing = true; document.body.style.overflow = 'auto'; $wire.closeModal()"
                                    class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                                Close
                            </button>
                            <form action="{{ route('cv-builder.export') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="template" value="{{ $template }}">
                                <button type="submit" 
                                        class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium shadow-sm flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download PDF
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>

