<!-- Warning Banner -->
<div class="bg-rose-50/40 border border-rose-200/60 rounded-md p-3.5 mb-4 text-left">
    <div class="flex items-start gap-2.5">
        <i class="ph ph-warning-octagon text-rose-600 text-base shrink-0 mt-0.5"></i>
        <div class="flex-1">
            <h4 class="text-[11px] font-bold text-rose-900 mb-1 uppercase tracking-wider">Warning: This action cannot be undone</h4>
            <p class="text-[11px] text-rose-700 leading-normal font-semibold">
                Deleting your account will permanently remove all of your data, including:
            </p>
            <ul class="mt-2 text-[10px] text-rose-700/90 space-y-1 list-disc list-inside font-semibold">
                <li>Job applications and tracking history</li>
                <li>CV builder data and saved resumes</li>
                <li>Personal information and profile settings</li>
                <li>All associated files and documents</li>
            </ul>
            <p class="mt-2.5 text-[10px] font-bold text-rose-800 uppercase tracking-wider">
                Please download any important data before proceeding.
            </p>
        </div>
    </div>
</div>

<!-- Delete Button -->
<div class="flex justify-end">
    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-3.5 py-1.5 bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold uppercase tracking-wider rounded-md transition-colors flex items-center gap-1 shadow-3xs focus:outline-none">
        <i class="ph ph-trash text-sm"></i>
        <span>Delete My Account</span>
    </button>
</div>
