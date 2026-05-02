<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                CV <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d983e4] via-primary-600 to-[#4e71c5]">Generator</span>
            </h1>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">Select your template · Preview · Export PDF</p>
        </div>
    </x-slot>

    <div class="bg-[#f8fafc] min-h-screen pb-20 relative overflow-hidden">
        {{-- Decorative Background Elements --}}
        <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-primary-50/30 to-transparent -z-10"></div>
        <div class="absolute top-40 -right-24 w-96 h-96 bg-primary-200/10 blur-[120px] rounded-full -z-10"></div>
        <div class="absolute bottom-20 -left-24 w-80 h-80 bg-purple-200/10 blur-[120px] rounded-full -z-10"></div>

        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">

            {{-- Top Navigation & Quick Stats --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 mb-10">
                <a href="{{ route('cv.builder') }}" class="group inline-flex items-center gap-3 px-6 py-3 bg-white border border-slate-200/60 rounded-2xl text-xs font-black text-slate-600 hover:text-primary-600 hover:border-primary-200 transition-all shadow-sm active:scale-95">
                    <i class="ph-bold ph-arrow-left transition-transform group-hover:-translate-x-1"></i>
                    BACK TO BUILDER
                </a>

                <div class="flex items-center gap-6 bg-white/80 backdrop-blur-xl border border-slate-200/60 rounded-[1.5rem] px-6 py-3 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="ph-duotone ph-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Generations</p>
                            <p class="text-xs font-black text-slate-900">Unlimited</p>
                        </div>
                    </div>
                    <div class="w-px h-8 bg-slate-100"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600">
                            <i class="ph-duotone ph-layout text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Templates</p>
                            <p class="text-xs font-black text-slate-900">4 Premium</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section Header --}}
            <div class="mb-10 text-center max-w-2xl mx-auto">
                <span class="px-4 py-1.5 bg-primary-50 text-primary-600 rounded-full text-[10px] font-black uppercase tracking-[2px] mb-4 inline-block">Design Selection</span>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-3">Choose Your Resume Template</h2>
                <p class="text-sm font-medium text-slate-500 leading-relaxed">Every template is meticulously crafted to be ATS-friendly and visually stunning. Select a style that matches your career goals.</p>
            </div>

            {{-- Template Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                @php
                $templates = [
                    [
                        'key'     => 'minimal',
                        'name'    => 'The Minimalist',
                        'desc'    => 'Clean single-column design, optimized for readability and high ATS scanning scores.',
                        'badge'   => 'ATS GOLD',
                        'color'   => 'slate',
                        'accent'  => 'bg-slate-900 text-white',
                        'border'  => 'hover:border-slate-400',
                        'preview' => 'bg-gradient-to-br from-slate-100 to-slate-200',
                        'icon'    => 'ph-file-text',
                        'lines'   => ['bg-slate-300', 'bg-slate-200', 'bg-slate-200', 'bg-slate-200'],
                    ],
                    [
                        'key'     => 'professional',
                        'name'    => 'Executive Pro',
                        'desc'    => 'Modern structured layout, ideal for senior roles and corporate career applications.',
                        'badge'   => 'Corporate',
                        'color'   => 'blue',
                        'accent'  => 'bg-blue-600 text-white',
                        'border'  => 'hover:border-blue-400',
                        'preview' => 'bg-gradient-to-br from-blue-100 to-primary-100',
                        'icon'    => 'ph-briefcase',
                        'lines'   => ['bg-blue-400', 'bg-blue-300', 'bg-blue-200', 'bg-blue-100'],
                    ],
                    [
                        'key'     => 'creative',
                        'name'    => 'Creative Flow',
                        'desc'    => 'Dynamic design for designers, developers, and creative-focused professionals.',
                        'badge'   => 'Designer Choice',
                        'color'   => 'primary',
                        'accent'  => 'bg-primary-600 text-white',
                        'border'  => 'hover:border-primary-400',
                        'preview' => 'bg-gradient-to-br from-primary-100 to-fuchsia-100',
                        'icon'    => 'ph-paint-brush-broad',
                        'lines'   => ['bg-primary-400', 'bg-primary-300', 'bg-fuchsia-300', 'bg-primary-200'],
                    ],
                    [
                        'key'     => 'elegant',
                        'name'    => 'Elegant Serif',
                        'desc'    => 'Premium two-column layout with a sophisticated, high-end editorial aesthetic.',
                        'badge'   => 'Most Popular',
                        'color'   => 'amber',
                        'accent'  => 'bg-amber-600 text-white',
                        'border'  => 'hover:border-amber-400',
                        'preview' => 'bg-gradient-to-br from-amber-100 to-orange-100',
                        'icon'    => 'ph-crown',
                        'lines'   => ['bg-amber-400', 'bg-amber-300', 'bg-orange-300', 'bg-amber-200'],
                    ],
                ];
                @endphp

                @foreach($templates as $t)
                <div class="group bg-white rounded-[2.5rem] border border-slate-200/60 {{ $t['border'] }} overflow-hidden hover:shadow-[0_32px_64px_-12px_rgba(0,0,0,0.1)] transition-all duration-500 flex flex-col relative">
                    {{-- Thumbnail Container --}}
                    <div class="relative aspect-[4/5] {{ $t['preview'] }} overflow-hidden flex items-start justify-center pt-8 px-6">
                        {{-- Simulated CV paper --}}
                        <div class="w-full h-full bg-white rounded-t-xl shadow-2xl px-5 py-6 space-y-3 group-hover:-translate-y-2 transition-transform duration-700 ease-out border-x border-t border-slate-100">
                            {{-- Header block --}}
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full {{ $t['lines'][0] }} opacity-60 flex-shrink-0"></div>
                                <div class="flex-1 space-y-1.5">
                                    <div class="h-2.5 {{ $t['lines'][0] }} rounded-full w-3/4 opacity-70"></div>
                                    <div class="h-2 {{ $t['lines'][1] }} rounded-full w-1/2 opacity-50"></div>
                                </div>
                            </div>
                            <div class="h-px bg-slate-100 w-full"></div>
                            {{-- Body lines --}}
                            <div class="space-y-2">
                                <div class="h-2 {{ $t['lines'][2] }} rounded-full w-full opacity-40"></div>
                                <div class="h-2 {{ $t['lines'][2] }} rounded-full w-5/6 opacity-40"></div>
                                <div class="h-2 {{ $t['lines'][2] }} rounded-full w-4/6 opacity-40"></div>
                            </div>
                            <div class="h-2 {{ $t['lines'][0] }} rounded-full w-1/3 opacity-60 mt-4"></div>
                            <div class="space-y-2">
                                <div class="h-2 {{ $t['lines'][3] }} rounded-full w-full opacity-40"></div>
                                <div class="h-2 {{ $t['lines'][3] }} rounded-full w-5/6 opacity-40"></div>
                            </div>
                        </div>
                        
                        {{-- Badge Overlay --}}
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1.5 bg-white/90 backdrop-blur shadow-sm rounded-xl text-[9px] font-black uppercase tracking-[1px] text-slate-800">{{ $t['badge'] }}</span>
                        </div>
                    </div>

                    {{-- Info & Action --}}
                    <div class="p-6 flex flex-col flex-1 bg-white relative z-10">
                        <div class="mb-6 flex-1">
                            <h4 class="text-lg font-black text-slate-900 tracking-tight mb-2">{{ $t['name'] }}</h4>
                            <p class="text-xs font-medium text-slate-500 leading-relaxed">{{ $t['desc'] }}</p>
                        </div>
                        
                        <form method="POST" action="{{ route('cv-builder.preview') }}" target="_blank">
                            @csrf
                            <input type="hidden" name="template" value="{{ $t['key'] }}">
                            <button type="submit" class="w-full py-4 {{ $t['accent'] }} rounded-2xl font-black text-[10px] uppercase tracking-[2px] flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-current/20 active:scale-95 transition-all duration-300">
                                <i class="ph-bold ph-eye text-base"></i>
                                LIVE PREVIEW
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ATS Certification Banner --}}
            <div class="mt-16 bg-white/60 backdrop-blur-xl rounded-[2.5rem] border border-slate-200/60 p-8 flex flex-col md:flex-row items-center gap-8 shadow-sm">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
                    <i class="ph-fill ph-shield-check text-emerald-500 text-3xl"></i>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h4 class="text-lg font-black text-slate-900 mb-1">ATS Optimization Verified</h4>
                    <p class="text-sm font-medium text-slate-500 leading-relaxed max-w-xl">Our proprietary CV engine ensures every template is parsed correctly by systems like Taleo, Workday, and Lever. Guaranteed 95%+ scanning accuracy.</p>
                </div>
                <div class="flex flex-col items-center gap-3 flex-shrink-0 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <div class="flex -space-x-2">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 border-2 border-white flex items-center justify-center text-emerald-600 text-[10px] font-black">1</div>
                        <div class="w-8 h-8 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center text-blue-600 text-[10px] font-black">2</div>
                        <div class="w-8 h-8 rounded-full bg-purple-100 border-2 border-white flex items-center justify-center text-purple-600 text-[10px] font-black">3</div>
                        <div class="w-8 h-8 rounded-full bg-amber-100 border-2 border-white flex items-center justify-center text-amber-600 text-[10px] font-black">4</div>
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Certified Designs</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
