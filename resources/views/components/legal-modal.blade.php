<!-- Terms of Service Modal -->
<div id="termsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full max-h-[85vh] overflow-hidden border border-gray-100">
                   <!-- Modal Header -->
                   <div class="bg-gradient-to-r from-purple-400 to-purple-500 p-5 text-white relative">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Terms of Service</h3>
                            <p class="text-white/90 text-sm">Please read and understand our service terms</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeTermsModal()" class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-colors relative z-10 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6 max-h-[50vh] overflow-y-auto">
                <!-- Philosophy Section -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-5 mb-5 border border-indigo-100">
                    <div class="text-center">
                        <h4 class="text-lg font-bold text-gray-900 mb-2">Our Commitment to You</h4>
                        <p class="text-gray-700 text-sm leading-relaxed">
                            At TraKerja, we believe every job seeker deserves a secure, transparent, and trustworthy platform. 
                            We provide comprehensive job application tracking tools while building an ecosystem that supports 
                            your career success with unwavering integrity and data security.
                        </p>
                    </div>
                </div>

                <!-- Key Points -->
                <div class="space-y-3">
                    <div class="bg-white border-l-4 border-blue-500 p-4 rounded-r-lg shadow-sm">
                        <h5 class="font-semibold text-gray-900 mb-1 text-sm">1. Service Agreement</h5>
                        <p class="text-gray-600 text-xs">By using TraKerja, you accept and agree to be bound by these terms and conditions.</p>
                    </div>

                    <div class="bg-white border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                        <h5 class="font-semibold text-gray-900 mb-1 text-sm">2. Data Security Guarantee</h5>
                        <p class="text-gray-600 text-xs">We use AES-256 encryption, automatic backups, and NEVER sell your data to third parties.</p>
                    </div>

                    <div class="bg-white border-l-4 border-purple-500 p-4 rounded-r-lg shadow-sm">
                        <h5 class="font-semibold text-gray-900 mb-1 text-sm">3. Complete Transparency</h5>
                        <p class="text-gray-600 text-xs">No hidden fees, no data selling, 100% transparent in all service aspects.</p>
                    </div>

                    <div class="bg-white border-l-4 border-orange-500 p-4 rounded-r-lg shadow-sm">
                        <h5 class="font-semibold text-gray-900 mb-1 text-sm">4. Our Commitment</h5>
                        <p class="text-gray-600 text-xs">We are committed to serving you with the highest integrity. Your career success is our success.</p>
                    </div>
                </div>

                <!-- Reading Progress -->
                <div class="mt-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-600">Reading Progress</span>
                        <span id="termsProgress" class="text-xs font-medium text-purple-600">0%</span>
                    </div>
                           <div class="w-full bg-gray-200 rounded-full h-1.5">
                               <div id="termsProgressBar" class="bg-gradient-to-r from-purple-400 to-purple-500 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                           </div>
                </div>
                
                <!-- Close Button Alternative -->
                <div class="mt-4 text-center">
                    <button type="button" onclick="closeTermsModal()" class="px-4 py-2 bg-gray-400 text-white text-xs rounded-lg hover:bg-gray-500 transition-colors cursor-pointer">
                        ✕ Close Terms Modal
                    </button>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-100">
                <div class="flex items-center">
                           <input type="checkbox" id="termsReadCheckbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" disabled>
                    <label for="termsReadCheckbox" class="ml-2 text-xs text-gray-700">
                        I have read and understood the Terms of Service
                    </label>
                </div>
                <button type="button" onclick="closeTermsModal()" class="px-6 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition-colors cursor-pointer">
                    Close Modal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Policy Modal -->
