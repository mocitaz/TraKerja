@extends('layouts.guest')

@section('title', 'Privacy Policy - TraKerja')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-emerald-50">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-[#4e71c5] to-[#d983e4] py-16">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center px-6 py-3 rounded-full bg-white/20 backdrop-blur-sm text-white mb-8">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span class="font-semibold">Data Protection</span>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                Privacy Policy
            </h1>
            <p class="text-xl text-white/90 max-w-3xl mx-auto mb-8">
                Privasi Anda adalah segalanya. Kami berkomitmen melindungi setiap detail pribadi Anda dengan teknologi keamanan terdepan.
            </p>
            <div class="flex items-center justify-center space-x-6 text-white/80">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Last updated: {{ date('F d, Y') }}</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>100% Secure</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <!-- Privacy Philosophy Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12 border border-gray-100">
            <div class="text-center mb-8">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-[#4e71c5]/10 to-[#d983e4]/10 text-[#4e71c5] mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span class="font-semibold">Filosofi Privasi TraKerja</span>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Privasi Anda adalah Segalanya</h2>
                <p class="text-lg text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Di TraKerja, kami memahami bahwa data pribadi Anda adalah aset yang paling berharga. 
                    Kami tidak hanya melindungi informasi Anda, tetapi juga memastikan bahwa setiap byte data 
                    diperlakukan dengan hormat, aman, dan transparan. Privasi bukan hanya kebijakan—ini adalah 
                    komitmen fundamental kami kepada Anda.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-gradient-to-br from-[#4e71c5]/5 to-[#d983e4]/5 rounded-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#4e71c5] to-[#d983e4] rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Zero Data Selling</h3>
                    <p class="text-gray-600">Kami TIDAK PERNAH menjual data Anda. Ini adalah janji yang tidak akan pernah kami langgar.</p>
                </div>
                
                <div class="text-center p-6 bg-gradient-to-br from-[#d983e4]/5 to-[#4e71c5]/5 rounded-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#d983e4] to-[#4e71c5] rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Military-Grade Encryption</h3>
                    <p class="text-gray-600">Data Anda dienkripsi dengan standar AES-256 yang digunakan oleh militer</p>
                </div>
                
                <div class="text-center p-6 bg-gradient-to-br from-[#4e71c5]/5 to-[#d983e4]/5 rounded-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#4e71c5] to-[#d983e4] rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Full Transparency</h3>
                    <p class="text-gray-600">Anda tahu persis apa yang kami lakukan dengan data Anda, kapan saja</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-2xl shadow-xl p-8 prose prose-lg max-w-none">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Pengenalan</h2>
                <p class="text-gray-700 leading-relaxed">
                    TraKerja ("kami," "kita," atau "kita") berkomitmen untuk melindungi privasi Anda. 
                    Privacy Policy ini menjelaskan bagaimana kami mengumpulkan, menggunakan, mengungkapkan, 
                    dan melindungi informasi Anda ketika Anda menggunakan platform tracking job application kami.
                </p>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mt-4">
                    <p class="text-blue-800 font-medium">
                        <strong>Komitmen Kami:</strong> Dengan menggunakan TraKerja, Anda menyetujui praktik data yang dijelaskan dalam kebijakan ini.
                    </p>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Information We Collect</h2>
                
                <h3 class="text-xl font-semibold text-gray-900 mb-3">2.1 Personal Information</h3>
                <p class="text-gray-700 leading-relaxed mb-4">
                    We collect information you provide directly to us, including:
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Name and contact information (email address)</li>
                    <li>Profile information and preferences</li>
                    <li>Job application data (company names, positions, dates, status)</li>
                    <li>Interview schedules and notes</li>
                    <li>Career goals and objectives</li>
                    <li>CV and resume information</li>
                </ul>

                <h3 class="text-xl font-semibold text-gray-900 mb-3 mt-6">2.2 Usage Information</h3>
                <p class="text-gray-700 leading-relaxed mb-4">
                    We automatically collect certain information when you use our service:
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Device information (browser type, operating system)</li>
                    <li>IP address and location data</li>
                    <li>Usage patterns and feature interactions</li>
                    <li>Performance and error logs</li>
                </ul>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">3. How We Use Your Information</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    We use the information we collect to:
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Provide and maintain our job tracking services</li>
                    <li>Process and organize your job applications</li>
                    <li>Send reminders and notifications</li>
                    <li>Generate analytics and insights</li>
                    <li>Improve our platform and user experience</li>
                    <li>Communicate with you about your account</li>
                    <li>Ensure security and prevent fraud</li>
                    <li>Comply with legal obligations</li>
                </ul>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Information Sharing and Disclosure</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li><strong>Service Providers:</strong> With trusted third-party providers who assist in operating our platform</li>
                    <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
                    <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of assets</li>
                    <li><strong>Consent:</strong> When you explicitly consent to sharing</li>
                </ul>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Data Security</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    We implement appropriate security measures to protect your personal information:
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Encryption of data in transit and at rest</li>
                    <li>Secure servers and databases</li>
                    <li>Regular security audits and updates</li>
                    <li>Access controls and authentication</li>
                    <li>Employee training on data protection</li>
                </ul>
                <p class="text-gray-700 leading-relaxed mt-4">
                    However, no method of transmission over the internet is 100% secure. While we strive to protect your information, we cannot guarantee absolute security.
                </p>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Data Retention</h2>
                <p class="text-gray-700 leading-relaxed">
                    We retain your personal information for as long as necessary to provide our services and fulfill the purposes outlined in this Privacy Policy. We will delete your data when:
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2 mt-4">
                    <li>You request account deletion</li>
                    <li>Your account has been inactive for an extended period</li>
                    <li>We are required to do so by law</li>
                </ul>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Your Rights and Choices</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    You have the following rights regarding your personal information:
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li><strong>Access:</strong> Request a copy of your personal data</li>
                    <li><strong>Correction:</strong> Update or correct inaccurate information</li>
                    <li><strong>Deletion:</strong> Request deletion of your personal data</li>
                    <li><strong>Portability:</strong> Export your data in a structured format</li>
                    <li><strong>Restriction:</strong> Limit how we process your data</li>
                    <li><strong>Objection:</strong> Object to certain processing activities</li>
                </ul>
                <p class="text-gray-700 leading-relaxed mt-4">
                    To exercise these rights, please contact us at infoteknalogi@gmail.com.
                </p>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Cookies and Tracking Technologies</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    We use cookies and similar technologies to enhance your experience:
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li><strong>Essential Cookies:</strong> Required for basic platform functionality</li>
                    <li><strong>Analytics Cookies:</strong> Help us understand how you use our service</li>
                    <li><strong>Preference Cookies:</strong> Remember your settings and preferences</li>
                </ul>
                <p class="text-gray-700 leading-relaxed mt-4">
                    You can control cookie settings through your browser, but disabling certain cookies may affect platform functionality.
                </p>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">9. International Data Transfers</h2>
                <p class="text-gray-700 leading-relaxed">
                    Your information may be transferred to and processed in countries other than your country of residence. We ensure appropriate safeguards are in place to protect your data during such transfers.
                </p>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Children's Privacy</h2>
                <p class="text-gray-700 leading-relaxed">
                    TraKerja is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If we become aware that we have collected such information, we will take steps to delete it.
                </p>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Changes to This Privacy Policy</h2>
                <p class="text-gray-700 leading-relaxed">
                    We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date. We encourage you to review this Privacy Policy periodically.
                </p>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Contact Us</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    If you have any questions about this Privacy Policy or our data practices, please contact us:
                </p>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700"><strong>Email:</strong> infoteknalogi@gmail.com</p>
                    <p class="text-gray-700"><strong>Company:</strong> PT Teknalogi Transformasi Digital</p>
                    <p class="text-gray-700"><strong>Instagram:</strong> @teknalogi.id</p>
                    <p class="text-gray-700"><strong>Response Time:</strong> We will respond to your inquiries within 48 hours</p>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">13. Compliance with Indonesian Law</h2>
                <p class="text-gray-700 leading-relaxed">
                    This Privacy Policy is designed to comply with Indonesian data protection laws and regulations. We are committed to protecting the privacy rights of our users in accordance with applicable Indonesian legislation.
                </p>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-8">
            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gradient-to-r from-[#d983e4] to-[#4e71c5] hover:shadow-lg transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Registration
            </a>
        </div>
    </div>
</div>
@endsection
