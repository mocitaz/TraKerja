<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg border border-white/30">
                        <img src="{{ asset('images/icon.png') }}" 
                             alt="TraKerja Logo" 
                             class="w-5 h-5 sm:w-6 sm:h-6"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent">
                            AI Resume Analyzer
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Analisis CV Anda dengan AI untuk rekomendasi perbaikan</p>
                    </div>
                </div>
            </div>
            <div class="hidden sm:flex items-center space-x-2">
                <div class="hidden sm:flex items-center space-x-2 bg-green-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded-full">
                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-primary-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-medium text-primary-700">Live</span>
                </div>
                <div class="text-xs text-gray-400 hidden sm:block">
                    {{ now()->format('M d, Y') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Info Card -->
            <div class="bg-gradient-to-br from-purple-50 to-blue-50 border border-purple-200 rounded-lg p-4 sm:p-6 mb-6">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-1">Cara Menggunakan</h3>
                        <ul class="text-xs sm:text-sm text-gray-700 space-y-1">
                            <li>1. Upload file CV/Resume Anda dalam format PDF</li>
                            <li>2. Masukkan deskripsi pekerjaan yang ingin Anda lamar</li>
                            <li>3. Tunggu AI menganalisis dan memberikan rekomendasi perbaikan</li>
                            <li>4. Terima rekomendasi perbaikan untuk setiap bagian CV Anda</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('ai-analyzer.analyze') }}" method="POST" enctype="multipart/form-data" id="analyzeForm">
                    @csrf

                    <!-- Error Messages -->
                    @if ($errors->has('analyze_error'))
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 m-4 sm:m-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ $errors->first('analyze_error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="p-4 sm:p-6 space-y-6">
                        <!-- Resume Upload -->
                        <div>
                            <label for="resume" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload CV/Resume (PDF)
                                <span class="text-red-500">*</span>
                            </label>
                            <div id="upload-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg id="upload-icon" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="resume" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                            <span>Pilih file</span>
                                            <input id="resume" name="resume" type="file" accept=".pdf" class="sr-only" required>
                                        </label>
                                        <p class="pl-1">atau drag & drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF hingga 10MB</p>
                                </div>
                            </div>
                            @error('resume')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <!-- Success message when file is uploaded -->
                            <div id="file-success" class="mt-3 hidden">
                                <div class="bg-green-50 border-l-4 border-green-400 p-3 rounded-r-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-green-800">
                                                File berhasil diunggah: <span id="file-name" class="font-semibold"></span>
                                            </p>
                                            <p class="text-xs text-green-700 mt-0.5">
                                                Ukuran: <span id="file-size"></span>
                                            </p>
                                        </div>
                                        <button type="button" id="remove-file" class="ml-3 flex-shrink-0 text-green-600 hover:text-green-800 transition-colors">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Job Description -->
                        <div>
                            <label for="job_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Pekerjaan (Job Description)
                                <span class="text-red-500">*</span>
                            </label>
                                <textarea
                                id="job_description"
                                name="job_description"
                                rows="8"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm @error('job_description') border-red-300 @enderror"
                                placeholder="Salin dan tempel deskripsi pekerjaan dari lowongan yang ingin Anda lamar di sini..."
                                required
                                minlength="50"
                                maxlength="2500"
                            >{{ old('job_description') }}</textarea>
                            @error('job_description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">
                                <span id="char-count">0</span> / 2500 karakter (minimal 50 karakter)
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('tracker') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                Batal
                            </a>
                            <button
                                type="submit"
                                id="submit-btn"
                                class="px-6 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center space-x-2"
                            >
                                <svg id="loading-spinner" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span id="submit-text">Analisis dengan AI</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // File upload handling with success indicator
        const resumeInput = document.getElementById('resume');
        const uploadArea = document.getElementById('upload-area');
        const fileSuccess = document.getElementById('file-success');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const removeFileBtn = document.getElementById('remove-file');
        
        resumeInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Format file size
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                
                // Update file info
                fileName.textContent = file.name;
                fileSize.textContent = sizeInMB + ' MB';
                
                // Show success message
                fileSuccess.classList.remove('hidden');
                
                // Update upload area styling
                uploadArea.classList.remove('border-gray-300');
                uploadArea.classList.add('border-green-400', 'bg-green-50');
            }
        });
        
        // Remove file handler
        removeFileBtn.addEventListener('click', function() {
            // Clear file input
            resumeInput.value = '';
            
            // Hide success message
            fileSuccess.classList.add('hidden');
            
            // Reset upload area styling
            uploadArea.classList.remove('border-green-400', 'bg-green-50');
            uploadArea.classList.add('border-gray-300');
        });

        // Character counter
        const jobDescTextarea = document.getElementById('job_description');
        const charCount = document.getElementById('char-count');
        
        jobDescTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
            
            if (length < 50) {
                charCount.classList.add('text-red-600');
                charCount.classList.remove('text-gray-500');
            } else {
                charCount.classList.remove('text-red-600');
                charCount.classList.add('text-gray-500');
            }
        });

        // Update char count on load if there's old input
        if (jobDescTextarea.value) {
            jobDescTextarea.dispatchEvent(new Event('input'));
        }

        // Form submission loading state
        document.getElementById('analyzeForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const loadingSpinner = document.getElementById('loading-spinner');
            
            submitBtn.disabled = true;
            submitText.textContent = 'Menganalisis...';
            loadingSpinner.classList.remove('hidden');
        });
    </script>
</x-app-layout>