<div id="privacyModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full max-h-[85vh] overflow-hidden border border-gray-100">
                   <!-- Modal Header -->
                   <div class="bg-gradient-to-r from-purple-400 to-purple-500 p-5 text-white relative">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Privacy Policy</h3>
                            <p class="text-white/90 text-sm">Understand how we protect your privacy</p>
                        </div>
                    </div>
                    <button type="button" onclick="closePrivacyModal()" class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-colors relative z-10 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6 max-h-[50vh] overflow-y-auto">
                <!-- Privacy Philosophy -->
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-5 mb-5 border border-emerald-100">
                    <div class="text-center">
                        <h4 class="text-lg font-bold text-gray-900 mb-2">Your Privacy is Our Priority</h4>
                        <p class="text-gray-700 text-sm leading-relaxed">
                            At TraKerja, we understand that your personal data is your most valuable asset. 
                            We not only protect your information but ensure that every byte of data is treated 
                            with respect, security, and complete transparency.
                        </p>
                    </div>
                </div>

                <!-- Key Privacy Points -->
                <div class="space-y-3">
                    <div class="bg-white border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                        <h5 class="font-semibold text-gray-900 mb-1 text-sm">Zero Data Selling</h5>
                        <p class="text-gray-600 text-xs">We NEVER sell your data. This is a promise we will never break.</p>
                    </div>

                    <div class="bg-white border-l-4 border-blue-500 p-4 rounded-r-lg shadow-sm">
                        <h5 class="font-semibold text-gray-900 mb-1 text-sm">Military-Grade Encryption</h5>
                        <p class="text-gray-600 text-xs">Your data is encrypted with AES-256 standard used by military and security agencies.</p>
                    </div>

                    <div class="bg-white border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                        <h5 class="font-semibold text-gray-900 mb-1 text-sm">Complete Transparency</h5>
                        <p class="text-gray-600 text-xs">You know exactly what we do with your data, anytime. Nothing is hidden.</p>
                    </div>

                    <div class="bg-white border-l-4 border-purple-500 p-4 rounded-r-lg shadow-sm">
                        <h5 class="font-semibold text-gray-900 mb-1 text-sm">Your Rights</h5>
                        <p class="text-gray-600 text-xs">You have full rights to access, modify, or delete your data at any time.</p>
                    </div>
                </div>

                <!-- Reading Progress -->
                <div class="mt-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-600">Reading Progress</span>
                        <span id="privacyProgress" class="text-xs font-medium text-purple-600">0%</span>
                    </div>
                           <div class="w-full bg-gray-200 rounded-full h-1.5">
                               <div id="privacyProgressBar" class="bg-gradient-to-r from-purple-400 to-purple-500 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                           </div>
                </div>
                
                <!-- Close Button Alternative -->
                <div class="mt-4 text-center">
                    <button type="button" onclick="closePrivacyModal()" class="px-4 py-2 bg-gray-400 text-white text-xs rounded-lg hover:bg-gray-500 transition-colors cursor-pointer">
                        ✕ Close Privacy Modal
                    </button>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-100">
                <div class="flex items-center">
                           <input type="checkbox" id="privacyReadCheckbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" disabled>
                    <label for="privacyReadCheckbox" class="ml-2 text-xs text-gray-700">
                        I have read and understood the Privacy Policy
                    </label>
                </div>
                <button type="button" onclick="closePrivacyModal()" class="px-6 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition-colors cursor-pointer">
                    Close Modal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Terms Modal Functions
function openTermsModal() {
    console.log('=== openTermsModal() called ===');
    const modal = document.getElementById('termsModal');
    console.log('Terms modal element:', modal);
    
    if (modal) {
        console.log('Terms modal found, opening...');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        console.log('Terms modal opened successfully');
        startTermsReadingProgress();
    } else {
        console.error('Terms modal not found - checking DOM...');
        console.log('All elements with "terms" in ID:', document.querySelectorAll('[id*="terms"]'));
        console.log('All elements with "modal" in ID:', document.querySelectorAll('[id*="modal"]'));
    }
}

