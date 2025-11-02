<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-4">
            <div class="flex items-center space-x-2.5 sm:space-x-4 min-w-0">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-7 h-7 sm:w-10 sm:h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow border border-white/30">
                        <img src="{{ asset('images/icon.png') }}" 
                             alt="TraKerja Logo" 
                             class="w-4.5 h-4.5 sm:w-6 sm:h-6"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-base sm:text-2xl font-bold bg-gradient-to-r from-[#d983e4] to-[#4e71c5] bg-clip-text text-transparent truncate">
                            Import CSV
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5 hidden sm:block">Import multiple job applications from CSV file</p>
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

    <div class="py-6 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-6 sm:p-8">

                    <!-- Instructions -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 sm:p-6 mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Import Guidelines</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                                    <div class="flex items-start">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span>Use the provided CSV template format</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span>Fields marked with <span class="font-semibold text-red-600">*</span> are <span class="font-semibold">required</span></span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span>Date format: <code class="bg-blue-100 px-1 rounded text-xs">YYYY-MM-DD</code></span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span>Interview time: <code class="bg-blue-100 px-1 rounded text-xs">YYYY-MM-DD HH:MM</code></span>
                                    </div>
                                    <div class="flex items-start md:col-span-2">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span>Invalid format will prompt template download</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if($errors->any() || session('download_template'))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-semibold text-red-800 mb-2">Import Error</h3>
                                    @if($errors->has('csv_file'))
                                        <p class="text-sm text-red-700 mb-3">{{ $errors->first('csv_file') }}</p>
                                    @endif
                                    
                                    @if(session('download_template'))
                                        <div class="mt-3">
                                            <a href="{{ route('csv.template') }}" 
                                               class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                Download Template
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Import Form -->
                    <form action="{{ route('csv.import.process') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- File Upload -->
                        <div class="space-y-2">
                            <label for="csv_file" class="block text-sm font-medium text-gray-700">
                                Select CSV File
                                <span class="text-red-500">*</span>
                            </label>
                            <div id="upload-area" class="mt-2 flex justify-center px-6 pt-8 pb-8 border-2 border-gray-300 border-dashed rounded-xl hover:border-primary-400 hover:bg-primary-50/30 transition-all duration-200 group">
                                <div class="space-y-3 text-center">
                                    <div id="upload-icon" class="mx-auto w-12 h-12 text-gray-400 group-hover:text-primary-500 transition-colors duration-200">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="flex text-sm text-gray-600">
                                            <label for="csv_file" class="relative cursor-pointer font-medium text-primary-600 hover:text-primary-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                                <span>Choose file</span>
                                                <input id="csv_file" name="csv_file" type="file" accept=".csv,.txt" class="sr-only" required>
                                            </label>
                                            <span class="ml-1">or drag and drop</span>
                                        </div>
                                        <p class="text-xs text-gray-500">CSV, TXT up to 10MB</p>
                                    </div>
                                </div>
                            </div>
                            @error('csv_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- File Success Indicator -->
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
                                                File berhasil dipilih: <span id="file-name" class="font-semibold"></span>
                                            </p>
                                            <p class="text-xs text-green-700 mt-0.5">
                                                Ukuran: <span id="file-size"></span> • Siap untuk diimport
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

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('tracker') }}" 
                               class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                            
                            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                                <a href="{{ route('csv.template') }}" 
                                   class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download Template
                                </a>
                                
                                <button type="submit" 
                                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Import CSV
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Template Information -->
                    <div class="mt-8 bg-gray-50 rounded-xl p-4 sm:p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">CSV Template Format</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <!-- Required Fields -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                    Required Fields
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Company Name</span>
                                        <span class="text-xs text-gray-500">Text (255 chars)</span>
                                    </div>
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Position</span>
                                        <span class="text-xs text-gray-500">Text (255 chars)</span>
                                    </div>
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Location</span>
                                        <span class="text-xs text-gray-500">Text (255 chars)</span>
                                    </div>
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Platform</span>
                                        <span class="text-xs text-gray-500">Text (255 chars)</span>
                                    </div>
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Application Status</span>
                                        <span class="text-xs text-gray-500">On Process, Declined, Accepted</span>
                                    </div>
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Recruitment Stage</span>
                                        <span class="text-xs text-gray-500">Applied, Follow Up, etc.</span>
                                    </div>
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Career Level</span>
                                        <span class="text-xs text-gray-500">Intern, Full Time, etc.</span>
                                    </div>
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Application Date</span>
                                        <span class="text-xs text-gray-500">YYYY-MM-DD</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Optional Fields -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                    Optional Fields
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Platform Link</span>
                                        <span class="text-xs text-gray-500">URL</span>
                                    </div>
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Notes</span>
                                        <span class="text-xs text-gray-500">Text</span>
                                    </div>
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Interview Date</span>
                                        <span class="text-xs text-gray-500">YYYY-MM-DD HH:MM</span>
                                    </div>
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Interview Type</span>
                                        <span class="text-xs text-gray-500">Phone, Video, etc.</span>
                                    </div>
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Interview Location</span>
                                        <span class="text-xs text-gray-500">Text</span>
                                    </div>
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-gray-700">Interview Notes</span>
                                        <span class="text-xs text-gray-500">Text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // File upload feedback
        const csvFileInput = document.getElementById('csv_file');
        const uploadArea = document.getElementById('upload-area');
        const fileSuccess = document.getElementById('file-success');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const removeFileBtn = document.getElementById('remove-file');
        
        csvFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file type
                const validTypes = ['.csv', '.txt', 'text/csv', 'text/plain', 'application/csv'];
                const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
                const isValid = validTypes.includes(fileExtension) || validTypes.includes(file.type);
                
                if (!isValid) {
                    alert('Please select a valid CSV or TXT file.');
                    csvFileInput.value = '';
                    return;
                }
                
                // Calculate file size
                let sizeText;
                if (file.size < 1024) {
                    sizeText = file.size + ' B';
                } else if (file.size < 1024 * 1024) {
                    sizeText = (file.size / 1024).toFixed(2) + ' KB';
                } else {
                    sizeText = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                }
                
                // Display file info
                fileName.textContent = file.name;
                fileSize.textContent = sizeText;
                fileSuccess.classList.remove('hidden');
                
                // Change upload area style
                uploadArea.classList.remove('border-gray-300', 'hover:border-primary-400', 'hover:bg-primary-50/30');
                uploadArea.classList.add('border-green-400', 'bg-green-50');
            }
        });
        
        removeFileBtn.addEventListener('click', function() {
            csvFileInput.value = '';
            fileSuccess.classList.add('hidden');
            uploadArea.classList.remove('border-green-400', 'bg-green-50');
            uploadArea.classList.add('border-gray-300', 'hover:border-primary-400', 'hover:bg-primary-50/30');
        });
        
        // Drag and drop support
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('border-primary-500', 'bg-primary-50');
        });
        
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-primary-500', 'bg-primary-50');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-primary-500', 'bg-primary-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                csvFileInput.files = files;
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                csvFileInput.dispatchEvent(event);
            }
        });
    </script>
</x-app-layout>

