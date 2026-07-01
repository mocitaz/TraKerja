<x-app-layout>
    <div class="bg-[#f0f4f9] min-h-screen pb-20">
        <div class="max-w-[770px] mx-auto px-4 pt-6 space-y-3.5">
            
            @if($hasCompleted)
                {{-- THANK YOU STATE --}}
                <div class="bg-white border border-zinc-200 rounded-lg shadow-sm overflow-hidden mt-10">
                    <div class="h-2 bg-primary-500"></div>
                    <div class="p-6 sm:p-8 text-center space-y-5 max-w-md mx-auto">
                        <div class="w-14 h-14 bg-emerald-50 border border-emerald-100 rounded-full flex items-center justify-center text-emerald-600 mx-auto shadow-2xs">
                            <i class="ph-bold ph-heart text-2xl animate-pulse"></i>
                        </div>
                        
                        <div class="space-y-2">
                            <h2 class="text-base font-bold text-zinc-900 tracking-tight">Thank You Very Much!</h2>
                            <p class="text-xs text-zinc-500 leading-relaxed">
                                Your valuable feedback has been saved. It helps us tremendously in improving TraKerja features.
                            </p>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('tracker') }}" class="inline-flex items-center justify-center w-full py-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded-md text-xs font-bold uppercase tracking-wider transition-colors">
                                Go to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            @else
                {{-- SURVEY FORM STATE --}}
                <form action="{{ route('survey.submit') }}" method="POST" class="space-y-3.5">
                    @csrf

                    {{-- Card 1: Form Header (Google Forms Style) --}}
                    <div class="bg-white border border-zinc-200 rounded-lg shadow-2xs overflow-hidden">
                        <div class="h-2.5 bg-primary-500"></div>
                        <div class="p-5 sm:p-6 space-y-3">
                            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 tracking-tight">TraKerja Service Evaluation & Satisfaction Survey</h1>
                            <p class="text-xs text-zinc-650 leading-relaxed text-justify">
                                Your feedback helps us continuously improve and personalize TraKerja. Please take a moment to answer the questions below.
                            </p>
                            <div class="pt-2 border-t border-zinc-100 flex items-center gap-1 text-[10px] text-red-600 font-semibold font-mono">
                                <span>*</span>
                                <span>Indicates required question</span>
                            </div>
                        </div>
                    </div>

                    {{-- Warning Banner Card --}}
                    <div class="bg-amber-50/70 border border-amber-200 rounded-lg p-3.5 flex items-start gap-3 shadow-2xs">
                        <i class="ph-bold ph-warning-circle text-base text-amber-600 shrink-0 mt-0.5"></i>
                        <div class="text-[11px] text-amber-800 leading-relaxed">
                            <p class="font-bold">Temporary Limited Access</p>
                            <p class="mt-0.5">Your account has been selected for periodic feedback. Please complete this survey to reactivate full access to Job Tracker and AI Studio.</p>
                        </div>
                    </div>

                    {{-- Dynamic Question Cards --}}
                    @php
                        $questions = [
                            'q1_overall' => [
                                'num' => 1,
                                'title' => 'Overall Satisfaction',
                                'desc' => 'How satisfied are you with TraKerja overall?',
                                'low' => 'Highly Dissatisfied',
                                'high' => 'Highly Satisfied'
                            ],
                            'q2_navigation' => [
                                'num' => 2,
                                'title' => 'Ease of Navigation & Menu Structure',
                                'desc' => 'How easy is it to navigate using the sidebar and menus in TraKerja?',
                                'low' => 'Highly Difficult',
                                'high' => 'Highly Easy'
                            ],
                            'q3_speed' => [
                                'num' => 3,
                                'title' => 'Page Speed & Performance',
                                'desc' => 'How would you rate the loading speed of the pages?',
                                'low' => 'Highly Slow',
                                'high' => 'Highly Fast'
                            ],
                            'q4_cv_builder' => [
                                'num' => 4,
                                'title' => 'Resume & CV Builder Quality',
                                'desc' => 'How helpful has the CV Builder feature been for you?',
                                'low' => 'Not Helpful',
                                'high' => 'Highly Helpful'
                            ],
                            'q5_ai_analyzer' => [
                                'num' => 5,
                                'title' => 'AI Analyzer Review Accuracy',
                                'desc' => 'How would you rate the usefulness of the AI Analyzer in reviewing resumes?',
                                'low' => 'Not Useful',
                                'high' => 'Highly Useful'
                            ],
                            'q6_job_tracker' => [
                                'num' => 6,
                                'title' => 'Job Tracker Effectiveness',
                                'desc' => 'How easy is it to manage & update your job application statuses?',
                                'low' => 'Highly Difficult',
                                'high' => 'Highly Easy'
                            ],
                            'q7_cover_letter' => [
                                'num' => 7,
                                'title' => 'AI Cover Letter Generator',
                                'desc' => 'How satisfied are you with the quality of the cover letters generated by AI?',
                                'low' => 'Dissatisfied',
                                'high' => 'Highly Satisfied'
                            ],
                            'q8_interviews' => [
                                'num' => 8,
                                'title' => 'Interview Management & Scheduling',
                                'desc' => 'How helpful has the interview scheduling and logging feature been?',
                                'low' => 'Not Helpful',
                                'high' => 'Highly Helpful'
                            ],
                            'q9_premium' => [
                                'num' => 9,
                                'title' => 'Premium Service Value',
                                'desc' => 'How would you rate the value for money of the Premium plans?',
                                'low' => 'Highly Poor',
                                'high' => 'Highly Good'
                            ],
                            'q10_recommend' => [
                                'num' => 10,
                                'title' => 'Recommendation Likelihood (Net Promoter Score)',
                                'desc' => 'How likely are you to recommend TraKerja to a friend or colleague?',
                                'low' => 'Highly Unlikely',
                                'high' => 'Highly Likely'
                            ],
                            'q11_design' => [
                                'num' => 11,
                                'title' => 'Visual Design & Aesthetics',
                                'desc' => 'How would you rate the visual aesthetics (Notion-Cupertino style)?',
                                'low' => 'Highly Poor',
                                'high' => 'Highly Aesthetic'
                            ],
                            'q12_cv_templates' => [
                                'num' => 12,
                                'title' => 'CV Templates Design & PDF Export',
                                'desc' => 'How would you rate the layout quality and PDF export of templates in CV Builder?',
                                'low' => 'Highly Poor',
                                'high' => 'Highly Good'
                            ]
                        ];
                    @endphp

                    @foreach($questions as $key => $q)
                        <div class="bg-white border border-zinc-200 rounded-lg p-5 space-y-3.5 shadow-2xs">
                            {{-- Title & Desc --}}
                            <div class="space-y-1">
                                <h3 class="text-xs sm:text-sm font-bold text-zinc-900 leading-snug">
                                    {{ $q['num'] }}. {{ $q['title'] }} <span class="text-red-500 font-mono ml-0.5">*</span>
                                </h3>
                                <p class="text-[11px] text-zinc-500 leading-relaxed">{{ $q['desc'] }}</p>
                            </div>
                            
                            {{-- GForm Linear Scale Row --}}
                            <div class="flex items-center justify-between gap-4 py-3 px-2 overflow-x-auto border border-zinc-100 rounded-lg bg-zinc-50/20">
                                <span class="text-[10px] sm:text-xs text-zinc-500 font-medium max-w-[80px] sm:max-w-[100px] leading-tight shrink-0">{{ $q['low'] }}</span>
                                
                                <div class="flex items-center justify-center gap-5 sm:gap-7 flex-1 min-w-[200px]">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex flex-col items-center gap-1.5 cursor-pointer group">
                                            <span class="text-[10px] font-bold text-zinc-400 group-hover:text-zinc-800 transition-colors font-mono">{{ $i }}</span>
                                            <input type="radio" name="{{ $key }}" value="{{ $i }}" class="w-4 h-4 text-zinc-900 border-zinc-300 focus:ring-zinc-950 focus:ring-offset-1 cursor-pointer" required {{ old($key) == $i ? 'checked' : '' }}>
                                        </label>
                                    @endfor
                                </div>
                                
                                <span class="text-[10px] sm:text-xs text-zinc-500 font-medium max-w-[80px] sm:max-w-[100px] leading-tight text-right shrink-0">{{ $q['high'] }}</span>
                            </div>
                            
                            @error($key)
                                <p class="text-[10px] text-red-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach

                    {{-- Written Feedback Card --}}
                    <div class="bg-white border border-zinc-200 rounded-lg p-5 space-y-3.5 shadow-2xs">
                        <div class="space-y-1">
                            <h3 class="text-xs sm:text-sm font-bold text-zinc-900 leading-snug">
                                {{ count($questions) + 1 }}. Suggestions, Feedback, or Additional Features (Optional)
                            </h3>
                            <p class="text-[11px] text-zinc-550 leading-relaxed">Provide specific suggestions on what we can improve on this platform.</p>
                        </div>
                        <div class="pt-1.5">
                            <textarea name="feedback" rows="4" placeholder="Type your answer here..."
                                      class="w-full px-3 py-2 bg-zinc-50/50 border border-zinc-200 focus:bg-white focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 rounded-md text-xs text-zinc-850 transition-all">{{ old('feedback') }}</textarea>
                            @error('feedback')
                                <p class="text-[10px] text-red-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Action Row Card --}}
                    <div class="flex items-center justify-between pt-2">
                        <button type="submit" class="px-6 py-2 bg-primary-500 hover:bg-primary-650 text-white rounded-md text-[10px] font-bold uppercase tracking-wider transition-colors shadow-2xs">
                            Submit Feedback
                        </button>
                        <button type="reset" class="px-4 py-2 text-zinc-500 hover:text-zinc-800 text-[10px] font-bold uppercase tracking-wider transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>
</x-app-layout>
