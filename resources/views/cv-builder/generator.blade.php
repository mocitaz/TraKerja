<x-app-layout>
    <x-slot name="header">
        <!-- Ignored layout slot, header is handled inline inside template container for premium consistency -->
    </x-slot>

    <div class="bg-[#fafafa] min-h-screen pb-16 relative overflow-hidden">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">

            <!-- Premium Notion-Inspired Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-zinc-200/50 pb-4 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-zinc-100 border border-zinc-200/60 rounded-lg flex items-center justify-center text-zinc-500 shrink-0 shadow-2xs">
                        <i class="ph ph-magic-wand text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm font-bold text-zinc-800 tracking-tight">CV Generator</h1>
                            <span class="px-1.5 py-0.5 bg-primary-50 text-zinc-800 text-[9px] font-black uppercase tracking-wider rounded border border-primary-100/60">Export</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Select your template · Preview · Export PDF</p>
                    </div>
                </div>

                <!-- Right: Stats Panel -->
                <div class="flex items-center gap-4 bg-white border border-zinc-200/60 rounded-md px-3 py-1.5 shadow-3xs shrink-0">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-emerald-50 rounded flex items-center justify-center text-emerald-600 shrink-0">
                            <i class="ph ph-check-circle text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[7px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Generations</p>
                            <p class="text-[10px] font-bold text-zinc-800 mt-0.5 leading-none">Unlimited</p>
                        </div>
                    </div>
                    <div class="w-px h-6 bg-zinc-200"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-primary-50 rounded flex items-center justify-center text-primary-650 /* [BRAND_PRIMARY] */ shrink-0">
                            <i class="ph ph-layout text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[7px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Templates</p>
                            <p class="text-[10px] font-bold text-zinc-800 mt-0.5 leading-none">4 Premium</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Navigation -->
            <div class="mb-5 flex justify-start">
                <a href="{{ route('cv.builder') }}" class="group inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-zinc-200 rounded-md text-[11px] font-bold text-zinc-600 hover:text-zinc-800 transition-colors shadow-3xs">
                    <i class="ph ph-arrow-left transition-transform group-hover:-translate-x-0.5"></i>
                    <span>Back to Builder</span>
                </a>
            </div>

            {{-- Section Header --}}
            <div class="mb-8 text-center max-w-xl mx-auto">
                <span class="px-2 py-0.5 bg-primary-50 text-zinc-800 rounded text-[8.5px] font-black uppercase tracking-wider mb-2.5 inline-block border border-primary-100/50">Design Selection</span>
                <h2 class="text-lg font-bold text-zinc-800 tracking-tight mb-2">Choose Your Resume Template</h2>
                <p class="text-[11px] font-semibold text-zinc-500 leading-relaxed">Every template is meticulously crafted to be ATS-friendly and visually stunning. Select a style that matches your career goals.</p>
            </div>

            {{-- Template Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                @php
                $templates = [
                    [
                        'key'     => 'minimal',
                        'name'    => 'The Minimalist',
                        'desc'    => 'Clean single-column design, optimized for readability and high ATS scanning scores.',
                        'badge'   => 'ATS GOLD',
                        'color'   => 'slate',
                        'accent'  => 'bg-zinc-900 text-white hover:bg-zinc-800',
                        'border'  => 'hover:border-zinc-350',
                        'preview' => 'bg-zinc-100/70',
                        'icon'    => 'ph-file-text',
                        'lines'   => ['bg-zinc-400', 'bg-zinc-300', 'bg-zinc-300', 'bg-zinc-300'],
                    ],
                    [
                        'key'     => 'professional',
                        'name'    => 'Executive Pro',
                        'desc'    => 'Modern structured layout, ideal for senior roles and corporate career applications.',
                        'badge'   => 'Corporate',
                        'color'   => 'blue',
                        'accent'  => 'bg-zinc-900 text-white hover:bg-zinc-800',
                        'border'  => 'hover:border-zinc-350',
                        'preview' => 'bg-blue-50/50',
                        'icon'    => 'ph-briefcase',
                        'lines'   => ['bg-blue-400', 'bg-blue-300', 'bg-blue-200', 'bg-blue-200'],
                    ],
                    [
                        'key'     => 'creative',
                        'name'    => 'Creative Flow',
                        'desc'    => 'Dynamic design for designers, developers, and creative-focused professionals.',
                        'badge'   => 'Designer Choice',
                        'color'   => 'primary',
                        'accent'  => 'bg-zinc-900 text-white hover:bg-zinc-800',
                        'border'  => 'hover:border-zinc-350',
                        'preview' => 'bg-primary-50/30',
                        'icon'    => 'ph-paint-brush-broad',
                        'lines'   => ['bg-primary-400', 'bg-primary-300', 'bg-fuchsia-300', 'bg-primary-200'],
                    ],
                    [
                        'key'     => 'elegant',
                        'name'    => 'Elegant Serif',
                        'desc'    => 'Premium two-column layout with a sophisticated, high-end editorial aesthetic.',
                        'badge'   => 'Most Popular',
                        'color'   => 'amber',
                        'accent'  => 'bg-zinc-900 text-white hover:bg-zinc-800',
                        'border'  => 'hover:border-zinc-350',
                        'preview' => 'bg-amber-50/20',
                        'icon'    => 'ph-crown',
                        'lines'   => ['bg-amber-400', 'bg-amber-300', 'bg-orange-300', 'bg-amber-200'],
                    ],
                ];
                @endphp

                @foreach($templates as $t)
                @php
                    $isPremiumTemplate = in_array($t['key'], ['creative', 'elegant']);
                    $monetizationEnabled = \App\Models\Setting::isMonetizationEnabled();
                    $userHasAccess = $monetizationEnabled ? (auth()->user()->isPremium() || !$isPremiumTemplate) : true;
                @endphp
                <div class="group bg-white rounded-lg border border-zinc-200/60 {{ $t['border'] }} overflow-hidden shadow-3xs transition-all duration-300 flex flex-col relative">
                    {{-- Thumbnail Container --}}
                    <div class="relative aspect-[4/5] {{ $t['preview'] }} overflow-hidden flex items-start justify-center pt-6 px-4 border-b border-zinc-100">
                        {{-- Simulated CV paper --}}
                        <div class="w-full h-full bg-white rounded-t border-x border-t border-zinc-150/60 px-4 py-4 space-y-2.5 group-hover:-translate-y-1 transition-transform duration-500 ease-out">
                            {{-- Header block --}}
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-full {{ $t['lines'][0] }} opacity-40 flex-shrink-0"></div>
                                <div class="flex-1 space-y-1">
                                    <div class="h-2 {{ $t['lines'][0] }} rounded-full w-3/4 opacity-50"></div>
                                    <div class="h-1.5 {{ $t['lines'][1] }} rounded-full w-1/2 opacity-30"></div>
                                </div>
                            </div>
                            <div class="h-px bg-zinc-100 w-full"></div>
                            {{-- Body lines --}}
                            <div class="space-y-1.5">
                                <div class="h-1.5 {{ $t['lines'][2] }} rounded-full w-full opacity-30"></div>
                                <div class="h-1.5 {{ $t['lines'][2] }} rounded-full w-5/6 opacity-30"></div>
                                <div class="h-1.5 {{ $t['lines'][2] }} rounded-full w-4/6 opacity-30"></div>
                            </div>
                            <div class="h-1.5 {{ $t['lines'][0] }} rounded-full w-1/3 opacity-40 mt-3"></div>
                            <div class="space-y-1.5">
                                <div class="h-1.5 {{ $t['lines'][3] }} rounded-full w-full opacity-30"></div>
                                <div class="h-1.5 {{ $t['lines'][3] }} rounded-full w-5/6 opacity-30"></div>
                            </div>
                        </div>
                        
                        {{-- Badge Overlay --}}
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-0.5 bg-white/95 border border-zinc-200/50 shadow-3xs rounded text-[8px] font-black uppercase tracking-wider text-zinc-800">{{ $t['badge'] }}</span>
                        </div>
                        
                        {{-- Lock Overlay for Free Users --}}
                        @if(!$userHasAccess)
                        <div class="absolute inset-0 bg-zinc-950/20 backdrop-blur-xs flex items-center justify-center z-20">
                            <div class="w-9 h-9 bg-white/95 rounded-md flex items-center justify-center shadow-md text-zinc-500 border border-zinc-250">
                                <i class="ph-fill ph-lock text-lg"></i>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Info & Action --}}
                    <div class="p-4 flex flex-col flex-1 bg-white relative z-10">
                        <div class="mb-4 flex-1">
                            <h4 class="text-xs font-bold text-zinc-800 tracking-tight mb-1 flex items-center gap-1.5">
                                {{ $t['name'] }}
                                @if(!$userHasAccess)
                                    <i class="ph-fill ph-crown text-amber-500 text-xs" title="Premium Template"></i>
                                @endif
                            </h4>
                            <p class="text-[10px] font-semibold text-zinc-400 leading-relaxed">{{ $t['desc'] }}</p>
                        </div>
                        
                        <form method="POST" action="{{ route('cv-builder.preview') }}" target="_blank" @if(!$userHasAccess) onsubmit="event.preventDefault(); typeof showToast === 'function' ? showToast('error', 'Premium Required', 'This template is only available for premium users. Upgrade to access premium templates!', 5000) : alert('This template is only available for premium users.');" @endif>
                            @csrf
                            <input type="hidden" name="template" value="{{ $t['key'] }}">
                            <button type="submit" class="w-full py-2 {{ !$userHasAccess ? 'bg-zinc-100 text-zinc-400 hover:bg-zinc-150 border border-zinc-250' : $t['accent'] }} rounded-md font-bold text-[10px] uppercase tracking-wider flex items-center justify-center gap-1.5 active:scale-98 transition-all duration-150 focus:outline-none">
                                @if(!$userHasAccess)
                                    <i class="ph ph-lock text-xs"></i>
                                    <span>Premium Only</span>
                                @else
                                    <i class="ph ph-eye text-xs"></i>
                                    <span>Live Preview</span>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ATS Certification Banner --}}
            <div class="mt-12 bg-white rounded-lg border border-zinc-200/60 p-5 flex flex-col md:flex-row items-center gap-6 shadow-3xs">
                <div class="w-10 h-10 bg-emerald-50 rounded flex items-center justify-center flex-shrink-0 shadow-3xs text-emerald-500 border border-emerald-100/50">
                    <i class="ph-fill ph-shield-check text-xl"></i>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h4 class="text-xs font-bold text-zinc-800 mb-0.5 uppercase tracking-wider">ATS Optimization Verified</h4>
                    <p class="text-[11px] font-medium text-zinc-500 leading-relaxed max-w-xl">Our proprietary CV engine ensures every template is parsed correctly by systems like Taleo, Workday, and Lever. Guaranteed 95%+ scanning accuracy.</p>
                </div>
                <div class="flex flex-col items-center gap-2 flex-shrink-0 bg-zinc-50/50 p-2.5 rounded border border-zinc-200/50">
                    <div class="flex -space-x-1">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 border-2 border-white flex items-center justify-center text-emerald-600 text-[8px] font-black leading-none">1</div>
                        <div class="w-6 h-6 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center text-blue-600 text-[8px] font-black leading-none">2</div>
                        <div class="w-6 h-6 rounded-full bg-purple-100 border-2 border-white flex items-center justify-center text-purple-600 text-[8px] font-black leading-none">3</div>
                        <div class="w-6 h-6 rounded-full bg-amber-100 border-2 border-white flex items-center justify-center text-amber-600 text-[8px] font-black leading-none">4</div>
                    </div>
                    <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Certified Designs</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
