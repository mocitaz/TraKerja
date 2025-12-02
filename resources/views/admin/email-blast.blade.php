<x-admin-layout>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    @if(session('details'))
                        <div class="mt-3 text-sm">
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
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Email Blast Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('admin.email-blast.send') }}" method="POST" id="emailBlastForm">
                    @csrf

                    <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                        <!-- Email Type Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Pilih Tipe Email
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                                <label class="relative flex cursor-pointer rounded-lg border-2 border-gray-200 p-4 focus:outline-none hover:border-primary-500 transition-colors">
                                    <input type="radio" name="email_type" value="welcome" class="sr-only" required>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-5 w-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                                    <div class="h-2 w-2 rounded-full bg-primary-600 hidden"></div>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">Welcome Email</p>
                                                <p class="text-xs text-gray-500 mt-1">Email selamat datang untuk user baru</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border-2 border-gray-200 p-4 focus:outline-none hover:border-primary-500 transition-colors">
                                    <input type="radio" name="email_type" value="verification" class="sr-only" required>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-5 w-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                                    <div class="h-2 w-2 rounded-full bg-primary-600 hidden"></div>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">Verification Email</p>
                                                <p class="text-xs text-gray-500 mt-1">Kirim email verifikasi untuk user</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border-2 border-gray-200 p-4 focus:outline-none hover:border-primary-500 transition-colors">
                                    <input type="radio" name="email_type" value="ai_analyzer" class="sr-only" required>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-5 w-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                                    <div class="h-2 w-2 rounded-full bg-primary-600 hidden"></div>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">AI Resume Analyzer</p>
                                                <p class="text-xs text-gray-500 mt-1">Pengumuman trial gratis AI Analyzer</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border-2 border-gray-200 p-4 focus:outline-none hover:border-primary-500 transition-colors">
                                    <input type="radio" name="email_type" value="job_reminder" class="sr-only" required>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-5 w-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                                    <div class="h-2 w-2 rounded-full bg-primary-600 hidden"></div>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">Job Application Reminder</p>
                                                <p class="text-xs text-gray-500 mt-1">Ajakan untuk catat lamaran kerja</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border-2 border-gray-200 p-4 focus:outline-none hover:border-primary-500 transition-colors">
                                    <input type="radio" name="email_type" value="monthly_motivation" class="sr-only" required>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-5 w-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                                    <div class="h-2 w-2 rounded-full bg-primary-600 hidden"></div>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">Monthly Motivation</p>
                                                <p class="text-xs text-gray-500 mt-1">Bulan baru semangat baru</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Target User Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Target User
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <label class="relative flex cursor-pointer rounded-lg border-2 border-gray-200 p-4 focus:outline-none hover:border-primary-500 transition-colors">
                                    <input type="radio" name="target_user" value="all" class="sr-only" checked required>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-5 w-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                                    <div class="h-2 w-2 rounded-full bg-primary-600 hidden"></div>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">Semua User</p>
                                                <p class="text-xs text-gray-500 mt-1">Semua user yang terdaftar</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border-2 border-gray-200 p-4 focus:outline-none hover:border-primary-500 transition-colors">
                                    <input type="radio" name="target_user" value="new" class="sr-only" required>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-5 w-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                                    <div class="h-2 w-2 rounded-full bg-primary-600 hidden"></div>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">User Baru</p>
                                                <p class="text-xs text-gray-500 mt-1">User yang registrasi dalam 7 hari terakhir</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border-2 border-gray-200 p-4 focus:outline-none hover:border-primary-500 transition-colors">
                                    <input type="radio" name="target_user" value="unverified" class="sr-only" required>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-5 w-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                                    <div class="h-2 w-2 rounded-full bg-primary-600 hidden"></div>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">User Belum Terverifikasi</p>
                                                <p class="text-xs text-gray-500 mt-1">User yang belum verifikasi email</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border-2 border-gray-200 p-4 focus:outline-none hover:border-primary-500 transition-colors">
                                    <input type="radio" name="target_user" value="verified" class="sr-only" required>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-5 w-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                                    <div class="h-2 w-2 rounded-full bg-primary-600 hidden"></div>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">User Terverifikasi</p>
                                                <p class="text-xs text-gray-500 mt-1">Hanya user yang sudah verifikasi email</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border-2 border-gray-200 p-4 focus:outline-none hover:border-primary-500 transition-colors">
                                    <input type="radio" name="target_user" value="premium" class="sr-only" required>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-5 w-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                                    <div class="h-2 w-2 rounded-full bg-primary-600 hidden"></div>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">User Premium</p>
                                                <p class="text-xs text-gray-500 mt-1">User dengan status premium/paid</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border-2 border-gray-200 p-4 focus:outline-none hover:border-primary-500 transition-colors">
                                    <input type="radio" name="target_user" value="free" class="sr-only" required>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-5 w-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                                    <div class="h-2 w-2 rounded-full bg-primary-600 hidden"></div>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">User Free</p>
                                                <p class="text-xs text-gray-500 mt-1">User dengan status free tier</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Anti-Spam Warning -->
                        <div class="bg-red-50 border-2 border-red-300 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-red-800 mb-2">⚠️ DILARANG SPAM!</h3>
                                    <div class="text-sm text-red-700 space-y-1.5">
                                        <p class="font-semibold">Gunakan fitur ini dengan bijaksana:</p>
                                        <ul class="list-disc list-inside space-y-1 ml-1">
                                            <li>Jangan kirim email berlebihan dalam waktu singkat</li>
                                            <li>Pastikan konten email relevan untuk target user</li>
                                            <li>Hormati privasi dan inbox pengguna</li>
                                            <li>Email spam dapat merusak reputasi domain</li>
                                            <li>Dapat menyebabkan akun email di-blacklist</li>
                                        </ul>
                                        <p class="font-semibold mt-3 bg-red-100 p-2 rounded">
                                            💡 Tips: Gunakan target user yang spesifik dan kirim hanya saat diperlukan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warning -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <p class="font-medium">Perhatian!</p>
                                    <p class="mt-1">Email akan dikirim ke semua user sesuai target yang dipilih. Pastikan Anda sudah memilih dengan benar.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-gray-50 px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                        <button type="button" onclick="window.location.href='{{ route('admin.index') }}'" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Batal
                        </button>
                        <button type="button" onclick="showConfirmModal()" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-primary-600 to-primary-700 rounded-lg hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Kirim Email Blast
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="error-modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideErrorModal()"></div>

        <!-- Modal Container -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal Panel -->
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-6 pt-6 pb-4">
                    <!-- Icon -->
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <!-- Title -->
                    <div class="text-center mb-5">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-2" id="error-modal-title">
                            Perhatian
                        </h3>
                        <p id="errorMessage" class="text-sm text-gray-600">
                            <!-- Error message will be inserted here -->
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 px-6 py-4 flex justify-center">
                    <button type="button" onclick="hideErrorModal()" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg border border-transparent bg-primary-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideConfirmModal()"></div>

        <!-- Modal Container -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal Panel -->
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-6 pt-6 pb-4">
                    <!-- Icon -->
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 mb-4">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>

                    <!-- Title -->
                    <div class="text-center mb-5">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-2" id="modal-title">
                            Konfirmasi Email Blast
                        </h3>
                        <p class="text-sm text-gray-500">
                            Apakah Anda yakin ingin mengirim email blast?
                        </p>
                    </div>

                    <!-- Details -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Tipe Email:</span>
                                <span id="confirmEmailType" class="text-sm font-semibold text-gray-900"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Target User:</span>
                                <span id="confirmTargetUser" class="text-sm font-semibold text-gray-900"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Anti-Spam Reminder -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div class="text-xs leading-relaxed text-red-800">
                                <p class="font-bold mb-1">⚠️ Ingat: DILARANG SPAM!</p>
                                <p>Gunakan fitur ini dengan bijaksana. Email spam dapat merusak reputasi dan menyebabkan blacklist.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Warning -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <p class="text-xs leading-relaxed text-yellow-800">
                            <strong>Perhatian:</strong> Email akan dikirim ke semua user sesuai target yang dipilih. Pastikan Anda sudah memilih dengan benar.
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                    <button type="button" onclick="submitEmailBlast()" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg border border-transparent bg-gradient-to-r from-primary-600 to-primary-700 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Ya, Kirim Sekarang
                    </button>
                    <button type="button" onclick="hideConfirmModal()" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Radio button styling
        function updateRadioStyles() {
            const form = document.getElementById('emailBlastForm');
            if (!form) return;
            
            form.querySelectorAll('input[type="radio"]').forEach(r => {
                const label = r.closest('label');
                if (!label) return;
                
                const indicator = label.querySelector('.h-2.w-2');
                const border = label.querySelector('.border-gray-200, .border-primary-500');
                
                if (r.checked) {
                    if (indicator) {
                        indicator.classList.remove('hidden');
                    }
                    if (border) {
                        border.classList.remove('border-gray-200');
                        border.classList.add('border-primary-500');
                    }
                } else {
                    if (indicator) {
                        indicator.classList.add('hidden');
                    }
                    if (border) {
                        border.classList.remove('border-primary-500');
                        border.classList.add('border-gray-200');
                    }
                }
            });
        }

        // Add event listeners to radio buttons
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('emailBlastForm');
            if (!form) return;
            
            form.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', updateRadioStyles);
            });
            
            // Initialize on page load
            updateRadioStyles();
        });

        // Show error modal
        function showErrorModal(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Hide error modal
        function hideErrorModal() {
            document.getElementById('errorModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Show confirmation modal
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
                'monthly_motivation': 'Monthly Motivation'
            }[emailType] || 'Email Blast';
            const targetText = {
                'all': 'Semua User',
                'new': 'User Baru',
                'unverified': 'User Belum Terverifikasi',
                'verified': 'User Terverifikasi',
                'premium': 'User Premium',
                'free': 'User Free'
            }[targetUser];

            // Update modal content
            document.getElementById('confirmEmailType').textContent = emailTypeText;
            document.getElementById('confirmTargetUser').textContent = targetText;
            
            // Show modal
            document.getElementById('confirmModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Hide confirmation modal
        function hideConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Submit form
        function submitEmailBlast() {
            document.getElementById('emailBlastForm').submit();
        }

        // Close modal on backdrop click
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target.id === 'confirmModal') {
                hideConfirmModal();
            }
        });

        document.getElementById('errorModal').addEventListener('click', function(e) {
            if (e.target.id === 'errorModal') {
                hideErrorModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('confirmModal').classList.contains('hidden')) {
                    hideConfirmModal();
                }
                if (!document.getElementById('errorModal').classList.contains('hidden')) {
                    hideErrorModal();
                }
            }
        });
    </script>
</x-admin-layout>