function closeTermsModal() {
    console.log('closeTermsModal called');
    const modal = document.getElementById('termsModal');
    if (modal) {
        console.log('Terms modal found, closing...');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        console.log('Terms modal closed successfully');
    } else {
        console.error('Terms modal not found');
    }
}

// Privacy Modal Functions
function openPrivacyModal() {
    console.log('=== openPrivacyModal() called ===');
    const modal = document.getElementById('privacyModal');
    console.log('Privacy modal element:', modal);
    
    if (modal) {
        console.log('Privacy modal found, opening...');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        console.log('Privacy modal opened successfully');
        startPrivacyReadingProgress();
    } else {
        console.error('Privacy modal not found - checking DOM...');
        console.log('All elements with "privacy" in ID:', document.querySelectorAll('[id*="privacy"]'));
        console.log('All elements with "modal" in ID:', document.querySelectorAll('[id*="modal"]'));
    }
}

function closePrivacyModal() {
    console.log('closePrivacyModal called');
    const modal = document.getElementById('privacyModal');
    if (modal) {
        console.log('Privacy modal found, closing...');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        console.log('Privacy modal closed successfully');
    } else {
        console.error('Privacy modal not found');
    }
}

// Reading Progress Tracking
function startTermsReadingProgress() {
    console.log('Starting Terms reading progress...');
    const modal = document.getElementById('termsModal');
    const content = modal.querySelector('.max-h-\\[50vh\\]');
    const progressBar = document.getElementById('termsProgressBar');
    const progressText = document.getElementById('termsProgress');
    const checkbox = document.getElementById('termsReadCheckbox');
    
    if (!content || !progressBar || !progressText || !checkbox) {
        console.error('Terms modal elements not found:', {content, progressBar, progressText, checkbox});
        return;
    }
    
    let scrollProgress = 0;
    let timeProgress = 0;
    let scrollComplete = false;
    let timeComplete = false;
    
    // Reset progress
    progressBar.style.width = '0%';
    progressText.textContent = '0%';
    checkbox.disabled = true;
    checkbox.checked = false;
    
    console.log('Terms progress reset');
    
    // Simple fallback - enable checkbox after 2 seconds
    setTimeout(() => {
        console.log('Terms checkbox fallback enabling...');
        checkbox.disabled = false;
               checkbox.classList.add('ring-2', 'ring-purple-500', 'ring-opacity-50');
        
        const label = checkbox.nextElementSibling;
        if (label) {
                   label.classList.add('text-purple-600', 'font-semibold');
            label.textContent = '✓ I have read and understood the Terms of Service';
        }
        
        updateRegisterButton();
    }, 2000);
    
    // Track scroll progress
    content.addEventListener('scroll', function() {
        const scrollTop = content.scrollTop;
        const scrollHeight = content.scrollHeight - content.clientHeight;
        const scrollPercent = Math.round((scrollTop / scrollHeight) * 100);
        
        scrollProgress = Math.min(scrollPercent, 100);
        scrollComplete = scrollProgress >= 100;
        
        console.log('Terms scroll progress:', scrollProgress + '%');
        updateProgress();
    });
    
    // Track time spent reading (minimum 3 seconds)
    let startTime = Date.now();
    let readingInterval = setInterval(() => {
        const timeSpent = Date.now() - startTime;
        const timePercent = Math.min(timeSpent / 3000, 1) * 100; // 3 seconds for full reading
        
        timeProgress = Math.min(timePercent, 100);
        timeComplete = timeProgress >= 100;
        
        console.log('Terms time progress:', timeProgress + '%');
        updateProgress();
        
        if (timeComplete) {
            clearInterval(readingInterval);
        }
    }, 200);
    
    function updateProgress() {
        const currentProgress = Math.max(scrollProgress, timeProgress);
        progressBar.style.width = currentProgress + '%';
        progressText.textContent = Math.round(currentProgress) + '%';
        
        console.log('Terms progress update:', {
            scrollProgress,
            timeProgress,
            currentProgress,
            scrollComplete,
            timeComplete
        });
        
        // Enable checkbox when both scroll and time requirements are met
        if (scrollComplete && timeComplete) {
            console.log('Terms checkbox enabling...');
            checkbox.disabled = false;
               checkbox.classList.add('ring-2', 'ring-purple-500', 'ring-opacity-50');
            
            // Add visual feedback
            const label = checkbox.nextElementSibling;
            if (label) {
                   label.classList.add('text-purple-600', 'font-semibold');
                label.textContent = '✓ I have read and understood the Terms of Service';
            }
            
            // Don't auto-check, let user click manually
            updateRegisterButton();
        }
    }
}

