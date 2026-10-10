<x-app-layout>
    @php
        $language = match(strtolower($coverLetter->language ?? '')) { 'en', 'english' => 'English', 'id', 'indonesian', 'bahasa indonesia' => 'Indonesian', default => $coverLetter->language ?: 'Not specified' };
        $filename = 'Cover_Letter_'.\Illuminate\Support\Str::slug($coverLetter->company_name.' '.$coverLetter->job_title, '_').'.txt';
    @endphp
    <div class="workspace-page cover-letter-workspace" x-data="coverLetterDetail(@js($filename))">
        <div class="cover-letter-container">
            <header class="cover-letter-header"><div><p class="workspace-eyebrow">AI Studio · Your saved draft</p><h1>Cover letter</h1><p>Review your words before your next opportunity.</p></div><a href="{{ route('ai-studio.index', ['tab' => 'cover-letter']) }}" class="cv-button cv-button-secondary" wire:navigate><i class="ph ph-arrow-left" aria-hidden="true"></i>Back to Studio</a></header>
            <div class="cover-letter-layout">
                <section class="cover-letter-paper" aria-labelledby="letter-heading"><header><div><span>Application for</span><h2 id="letter-heading">{{ $coverLetter->job_title }}</h2><p>{{ $coverLetter->company_name }}</p></div><span class="cover-letter-draft">Draft</span></header><div class="cover-letter-body" id="letter-content" x-ref="content" tabindex="0" aria-label="Cover letter content">{{ $coverLetter->content }}</div><footer><span>Generated content. Check every detail before sending.</span></footer></section>
                <aside class="cover-letter-sidebar">
                    <section class="cover-letter-actions"><h2>Make it yours</h2><p>Copy the text into your application, or keep a file for later.</p><button type="button" class="cv-button cv-button-primary" @click="copy()" :disabled="copying"><i class="ph ph-copy" aria-hidden="true"></i><span x-text="copying ? 'Copying...' : (copied ? 'Copied' : 'Copy letter')">Copy letter</span></button><div><button type="button" class="cv-button cv-button-secondary" @click="window.print()"><i class="ph ph-printer" aria-hidden="true"></i>Print letter</button><button type="button" class="cv-button cv-button-secondary" @click="download()"><i class="ph ph-download-simple" aria-hidden="true"></i>Download TXT</button></div><p class="cover-letter-action-status" aria-live="polite" x-text="notice" x-show="notice"></p><button type="button" class="cover-letter-select" @click="selectText()" x-show="copyFailed">Select text to copy manually</button></section>
                    <section class="cover-letter-context"><h2>Draft details</h2><dl><div><dt>Company</dt><dd>{{ $coverLetter->company_name }}</dd></div><div><dt>Role</dt><dd>{{ $coverLetter->job_title }}</dd></div><div><dt>Tone</dt><dd>{{ ucfirst($coverLetter->tone ?: 'Not specified') }}</dd></div><div><dt>Language</dt><dd>{{ $language }}</dd></div><div><dt>Created</dt><dd>{{ $coverLetter->created_at->timezone('Asia/Jakarta')->format('d M Y · H:i') }} WIB</dd></div></dl></section>
                    <section class="cover-letter-review-note"><h3>One last read</h3><p>Confirm the company, role, and achievements match your application. Add your own voice before sharing.</p><a href="{{ route('ai-studio.index', ['tab' => 'cover-letter']) }}" wire:navigate>Create another letter <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a></section>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
