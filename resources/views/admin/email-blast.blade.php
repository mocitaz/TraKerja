<x-admin-layout>
@php
    $audiences = [
        'all' => ['All members', 'Every non-admin account', 'users'],
        'verified' => ['Verified', 'Confirmed email addresses', 'shield-check'],
        'unverified' => ['Unverified', 'Waiting for email verification', 'envelope-simple'],
        'premium' => ['Premium', 'Active paid membership', 'crown'],
        'free' => ['Free', 'Without active premium access', 'sparkle'],
        'new' => ['New members', 'Registered in the last 7 days', 'user-plus'],
    ];

    $templates = [
        'Product announcements' => [
            'brand_transformation' => 'TraKerja New Look (Pembaruan Antarmuka)',
            'product_update' => 'Product update',
            'new_vibe' => 'New workspace',
            'ai_analyzer' => 'AI analyzer',
            'ai_photo' => 'Photo studio',
            'follow_up_feature' => 'Follow-up feature',
            'chrome_extension' => 'Chrome extension',
            'maintenance_completed' => 'Maintenance completed',
        ],
        'Getting started' => [
            'welcome' => 'Welcome',
            'verification' => 'Verify email',
            'verification_reminder' => 'Verification reminder',
        ],
        'Career & engagement' => [
            'job_reminder' => 'Application reminder',
            'monthly_motivation' => 'Monthly motivation',
            'hiring_season' => 'Hiring season',
            're_engagement' => 'Re-engagement',
        ],
        'Celebrations' => [
            'idul_adha' => 'Idul Adha',
            'waisak' => 'Waisak',
            'pancasila' => 'Pancasila Day',
            'tahun_baru_islam' => 'Islamic New Year',
            'kemerdekaan_ri' => 'Independence Day',
            'maulid_nabi' => 'Maulid Nabi',
            'sumpah_pemuda' => 'Youth Pledge Day',
            'pahlawan' => 'Heroes Day',
            'guru_nasional' => 'Teachers Day',
            'hari_ibu' => 'Mother’s Day',
            'natal' => 'Christmas',
        ],
        'Your message' => [
            'custom' => 'Custom email',
        ],
    ];
@endphp

