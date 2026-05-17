<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Compact On Process -->
    <div class="bg-white border border-slate-200/60 rounded-2xl p-4 flex items-center justify-between hover:border-indigo-600/50 transition-all group">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">On Process</span>
                <span wire:loading.remove class="text-xl font-black text-slate-900 leading-none">{{ $onProcessCount }}</span>
                <div wire:loading class="h-5 w-10 rounded bg-slate-100 skeleton"></div>
            </div>
        </div>
        <div class="px-2 py-1 bg-indigo-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
            <span class="text-[9px] font-black text-indigo-600 uppercase">Active</span>
        </div>
    </div>

    <!-- Compact Accepted -->
    <div class="bg-white border border-slate-200/60 rounded-2xl p-4 flex items-center justify-between hover:border-emerald-600/50 transition-all group">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Accepted</span>
                <span wire:loading.remove class="text-xl font-black text-slate-900 leading-none">{{ $offeringAcceptedCount }}</span>
                <div wire:loading class="h-5 w-10 rounded bg-slate-100 skeleton"></div>
            </div>
        </div>
        <div class="px-2 py-1 bg-emerald-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
            <span class="text-[9px] font-black text-emerald-600 uppercase">Hired</span>
        </div>
    </div>

    <!-- Compact Declined -->
    <div class="bg-white border border-slate-200/60 rounded-2xl p-4 flex items-center justify-between hover:border-rose-600/50 transition-all group">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Declined</span>
                <span wire:loading.remove class="text-xl font-black text-slate-900 leading-none">{{ $declinedCount }}</span>
                <div wire:loading class="h-5 w-10 rounded bg-slate-100 skeleton"></div>
            </div>
        </div>
        <div class="px-2 py-1 bg-rose-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
            <span class="text-[9px] font-black text-rose-600 uppercase">Closed</span>
        </div>
    </div>
</div>