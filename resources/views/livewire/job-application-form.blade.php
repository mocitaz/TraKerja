<div class="relative">
    @if (session()->has('message'))
        <div class="mb-4 bg-emerald-50 border border-emerald-100 rounded-xl p-3 flex items-center gap-2 animate-fadeIn">
            <i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i>
            <p class="text-[11px] font-bold text-emerald-800">{{ session('message') }}</p>
        </div>
    @endif

    {{-- Global Error Feedback --}}
    @if ($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-100 rounded-2xl p-4 flex items-start gap-3 animate-fadeIn">
            <i class="ph-fill ph-warning-circle text-rose-500 text-xl shrink-0 mt-0.5"></i>
            <div>
                <p class="text-[11px] font-black text-rose-800 uppercase tracking-widest">Action Required</p>
                <p class="text-[10px] font-bold text-rose-600 mt-1">Some required fields are missing or invalid. Please check the highlights below.</p>
            </div>
        </div>
    @endif

    <form wire:submit="save" class="space-y-5">
        {{-- Row 1: Company & Position --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Company *</label>
                <div class="relative group">
                    <i class="ph-bold ph-buildings absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm group-focus-within:text-indigo-600 transition-colors"></i>
                    <input wire:model.live="company_name" type="text" class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-600/10 focus:bg-white focus:border-indigo-200 transition-all outline-none" placeholder="e.g. Google">
                </div>
                @error('company_name') <p class="text-rose-500 text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Position *</label>
                <div class="relative group">
                    <i class="ph-bold ph-identification-card absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm group-focus-within:text-indigo-600 transition-colors"></i>
                    <input wire:model.live="position" type="text" class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-600/10 focus:bg-white focus:border-indigo-200 transition-all outline-none" placeholder="e.g. Designer">
                </div>
                @error('position') <p class="text-rose-500 text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Row 2: Location --}}
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Location Context *</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <select wire:model.live="selectedProvince" class="block w-full px-3 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold text-slate-700 outline-none transition-all {{ ($isRemote || $isInternational) ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer hover:bg-white hover:border-slate-200' }}" {{ ($isRemote || $isInternational) ? 'disabled' : '' }}>
                    <option value="">Select Province</option>
                    @foreach($provinces as $province => $cities) <option value="{{ $province }}">{{ $province }}</option> @endforeach
                </select>
                <select wire:model.live="selectedCity" class="block w-full px-3 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold text-slate-700 outline-none transition-all {{ ($isRemote || $isInternational || empty($selectedProvince)) ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer hover:bg-white hover:border-slate-200' }}" {{ ($isRemote || $isInternational || empty($selectedProvince)) ? 'disabled' : '' }}>
                    <option value="">Select City</option>
                    @if(!empty($selectedProvince) && isset($provinces[$selectedProvince]))
                        @foreach($provinces[$selectedProvince] as $city) <option value="{{ $city }}">{{ $city }}</option> @endforeach
                    @endif
                </select>
            </div>
            @error('selectedProvince') <p class="text-rose-500 text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
        </div>

        {{-- Row 3: Work Mode (Segmented Style) --}}
        <div class="flex p-1 bg-slate-100/50 rounded-xl border border-slate-100">
            <label class="flex-1 flex items-center justify-center gap-3 py-2.5 px-4 cursor-pointer group transition-all">
                <input type="checkbox" wire:model.live="isRemote" class="sr-only peer">
                <div class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-400 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 transition-all">
                    <i class="ph-bold ph-house-line text-sm"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 peer-checked:text-indigo-600 transition-colors">Remote</span>
                    <span class="text-[7px] font-bold text-slate-400/60 peer-checked:text-indigo-600/60 leading-none">Work from home</span>
                </div>
            </label>
            <div class="w-px my-2 bg-slate-200"></div>
            <label class="flex-1 flex items-center justify-center gap-3 py-2.5 px-4 cursor-pointer group transition-all">
                <input type="checkbox" wire:model.live="isInternational" class="sr-only peer">
                <div class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-400 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 transition-all">
                    <i class="ph-bold ph-airplane-tilt text-sm"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 peer-checked:text-indigo-600 transition-colors">International</span>
                    <span class="text-[7px] font-bold text-slate-400/60 peer-checked:text-indigo-600/60 leading-none">Global opportunity</span>
                </div>
            </label>
        </div>

        {{-- Row 4: Platform & Career Level --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Platform *</label>
                <select wire:model.live="platform" class="block w-full px-3 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold text-slate-700 outline-none transition-all hover:bg-white hover:border-slate-200">
                    <option value="">Select Platform</option>
                    @foreach($platformOptions as $p) <option value="{{ $p }}">{{ $p }}</option> @endforeach
                </select>
                @error('platform') <p class="text-rose-500 text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Career Level</label>
                <select wire:model.live="career_level" class="block w-full px-3 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold text-slate-700 outline-none transition-all hover:bg-white hover:border-slate-200">
                    <option value="">Select Level</option>
                    @foreach($careerLevelOptions as $cl) <option value="{{ $cl }}">{{ $cl }}</option> @endforeach
                </select>
                @error('career_level') <p class="text-rose-500 text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Row 5: Status & Stage --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">App Status *</label>
                <select wire:model.live="application_status" class="block w-full px-3 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold text-slate-700 outline-none transition-all hover:bg-white hover:border-slate-200">
                    @foreach($applicationStatusOptions as $opt) <option value="{{ $opt }}">{{ $opt }}</option> @endforeach
                </select>
                @error('application_status') <p class="text-rose-500 text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Current Stage *</label>
                <select wire:model.live="recruitment_stage" class="block w-full px-3 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold text-slate-700 outline-none transition-all hover:bg-white hover:border-slate-200">
                    @foreach($recruitmentStageOptions as $stg) <option value="{{ $stg }}">{{ $stg }}</option> @endforeach
                </select>
                @error('recruitment_stage') <p class="text-rose-500 text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Row 6: App Link & Date --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Job Link (URL)</label>
                <div class="relative group">
                    <i class="ph-bold ph-link absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm group-focus-within:text-indigo-600 transition-colors"></i>
                    <input wire:model.live="platform_link" type="url" class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-600/10 focus:bg-white focus:border-indigo-200 transition-all outline-none" placeholder="https://...">
                </div>
                @error('platform_link') <p class="text-rose-500 text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Application Date *</label>
                <div class="relative">
                    <input wire:model.live="application_date" type="date" class="block w-full px-3 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold text-slate-700 outline-none transition-all hover:bg-white hover:border-slate-200">
                </div>
                @error('application_date') <p class="text-rose-500 text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Interview Fields (visible when status = Interview or stage = HR/User Interview) --}}
        @if(in_array($application_status, ['Interview']) || in_array($recruitment_stage, ['HR - Interview', 'User - Interview']))
        <div class="bg-indigo-50/60 border border-indigo-100 rounded-2xl p-4 space-y-3">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-5 h-5 bg-indigo-100 rounded-md flex items-center justify-center">
                    <i class="ph-bold ph-chats-circle text-indigo-600 text-[10px]"></i>
                </div>
                <span class="text-[10px] font-black text-indigo-700 uppercase tracking-widest">Interview Details</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Interview Date & Time</label>
                    <input wire:model.live="interview_date" type="datetime-local"
                           class="block w-full px-3 py-2.5 bg-white border border-indigo-200 rounded-xl text-[11px] font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all">
                    @error('interview_date') <p class="text-rose-500 text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Interview Type</label>
                    <select wire:model.live="interview_type"
                            class="block w-full px-3 py-2.5 bg-white border border-indigo-200 rounded-xl text-[11px] font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all">
                        <option value="">Select Type</option>
                        @foreach(['Phone', 'Video', 'In-person', 'Panel'] as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                    @error('interview_type') <p class="text-rose-500 text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">
                    {{ in_array($interview_type, ['Phone','Video']) ? 'Meeting Link' : 'Location / Link' }}
                </label>
                <div class="relative">
                    <i class="ph-bold {{ in_array($interview_type, ['Phone','Video']) ? 'ph-video-camera' : 'ph-map-pin' }} absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input wire:model.live="interview_location" type="text"
                           class="block w-full pl-9 pr-4 py-2.5 bg-white border border-indigo-200 rounded-xl text-[11px] font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all"
                           placeholder="{{ in_array($interview_type, ['Phone','Video']) ? 'https://meet.google.com/...' : 'Office address or meeting link' }}">
                </div>
                @error('interview_location') <p class="text-rose-500 text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>
        </div>
        @endif

        {{-- Notes --}}
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Brief Notes</label>
            <textarea wire:model.live="notes" rows="2" class="block w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-medium outline-none resize-none transition-all focus:bg-white focus:border-slate-200" placeholder="Add specific reminders..."></textarea>
            @error('notes') <p class="text-rose-500 text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-6 border-t border-slate-100 mt-2">
            <button type="button" wire:click="resetForm" class="text-[10px] font-black text-slate-300 hover:text-rose-500 uppercase tracking-[2px] transition-colors">Reset</button>
            <div class="flex items-center gap-4">
                <button type="button" onclick="closeJobModal()" class="text-[10px] font-black text-slate-400 hover:text-slate-600 uppercase tracking-[2px] transition-all">Cancel</button>
                <button type="submit" class="magnetic-btn px-10 py-3 bg-primary-600 text-white text-[10px] font-black rounded-xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-100 active:scale-95 uppercase tracking-[2px] flex items-center gap-2 group">
                    <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                        <i class="ph-bold ph-check text-base"></i>
                        {{ $isEditing ? 'UPDATE' : 'SAVE' }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <i class="ph-bold ph-spinner animate-spin text-base"></i>
                        PROCESSING...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
