<div id="jobModal" data-dialog-native="closeJobModal" data-refresh-on-save="{{ request()->routeIs('dashboard') ? 'true' : 'false' }}" role="dialog" aria-modal="true"
     aria-labelledby="modalTitle" aria-describedby="job-modal-description" tabindex="-1"
     class="job-dialog hidden fixed inset-0 z-[99999] flex items-center justify-center">
    <div class="job-dialog__panel">
        <header class="job-dialog__header">
            <div>
                <h2 id="modalTitle">Add application</h2>
                <p id="job-modal-description">Keep your next opportunity in one place.</p>
            </div>
            <button type="button" onclick="closeJobModal()" class="job-dialog__close" aria-label="Close application form">
                <i class="ph ph-x" aria-hidden="true"></i>
            </button>
        </header>
        <div class="job-dialog__body">
            @livewire('job-application-form')
        </div>
    </div>
</div>
