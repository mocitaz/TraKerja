<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight">
                CV <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-violet-600">Generator</span>
            </h1>
            <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-widest mt-1">Select a template · Preview · Export as PDF</p>
        </div>
    </x-slot>

    <div class="bg-[#f8fafc] min-h-screen pb-20">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">

            {{-- Top Bar: Back + Stats --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-10">
                <a href="{{ route('cv.builder') }}" class="inline-flex items-center gap-2.5 px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:text-primary-600 hover:border-primary-200 hover:bg-primary-50 transition-all shadow-sm">
                    <i class="ph ph-arrow-left text-base"></i>
                    Back to Builder
                </a>

                <div class="flex items-center gap-3 bg-white border border-slate-200 rounded-xl px-5 py-3 shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 bg-emerald-50 rounded-lg flex items-center justify-center">
                            <i class="ph-fill ph-check-circle text-emerald-500 text-base"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none">Exports</p>
                            <p class="text-xs font-bold text-slate-800 mt-0.5">Unlimited</p>
                        </div>
                    </div>
                    <div class="w-px h-8 bg-slate-100"></div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 bg-primary-50 rounded-lg flex items-center justify-center">
                            <i class="ph-fill ph-layout text-primary-600 text-base"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none">Templates</p>
                            <p class="text-xs font-bold text-slate-800 mt-0.5">4 Available</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section Header --}}
            <div class="mb-8">
                <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">Choose Your Template</h2>
                <p class="text-sm text-slate-400 mt-1">All templates are ATS-friendly and export-ready. Click Preview to see your data applied.</p>
            </div>

            {{-- Template Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                @php
                $templates = [
                    [
                        'key'     => 'minimal',
                        'name'    => 'Minimal',
                        'desc'    => 'Clean single-column design, optimized for readability and ATS scanning.',
                        'badge'   => 'ATS Friendly',
                        'color'   => 'slate',
                        'accent'  => 'bg-slate-100 text-slate-600',
                        'border'  => 'hover:border-slate-400/40',
                        'preview' => 'bg-gradient-to-br from-slate-50 to-slate-100',
                        'icon'    => 'ph-file-text',
                        'lines'   => ['bg-slate-300', 'bg-slate-200', 'bg-slate-200', 'bg-slate-200'],
                    ],
                    [
                        'key'     => 'professional',
                        'name'    => 'Professional',
                        'desc'    => 'Modern structured layout, ideal for senior roles and corporate applications.',
                        'badge'   => 'Corporate',
                        'color'   => 'blue',
                        'accent'  => 'bg-blue-50 text-blue-600',
                        'border'  => 'hover:border-blue-300/50',
                        'preview' => 'bg-gradient-to-br from-blue-50 to-indigo-50',
                        'icon'    => 'ph-briefcase',
                        'lines'   => ['bg-blue-300', 'bg-blue-200', 'bg-blue-200', 'bg-blue-100'],
                    ],
                    [
                        'key'     => 'creative',
                        'name'    => 'Creative',
                        'desc'    => 'Dynamic, unique design for designers, developers, and creative professionals.',
                        'badge'   => 'Creative',
                        'color'   => 'violet',
                        'accent'  => 'bg-violet-50 text-violet-600',
                        'border'  => 'hover:border-violet-300/50',
                        'preview' => 'bg-gradient-to-br from-violet-50 to-fuchsia-50',
                        'icon'    => 'ph-paint-brush-broad',
                        'lines'   => ['bg-violet-300', 'bg-violet-200', 'bg-fuchsia-200', 'bg-violet-100'],
                    ],
                    [
                        'key'     => 'elegant',
                        'name'    => 'Elegant',
                        'desc'    => 'Premium two-column layout with a sophisticated, high-end aesthetic.',
                        'badge'   => 'Premium',
                        'color'   => 'amber',
                        'accent'  => 'bg-amber-50 text-amber-600',
                        'border'  => 'hover:border-amber-300/50',
                        'preview' => 'bg-gradient-to-br from-amber-50 to-orange-50',
                        'icon'    => 'ph-crown',
                        'lines'   => ['bg-amber-300', 'bg-amber-200', 'bg-orange-200', 'bg-amber-100'],
                    ],
                ];
                @endphp

                @foreach($templates as $t)
                <div class="group bg-white rounded-2xl border border-slate-200/80 {{ $t['border'] }} overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col">
                    {{-- Thumbnail --}}
                    <div class="relative aspect-[3/4] {{ $t['preview'] }} overflow-hidden flex items-start justify-center pt-5 px-4">
                        {{-- Simulated CV paper --}}
                        <div class="w-full bg-white rounded-lg shadow-md px-4 py-4 space-y-2.5 group-hover:-translate-y-1 transition-transform duration-500">
                            {{-- Header block --}}
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 rounded-full {{ $t['lines'][0] }} opacity-60 flex-shrink-0"></div>
                                <div class="flex-1 space-y-1">
                                    <div class="h-2 {{ $t['lines'][0] }} rounded-full w-3/4 opacity-70"></div>
                                    <div class="h-1.5 {{ $t['lines'][1] }} rounded-full w-1/2 opacity-50"></div>
                                </div>
                            </div>
                            <div class="h-px bg-slate-100 w-full"></div>
                            {{-- Body lines --}}
                            <div class="space-y-1.5">
                                <div class="h-1.5 {{ $t['lines'][2] }} rounded-full w-full opacity-40"></div>
                                <div class="h-1.5 {{ $t['lines'][2] }} rounded-full w-5/6 opacity-40"></div>
                                <div class="h-1.5 {{ $t['lines'][2] }} rounded-full w-4/6 opacity-40"></div>
                            </div>
                            <div class="h-1.5 {{ $t['lines'][0] }} rounded-full w-1/3 opacity-60 mt-2"></div>
                            <div class="space-y-1.5">
                                <div class="h-1.5 {{ $t['lines'][3] }} rounded-full w-full opacity-40"></div>
                                <div class="h-1.5 {{ $t['lines'][3] }} rounded-full w-5/6 opacity-40"></div>
                            </div>
                            <div class="h-1.5 {{ $t['lines'][0] }} rounded-full w-1/3 opacity-60 mt-2"></div>
                            <div class="space-y-1.5">
                                <div class="h-1.5 {{ $t['lines'][3] }} rounded-full w-4/5 opacity-40"></div>
                                <div class="h-1.5 {{ $t['lines'][3] }} rounded-full w-3/5 opacity-40"></div>
                            </div>
                        </div>
                        {{-- Badge --}}
                        <div class="absolute top-3 right-3">
                            <span class="px-2.5 py-1 {{ $t['accent'] }} rounded-full text-[9px] font-black uppercase tracking-widest">{{ $t['badge'] }}</span>
                        </div>
                    </div>

                    {{-- Info + CTA --}}
                    <div class="p-5 flex flex-col flex-1">
                        <div class="mb-5 flex-1">
                            <h4 class="text-base font-extrabold text-slate-900 tracking-tight mb-1">{{ $t['name'] }}</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">{{ $t['desc'] }}</p>
                        </div>
                        <form method="POST" action="{{ route('cv-builder.preview') }}" target="_blank">
                            @csrf
                            <input type="hidden" name="template" value="{{ $t['key'] }}">
                            <button type="submit" class="w-full py-3 {{ $t['accent'] }} border border-current/20 rounded-xl font-bold text-xs flex items-center justify-center gap-2 hover:opacity-90 active:scale-95 transition-all duration-200 tracking-wide">
                                <i class="ph ph-eye text-base"></i>
                                Preview {{ $t['name'] }}
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ATS Info Banner --}}
            <div class="mt-10 bg-white rounded-2xl border border-slate-200 p-6 flex flex-col md:flex-row items-center gap-6">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-shield-check text-emerald-500 text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-extrabold text-slate-800 mb-1">All Templates Pass ATS Screening</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Our templates are tested against major Applicant Tracking Systems — ensuring your resume gets through automated filters and reaches hiring managers.</p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="flex -space-x-1">
                        <span class="w-7 h-7 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center text-[10px] font-bold text-slate-600">✓</span>
                        <span class="w-7 h-7 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center text-[10px] font-bold text-blue-600">✓</span>
                        <span class="w-7 h-7 rounded-full bg-violet-100 border-2 border-white flex items-center justify-center text-[10px] font-bold text-violet-600">✓</span>
                    </div>
                    <span class="text-xs font-bold text-slate-500">4/4 templates</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
