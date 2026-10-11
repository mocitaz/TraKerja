<div class="admin-users-workspace">
    <!-- Header -->
    <section class="admin-welcome">
        <div>
            <span class="admin-eyebrow">People & access</span>
            <h1>User Management</h1>
            <p>Kelola data member, pantau progres lamaran, aktivitas login, dan hak akses dalam satu tempat.</p>
        </div>
        <div class="au-header-actions">
            <button type="button" wire:click="exportUsers" wire:loading.attr="disabled" wire:target="exportUsers" class="au-button">
                <i class="ph ph-download-simple" aria-hidden="true"></i>
                <span wire:loading.remove wire:target="exportUsers">Export CSV</span>
                <span wire:loading wire:target="exportUsers">Menyiapkan…</span>
            </button>
            <button type="button" wire:click="openCreateAdminModal" class="au-button au-button--primary">
                <i class="ph ph-user-plus" aria-hidden="true"></i>
                Tambah Admin
            </button>
        </div>
    </section>

    <!-- KPI Metric Summary Cards -->
    <section class="au-kpi-grid" aria-label="Member metrics overview">
        <div class="au-kpi-card">
            <div class="au-kpi-header">
                <span>Total Member</span>
                <div class="au-kpi-icon au-kpi-icon--blue"><i class="ph ph-users"></i></div>
            </div>
            <strong>{{ number_format($stats['total']) }}</strong>
            <small>Pengguna terdaftar non-admin</small>
        </div>

        <div class="au-kpi-card">
            <div class="au-kpi-header">
                <span>Premium Member</span>
                <div class="au-kpi-icon au-kpi-icon--amber"><i class="ph ph-crown"></i></div>
            </div>
            <strong>{{ number_format($stats['premium']) }}</strong>
            <small>{{ $stats['total'] > 0 ? round(($stats['premium'] / $stats['total']) * 100, 1) : 0 }}% rasio konversi premium</small>
        </div>

        <div class="au-kpi-card">
            <div class="au-kpi-header">
                <span>Free Member</span>
                <div class="au-kpi-icon au-kpi-icon--slate"><i class="ph ph-user"></i></div>
            </div>
            <strong>{{ number_format($stats['free']) }}</strong>
            <small>Pengguna paket standar</small>
        </div>

        <div class="au-kpi-card">
            <div class="au-kpi-header">
                <span>Total Lamaran</span>
                <div class="au-kpi-icon au-kpi-icon--indigo"><i class="ph ph-paper-plane-tilt"></i></div>
            </div>
            <strong>{{ number_format($stats['total_applications']) }}</strong>
            <small>Lamaran tercatat di platform</small>
        </div>

        <div class="au-kpi-card">
            <div class="au-kpi-header">
                <span>Email Terverifikasi</span>
                <div class="au-kpi-icon au-kpi-icon--emerald"><i class="ph ph-seal-check"></i></div>
            </div>
            <strong>{{ number_format($stats['verified']) }}</strong>
            <small>{{ $stats['total'] > 0 ? round(($stats['verified'] / $stats['total']) * 100, 1) : 0 }}% akun valid terverifikasi</small>
        </div>
    </section>

    <!-- Member Directory Table Container -->
    <section class="au-directory" aria-labelledby="member-directory-heading">
        <div class="au-directory-heading">
            <div>
                <h2 id="member-directory-heading">
                    Direktori Member <span>{{ number_format($users->total()) }}</span>
                </h2>
                <p>Daftar seluruh akun member TraKerja beserta rincian lamaran dan status aktif</p>
            </div>
            <span class="au-loading" wire:loading wire:target="search,filterPremium,filterAiAnalyzer,perPage,gotoPage,nextPage,previousPage">
                <i class="ph ph-spinner ph-spin"></i> Memperbarui data…
            </span>
        </div>

        <!-- Filters Toolbar -->
        <div class="au-filters">
            <label class="au-search">
                <span class="sr-only">Cari member</span>
                <i class="ph ph-magnifying-glass" aria-hidden="true"></i>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email member…">
            </label>
            <label>
                <span class="sr-only">Filter Paket</span>
                <select wire:model.live="filterPremium">
                    <option value="all">Semua Paket</option>
                    <option value="premium">Member Premium</option>
                    <option value="free">Member Free</option>
                </select>
            </label>
            <label>
                <span class="sr-only">Filter Trial AI</span>
                <select wire:model.live="filterAiAnalyzer">
                    <option value="all">Semua Status AI</option>
                    <option value="used">Trial AI Terpakai</option>
                    <option value="not_used">Trial AI Belum Terpakai</option>
                </select>
            </label>
            <label class="au-page-size">
                <span class="sr-only">Jumlah Baris</span>
                <select wire:model.live="perPage">
                    @foreach([10, 20, 50, 100] as $size)
                        <option value="{{ $size }}">{{ $size }} baris</option>
                    @endforeach
                </select>
            </label>
            @if($search !== '' || $filterPremium !== 'all' || $filterAiAnalyzer !== 'all')
                <button type="button" wire:click="resetFilters" class="au-clear">
                    Reset filter <i class="ph ph-x" aria-hidden="true"></i>
                </button>
            @endif
        </div>

        <!-- Table -->
        <div class="au-table-scroll" tabindex="0" aria-label="Tabel Direktori Member">
            <table class="au-table">
                <thead>
                    <tr>
                        <th scope="col">Member</th>
                        <th scope="col">Paket Membership</th>
                        <th scope="col">Aktivitas Lamaran</th>
                        <th scope="col">Aktivitas Terakhir</th>
                        <th scope="col">Kelola Akses</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr wire:key="member-{{ $user->id }}">
                            <!-- Column 1: Member Info -->
                            <td>
                                <div class="au-member">
                                    <div class="au-avatar-wrapper">
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="au-avatar-img" loading="lazy">
                                    </div>
                                    <div class="au-member-info">
                                        <strong class="au-member-name">{{ $user->name }}</strong>
                                        <span class="au-member-email">{{ $user->email }}</span>
                                        <div class="au-member-tags">
                                            @if($user->hasVerifiedEmail())
                                                <span class="au-tag au-tag--verified" title="Email terverifikasi">
                                                    <i class="ph ph-check-circle"></i> Terverifikasi
                                                </span>
                                            @endif
                                            @if($user->google_id)
                                                <span class="au-tag au-tag--google" title="Login via Google">
                                                    <i class="ph ph-google-logo"></i> Google
                                                </span>
                                            @endif
                                            @if($user->linkedin_id)
                                                <span class="au-tag au-tag--linkedin" title="Login via LinkedIn">
                                                    <i class="ph ph-linkedin-logo"></i> LinkedIn
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Column 2: Plan & Status -->
                            <td>
                                <div class="au-plan-container">
                                    @if($user->isPremium())
                                        <span class="au-plan-badge au-plan-badge--premium">
                                            <i class="ph ph-crown-simple"></i> Premium
                                        </span>
                                        @if($user->premium_until)
                                            <span class="au-plan-sub">Aktif s/d {{ $user->premium_until->format('d M Y') }}</span>
                                        @else
                                            <span class="au-plan-sub">Akses aktif</span>
                                        @endif
                                    @else
                                        <span class="au-plan-badge au-plan-badge--free">
                                            <i class="ph ph-user"></i> Free
                                        </span>
                                        @if($user->has_used_ai_analyzer_trial)
                                            <span class="au-plan-sub">Trial AI aktif</span>
                                        @endif
                                    @endif
                                </div>
                            </td>

                            <!-- Column 3: Applications Activity (Applied & Declined) -->
                            <td>
                                <div class="au-app-stats">
                                    <span class="au-chip au-chip--applied" title="Total lamaran dikirim">
                                        <i class="ph ph-paper-plane-tilt"></i>
                                        <strong>{{ number_format($user->applied_count) }}</strong> applied
                                    </span>
                                    <span class="au-chip {{ $user->declined_count > 0 ? 'au-chip--declined' : 'au-chip--empty' }}" title="Total lamaran ditolak">
                                        <i class="ph ph-x-circle"></i>
                                        <strong>{{ number_format($user->declined_count) }}</strong> declined
                                    </span>
                                </div>
                            </td>

                            <!-- Column 4: Last Activity / Login -->
                            <td>
                                <div class="au-activity">
                                    @if($user->last_activity_at && $user->last_activity_at->diffInHours(now()) < 24)
                                        <div class="au-activity-top">
                                            <span class="au-dot au-dot--active"></span>
                                            <strong>Aktif</strong>
                                        </div>
                                        <small>{{ $user->last_activity_at->diffForHumans() }}</small>
                                    @elseif($user->last_activity_at)
                                        <div class="au-activity-top">
                                            <span class="au-dot au-dot--recent"></span>
                                            <span>{{ $user->last_activity_at->diffForHumans() }}</span>
                                        </div>
                                        <small>{{ $user->last_activity_at->format('d M Y, H:i') }}</small>
                                    @else
                                        <div class="au-activity-top">
                                            <span class="au-dot au-dot--idle"></span>
                                            <span style="color: #94a3b8;">Belum login</span>
                                        </div>
                                        <small>Dibuat {{ $user->created_at->format('d M Y') }}</small>
                                    @endif
                                </div>
                            </td>

                            <!-- Column 5: Manage Access Actions -->
                            <td>
                                <div class="au-row-actions" x-data="{ open: false, left: 0, top: 0 }">
                                    <button type="button" wire:click="editUser({{ $user->id }})" class="au-manage" aria-label="Kelola {{ $user->name }}">
                                        Kelola <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" class="au-more" x-ref="more"
                                        @click="const rect = $el.getBoundingClientRect(); left = Math.max(12, Math.min(rect.right - 220, window.innerWidth - 232)); top = Math.max(12, Math.min(rect.bottom + 8, window.innerHeight - 200)); open = !open; if(open) $nextTick(() => $refs.firstAction.focus())"
                                        :aria-expanded="open.toString()" aria-controls="member-actions-{{ $user->id }}" aria-label="Menu aksi {{ $user->name }}">
                                        <i class="ph ph-dots-three" aria-hidden="true"></i>
                                    </button>

                                    <!-- Dropdown Quick Actions -->
                                    <template x-teleport="body">
                                        <div id="member-actions-{{ $user->id }}" class="au-action-menu" x-cloak x-show="open"
                                            x-transition.opacity.duration.120ms :style="`left: ${left}px; top: ${top}px`"
                                            @click.outside="open = false" @keydown.escape.stop="open = false; $refs.more.focus()"
                                            @scroll.window="open = false" @resize.window="open = false" role="region" aria-label="Aksi cepat untuk {{ $user->name }}">
                                            <p>{{ $user->name }}</p>
                                            <button type="button" x-ref="firstAction" @click="open = false" wire:click="openPremiumConfirmModal({{ $user->id }})">
                                                <i class="ph ph-crown" aria-hidden="true"></i>
                                                {{ $user->is_premium ? 'Hapus Akses Premium' : 'Berikan Akses Premium' }}
                                            </button>
                                            <button type="button" @click="open = false" wire:click="openSendEmailModal({{ $user->id }})">
                                                <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
                                                Kirim Email
                                            </button>
                                            <button type="button" @click="open = false" wire:click="confirmDeleteUserModal({{ $user->id }})" class="au-danger">
                                                <i class="ph ph-trash" aria-hidden="true"></i>
                                                Hapus Akun
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="au-empty">
                                    <div class="au-empty-icon"><i class="ph ph-users"></i></div>
                                    <h3>Tidak ada member ditemukan</h3>
                                    <p>{{ $search !== '' || $filterPremium !== 'all' || $filterAiAnalyzer !== 'all' ? 'Coba ubah kata kunci pencarian atau setel ulang filter Anda.' : 'Member yang baru mendaftar akan tampil di sini.' }}</p>
                                    @if($search !== '' || $filterPremium !== 'all' || $filterAiAnalyzer !== 'all')
                                        <button type="button" wire:click="resetFilters" class="au-button" style="margin-top: 14px;">
                                            Reset Semua Filter
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <footer class="au-directory-footer">
            <span>Menampilkan <strong>{{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}</strong> dari total {{ number_format($users->total()) }} member</span>
            <div>{{ $users->links() }}</div>
        </footer>
    </section>

    <!-- =====================================================================
         MODAL 1: Manage Account ($showEditModal)
         ===================================================================== -->
    @if($showEditModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showEditModal" data-dialog-close="$wire.closeModal()" aria-labelledby="showEditModal-title" class="au-dialog-overlay">
                <section class="au-dialog">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showEditModal-title">Kelola Akun Member</h2>
                            <p>Rincian profil, hak akses membership, dan kuota kredit AI.</p>
                        </div>
                        <button type="button" wire:click="closeModal" class="au-dialog-close" aria-label="Tutup dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        @php($editingUser = $editingUserId ? \App\Models\User::find($editingUserId) : null)
                        @if($editingUser)
                            <!-- Profile Banner Card -->
                            <div class="au-dialog-person">
                                <img src="{{ $editingUser->avatar_url }}" alt="{{ $editingUser->name }}">
                                <div>
                                    <strong>{{ $editingUser->name }}</strong>
                                    <span>{{ $editingUser->email }}</span>
                                </div>
                                <div style="display: flex; gap: 6px;">
                                    <span class="au-plan-badge {{ $editingUser->isAdmin() ? 'au-plan-badge--premium' : 'au-plan-badge--free' }}">
                                        {{ $editingUser->isAdmin() ? 'Admin' : 'Member' }}
                                    </span>
                                    <span class="au-plan-badge {{ $editingUser->isPremium() ? 'au-plan-badge--premium' : 'au-plan-badge--free' }}">
                                        {{ $editingUser->isPremium() ? 'Premium' : 'Free' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Account Details Grid -->
                            <dl class="au-account-details">
                                <div class="au-detail-tile">
                                    <dt>Bergabung</dt>
                                    <dd>{{ $editingUser->created_at->format('d M Y') }}</dd>
                                </div>
                                <div class="au-detail-tile">
                                    <dt>Status Email</dt>
                                    <dd>{{ $editingUser->hasVerifiedEmail() ? 'Terverifikasi' : 'Belum Verifikasi' }}</dd>
                                </div>
                                <div class="au-detail-tile">
                                    <dt>Masa Aktif Premium</dt>
                                    <dd>{{ $editingUser->premium_until?->format('d M Y') ?? ($editingUser->isPremium() ? 'Aktif Permanen' : '—') }}</dd>
                                </div>
                                <div class="au-detail-tile">
                                    <dt>Metode Sign-in</dt>
                                    <dd>{{ collect([$editingUser->password ? 'Email' : null, $editingUser->google_id ? 'Google' : null, $editingUser->linkedin_id ? 'LinkedIn' : null])->filter()->implode(' · ') ?: 'Standar' }}</dd>
                                </div>
                            </dl>
                        @endif

                        <!-- Form 1: Profile & Access -->
                        <form wire:submit="updateUser" class="au-form au-form-section">
                            <h3>Informasi Akun & Akses</h3>
                            <label for="edit-member-name">
                                Nama Lengkap
                                <input id="edit-member-name" type="text" wire:model="editName" required placeholder="Nama lengkap member…">
                                @error('editName')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label for="edit-member-email">
                                Alamat Email
                                <input id="edit-member-email" type="email" wire:model="editEmail" required placeholder="nama@email.com">
                                @error('editEmail')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <div class="au-checkbox-row">
                                <label class="au-checkbox-label">
                                    <input type="checkbox" wire:model="editIsPremium">
                                    Akses Member Premium
                                </label>
                                <label class="au-checkbox-label">
                                    <input type="checkbox" wire:model="editIsAdmin">
                                    Administrator
                                </label>
                            </div>
                            <p class="au-form-hint">Mengubah status premium akan otomatis memperbarui masa berlaku membership dan status pembayaran user.</p>
                            <div class="au-form-actions">
                                <button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="updateUser">
                                    <span wire:loading.remove wire:target="updateUser">Simpan Perubahan Akun</span>
                                    <span wire:loading wire:target="updateUser">Menyimpan…</span>
                                </button>
                            </div>
                        </form>

                        <!-- Form 2: AI Quota Credits -->
                        <form wire:submit="saveAiQuotas" class="au-form au-form-section" style="margin-top: 18px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <h3>Alokasi Kuota AI Credits</h3>
                                <button type="button" class="au-clear" style="padding: 4px 8px;" wire:click="resetAllAiQuotas" wire:confirm="Reset kredit member ini ke default sesuai paket membership mereka?">
                                    <i class="ph ph-arrow-counter-clockwise"></i> Reset ke Default
                                </button>
                            </div>
                            <div class="au-quota-grid">
                                <div class="au-quota-tile">
                                    <label for="credit-editAiCredits">
                                        Analyze CV
                                        <input id="credit-editAiCredits" type="number" wire:model="editAiCredits" min="0" step="1" required>
                                        @error('editAiCredits')<span class="au-field-error">{{ $message }}</span>@enderror
                                    </label>
                                </div>
                                <div class="au-quota-tile">
                                    <label for="credit-editClCredits">
                                        Cover Letter
                                        <input id="credit-editClCredits" type="number" wire:model="editClCredits" min="0" step="1" required>
                                        @error('editClCredits')<span class="au-field-error">{{ $message }}</span>@enderror
                                    </label>
                                </div>
                                <div class="au-quota-tile">
                                    <label for="credit-editPhotoCredits">
                                        AI Photo Studio
                                        <input id="credit-editPhotoCredits" type="number" wire:model="editPhotoCredits" min="0" step="1" required>
                                        @error('editPhotoCredits')<span class="au-field-error">{{ $message }}</span>@enderror
                                    </label>
                                </div>
                            </div>
                            <p class="au-form-hint">Status Trial AI Analyzer: <strong>{{ $editingUser?->has_used_ai_analyzer_trial ? 'Sudah Digunakan' : 'Belum Digunakan' }}</strong>.</p>
                            <div class="au-form-actions">
                                <button type="submit" class="au-button au-button--primary" wire:loading.attr="disabled" wire:target="saveAiQuotas">
                                    <span wire:loading.remove wire:target="saveAiQuotas">Simpan Kuota AI</span>
                                    <span wire:loading wire:target="saveAiQuotas">Menyimpan…</span>
                                </button>
                            </div>
                        </form>

                        <!-- Account Quick Actions -->
                        @if($editingUser)
                            <section class="au-account-actions" aria-label="Tindakan Akun Cepat">
                                <h3>Tindakan Cepat</h3>
                                <div class="au-account-action-buttons">
                                    <button type="button" wire:click="openPremiumConfirmModal({{ $editingUser->id }})" class="au-button">
                                        <i class="ph ph-crown"></i>
                                        {{ $editingUser->is_premium ? 'Hapus Premium' : 'Berikan Premium' }}
                                    </button>
                                    <button type="button" wire:click="openSendEmailModal({{ $editingUser->id }})" class="au-button">
                                        <i class="ph ph-paper-plane-tilt"></i>
                                        Kirim Email
                                    </button>
                                    <button type="button" wire:click="confirmDeleteUserModal({{ $editingUser->id }})" class="au-account-delete">
                                        <i class="ph ph-trash"></i>
                                        Hapus Akun
                                    </button>
                                </div>
                            </section>
                        @endif
                    </div>
                </section>
            </div>
        </template>
    @endif

    <!-- =====================================================================
         MODAL 2: Add Administrator ($showCreateAdminModal)
         ===================================================================== -->
    @if($showCreateAdminModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showCreateAdminModal" data-dialog-close="$wire.closeCreateAdminModal()" aria-labelledby="showCreateAdminModal-title" class="au-dialog-overlay">
                <section class="au-dialog">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showCreateAdminModal-title">Tambah Administrator Baru</h2>
                            <p>Buat akun admin baru dengan hak akses ke seluruh dashboard manajemen TraKerja.</p>
                        </div>
                        <button type="button" wire:click="closeCreateAdminModal" class="au-dialog-close" aria-label="Tutup dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <form wire:submit="createAdmin" class="au-form">
                            <label for="admin-name">
                                Nama Lengkap
                                <input id="admin-name" type="text" wire:model="newAdminName" required placeholder="Nama administrator…">
                                @error('newAdminName')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label for="admin-email">
                                Alamat Email
                                <input id="admin-email" type="email" wire:model="newAdminEmail" required placeholder="admin@trakerja.com">
                                @error('newAdminEmail')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label for="admin-password">
                                Kata Sandi
                                <input id="admin-password" type="password" wire:model="newAdminPassword" required minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter…">
                                @error('newAdminPassword')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label for="admin-password-confirmation">
                                Konfirmasi Kata Sandi
                                <input id="admin-password-confirmation" type="password" wire:model="newAdminPasswordConfirmation" required minlength="8" autocomplete="new-password" placeholder="Ulangi kata sandi…">
                                @error('newAdminPasswordConfirmation')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>

                            <div class="au-dialog-actions">
                                <button type="button" wire:click="closeCreateAdminModal" class="au-button">Batal</button>
                                <button type="submit" wire:loading.attr="disabled" wire:target="createAdmin" class="au-button au-button--primary">
                                    <span wire:loading.remove wire:target="createAdmin">Buat Akun Admin</span>
                                    <span wire:loading wire:target="createAdmin">Membuat Akun…</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </template>
    @endif

    <!-- =====================================================================
         MODAL 3: Send Email ($showSendEmailModal)
         ===================================================================== -->
    @if($showSendEmailModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showSendEmailModal" data-dialog-close="$wire.closeSendEmailModal()" aria-labelledby="showSendEmailModal-title" class="au-dialog-overlay">
                <section class="au-dialog au-dialog--small">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showSendEmailModal-title">Kirim Email ke Member</h2>
                            <p>Pilih template email otomatis yang ingin dikirimkan langsung ke inbox user.</p>
                        </div>
                        <button type="button" wire:click="closeSendEmailModal" class="au-dialog-close" aria-label="Tutup dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <div class="au-recipient">
                            <strong>{{ $emailTargetUserName }}</strong>
                            <span>{{ $emailTargetUserEmail }}</span>
                        </div>
                        <form wire:submit="sendEmail" class="au-form">
                            <label for="member-email-type">
                                Template Pesan
                                <select id="member-email-type" wire:model.live="emailType">
                                    @foreach([
                                        'welcome'=>'Welcome Email (Sambutan)',
                                        'verification'=>'Verifikasi Email Akun',
                                        'verification_reminder'=>'Pengingat Verifikasi Email',
                                        'job_reminder'=>'Pengingat Lamaran Kerja',
                                        'monthly_motivation'=>'Motivasi Bulanan Karier',
                                        're_engagement'=>'Re-engagement (Ajak Kembali)',
                                        'product_update'=>'Update Fitur TraKerja',
                                        'new_vibe'=>'Pengumuman Suasana Baru',
                                        'ai_analyzer'=>'Pengumuman AI Analyzer',
                                        'ai_photo'=>'Pengumuman AI Photo Studio',
                                        'follow_up_feature'=>'Pengumuman Fitur Follow-up',
                                        'premium_granted'=>'Notifikasi Akses Premium Diberikan',
                                        'hiring_season'=>'Hiring Season Alert',
                                        'kemerdekaan_ri'=>'Hari Kemerdekaan RI',
                                        'pahlawan'=>'Hari Pahlawan',
                                        'hari_ibu'=>'Hari Ibu',
                                        'natal'=>'Hari Raya Natal',
                                        'tahun_baru_islam'=>'Tahun Baru Islam'
                                    ] as $type => $label)
                                        <option value="{{ $type }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('emailType')<span class="au-field-error">{{ $message }}</span>@enderror
                            </label>
                            <p class="au-form-hint">Email akan dikirimkan secara instan melalui mailer sistem TraKerja ke alamat tujuan di atas.</p>
                            <div class="au-dialog-actions">
                                <button type="button" wire:click="closeSendEmailModal" class="au-button">Batal</button>
                                <button type="submit" wire:loading.attr="disabled" wire:target="sendEmail" class="au-button au-button--primary">
                                    <span wire:loading.remove wire:target="sendEmail">Kirim Sekarang</span>
                                    <span wire:loading wire:target="sendEmail">Mengirim…</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </template>
    @endif

    <!-- =====================================================================
         MODAL 4: Confirm Premium ($showPremiumConfirmModal)
         ===================================================================== -->
    @if($showPremiumConfirmModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showPremiumConfirmModal" data-dialog-close="$wire.closePremiumConfirmModal()" aria-labelledby="showPremiumConfirmModal-title" class="au-dialog-overlay">
                <section class="au-dialog au-dialog--small">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showPremiumConfirmModal-title">
                                {{ $confirmTargetUserIsPremium ? 'Hapus Akses Premium?' : 'Berikan Akses Premium?' }}
                            </h2>
                            <p>Konfirmasi perubahan hak istimewa akun member ini.</p>
                        </div>
                        <button type="button" wire:click="closePremiumConfirmModal" class="au-dialog-close" aria-label="Tutup dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <div class="au-recipient au-recipient--warning">
                            <strong>{{ $confirmTargetUserName }}</strong>
                            <span>
                                {{ $confirmTargetUserIsPremium 
                                    ? 'Akses premium akan dinonaktifkan. Kuota kredit dan fitur eksklusif akan dikembalikan ke standar paket gratis.' 
                                    : 'Akses premium akan diaktifkan selama ' . config('pakasir.premium_duration_days', 365) . ' hari dan seluruh kuota fitur AI akan dimaksimalkan.' }}
                            </span>
                        </div>
                        <div class="au-dialog-actions">
                            <button type="button" wire:click="closePremiumConfirmModal" class="au-button">Batal</button>
                            <button type="button" wire:click="toggleManualPremium" wire:loading.attr="disabled" wire:target="toggleManualPremium" class="au-button au-button--primary">
                                <span wire:loading.remove wire:target="toggleManualPremium">{{ $confirmTargetUserIsPremium ? 'Ya, Hapus Premium' : 'Ya, Berikan Premium' }}</span>
                                <span wire:loading wire:target="toggleManualPremium">Memproses…</span>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </template>
    @endif

    <!-- =====================================================================
         MODAL 5: Confirm Delete ($showDeleteConfirmModal)
         ===================================================================== -->
    @if($showDeleteConfirmModal)
        <template x-teleport="body">
            <div x-data role="dialog" aria-modal="true" tabindex="-1" x-dialog="$wire.showDeleteConfirmModal" data-dialog-close="$wire.closeDeleteConfirmModal()" aria-labelledby="showDeleteConfirmModal-title" class="au-dialog-overlay">
                <section class="au-dialog au-dialog--small">
                    <header class="au-dialog-heading">
                        <div>
                            <h2 id="showDeleteConfirmModal-title" style="color: #ef4444;">Hapus Akun Member Ini?</h2>
                            <p>Tindakan ini bersifat permanen dan tidak dapat dibatalkan.</p>
                        </div>
                        <button type="button" wire:click="closeDeleteConfirmModal" class="au-dialog-close" aria-label="Tutup dialog">
                            <i class="ph ph-x" aria-hidden="true"></i>
                        </button>
                    </header>
                    <div class="au-dialog-body">
                        <div class="au-recipient au-recipient--danger">
                            <strong>{{ $deleteTargetUserName }}</strong>
                            <span>Seluruh data akun, lamaran kerja yang tersimpan, riwayat aktivitas, dan data profil akan dihapus secara permanen dari basis data.</span>
                        </div>
                        <div class="au-dialog-actions">
                            <button type="button" wire:click="closeDeleteConfirmModal" class="au-button">Batal, Simpan Akun</button>
                            <button type="button" wire:click="performDelete" wire:loading.attr="disabled" wire:target="performDelete" class="au-button au-button--danger">
                                <span wire:loading.remove wire:target="performDelete">Hapus Permanen</span>
                                <span wire:loading wire:target="performDelete">Menghapus…</span>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </template>
    @endif
</div>
