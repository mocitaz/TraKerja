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
            'brand_transformation' => 'Suasana Baru TraKerja (Teman Perjuangan Karier)',
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
            <h1>{{ __('Email Blast') }}</h1>
            <p>{{ __('Deliver targeted, personalized announcements and updates to your members.') }}</p>
        </div>
        <div class="eb-header-actions">
            <a href="{{ route('admin.email-blast.history') }}" class="au-button">
                <i class="ph ph-clock-counter-clockwise" aria-hidden="true"></i>
                <span>{{ __('Campaign history') }}</span>
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
                        <h2><span class="eb-step">1</span> {{ __('Choose your audience') }}</h2>
                        <p>{{ __('Segment filters live against all non-admin members.') }}</p>
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
                                <strong>{{ __($label) }}</strong>
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
                        <h2><span class="eb-step">2</span> {{ __('Prepare your message') }}</h2>
                        <p>{{ __('Choose an editorial template or compose a tailored custom message.') }}</p>
                    </div>
                </header>
                <div class="eb-editor au-form">
                    <label for="blast-template">
                        <span>{{ __('Email template') }}</span>
                        <div class="eb-select-wrapper">
                            <select id="blast-template" name="email_type" x-model="emailType">
                                @foreach($templates as $group => $options)
                                    <optgroup label="{{ $group }}">
                                        @foreach($options as $key => $label)
                                            <option value="{{ $key }}">{{ __($label) }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <i class="ph ph-caret-down eb-select-chevron" aria-hidden="true"></i>
                        </div>
                        <p class="au-form-hint eb-template-hint" x-show="emailType !== 'custom'" x-text="templateDescription()"></p>
                    </label>

                    <fieldset class="eb-banner-picker">
                        <legend>{{ __('Email banner') }} <span>{{ __('Optional') }}</span></legend>
                        <p>{{ __('Choose a visual for this campaign, or keep your email text-only.') }}</p>
                        <div class="eb-banner-options">
                            <label class="eb-banner-option" :class="{ 'is-selected': banner === '' }">
                                <input type="radio" name="banner" value="" x-model="banner">
                                <span class="eb-banner-none"><i class="ph ph-text-align-left" aria-hidden="true"></i></span>
                                <span>{{ __('No banner') }}</span>
                            </label>
                            @foreach(\App\Support\EmailBannerCatalog::all() as $key => $banner)
                                <label class="eb-banner-option" :class="{ 'is-selected': banner === '{{ $key }}' }">
                                    <input type="radio" name="banner" value="{{ $key }}" x-model="banner">
                                    <img src="{{ asset($banner['path']) }}" width="300" height="100" alt="" loading="lazy" decoding="async">
                                    <span>{{ __($banner['label']) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <!-- Custom Fields (shown only if emailType === 'custom') -->
                    <div class="eb-custom-fields" x-show="emailType === 'custom'" x-cloak>
                        <label for="blast-subject">
                            <span>{{ __('Subject line') }}</span>
                            <input id="blast-subject" type="text" x-model="custom.subject" maxlength="255" :required="emailType === 'custom'" placeholder="{{ __('e.g. Halo rekan! Update penting terkait akun TraKerja kamu') }}">
                        </label>

                        <label for="blast-content">
                            <span>{{ __('Message body') }}</span>
                            <textarea id="blast-content" x-model="custom.content" maxlength="5000" :required="emailType === 'custom'" rows="7" placeholder="{{ __('Tuliskan isi email di sini…') }}"></textarea>
                        </label>
                        <span class="eb-character-count" x-text="custom.content.length + @js(__(' / 5,000 characters'))"></span>

                        <details class="ai-data eb-action-details">
                            <summary>
                                <i class="ph ph-cursor-click" aria-hidden="true"></i>
                                <span>{{ __('Add call-to-action button (optional)') }}</span>
                            </summary>
                            <div class="eb-button-fields">
                                <label for="blast-button-text">
                                    <span>{{ __('Button text') }}</span>
                                    <input id="blast-button-text" type="text" x-model="custom.buttonText" maxlength="100" placeholder="{{ __('Buka Dashboard') }}">
                                </label>
                                <label for="blast-button-url">
                                    <span>{{ __('Destination URL') }}</span>
                                    <input id="blast-button-url" type="url" x-model="custom.buttonUrl" maxlength="500" placeholder="https://trakerja.com/dashboard">
                                </label>
                            </div>
                        </details>
                        <p class="au-form-hint">{{ __('Greeting & sign-off are generated automatically. Content is sanitized and formatted with clean paragraphs.') }}</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Right Column: Campaign Summary Panel (Identik 1:1 dengan Card Follow-up Orange di Dashboard User) -->
        <aside class="eb-review-panel next-move-card next-move-followup">
            <div class="next-move-card-top">
                <span>{{ __('Keep in touch') }}</span>
                <i class="ph ph-chat-circle-text" aria-hidden="true"></i>
            </div>
            <h2>{{ __('Campaign summary') }}</h2>
            <div class="next-move-content">
                <div class="next-move-subject">
                    <strong x-text="audienceLabel()"></strong>
                    <span>{{ __('Target audience segment') }}</span>
                </div>
                <div class="next-move-subject">
                    <strong x-text="templateLabel()"></strong>
                    <span>{{ __('Selected email template') }}</span>
                </div>
                <div class="next-move-subject" x-show="emailType === 'custom' && custom.subject.trim()">
                    <strong class="eb-truncate" x-text="custom.subject"></strong>
                    <span>{{ __('Custom subject line') }}</span>
                </div>
                <p class="next-move-description">{{ __('Review settings and verify test rendering before dispatching to members.') }}</p>
                <div class="next-move-task-controls">
                    <span class="eb-task-chip"><i class="ph ph-check" aria-hidden="true"></i>{{ __('Admin test rendered') }}</span>
                    <span class="eb-task-chip"><i class="ph ph-shield-check" aria-hidden="true"></i>{{ __('Rate-limit enabled') }}</span>
                </div>
            </div>

            <button type="submit" class="next-move-action eb-preview-button" :disabled="loading">
                <span x-text="loading ? @js(__('Generating preview…')) : @js(__('Review & preview email'))"></span>
                <i class="ph ph-arrow-right" aria-hidden="true"></i>
            </button>
            <p class="next-move-footnote">{{ __('Opening preview does not send any emails · No message sent automatically') }}</p>
            <p class="eb-inline-error" role="alert" x-show="error && !modal" x-text="error" x-cloak></p>
        </aside>
    </form>

    <!-- Modal Dialogs -->
    <template x-teleport="body">
        <div x-show="modal" x-cloak x-dialog="modal" data-dialog-close="!sending && (modal = null)" role="dialog" aria-modal="true" tabindex="-1" aria-labelledby="blast-dialog-title" class="au-dialog-overlay">
            <section class="au-dialog eb-dialog" :class="{ 'eb-dialog--preview': modal === 'preview', 'eb-dialog--progress': modal === 'progress' }">
                <header class="au-dialog-heading">
                    <div>
                        <h2 id="blast-dialog-title" x-text="modal === 'preview' ? @js(__('Preview email')) : modal === 'confirm' ? @js(__('Confirm campaign delivery')) : @js(__('Campaign delivery progress'))"></h2>
                        <p x-text="modal === 'preview' ? @js(__('Rendered with your admin account data for desktop & mobile testing.')) : modal === 'confirm' ? @js(__('Check the final recipient count and template before starting transmission.')) : (sending ? (paused ? @js(__('Pengiriman dijeda sementara. Tekan tombol Lanjutkan untuk meneruskan.')) : @js(__('Keep this window open until all emails are delivered.'))) : @js(__('Semua proses pengiriman email telah selesai.')))"></p>
                    </div>
                    <div class="eb-dialog-head-actions">
                        <template x-if="modal === 'progress'">
                            <span class="eb-progress-pill" :class="{ 'is-paused': paused, 'is-done': !sending }">
                                <span class="eb-pulse-dot" x-show="sending && !paused"></span>
                                <i class="ph ph-pause-circle" x-show="paused" aria-hidden="true"></i>
                                <i class="ph ph-check-circle" x-show="!sending" aria-hidden="true"></i>
                                <span x-text="!sending ? 'Selesai' : (paused ? 'Dijeda' : progressPercent() + '%')"></span>
                            </span>
                        </template>
                        <button type="button" @click="modal = null" :disabled="sending" class="au-dialog-close" aria-label="{{ __('Close dialog') }}">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </div>
                </header>

                <!-- Modal View 1: Preview -->
                <div x-show="modal === 'preview'" class="eb-preview-content">
                    <div class="eb-preview-toolbar">
                        <div class="eb-device-switch" role="group" aria-label="{{ __('Device width') }}">
                            <button type="button" @click="device = 'desktop'" :aria-pressed="(device === 'desktop').toString()" :class="{ 'is-active': device === 'desktop' }">
                                <i class="ph ph-desktop" aria-hidden="true"></i> {{ __('Desktop') }}
                            </button>
                            <button type="button" @click="device = 'mobile'" :aria-pressed="(device === 'mobile').toString()" :class="{ 'is-active': device === 'mobile' }">
                                <i class="ph ph-device-mobile" aria-hidden="true"></i> {{ __('Mobile') }}
                            </button>
                        </div>
                        <div class="eb-preview-meta">
                            <span class="eb-badge-recipients" x-text="users.length + @js(__(' eligible recipients'))"></span>
                        </div>
                    </div>
                    <div class="eb-preview-stage">
                        <div x-show="loading" class="eb-preview-loading">
                            <i class="ph ph-spinner-gap eb-spin" aria-hidden="true"></i>
                            <span>{{ __('Rendering responsive email template…') }}</span>
                        </div>
                        <iframe x-ref="previewFrame" title="{{ __('Email content preview') }}" sandbox="" :class="{ 'is-mobile': device === 'mobile' }" x-show="!loading && previewReady"></iframe>
                    </div>
                    <div class="eb-preview-footer">
                        <span>{{ __('Preview only · No emails sent to users yet') }}</span>
                        <button type="button" @click="modal = 'confirm'" :disabled="!previewReady || loading || !users.length" class="au-button au-button--primary">
                            <span>{{ __('Review recipients & send') }}</span>
                            <i class="ph ph-arrow-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal View 2: Confirmation -->
                <div x-show="modal === 'confirm'" class="au-dialog-body">
                    <dl class="au-account-details">
                        <div>
                            <dt>{{ __('Target audience') }}</dt>
                            <dd x-text="audienceLabel()"></dd>
                        </div>
                        <div>
                            <dt>{{ __('Total recipients') }}</dt>
                            <dd class="eb-count-highlight" x-text="users.length + @js(__(' members'))"></dd>
                        </div>
                        <div>
                            <dt>{{ __('Selected template') }}</dt>
                            <dd x-text="templateLabel()"></dd>
                        </div>
                    </dl>
                    <p class="eb-confirm-copy">
                        {{ __('You are about to send this email to') }} <strong x-text="users.length"></strong> {{ __('eligible members. Deliveries are processed directly and cannot be undone once dispatched.') }}
                    </p>
                    <details class="ai-data eb-recipients-drawer">
                        <summary>{{ __('View recipient list (') }}<span x-text="users.length"></span>)</summary>
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
                        <button type="button" @click="modal = 'preview'" class="au-button">{{ __('Back to preview') }}</button>
                        <button type="button" @click="send()" :disabled="sending || !users.length" class="au-button au-button--primary">
                            <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
                            <span>{{ __('Send campaign') }}</span>
                        </button>
                    </div>
                </div>

                <!-- Modal View 3: Progress & Delivery -->
                <div x-show="modal === 'progress'" class="au-dialog-body eb-progress-body">
                    <!-- Progress Hero Card -->
                    <div class="eb-delivery-hero">
                        <div class="eb-hero-top">
                            <div class="eb-hero-eyebrow">
                                <span class="eb-status-tag" :class="{ 'is-paused': paused, 'is-completed': !sending }">
                                    <span class="eb-live-indicator" x-show="sending && !paused"></span>
                                    <span x-text="sending ? (paused ? @js(__('PENGIRIMAN DIJEDA')) : @js(__('SENDING CAMPAIGN'))) : (cancelled ? @js(__('PENGIRIMAN DIHENTIKAN')) : @js(__('DELIVERY COMPLETED')))"></span>
                                </span>
                                <span class="eb-eta-tag" x-show="sending">
                                    <i class="ph ph-clock" aria-hidden="true"></i>
                                    <span x-text="etaText()"></span>
                                </span>
                            </div>
                            <div class="eb-hero-counter">
                                <span class="eb-counter-current" x-text="processed.toLocaleString(@js(__('id-ID')))"></span>
                                <span class="eb-counter-sep">/</span>
                                <span class="eb-counter-total" x-text="users.length.toLocaleString(@js(__('id-ID')))"></span>
                                <span class="eb-counter-unit">{{ __('emails') }}</span>
                            </div>
                        </div>

                        <!-- Animated Progress Bar -->
                        <div class="eb-prog-track" role="progressbar" :aria-valuenow="progressPercent()" aria-valuemin="0" aria-valuemax="100">
                            <div class="eb-prog-fill" :class="{ 'is-animating': sending && !paused }" :style="'width:' + progressPercent() + '%'">
                                <div class="eb-prog-shimmer" x-show="sending && !paused"></div>
                            </div>
                        </div>
                        <div class="eb-prog-subline">
                            <span class="eb-pct-text" x-text="progressPercent() + '% selesai'"></span>
                            <span class="eb-rate-text" x-text="elapsedSeconds > 0 ? (processed / Math.max(1, elapsedSeconds)).toFixed(1) + @js(__(' email/dtk')) : @js(__('Menghubungkan server…'))"></span>
                        </div>
                    </div>

                    <!-- 3-Column Symmetrical Metrics Grid -->
                    <div class="eb-delivery-grid">
                        <div class="eb-pmetric-card eb-pmetric--success">
                            <div class="eb-pmetric-icon">
                                <i class="ph ph-check-circle" aria-hidden="true"></i>
                            </div>
                            <div class="eb-pmetric-info">
                                <span class="eb-pmetric-label">{{ __('Successfully Sent') }}</span>
                                <strong class="eb-pmetric-val" x-text="successCount.toLocaleString(@js(__('id-ID')))"></strong>
                            </div>
                        </div>
                        <div class="eb-pmetric-card eb-pmetric--failed">
                            <div class="eb-pmetric-icon">
                                <i class="ph ph-warning-circle" aria-hidden="true"></i>
                            </div>
                            <div class="eb-pmetric-info">
                                <span class="eb-pmetric-label">{{ __('Failed / Bounced') }}</span>
                                <strong class="eb-pmetric-val" x-text="failures.length.toLocaleString(@js(__('id-ID')))"></strong>
                            </div>
                        </div>
                        <div class="eb-pmetric-card eb-pmetric--queue">
                            <div class="eb-pmetric-icon">
                                <i class="ph ph-hourglass-simple" aria-hidden="true"></i>
                            </div>
                            <div class="eb-pmetric-info">
                                <span class="eb-pmetric-label">{{ __('In Queue') }}</span>
                                <strong class="eb-pmetric-val" x-text="Math.max(0, users.length - processed).toLocaleString(@js(__('id-ID')))"></strong>
                            </div>
                        </div>
                    </div>

                    <!-- Active Transmitting Recipient Card (When Sending) -->
                    <div class="eb-active-box" x-show="sending && currentUser">
                        <div class="eb-active-avatar">
                            <span class="eb-avatar-radar" x-show="!paused"></span>
                            <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
                        </div>
                        <div class="eb-active-details">
                            <div class="eb-active-name-row">
                                <strong class="eb-active-name" x-text="currentUser?.name || @js(__('Menyiapkan...'))"></strong>
                                <span class="eb-transmitting-badge" :class="{ 'is-paused': paused }">
                                    <span class="eb-pulse-dot" x-show="!paused"></span>
                                    <span x-text="paused ? @js(__('Dijeda')) : @js(__('Transmitting…'))"></span>
                                </span>
                            </div>
                            <span class="eb-active-email" x-text="currentUser?.email"></span>
                        </div>
                    </div>

                    <!-- Live Stream Transmission Feed -->
                    <div class="eb-feed-wrapper" x-show="recentLogs.length">
                        <div class="eb-feed-header">
                            <span>
                                <i class="ph ph-activity" aria-hidden="true"></i>
                                {{ __('Aktivitas Pengiriman Terkini') }}
                            </span>
                            <span class="eb-feed-count" x-text="recentLogs.length + @js(__(' log terakhir'))"></span>
                        </div>
                        <div class="eb-feed-list" role="log" aria-live="polite">
                            <template x-for="(log, idx) in recentLogs" :key="idx + '-' + log.email + '-' + log.time">
                                <div class="eb-feed-item" :class="log.success ? 'is-success' : 'is-error'">
                                    <div class="eb-feed-status">
                                        <i class="ph ph-check" x-show="log.success" aria-hidden="true"></i>
                                        <i class="ph ph-x" x-show="!log.success" aria-hidden="true"></i>
                                    </div>
                                    <div class="eb-feed-info">
                                        <div class="eb-feed-main">
                                            <strong class="eb-feed-name" x-text="log.name"></strong>
                                            <span class="eb-feed-email" x-text="log.email"></span>
                                        </div>
                                        <span class="eb-feed-error" x-show="!log.success && log.error" x-text="log.error"></span>
                                    </div>
                                    <span class="eb-feed-time" x-text="log.time"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Log Error Notice if any -->
                    <p class="eb-inline-error" x-show="logError" x-text="logError"></p>

                    <!-- Failures Collapsible Drawer -->
                    <details class="ai-data eb-failures-drawer" x-show="failures.length">
                        <summary>
                            <i class="ph ph-warning" aria-hidden="true"></i>
                            <span>{{ __('Lihat rincian gagal terkirim (') }}<span x-text="failures.length"></span>)</span>
                        </summary>
                        <div class="eb-recipient-list">
                            <template x-for="(failure, index) in failures" :key="index">
                                <div class="eb-recipient-item eb-recipient-item--failed">
                                    <strong x-text="failure.email"></strong>
                                    <span x-text="failure.error"></span>
                                </div>
                            </template>
                        </div>
                    </details>

                    <!-- Completion Celebration Banner (When !sending) -->
                    <div class="eb-complete-banner" x-show="!sending" :class="{ 'is-cancelled': cancelled }">
                        <div class="eb-complete-icon">
                            <i class="ph" :class="cancelled ? 'ph-prohibit' : (failures.length === 0 ? 'ph-seal-check' : 'ph-check-circle')"></i>
                        </div>
                        <div class="eb-complete-content">
                            <strong x-text="cancelled ? @js(__('Pengiriman Dihentikan')) : (failures.length === 0 ? @js(__('Semua Email Berhasil Terkirim!')) : @js(__('Pengiriman Selesai dengan Sebagian Gagal')))"></strong>
                            <p x-text="deliveryStatus"></p>
                        </div>
                    </div>

                    <!-- In-Progress Control Action Bar (While Sending) -->
                    <div x-show="sending" class="eb-delivery-actions">
                        <button type="button" @click="togglePause()" class="au-button" :class="paused ? 'au-button--primary' : 'au-button--secondary'">
                            <i class="ph" :class="paused ? 'ph-play' : 'ph-pause'" aria-hidden="true"></i>
                            <span x-text="paused ? @js(__('Lanjutkan Pengiriman')) : @js(__('Jeda Sementara'))"></span>
                        </button>
                        <button type="button" @click="cancelSend()" :disabled="cancelled" class="au-button au-button--danger">
                            <i class="ph ph-stop" aria-hidden="true"></i>
                            <span x-text="cancelled ? @js(__('Menghentikan…')) : @js(__('Hentikan Campaign'))"></span>
                        </button>
                    </div>

                    <!-- Final Action Bar (When Done) -->
                    <div x-show="!sending" class="au-dialog-actions eb-dialog-actions--done">
                        <a href="{{ route('admin.email-blast.history') }}" class="au-button">
                            <i class="ph ph-clock-counter-clockwise" aria-hidden="true"></i>
                            <span>{{ __('Lihat Riwayat') }}</span>
                        </a>
                        <button type="button" @click="modal = null" class="au-button au-button--primary">
                            <i class="ph ph-check" aria-hidden="true"></i>
                            <span>{{ __('Selesai') }}</span>
                        </button>
                    </div>
                </div>

                <p class="eb-inline-error eb-dialog-error" role="alert" x-show="error" x-text="error"></p>
            </section>
        </div>
    </template>
</div>
</x-admin-layout>
