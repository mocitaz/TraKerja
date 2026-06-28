<x-admin-layout>
    <div class="max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 pb-10 space-y-5" 
         x-data="{ activeTab: '{{ request('tab', 'general') }}' }">
         
        <!-- Sticky Global Sub-Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-zinc-200/80">
            <div class="flex items-center gap-2.5 min-w-0">
                <span class="text-xs font-mono font-medium text-zinc-400">Admin</span>
                <span class="text-zinc-300">/</span>
                <h1 class="text-sm font-semibold tracking-tight text-zinc-900">System Settings</h1>
            </div>
        </div>

        <!-- Cupertino-Notion Tabs -->
        <div class="flex border-b border-zinc-200">
            <button @click="activeTab = 'general'; window.history.replaceState(null, '', '?tab=general')"
                    :class="activeTab === 'general' ? 'border-zinc-900 text-zinc-900 font-semibold' : 'border-transparent text-zinc-400 hover:text-zinc-650'"
                    class="px-4 py-2 border-b-2 text-xs font-medium transition-colors tracking-tight">
                General Settings
            </button>
            <button @click="activeTab = 'integration'; window.history.replaceState(null, '', '?tab=integration')"
                    :class="activeTab === 'integration' ? 'border-zinc-900 text-zinc-900 font-semibold' : 'border-transparent text-zinc-400 hover:text-zinc-650'"
                    class="px-4 py-2 border-b-2 text-xs font-medium transition-colors tracking-tight">
                Integration Hub
            </button>
            <button @click="activeTab = 'database'; window.history.replaceState(null, '', '?tab=database')"
                    :class="activeTab === 'database' ? 'border-zinc-900 text-zinc-900 font-semibold' : 'border-transparent text-zinc-400 hover:text-zinc-650'"
                    class="px-4 py-2 border-b-2 text-xs font-medium transition-colors tracking-tight">
                Database & Storage
            </button>
        </div>

        <!-- Tab Contents -->
        <div>
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
