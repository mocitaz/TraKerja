<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AiAnalyzerController extends Controller
{
    private const VERCEL_API_URL = 'https://ai-analyzer-seven.vercel.app/analyze';

    /**
     * Display the AI Analyzer page
     */
    public function index(): View
    {
        // Redirect admin users
        if (Auth::user() && (Auth::user()->isAdmin() || Auth::user()->role === 'admin')) {
            return redirect()->route('admin.index');
        }

        $user = Auth::user();
        $canAccess = $user->canAccessAiAnalyzerWithLimit();
        $hasUsedTrial = $user->hasUsedAiAnalyzerTrial();
        $isPremium = $user->isPremium();
        $remainingUses = $user->getRemainingAiAnalyzer();

        return view('ai-analyzer.index', [
            'canAccess' => $canAccess,
            'hasUsedTrial' => $hasUsedTrial,
            'isPremium' => $isPremium,
            'remainingUses' => $remainingUses,
        ]);
    }

    /**
     * Analyze resume with AI
     */
    public function analyze(Request $request)
    {
        // Redirect admin users
        if (Auth::user() && (Auth::user()->isAdmin() || Auth::user()->role === 'admin')) {
            abort(403, 'Admin cannot access AI Analyzer');
        }

        $user = Auth::user();

        // Check if user can access AI Analyzer with monthly limit
        if (!$user->canAccessAiAnalyzerWithLimit()) {
            $isPremium = $user->isPremium();
            $errorMsg = $isPremium 
                ? 'Anda sudah mencapai batas 5x analisa bulan ini. Batas akan reset bulan depan.'
                : 'Anda sudah menggunakan free trial AI Analyzer (1x). Upgrade ke Premium untuk 5x analisa per bulan!';
            
            return back()->withErrors([
                'analyze_error' => $errorMsg
            ])->withInput();
        }

        // Increase execution time limit for API call (AI analysis can take time)
        // Try both methods to ensure it works in different PHP configurations
        @ini_set('max_execution_time', 180);
        @set_time_limit(180); // 3 minutes
        
        // Also increase memory limit if needed for large file processing
        @ini_set('memory_limit', '256M');

        // Validate request
        $validated = $request->validate([
            'resume' => 'required|file|mimes:pdf|max:10240', // Max 10MB
            'job_description' => 'required|string|min:50|max:10000', // Increased limit
        ], [
            'resume.required' => 'File resume wajib diunggah.',
            'resume.mimes' => 'File resume harus berupa PDF.',
            'resume.max' => 'Ukuran file resume maksimal 10MB.',
            'job_description.required' => 'Job description wajib diisi.',
            'job_description.min' => 'Job description minimal 50 karakter.',
            'job_description.max' => 'Job description maksimal 10000 karakter.',
        ]);

        try {
            // Prepare multipart form data for Vercel API
            $file = $request->file('resume');
            $jobDescription = $request->input('job_description');

            if (!$file || !$file->isValid()) {
                return back()->withErrors([
                    'analyze_error' => 'File resume tidak valid atau gagal diunggah.'
                ])->withInput();
            }

            // Send request to Vercel API with multipart/form-data
            \Log::info('Sending request to AI Analyzer API', [
                'file_size' => $file->getSize(),
                'file_name' => $file->getClientOriginalName(),
                'job_desc_length' => strlen($jobDescription)
            ]);

            $response = Http::timeout(150) // 2.5 minutes timeout
                ->asMultipart()
                ->attach('resume', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                ->post(self::VERCEL_API_URL, [
                    'job_description' => $jobDescription,
                ]);

            \Log::info('AI Analyzer API response', [
                'status' => $response->status(),
                'successful' => $response->successful()
            ]);

            if (!$response->successful()) {
                $responseBody = $response->json();
                $errorMessage = $responseBody['error'] ?? 'Terjadi kesalahan saat menganalisis resume.';
                
                \Log::error('AI Analyzer API error', [
                    'status' => $response->status(),
                    'error' => $errorMessage,
                    'body' => $response->body()
                ]);
                
                // Check if it's a rate limit error (429 or error message contains rate limit keywords)
                $isRateLimitError = $response->status() == 429 || 
                                    $response->status() == 500 && (
                                        stripos($errorMessage, 'rate limit') !== false || 
                                        stripos($errorMessage, 'rate_limit_exceeded') !== false ||
                                        stripos($errorMessage, 'tokens per min') !== false
                                    );
                
                // Check if it's a quota exceeded error
                $errorStr = is_string($errorMessage) ? $errorMessage : json_encode($errorMessage);
                $isQuotaError = $response->status() == 429 || 
                                $response->status() == 500 && (
                                    stripos($errorStr, 'exceeded your current quota') !== false ||
                                    stripos($errorStr, 'insufficient_quota') !== false ||
                                    stripos($errorStr, 'quota') !== false
                                );
                
                if ($isQuotaError) {
                    return back()->withErrors([
                        'analyze_error' => "Maaf, layanan AI Analyzer sedang mengalami gangguan sementara. Tim kami sedang memperbaikinya. Silakan coba lagi nanti atau hubungi support jika masalah berlanjut."
                    ])->withInput();
                }
                
                if ($isRateLimitError) {
                    // Try to extract wait time from error message
                    // Pattern: "Please try again in 22h38m38.4s" or similar
                    $waitTime = 'beberapa saat';
                    
                    if (preg_match('/try again in (\d+h)?(\d+m)?(\d+(?:\.\d+)?s)?/i', $errorStr, $matches)) {
                        $hours = isset($matches[1]) ? (int)$matches[1] : 0;
                        $minutes = isset($matches[2]) ? (int)$matches[2] : 0;
                        
                        if ($hours > 0) {
                            $waitTime = "sekitar {$hours} jam";
                            if ($minutes > 0) {
                                $waitTime .= " {$minutes} menit";
                            }
                        } elseif ($minutes > 0) {
                            $waitTime = "sekitar {$minutes} menit";
                        } else {
                            $waitTime = "beberapa saat";
                        }
                    }
                    
                    return back()->withErrors([
                        'analyze_error' => "Server sedang sibuk karena banyak permintaan. Mohon dicoba lagi {$waitTime} lagi."
                    ])->withInput();
                }
                
                return back()->withErrors([
                    'analyze_error' => $errorMessage . ' (Status: ' . $response->status() . ')'
                ])->withInput();
            }

            $analysisResult = $response->json();

            // Increment AI Analyzer usage count
            $user->incrementAiAnalyzerCount();
            
            // Also mark trial as used for tracking (backward compatibility)
            if (!$user->isPremium() && !$user->has_used_ai_analyzer_trial) {
                $user->useAiAnalyzerTrial();
            }

            // Return view with results
            return view('ai-analyzer.result', [
                'result' => $analysisResult,
                'job_description' => $jobDescription,
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->withErrors([
                'analyze_error' => 'Gagal terhubung ke server analisis. Silakan coba lagi beberapa saat.'
            ])->withInput();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            \Log::error('AI Analyzer request exception', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'exception' => $e
            ]);

            return back()->withErrors([
                'analyze_error' => 'Gagal mengirim permintaan ke server analisis. Silakan coba lagi.'
            ])->withInput();
        } catch (\Exception $e) {
            \Log::error('AI Analyzer error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            // Check if it's a timeout error
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'Maximum execution time') !== false || strpos($errorMessage, 'timeout') !== false) {
                return back()->withErrors([
                    'analyze_error' => 'Proses analisis memakan waktu terlalu lama. Silakan coba lagi dengan file yang lebih kecil atau hubungi administrator.'
                ])->withInput();
            }

            return back()->withErrors([
                'analyze_error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->withInput();
        }
    }
}
