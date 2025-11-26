<x-admin-layout>
    <div class="min-h-screen w-full bg-slate-100 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl space-y-6">
            {{-- Status Messages --}}
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 shadow-sm text-sm font-semibold text-emerald-800">
                    {{ session('success') }}
                    @if(session('details'))
                        <div class="mt-3 text-xs font-normal text-emerald-700">
                            <p>Total: {{ session('details')['total'] }} user</p>
                            <p>Berhasil: {{ session('details')['success'] }} user</p>
                            @if(isset(session('details')['failed']) && session('details')['failed'] > 0)
                                <p class="text-red-600">Gagal: {{ session('details')['failed'] }} user</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 shadow-sm text-sm font-semibold text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Hero --}}
            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Email Blast</p>
                        <h1 class="text-2xl font-semibold text-slate-900">Broadcast pesan penting tanpa spam</h1>
                        <p class="text-sm text-slate-500">Pilih tipe email, target user, dan kirim dengan percaya diri.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">
                        <svg class="h-4 w-4 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        kontrolkan komunikasi
                    </div>
                </div>
            </section>

            {{-- Form --}}
            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
                <form action="{{ route('admin.email-blast.send') }}" method="POST" id="emailBlastForm">
                    @csrf
                    <div class="space-y-8">
                        <div class="space-y-3">
                            <p class="text-sm font-semibold text-slate-600 uppercase tracking-[0.3em]">Pilih Tipe Email</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach([
                                    ['value'=>'welcome','title'=>'Welcome Email','description'=>'Email selamat datang untuk user baru'],
                                    ['value'=>'verification','title'=>'Verification Email','description'=>'Kirim email verifikasi untuk user'],
                                    ['value'=>'ai_analyzer','title'=>'AI Resume Analyzer','description'=>'Pengumuman trial gratis AI Analyzer'],
                                    ['value'=>'job_reminder','title'=>'Job Application Reminder','description'=>'Ajakan untuk catat lamaran kerja'],
                                    ['value'=>'monthly_motivation','title'=>'Monthly Motivation','description'=>'Bulan baru semangat baru'],
                                ] as $option)
                                    <label class="relative flex cursor-pointer rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-purple-500 focus-within:border-purple-500">
                                        <input type="radio" name="email_type" value="{{ $option['value'] }}" class="sr-only" required>
                                        <div class="flex items-center gap-3">
                                            <div class="h-5 w-5 rounded-full border-2 border-slate-300 flex items-center justify-center">
                                                <span class="h-2 w-2 rounded-full bg-primary-600 hidden"></span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $option['title'] }}</p>
                                                <p class="text-xs text-slate-500">{{ $option['description'] }}</p>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-3">
                            <p class="text-sm font-semibold text-slate-600 uppercase tracking-[0.3em]">Target User</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach([
                                    ['value'=>'all','title'=>'Semua User','description'=>'Semua user yang terdaftar','checked'=>true],
                                    ['value'=>'new','title'=>'User Baru','description'=>'Registrasi 7 hari terakhir'],
                                    ['value'=>'unverified','title'=>'User Belum Terverifikasi','description'=>'Belum verifikasi email'],
                                    ['value'=>'verified','title'=>'User Terverifikasi','description'=>'Sudah verifikasi email'],
                                    ['value'=>'premium','title'=>'User Premium','description'=>'Status premium/paid'],
                                    ['value'=>'free','title'=>'User Free','description'=>'Status free tier'],
                                ] as $option)
                                    <label class="relative flex cursor-pointer rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-purple-500 focus-within:border-purple-500">
                                        <input type="radio" name="target_user" value="{{ $option['value'] }}" class="sr-only" {{ $option['checked'] ?? false ? 'checked' : '' }} required>
                                        <div class="flex items-center gap-3">
                                            <div class="h-5 w-5 rounded-full border-2 border-slate-300 flex items-center justify-center">
                                                <span class="h-2 w-2 rounded-full bg-primary-600 hidden"></span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $option['title'] }}</p>
                                                <p class="text-xs text-slate-500">{{ $option['description'] }}</p>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-red-100 text-red-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-red-800">DILARANG SPAM!</p>
                                </div>
                                <div class="space-y-1 text-xs text-red-800">
                                    <p class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>Jangan kirim email berlebihan dalam waktu singkat.</p>
                                    <p class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>Pastikan konten email relevan untuk target user.</p>
                                    <p class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>Hormati privasi dan inbox pengguna.</p>
                                    <p class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>Email spam dapat merusak reputasi domain.</p>
                                    <p class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>Dapat menyebabkan akun email di-blacklist.</p>
                                </div>
                                <p class="rounded-xl bg-red-100 px-3 py-2 text-xs font-semibold text-red-800">
                                    Tips: Gunakan target user spesifik dan kirim hanya saat diperlukan.
                                </p>
                            </div>
                            <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-800 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-yellow-100 text-yellow-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Perhatian!</p>
                                        <p class="text-xs text-yellow-700">Email akan dikirim sesuai target yang dipilih. Pastikan pilihan sudah tepat.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-6 flex flex-wrap justify-end gap-3 border-t border-slate-200 pt-4">
                        <button type="button" onclick="window.location.href='{{ route('admin.index') }}'"
                                class="rounded-2xl border border-purple-200 bg-white px-5 py-2 text-sm font-semibold text-purple-800 shadow-sm transition hover:border-purple-300">
                            Batal
                        </button>
                        <button type="button" onclick="showConfirmModal()"
                                class="rounded-2xl bg-gradient-to-r from-purple-100 via-purple-100 to-purple-200 px-6 py-2 text-sm font-semibold text-purple-900 shadow-sm shadow-purple-200/60 transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-purple-100">
                            Kirim Email Blast
                        </button>
                    </div>
                </form>
            </section>

            {{-- Modals --}}
            <div id="errorModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 text-center">
                <div class="absolute inset-0 bg-black/40" onclick="hideErrorModal()"></div>
                <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-lg">
                    <div class="flex flex-col items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                        <h3 class="text-lg font-semibold text-slate-900">Perhatian</h3>
                        <p id="errorMessage" class="text-sm text-slate-600"></p>
                        <button type="button" onclick="hideErrorModal()"
                                class="mt-3 w-full rounded-2xl bg-gradient-to-r from-primary-600 to-primary-700 px-4 py-2 text-sm font-semibold text-white shadow-sm">
                        Mengerti
                    </button>
            </div>
        </div>
    </div>

            <div id="confirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 text-center">
                <div class="absolute inset-0 bg-black/40" onclick="hideConfirmModal()"></div>
                <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-lg">
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                            <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Email Blast</h3>
                            <p class="text-sm text-slate-500">Apakah Anda yakin ingin mengirim broadcast ini?</p>
                    </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                            <div class="flex justify-between text-xs uppercase tracking-[0.3em] text-slate-400">
                                <span>Tipe Email</span>
                                <span id="confirmEmailType" class="text-slate-900 font-semibold"></span>
                            </div>
                            <div class="mt-3 flex justify-between text-xs uppercase tracking-[0.3em] text-slate-400">
                                <span>Target User</span>
                                <span id="confirmTargetUser" class="text-slate-900 font-semibold"></span>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-red-200 bg-red-50 p-3 text-xs font-semibold text-red-800">
                            ⚠️ Ingat: DILARANG SPAM! Gunakan fitur ini dengan bijaksana.
                    </div>
                        <div class="mt-2 flex flex-col gap-3 lg:flex-row-reverse">
                            <button type="button" onclick="submitEmailBlast()" class="rounded-2xl bg-gradient-to-r from-purple-600 to-purple-700 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-purple-600/30">
                                Ya, Kirim Sekarang
                            </button>
                            <button type="button" onclick="hideConfirmModal()" class="rounded-2xl border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-600">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateRadioStyles() {
            const form = document.getElementById('emailBlastForm');
            if (!form) return;
            form.querySelectorAll('input[type="radio"]').forEach(r => {
                const label = r.closest('label');
                if (!label) return;
                const indicator = label.querySelector('.h-2.w-2');
                    if (indicator) {
                    indicator.classList.toggle('hidden', !r.checked);
                }
                label.classList.toggle('border-purple-500', r.checked);
                label.classList.toggle('border-slate-200', !r.checked);
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('emailBlastForm');
            if (!form) return;
            form.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', updateRadioStyles);
            });
            updateRadioStyles();
        });

        function showErrorModal(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideErrorModal() {
            document.getElementById('errorModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function showConfirmModal() {
            const emailType = document.querySelector('input[name="email_type"]:checked')?.value;
            const targetUser = document.querySelector('input[name="target_user"]:checked')?.value;
            if (!emailType || !targetUser) {
                let message = 'Harap pilih ';
                if (!emailType && !targetUser) {
                    message += 'tipe email dan target user terlebih dahulu.';
                } else if (!emailType) {
                    message += 'tipe email terlebih dahulu.';
                } else {
                    message += 'target user terlebih dahulu.';
                }
                showErrorModal(message);
                return;
            }
            
            const emailTypeText = {
                'welcome': 'Welcome Email',
                'verification': 'Verification Email',
                'ai_analyzer': 'AI Resume Analyzer',
                'job_reminder': 'Job Application Reminder',
                'monthly_motivation': 'Monthly Motivation',
            }[emailType] || 'Email Blast';

            const targetText = {
                'all': 'Semua User',
                'new': 'User Baru',
                'unverified': 'User Belum Terverifikasi',
                'verified': 'User Terverifikasi',
                'premium': 'User Premium',
                'free': 'User Free',
            }[targetUser] || 'Target Special';

            document.getElementById('confirmEmailType').textContent = emailTypeText;
            document.getElementById('confirmTargetUser').textContent = targetText;
            document.getElementById('confirmModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function submitEmailBlast() {
            document.getElementById('emailBlastForm').submit();
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('confirmModal').classList.contains('hidden')) {
                    hideConfirmModal();
                }
                if (!document.getElementById('errorModal').classList.contains('hidden')) {
                    hideErrorModal();
                }
            }
        });

        document.getElementById('confirmModal')?.addEventListener('click', function (e) {
            if (e.target.id === 'confirmModal') {
                hideConfirmModal();
            }
        });

        document.getElementById('errorModal')?.addEventListener('click', function (e) {
            if (e.target.id === 'errorModal') {
                hideErrorModal();
            }
        });
    </script>
</x-admin-layout>