<div class="email-workspace" x-data="emailBlastWorkspace({ previewUrl: '{{ route('admin.email-blast.preview') }}', initUrl: '{{ route('admin.email-blast.init') }}', sendUrl: '{{ route('admin.email-blast.send-single') }}', logUrl: '{{ route('admin.email-blast.store-log') }}' })">
    <!-- Header Section -->
    <section class="admin-welcome eb-header">
        <div>
            <span class="admin-eyebrow">Community messages</span>
            <h1>Email Blast</h1>
            <p>Deliver targeted, personalized announcements and updates to your members.</p>
        </div>
        <div class="eb-header-actions">
            <a href="{{ route('admin.email-blast.history') }}" class="au-button">
                <i class="ph ph-clock-counter-clockwise" aria-hidden="true"></i>
                <span>Campaign history</span>
                <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
            </a>
        </div>
    </section>

    <!-- Main Composer Grid -->
    <form x-ref="composer" @submit.prevent="preview()" class="eb-composer-grid">
        <div class="eb-composer-main">
            <!-- Step 1: Target Audience -->
            <section class="ai-panel eb-card">
                <header class="ai-panel-heading eb-card-heading">
                    <div>
                        <h2><span class="eb-step">1</span> Choose your audience</h2>
                        <p>Segment filters live against all non-admin members.</p>
                    </div>
                </header>
                <div class="eb-audiences">
                    @foreach($audiences as $key => [$label, $description, $icon])
                        <label class="eb-audience-card" :class="{ 'is-selected': audience === '{{ $key }}' }">
                            <input type="radio" name="target_user" value="{{ $key }}" x-model="audience" required class="eb-audience-radio">
                            <div class="eb-audience-icon">
                                <i class="ph ph-{{ $icon }}" aria-hidden="true"></i>
                            </div>
                            <div class="eb-audience-copy">
                                <strong>{{ $label }}</strong>
                                <span>{{ $description }}</span>
                            </div>
                            <b class="eb-audience-count">{{ number_format($audienceCounts[$key]) }}</b>
                        </label>
                    @endforeach
                </div>
            </section>

            <!-- Step 2: Message & Template Selection -->
            <section class="ai-panel eb-card">
                <header class="ai-panel-heading eb-card-heading">
                    <div>
                        <h2><span class="eb-step">2</span> Prepare your message</h2>
                        <p>Choose an editorial template or compose a tailored custom message.</p>
                    </div>
                </header>
                <div class="eb-editor au-form">
                    <label for="blast-template">
                        <span>Email template</span>
                        <div class="eb-select-wrapper">
                            <select id="blast-template" name="email_type" x-model="emailType">
                                @foreach($templates as $group => $options)
                                    <optgroup label="{{ $group }}">
                                        @foreach($options as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <i class="ph ph-caret-down eb-select-chevron" aria-hidden="true"></i>
                        </div>
                        <p class="au-form-hint eb-template-hint" x-show="emailType !== 'custom'" x-text="templateDescription()"></p>
                    </label>

                    <!-- Custom Fields (shown only if emailType === 'custom') -->
                    <div class="eb-custom-fields" x-show="emailType === 'custom'" x-cloak>
                        <label for="blast-subject">
                            <span>Subject line</span>
                            <input id="blast-subject" type="text" x-model="custom.subject" maxlength="255" :required="emailType === 'custom'" placeholder="e.g. Halo rekan! Update penting terkait akun TraKerja kamu">
                        </label>

                        <label for="blast-content">
                            <span>Message body</span>
                            <textarea id="blast-content" x-model="custom.content" maxlength="5000" :required="emailType === 'custom'" rows="7" placeholder="Tuliskan isi email di sini…"></textarea>
                        </label>
                        <span class="eb-character-count" x-text="custom.content.length + ' / 5,000 characters'"></span>

                        <details class="ai-data eb-action-details">
                            <summary>
                                <i class="ph ph-cursor-click" aria-hidden="true"></i>
                                <span>Add call-to-action button (optional)</span>
                            </summary>
                            <div class="eb-button-fields">
                                <label for="blast-button-text">
                                    <span>Button text</span>
                                    <input id="blast-button-text" type="text" x-model="custom.buttonText" maxlength="100" placeholder="Buka Dashboard">
                                </label>
                                <label for="blast-button-url">
                                    <span>Destination URL</span>
                                    <input id="blast-button-url" type="url" x-model="custom.buttonUrl" maxlength="500" placeholder="https://trakerja.com/dashboard">
                                </label>
                            </div>
                        </details>
                        <p class="au-form-hint">Greeting & sign-off are generated automatically. Content is sanitized and formatted with clean paragraphs.</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Right Column: Campaign Summary Panel (Identik 1:1 dengan Card Follow-up Orange di Dashboard User) -->
        <aside class="eb-review-panel next-move-card next-move-followup">
            <div class="next-move-card-top">
                <span>Keep in touch</span>
                <i class="ph ph-chat-circle-text" aria-hidden="true"></i>
            </div>
            <h2>Campaign summary</h2>
            <div class="next-move-content">
                <div class="next-move-subject">
                    <strong x-text="audienceLabel()"></strong>
                    <span>Target audience segment</span>
                </div>
                <div class="next-move-subject">
                    <strong x-text="templateLabel()"></strong>
                    <span>Selected email template</span>
                </div>
                <div class="next-move-subject" x-show="emailType === 'custom' && custom.subject.trim()">
                    <strong class="eb-truncate" x-text="custom.subject"></strong>
                    <span>Custom subject line</span>
                </div>
                <p class="next-move-description">Review settings and verify test rendering before dispatching to members.</p>
                <div class="next-move-task-controls">
                    <span class="eb-task-chip"><i class="ph ph-check" aria-hidden="true"></i>Admin test rendered</span>
                    <span class="eb-task-chip"><i class="ph ph-shield-check" aria-hidden="true"></i>Rate-limit enabled</span>
                </div>
            </div>

            <button type="submit" class="next-move-action eb-preview-button" :disabled="loading">
                <span x-text="loading ? 'Generating preview…' : 'Review & preview email'"></span>
                <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </button>
            <p class="next-move-footnote">Opening preview does not send any emails · No message sent automatically</p>
            <p class="eb-inline-error" role="alert" x-show="error && !modal" x-text="error" x-cloak></p>
        </aside>
    </form>

    <!-- Modal Dialogs -->
    <template x-teleport="body">
        <div x-show="modal" x-cloak x-dialog="modal" data-dialog-close="!sending && (modal = null)" role="dialog" aria-modal="true" tabindex="-1" aria-labelledby="blast-dialog-title" class="au-dialog-overlay">
            <section class="au-dialog eb-dialog" :class="{ 'eb-dialog--preview': modal === 'preview' }">
                <header class="au-dialog-heading">
                    <div>
                        <h2 id="blast-dialog-title" x-text="modal === 'preview' ? 'Preview email' : modal === 'confirm' ? 'Confirm campaign delivery' : 'Campaign delivery progress'"></h2>
                        <p x-text="modal === 'preview' ? 'Rendered with your admin account data for desktop & mobile testing.' : modal === 'confirm' ? 'Check the final recipient count and template before starting transmission.' : 'Keep this window open until all emails are delivered.'"></p>
                    </div>
                    <button type="button" @click="modal = null" :disabled="sending" class="au-dialog-close" aria-label="Close dialog">
                        <i class="ph ph-x" aria-hidden="true"></i>
                    </button>
                </header>

                <!-- Modal View 1: Preview -->
                <div x-show="modal === 'preview'" class="eb-preview-content">
                    <div class="eb-preview-toolbar">
                        <div class="eb-device-switch" role="group" aria-label="Device width">
                            <button type="button" @click="device = 'desktop'" :aria-pressed="(device === 'desktop').toString()" :class="{ 'is-active': device === 'desktop' }">
                                <i class="ph ph-desktop" aria-hidden="true"></i> Desktop
                            </button>
                            <button type="button" @click="device = 'mobile'" :aria-pressed="(device === 'mobile').toString()" :class="{ 'is-active': device === 'mobile' }">
                                <i class="ph ph-device-mobile" aria-hidden="true"></i> Mobile
                            </button>
                        </div>
                        <div class="eb-preview-meta">
                            <span class="eb-badge-recipients" x-text="users.length + ' eligible recipients'"></span>
                        </div>
                    </div>
                    <div class="eb-preview-stage">
                        <div x-show="loading" class="eb-preview-loading">
                            <i class="ph ph-spinner-gap eb-spin" aria-hidden="true"></i>
                            <span>Rendering responsive email template…</span>
                        </div>
                        <iframe x-ref="previewFrame" title="Email content preview" sandbox="" :class="{ 'is-mobile': device === 'mobile' }" x-show="!loading && previewReady"></iframe>
                    </div>
                    <div class="eb-preview-footer">
                        <span>Preview only · No emails sent to users yet</span>
                        <button type="button" @click="modal = 'confirm'" :disabled="!previewReady || loading || !users.length" class="au-button au-button--primary">
                            <span>Review recipients & send</span>
                            <i class="ph ph-arrow-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal View 2: Confirmation -->
                <div x-show="modal === 'confirm'" class="au-dialog-body">
                    <dl class="au-account-details">
                        <div>
                            <dt>Target audience</dt>
                            <dd x-text="audienceLabel()"></dd>
                        </div>
                        <div>
                            <dt>Total recipients</dt>
                            <dd class="eb-count-highlight" x-text="users.length + ' members'"></dd>
                        </div>
                        <div>
                            <dt>Selected template</dt>
                            <dd x-text="templateLabel()"></dd>
                        </div>
                    </dl>
                    <p class="eb-confirm-copy">
                        You are about to send this email to <strong x-text="users.length"></strong> eligible members. Deliveries are processed directly and cannot be undone once dispatched.
                    </p>
                    <details class="ai-data eb-recipients-drawer">
                        <summary>View recipient list (<span x-text="users.length"></span>)</summary>
                        <div class="eb-recipient-list">
                            <template x-for="user in users" :key="user.id">
                                <div class="eb-recipient-item">
                                    <strong x-text="user.name"></strong>
                                    <span x-text="user.email"></span>
                                </div>
                            </template>
                        </div>
                    </details>
                    <div class="au-dialog-actions">
                        <button type="button" @click="modal = 'preview'" class="au-button">Back to preview</button>
                        <button type="button" @click="send()" :disabled="sending || !users.length" class="au-button au-button--primary">
                            <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
                            <span>Send campaign</span>
                        </button>
                    </div>
                </div>

                <!-- Modal View 3: Progress & Delivery -->
                <div x-show="modal === 'progress'" class="au-dialog-body">
                    <div class="eb-delivery-summary">
                        <span class="eb-delivery-eyebrow" x-text="sending ? 'Sending emails…' : 'Delivery complete'"></span>
                        <strong class="eb-delivery-progress-num" x-text="processed + ' / ' + users.length"></strong>
                        <div class="ai-bar-track">
                            <span :style="'width:' + (users.length ? (processed / users.length * 100) : 0) + '%'"></span>
                        </div>
                    </div>
                    <div class="eb-delivery-counts">
                        <div class="eb-count-box eb-count-box--success">
                            <span>Successfully sent</span>
                            <strong x-text="successCount"></strong>
                        </div>
                        <div class="eb-count-box eb-count-box--failed">
                            <span>Failed / unconfirmed</span>
                            <strong x-text="failures.length"></strong>
                        </div>
                    </div>
                    <p class="eb-delivery-current" role="status" x-text="deliveryStatus"></p>
                    <p class="eb-inline-error" x-show="logError" x-text="logError"></p>

                    <details class="ai-data eb-failures-drawer" x-show="failures.length">
                        <summary>View failed deliveries (<span x-text="failures.length"></span>)</summary>
                        <div class="eb-recipient-list">
                            <template x-for="(failure, index) in failures" :key="index">
                                <div class="eb-recipient-item eb-recipient-item--failed">
                                    <strong x-text="failure.email"></strong>
                                    <span x-text="failure.error"></span>
                                </div>
                            </template>
                        </div>
                    </details>

                    <div x-show="!sending" class="au-dialog-actions">
                        <a href="{{ route('admin.email-blast.history') }}" class="au-button">View history</a>
                        <button type="button" @click="modal = null" class="au-button au-button--primary">Done</button>
                    </div>
                </div>

                <p class="eb-inline-error eb-dialog-error" role="alert" x-show="error" x-text="error"></p>
            </section>
        </div>
    </template>
</div>
</x-admin-layout>
