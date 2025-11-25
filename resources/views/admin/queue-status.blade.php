<x-admin-layout>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Queue Worker Status -->
            <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Queue Worker Status</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            @if($queueWorkerRunning)
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-sm font-medium text-gray-700">Queue worker sedang berjalan</span>
                            @else
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Queue worker tidak berjalan</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Instructions -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                        <h4 class="text-sm font-semibold text-blue-900 mb-2">Cara Menjalankan Queue Worker:</h4>
                        <div class="space-y-2 text-sm text-blue-800">
                            <p><strong>1. Development (Terminal):</strong></p>
                            <code class="block bg-white p-2 rounded border border-blue-200 text-xs font-mono">
                                php artisan queue:work
                            </code>
                            
                            <p class="mt-3"><strong>2. Production (Supervisor):</strong></p>
                            <p class="text-xs text-blue-700 mb-2">Setup supervisor untuk auto-restart queue worker:</p>
                            <div class="bg-white rounded border border-blue-200 p-3 space-y-2 text-xs">
                                <p class="font-medium text-gray-700">a. Tentukan path absolut project:</p>
                                <code class="block font-mono text-gray-800 bg-gray-50 p-2 rounded">
                                    cd domains/trakerja.web.id<br>
                                    pwd<br>
                                    # Output: /home/username/domains/trakerja.web.id
                                </code>
                                
                                <p class="font-medium text-gray-700 mt-3">b. Install supervisor:</p>
                                <code class="block font-mono text-gray-800 bg-gray-50 p-2 rounded">
                                    sudo apt-get install supervisor
                                </code>
                                
                                <p class="font-medium text-gray-700 mt-3">c. Create config: /etc/supervisor/conf.d/trakerja-worker.conf</p>
                                <code class="block font-mono text-gray-800 bg-gray-50 p-2 rounded overflow-x-auto">
                                    [program:trakerja-worker]<br>
                                    process_name=%(program_name)s_%(process_num)02d<br>
                                    command=php /home/username/domains/trakerja.web.id/artisan queue:work --sleep=3 --tries=3<br>
                                    autostart=true<br>
                                    autorestart=true<br>
                                    user=www-data<br>
                                    numprocs=2<br>
                                    redirect_stderr=true<br>
                                    stdout_logfile=/home/username/domains/trakerja.web.id/storage/logs/worker.log
                                </code>
                                <p class="text-xs text-red-600 mt-2">⚠️ Ganti <code>/home/username/domains/trakerja.web.id</code> dengan path absolut project Anda (hasil dari pwd)</p>
                            </div>
                            
                            <p class="mt-3"><strong>3. Manual Check:</strong></p>
                            <code class="block bg-white p-2 rounded border border-blue-200 text-xs font-mono">
                                # Cek pending jobs di database<br>
                                SELECT COUNT(*) FROM jobs;<br><br>
                                # Cek failed jobs<br>
                                SELECT COUNT(*) FROM failed_jobs;
                            </code>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Queue Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Pending Jobs -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Pending Jobs</h3>
                                <p class="text-xs text-gray-500">Email yang sedang menunggu dikirim</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-3xl font-bold text-gray-900">{{ $pendingJobs }}</p>
                        @if($pendingJobs > 0)
                            <p class="text-xs text-gray-500 mt-2">Pastikan queue worker sedang berjalan untuk memproses email</p>
                        @else
                            <p class="text-xs text-green-600 mt-2">✓ Semua email sudah diproses</p>
                        @endif
                    </div>
                </div>

                <!-- Failed Jobs -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Failed Jobs</h3>
                                <p class="text-xs text-gray-500">Email yang gagal dikirim</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-3xl font-bold text-gray-900">{{ $failedJobs }}</p>
                        @if($failedJobs > 0)
                            <p class="text-xs text-red-600 mt-2">Ada email yang gagal dikirim. Cek detail di bawah.</p>
                        @else
                            <p class="text-xs text-green-600 mt-2">✓ Tidak ada email yang gagal</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Failed Jobs -->
            @if($failedJobs > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Failed Jobs</h3>
                        <p class="text-xs text-gray-500 mt-1">10 email terakhir yang gagal dikirim</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Queue</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Connection</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Failed At</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exception</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentFailedJobs as $failedJob)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $failedJob->queue ?? 'default' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $failedJob->connection ?? 'database' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($failedJob->failed_at)->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <details class="cursor-pointer">
                                                <summary class="text-red-600 hover:text-red-700">
                                                    {{ Str::limit($failedJob->exception, 50) }}
                                                </summary>
                                                <pre class="mt-2 p-2 bg-gray-50 rounded text-xs overflow-auto max-h-40">{{ $failedJob->exception }}</pre>
                                            </details>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada failed jobs
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <p class="text-xs text-gray-600">
                            <strong>Note:</strong> Untuk retry failed jobs, gunakan command: 
                            <code class="bg-white px-2 py-1 rounded border">php artisan queue:retry all</code>
                        </p>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.email-blast') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Kembali ke Email Blast
                </a>
                <button onclick="location.reload()" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-primary-600 to-primary-700 rounded-lg hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Refresh Status
                </button>
            </div>
        </div>
    </div>
</x-admin-layout>