function startPrivacyReadingProgress() {
    console.log('Starting Privacy reading progress...');
    const modal = document.getElementById('privacyModal');
    const content = modal.querySelector('.max-h-\\[50vh\\]');
    const progressBar = document.getElementById('privacyProgressBar');
    const progressText = document.getElementById('privacyProgress');
    const checkbox = document.getElementById('privacyReadCheckbox');
    
    if (!content || !progressBar || !progressText || !checkbox) {
        console.error('Privacy modal elements not found:', {content, progressBar, progressText, checkbox});
        return;
    }
    
    let scrollProgress = 0;
    let timeProgress = 0;
    let scrollComplete = false;
    let timeComplete = false;
    
    // Reset progress
    progressBar.style.width = '0%';
    progressText.textContent = '0%';
    checkbox.disabled = true;
    checkbox.checked = false;
    
    console.log('Privacy progress reset');
    
    // Simple fallback - enable checkbox after 2 seconds
    setTimeout(() => {
        console.log('Privacy checkbox fallback enabling...');
        checkbox.disabled = false;
               checkbox.classList.add('ring-2', 'ring-purple-500', 'ring-opacity-50');
        
        const label = checkbox.nextElementSibling;
        if (label) {
                   label.classList.add('text-purple-600', 'font-semibold');
            label.textContent = '✓ I have read and understood the Privacy Policy';
        }
        
        updateRegisterButton();
    }, 2000);
    
    // Track scroll progress
    content.addEventListener('scroll', function() {
        const scrollTop = content.scrollTop;
        const scrollHeight = content.scrollHeight - content.clientHeight;
        const scrollPercent = Math.round((scrollTop / scrollHeight) * 100);
        
        scrollProgress = Math.min(scrollPercent, 100);
        scrollComplete = scrollProgress >= 100;
        
        console.log('Privacy scroll progress:', scrollProgress + '%');
        updateProgress();
    });
    
    // Track time spent reading (minimum 3 seconds)
    let startTime = Date.now();
    let readingInterval = setInterval(() => {
        const timeSpent = Date.now() - startTime;
        const timePercent = Math.min(timeSpent / 3000, 1) * 100; // 3 seconds for full reading
        
        timeProgress = Math.min(timePercent, 100);
        timeComplete = timeProgress >= 100;
        
        console.log('Privacy time progress:', timeProgress + '%');
        updateProgress();
        
        if (timeComplete) {
            clearInterval(readingInterval);
        }
    }, 500);
    
    function updateProgress() {
        const currentProgress = Math.max(scrollProgress, timeProgress);
        progressBar.style.width = currentProgress + '%';
        progressText.textContent = Math.round(currentProgress) + '%';
        
        console.log('Privacy progress update:', {
            scrollProgress,
            timeProgress,
            currentProgress,
            scrollComplete,
            timeComplete
        });
        
        // Enable checkbox when both scroll and time requirements are met
        if (scrollComplete && timeComplete) {
            console.log('Privacy checkbox enabling...');
            checkbox.disabled = false;
               checkbox.classList.add('ring-2', 'ring-purple-500', 'ring-opacity-50');
            
            // Add visual feedback
            const label = checkbox.nextElementSibling;
            if (label) {
                   label.classList.add('text-purple-600', 'font-semibold');
                label.textContent = '✓ I have read and understood the Privacy Policy';
            }
            
            // Don't auto-check, let user click manually
            updateRegisterButton();
        }
    }
}

