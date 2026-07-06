<x-admin-layout>
    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 pb-10 space-y-4" 
         x-data="{ activeTab: '{{ request('tab', 'general') }}' }">
         
        <!-- Sticky Global Sub-Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3.5 border-b border-zinc-150/60">
            <div class="flex items-center gap-1.5 min-w-0">
                <span class="text-xs font-mono font-bold text-zinc-400 uppercase tracking-wider">Admin Portal</span>
                <span class="text-zinc-300 text-xs">/</span>
                <h1 class="text-xs font-mono font-bold text-zinc-800 uppercase tracking-wider">System Settings</h1>
            </div>
        </div>

        <!-- Cupertino-Notion Tabs -->
        <div class="flex border-b border-zinc-150/60 bg-transparent space-x-1">
            <button @click="activeTab = 'general'; window.history.replaceState(null, '', '?tab=general')"
                    :class="activeTab === 'general' ? 'border-zinc-900 text-zinc-900 font-bold' : 'border-transparent text-zinc-450 hover:text-zinc-700'"
                    class="px-3 py-2 border-b-2 text-xs font-semibold transition-colors focus:outline-none shadow-none">
                General Settings
            </button>
            <button @click="activeTab = 'integration'; window.history.replaceState(null, '', '?tab=integration')"
                    :class="activeTab === 'integration' ? 'border-zinc-900 text-zinc-900 font-bold' : 'border-transparent text-zinc-450 hover:text-zinc-700'"
                    class="px-3 py-2 border-b-2 text-xs font-semibold transition-colors focus:outline-none shadow-none">
                Integration Hub
            </button>
            <button @click="activeTab = 'database'; window.history.replaceState(null, '', '?tab=database')"
                    :class="activeTab === 'database' ? 'border-zinc-900 text-zinc-900 font-bold' : 'border-transparent text-zinc-450 hover:text-zinc-700'"
                    class="px-3 py-2 border-b-2 text-xs font-semibold transition-colors focus:outline-none shadow-none">
                Database & Storage
            </button>
        </div>

        <!-- Tab Contents -->
        <div class="pt-1">
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
