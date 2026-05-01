<div class="relative">
    @if (session()->has('message'))
        <div class="mb-4 bg-emerald-50 border border-emerald-100 rounded-xl p-3 flex items-center gap-2 animate-fadeIn">
            <i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i>
            <p class="text-[11px] font-bold text-emerald-800">{{ session('message') }}</p>
        </div>
    @endif

    <form wire:submit="save" class="space-y-4">
        {{-- Row 1: Company & Position --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Company *</label>
                <div class="relative group">
                    <i class="ph-bold ph-buildings absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm group-focus-within:text-indigo-600 transition-colors"></i>
                    <input wire:model.live="company_name" type="text" class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-600/10 focus:bg-white focus:border-indigo-200 transition-all outline-none" placeholder="e.g. Google">
                </div>
                @error('company_name') <p class="text-rose-500 text-[9px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Position *</label>
                <div class="relative group">
                    <i class="ph-bold ph-identification-card absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm group-focus-within:text-indigo-600 transition-colors"></i>
                    <input wire:model.live="position" type="text" class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-600/10 focus:bg-white focus:border-indigo-200 transition-all outline-none" placeholder="e.g. Designer">
                </div>
                @error('position') <p class="text-rose-500 text-[9px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
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

        {{-- Row 4: Platform & Status --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Platform</label>
                <select wire:model.live="platform" class="block w-full px-3 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold text-slate-700 outline-none transition-all hover:bg-white hover:border-slate-200">
                    <option value="">Select Platform</option>
                    @foreach($platformOptions as $p) <option value="{{ $p }}">{{ $p }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Current Status</label>
                <select wire:model.live="application_status" class="block w-full px-3 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold text-slate-700 outline-none transition-all hover:bg-white hover:border-slate-200">
                    @foreach($applicationStatusOptions as $opt) <option value="{{ $opt }}">{{ $opt }}</option> @endforeach
                </select>
            </div>
        </div>

        {{-- Row 5: Stage & Date --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Stage</label>
                <select wire:model.live="recruitment_stage" class="block w-full px-3 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold text-slate-700 outline-none transition-all hover:bg-white hover:border-slate-200">
                    <option value="">Select Stage</option>
                    @foreach($recruitmentStageOptions as $stg) <option value="{{ $stg }}">{{ $stg }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">App Date</label>
                <div class="relative">
                    <input wire:model.live="application_date" type="date" class="block w-full px-3 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold text-slate-700 outline-none transition-all hover:bg-white hover:border-slate-200">
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Brief Notes</label>
            <textarea wire:model.live="notes" rows="2" class="block w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-medium outline-none resize-none transition-all focus:bg-white focus:border-slate-200" placeholder="Add specific reminders..."></textarea>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-6 border-t border-slate-100 mt-2">
            <button type="button" wire:click="resetForm" class="text-[10px] font-black text-slate-300 hover:text-rose-500 uppercase tracking-[2px] transition-colors">Reset</button>
            <div class="flex items-center gap-4">
                <button type="button" onclick="closeJobModal()" class="text-[10px] font-black text-slate-400 hover:text-slate-600 uppercase tracking-[2px] transition-all">Cancel</button>
                <button type="submit" class="magnetic-btn px-10 py-3 bg-slate-900 text-white text-[10px] font-black rounded-xl hover:bg-indigo-600 transition-all shadow-xl shadow-slate-200 active:scale-95 uppercase tracking-[2px] flex items-center gap-2 group">
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