// Update register button state
function updateRegisterButton() {
    console.log('Updating register button state...');
    const termsChecked = document.getElementById('termsReadCheckbox').checked;
    const privacyChecked = document.getElementById('privacyReadCheckbox').checked;
    const mainTermsCheckbox = document.getElementById('terms'); // Main checkbox in register form
    const registerButton = document.querySelector('button[type="submit"]');
    
    console.log('Checkbox states:', {termsChecked, privacyChecked, mainTermsCheckbox: mainTermsCheckbox?.checked});
    
    if (!registerButton) {
        console.error('Register button not found');
        return;
    }
    
    if (termsChecked && privacyChecked) {
        console.log('Both modal checkboxes checked, enabling main checkbox and register button');
        
        // Enable and check the main terms checkbox
        if (mainTermsCheckbox) {
            mainTermsCheckbox.disabled = false;
            mainTermsCheckbox.checked = true;
            console.log('Main terms checkbox enabled and checked');
        }
        
        registerButton.disabled = false;
        registerButton.classList.remove('opacity-50', 'cursor-not-allowed');
        registerButton.classList.add('hover:shadow-xl', 'hover:scale-[1.02]');
    } else {
        console.log('Modal checkboxes not complete, disabling main checkbox and register button');
        
        // Disable the main terms checkbox
        if (mainTermsCheckbox) {
            mainTermsCheckbox.disabled = true;
            mainTermsCheckbox.checked = false;
        }
        
        registerButton.disabled = true;
        registerButton.classList.add('opacity-50', 'cursor-not-allowed');
        registerButton.classList.remove('hover:shadow-xl', 'hover:scale-[1.02]');
    }
}

