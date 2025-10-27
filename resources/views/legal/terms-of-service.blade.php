@extends('layouts.guest')

@section('title', 'Terms of Service - TraKerja')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-emerald-50">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-[#d983e4] to-[#4e71c5] py-16">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center px-6 py-3 rounded-full bg-white/20 backdrop-blur-sm text-white mb-8">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold">Legal Protection</span>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                Terms of Service
            </h1>
            <p class="text-xl text-white/90 max-w-3xl mx-auto mb-8">
                Transparansi dan kepercayaan adalah fondasi TraKerja. Kami berkomitmen melindungi hak dan privasi Anda.
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span>100% Transparent</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <!-- Philosophy Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12 border border-gray-100">
            <div class="text-center mb-8">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-[#d983e4]/10 to-[#4e71c5]/10 text-[#d983e4] mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span class="font-semibold">Filosofi TraKerja</span>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Kepercayaan adalah Segalanya</h2>
                <p class="text-lg text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Di TraKerja, kami percaya bahwa setiap job seeker berhak mendapatkan platform yang aman, transparan, dan dapat dipercaya. 
                    Kami tidak hanya menyediakan tools untuk tracking lamaran kerja, tetapi juga membangun ekosistem yang mendukung 
                    kesuksesan karir Anda dengan integritas dan keamanan data yang tak tergoyahkan.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-gradient-to-br from-[#d983e4]/5 to-[#4e71c5]/5 rounded-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#d983e4] to-[#4e71c5] rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Keamanan Data</h3>
                    <p class="text-gray-600">Enkripsi end-to-end dan backup otomatis untuk melindungi informasi pribadi Anda</p>
                </div>
                
                <div class="text-center p-6 bg-gradient-to-br from-[#4e71c5]/5 to-[#d983e4]/5 rounded-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#4e71c5] to-[#d983e4] rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Transparansi Total</h3>
                    <p class="text-gray-600">Tidak ada biaya tersembunyi, tidak ada data yang dijual, 100% transparan</p>
                </div>
                
                <div class="text-center p-6 bg-gradient-to-br from-[#d983e4]/5 to-[#4e71c5]/5 rounded-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#d983e4] to-[#4e71c5] rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Kesuksesan Anda</h3>
                    <p class="text-gray-600">Fokus kami adalah membantu Anda mencapai tujuan karir dengan tools yang powerful</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-2xl shadow-xl p-8 prose prose-lg max-w-none">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Penerimaan Ketentuan</h2>
                <p class="text-gray-700 leading-relaxed">
                    Dengan mengakses dan menggunakan TraKerja ("Layanan"), Anda menerima dan menyetujui untuk terikat oleh ketentuan dan 
                    ketentuan perjanjian ini. Jika Anda tidak setuju untuk mematuhi ketentuan di atas, harap jangan menggunakan layanan ini.
                </p>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mt-4">
                    <p class="text-blue-800 font-medium">
                        <strong>Komitmen Kami:</strong> Kami berjanji untuk selalu transparan dan melindungi hak-hak Anda sebagai pengguna.
                    </p>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Description of Service</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    TraKerja is a professional job application management platform designed to help job seekers in Indonesia track, organize, and manage their job applications. Our service includes:
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Job application tracking and organization</li>
                    <li>Analytics and insights for job search performance</li>
                    <li>Goal setting and progress monitoring</li>
                    <li>Interview scheduling and reminders</li>
                    <li>CV builder and career summary tools</li>
                    <li>Data security and privacy protection</li>
                </ul>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">3. User Accounts</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    To use our service, you must create an account. You are responsible for:
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Providing accurate and complete information during registration</li>
                    <li>Maintaining the security of your account credentials</li>
                    <li>All activities that occur under your account</li>
                    <li>Notifying us immediately of any unauthorized use</li>
                </ul>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Acceptable Use</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    You agree to use TraKerja only for lawful purposes and in accordance with these Terms. You may not:
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Use the service for any illegal or unauthorized purpose</li>
                    <li>Attempt to gain unauthorized access to any part of the service</li>
                    <li>Interfere with or disrupt the service or servers</li>
                    <li>Upload malicious code or harmful content</li>
                    <li>Violate any applicable laws or regulations</li>
                    <li>Impersonate any person or entity</li>
                </ul>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Jaminan Keamanan Data & Privasi</h2>
                <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-xl p-6 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-green-800 mb-2">🛡️ Perlindungan Data Terjamin</h3>
                            <p class="text-green-700 leading-relaxed">
                                Privasi Anda adalah prioritas utama kami. Kami menggunakan teknologi enkripsi tingkat militer dan 
                                protokol keamanan terdepan untuk melindungi setiap byte data Anda.
                            </p>
                        </div>
                    </div>
                </div>
                
                <p class="text-gray-700 leading-relaxed mb-4">
                    Kami mengumpulkan dan memproses data Anda sesuai dengan Privacy Policy kami. Dengan menggunakan layanan kami, Anda menyetujui:
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-3">
                    <li><strong>Enkripsi End-to-End:</strong> Semua data job application Anda dienkripsi dengan standar AES-256</li>
                    <li><strong>Backup Otomatis:</strong> Data Anda disimpan di multiple server dengan backup harian</li>
                    <li><strong>Zero Data Selling:</strong> Kami TIDAK PERNAH menjual data Anda ke pihak ketiga</li>
                    <li><strong>Access Control:</strong> Hanya Anda yang memiliki akses penuh ke data Anda</li>
                    <li><strong>Audit Trail:</strong> Setiap akses data dicatat untuk keamanan maksimal</li>
                </ul>
                
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                    <p class="text-yellow-800 font-medium">
                        <strong>Garansi Keamanan:</strong> Jika terjadi kebocoran data karena kelalaian kami, 
                        kami akan memberikan kompensasi penuh sesuai dengan kerugian yang Anda alami.
                    </p>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Intellectual Property</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    The service and its original content, features, and functionality are owned by PT Teknalogi Transformasi Digital and are protected by international copyright, trademark, and other intellectual property laws.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    You retain ownership of your job application data and content. By using our service, you grant us a limited license to process and store your data to provide the service.
                </p>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Service Availability</h2>
                <p class="text-gray-700 leading-relaxed">
                    We strive to provide continuous service availability but cannot guarantee uninterrupted access. We may temporarily suspend the service for maintenance, updates, or technical issues. We will notify users of planned maintenance when possible.
                </p>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Limitation of Liability</h2>
                <p class="text-gray-700 leading-relaxed">
                    TraKerja is provided "as is" without warranties of any kind. We shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of profits, data, or business opportunities.
                </p>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Termination</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    We may terminate or suspend your account immediately, without prior notice, for any reason, including breach of these Terms. Upon termination:
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Your right to use the service will cease immediately</li>
                    <li>We may delete your account and data</li>
                    <li>You may request data export before termination</li>
                </ul>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Changes to Terms</h2>
                <p class="text-gray-700 leading-relaxed">
                    We reserve the right to modify these Terms at any time. We will notify users of significant changes via email or through the service. Continued use of the service after changes constitutes acceptance of the new Terms.
                </p>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Governing Law</h2>
                <p class="text-gray-700 leading-relaxed">
                    These Terms shall be governed by and construed in accordance with the laws of the Republic of Indonesia. Any disputes arising from these Terms shall be subject to the exclusive jurisdiction of the courts of Indonesia.
                </p>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Komitmen & Jaminan Kami</h2>
                <div class="bg-gradient-to-r from-[#d983e4]/10 to-[#4e71c5]/10 border border-[#d983e4]/20 rounded-xl p-6 mb-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#d983e4] to-[#4e71c5] rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">💝 Janji Kami kepada Anda</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Kami berkomitmen untuk selalu melayani Anda dengan integritas tertinggi. 
                            Kesuksesan karir Anda adalah kesuksesan kami. Kami akan terus berinovasi 
                            dan meningkatkan layanan untuk memberikan pengalaman terbaik.
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white border-2 border-green-200 rounded-xl p-6">
                        <h4 class="text-lg font-bold text-green-800 mb-3">✅ Jaminan Layanan</h4>
                        <ul class="text-green-700 space-y-2">
                            <li>• 99.9% Uptime Guarantee</li>
                            <li>• 24/7 Data Backup</li>
                            <li>• Free Lifetime Updates</li>
                            <li>• No Hidden Fees Ever</li>
                        </ul>
                    </div>
                    
                    <div class="bg-white border-2 border-blue-200 rounded-xl p-6">
                        <h4 class="text-lg font-bold text-blue-800 mb-3">🔒 Jaminan Keamanan</h4>
                        <ul class="text-blue-700 space-y-2">
                            <li>• Military-Grade Encryption</li>
                            <li>• Zero Data Selling Policy</li>
                            <li>• GDPR Compliance</li>
                            <li>• Regular Security Audits</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">13. Informasi Kontak</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Jika Anda memiliki pertanyaan tentang Terms of Service ini, atau membutuhkan bantuan, 
                    tim kami siap membantu Anda 24/7:
                </p>
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-6 rounded-xl border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-bold text-gray-900 mb-3">📧 Email Support</h4>
                            <p class="text-gray-700 mb-2"><strong>Email:</strong> infoteknalogi@gmail.com</p>
                            <p class="text-sm text-gray-600">Response time: < 2 jam</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-3">🏢 Perusahaan</h4>
                            <p class="text-gray-700 mb-2"><strong>Company:</strong> PT Teknalogi Transformasi Digital</p>
                            <p class="text-gray-700"><strong>Instagram:</strong> @teknalogi.id</p>
                        </div>
                    </div>
                </div>
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
