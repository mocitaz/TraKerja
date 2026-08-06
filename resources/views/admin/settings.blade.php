<x-admin-layout>
    <div class="space-y-6 pb-12 w-full" x-data="{ activeTab: '{{ request('tab', 'general') }}' }">
         
        <!-- Header Section (Sleek and Clean) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-200">
            <div>
                <h1 class="text-lg font-black text-zinc-900 tracking-tight flex items-center gap-1.5">
                    System Settings <span class="text-[9px] font-mono font-bold text-zinc-450 bg-zinc-100 border border-zinc-200 px-1.5 py-0.2 rounded">Global Configuration</span>
                </h1>
                <p class="text-[10px] text-zinc-500 font-semibold mt-0.5">Manage global variables, API integrations, and database maintenance scripts.</p>
            </div>
        </div>

        <!-- Cupertino-Notion Tabs -->
        <div class="flex border border-zinc-200 border-b-0 bg-white rounded-t-xl shadow-3xs overflow-hidden">
            <button @click="activeTab = 'general'; window.history.replaceState(null, '', '?tab=general')"
                    :class="activeTab === 'general' ? 'border-zinc-950 text-zinc-950 bg-zinc-50/50 font-extrabold' : 'border-transparent text-zinc-400 hover:text-zinc-700'"
                    class="px-5 py-3.5 border-b-2 text-xs font-bold transition-all focus:outline-none shadow-none">
                General Settings
            </button>
            <button @click="activeTab = 'integration'; window.history.replaceState(null, '', '?tab=integration')"
                    :class="activeTab === 'integration' ? 'border-zinc-950 text-zinc-950 bg-zinc-50/50 font-extrabold' : 'border-transparent text-zinc-400 hover:text-zinc-700'"
                    class="px-5 py-3.5 border-b-2 text-xs font-bold transition-all focus:outline-none shadow-none">
                Integration Hub
            </button>
            <button @click="activeTab = 'database'; window.history.replaceState(null, '', '?tab=database')"
                    :class="activeTab === 'database' ? 'border-zinc-950 text-zinc-950 bg-zinc-50/50 font-extrabold' : 'border-transparent text-zinc-400 hover:text-zinc-700'"
                    class="px-5 py-3.5 border-b-2 text-xs font-bold transition-all focus:outline-none shadow-none">
                Database & Storage
            </button>
        </div>

        <!-- Tab Contents -->
        <div class="pt-0">
            <!-- General Settings Tab -->
            <div x-show="activeTab === 'general'" x-cloak class="space-y-4">
                @livewire('admin.global-settings')
            </div>

            <!-- Integration Hub Tab -->
            <div x-show="activeTab === 'integration'" x-cloak class="space-y-4">
                @livewire('admin.integration-hub')
            </div>

            <!-- Database & Storage Tab -->
            <div x-show="activeTab === 'database'" x-cloak class="space-y-4">
                @livewire('admin.database-maintenance')
            </div>
        </div>

    </div>
</x-admin-layout>