// Check registration validation
function validateRegistration() {
    const mainTermsCheckbox = document.getElementById('terms');
    const termsChecked = document.getElementById('termsReadCheckbox').checked;
    const privacyChecked = document.getElementById('privacyReadCheckbox').checked;
    
    console.log('Validation check:', {
        mainTermsCheckbox: mainTermsCheckbox?.checked,
        termsChecked,
        privacyChecked
    });
    
    if (!mainTermsCheckbox || !mainTermsCheckbox.checked) {
        // Create a more professional notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center';
        notification.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <span class="font-medium">Please read and agree to both legal documents before proceeding.</span>
        `;
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
        
        return false;
    }
    return true;
}

        // Simple global functions for testing
        window.testTermsModal = function() {
            console.log('Test function called - opening Terms modal');
            openTermsModal();
        };
        
        window.testPrivacyModal = function() {
            console.log('Test function called - opening Privacy modal');
            openPrivacyModal();
        };
        
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== DOM Content Loaded - Initializing legal modals ===');
            
            // Add click event listeners to modal buttons
            const termsButton = document.getElementById('termsButton');
            const privacyButton = document.getElementById('privacyButton');
            
            console.log('Terms button found:', termsButton);
            console.log('Privacy button found:', privacyButton);
            
            if (termsButton) {
                // Remove any existing listeners
                termsButton.removeEventListener('click', arguments.callee);
                
                termsButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('=== Terms button clicked - opening modal ===');
                    openTermsModal();
                });
                console.log('Terms button event listener added successfully');
            } else {
                console.error('Terms button not found - will retry...');
            }
            
            if (privacyButton) {
                // Remove any existing listeners
                privacyButton.removeEventListener('click', arguments.callee);
                
                privacyButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('=== Privacy button clicked - opening modal ===');
                    openPrivacyModal();
                });
                console.log('Privacy button event listener added successfully');
            } else {
                console.error('Privacy button not found - will retry...');
            }
            
            // Add click event listeners using event delegation for both open and close buttons
            document.addEventListener('click', function(e) {
                // Check for Terms button click
                if (e.target.id === 'termsButton' || e.target.closest('#termsButton')) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Terms button clicked via event delegation');
                    openTermsModal();
                    return;
                }
                
                // Check for Privacy button click
                if (e.target.id === 'privacyButton' || e.target.closest('#privacyButton')) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Privacy button clicked via event delegation');
                    openPrivacyModal();
                    return;
                }
                
                // Check if clicked element or its parent has onclick="closeTermsModal()"
                if (e.target.closest('button[onclick="closeTermsModal()"]') || e.target.onclick && e.target.onclick.toString().includes('closeTermsModal')) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Terms close button clicked via event delegation');
                    closeTermsModal();
                }
                
                // Check if clicked element or its parent has onclick="closePrivacyModal()"
                if (e.target.closest('button[onclick="closePrivacyModal()"]') || e.target.onclick && e.target.onclick.toString().includes('closePrivacyModal')) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Privacy close button clicked via event delegation');
                    closePrivacyModal();
                }
            });
    
    // Add close event listeners to modal overlays
    const termsModal = document.getElementById('termsModal');
    const privacyModal = document.getElementById('privacyModal');
    
    if (termsModal) {
        termsModal.addEventListener('click', function(e) {
            if (e.target === termsModal) {
                closeTermsModal();
            }
        });
    }
    
    if (privacyModal) {
        privacyModal.addEventListener('click', function(e) {
            if (e.target === privacyModal) {
                closePrivacyModal();
            }
        });
    }
    
    // Add checkbox change listeners
    const termsCheckbox = document.getElementById('termsReadCheckbox');
    const privacyCheckbox = document.getElementById('privacyReadCheckbox');
    const mainTermsCheckbox = document.getElementById('terms');
    
    if (termsCheckbox) {
        termsCheckbox.addEventListener('change', function() {
            console.log('Terms modal checkbox changed:', this.checked);
            updateRegisterButton();
        });
    }
    
    if (privacyCheckbox) {
        privacyCheckbox.addEventListener('change', function() {
            console.log('Privacy modal checkbox changed:', this.checked);
            updateRegisterButton();
        });
    }
    
    if (mainTermsCheckbox) {
        mainTermsCheckbox.addEventListener('change', function() {
            console.log('Main terms checkbox changed:', this.checked);
            updateRegisterButton();
        });
    }
    
            // Add keyboard support (ESC key)
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const termsModal = document.getElementById('termsModal');
                    const privacyModal = document.getElementById('privacyModal');
                    
                    if (termsModal && !termsModal.classList.contains('hidden')) {
                        console.log('ESC key pressed, closing Terms modal');
                        closeTermsModal();
                    } else if (privacyModal && !privacyModal.classList.contains('hidden')) {
                        console.log('ESC key pressed, closing Privacy modal');
                        closePrivacyModal();
                    }
                }
            });
            
            console.log('Legal modals initialized');
        });
        
        // Fallback initialization on window load
        window.addEventListener('load', function() {
            console.log('Window loaded - Fallback initialization...');
            
            const termsButton = document.getElementById('termsButton');
            const privacyButton = document.getElementById('privacyButton');
            
            console.log('Fallback - Terms button found:', termsButton);
            console.log('Fallback - Privacy button found:', privacyButton);
            
            if (termsButton && !termsButton.hasAttribute('data-listener-added')) {
                termsButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Terms button clicked (fallback) - opening modal...');
                    openTermsModal();
                });
                termsButton.setAttribute('data-listener-added', 'true');
            }
            
            if (privacyButton && !privacyButton.hasAttribute('data-listener-added')) {
                privacyButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Privacy button clicked (fallback) - opening modal...');
                    openPrivacyModal();
                });
                privacyButton.setAttribute('data-listener-added', 'true');
            }
        });
</script>
