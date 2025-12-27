<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\AiAnalyzerFreeTrialAnnouncementMail;
use App\Mail\JobApplicationReminderMail;
use App\Mail\MonthlyMotivationMail;
use App\Mail\WelcomeMail;
use App\Mail\CustomEmailBlastMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmailBlastController extends Controller
{
    /**
     * Display the email blast page
     * 
     * Fitur Email Blast mendukung:
     * - Predefined email templates (welcome, verification, ai_analyzer, job_reminder, monthly_motivation)
     * - Custom email dengan konten yang dapat dikustomisasi
     * - Target user spesifik (all, verified, premium, free, new, unverified)
     */
    public function index()
    {
        return view('admin.email-blast');
    }

    /**
     * Send email blast synchronously
     * 
     * Support email types:
     * - welcome: Welcome email untuk user baru
     * - verification: Email verifikasi
     * - ai_analyzer: Pengumuman AI Resume Analyzer free trial
     * - job_reminder: Reminder untuk mencatat lamaran kerja
     * - monthly_motivation: Motivasi bulanan
     * - custom: Custom email dengan subject, content, dan button yang dapat dikustomisasi
     * 
     * Custom email fields:
     * - custom_subject (required): Subject email
     * - custom_content (required): Konten email
     * - custom_button_text (optional): Teks button CTA
     * - custom_button_url (optional): URL untuk button CTA
     */
    public function send(Request $request)
    {
        $validationRules = [
            'email_type' => 'required|in:ai_analyzer,job_reminder,monthly_motivation,welcome,verification,custom',
            'target_user' => 'required|in:all,verified,premium,free,new,unverified',
        ];

        // Add validation for custom email fields
        if ($request->email_type === 'custom') {
            $validationRules['custom_subject'] = 'required|string|max:255';
            $validationRules['custom_content'] = 'required|string|max:5000';
            $validationRules['custom_button_text'] = 'nullable|string|max:100';
            $validationRules['custom_button_url'] = 'nullable|url|max:500';
        }

        $request->validate($validationRules);

        $emailType = $request->email_type;
        $targetUser = $request->target_user;

        // Get users based on target
        $query = User::where('role', '!=', 'admin');

        switch ($targetUser) {
            case 'verified':
                $query->whereNotNull('email_verified_at');
                break;
            case 'unverified':
                $query->whereNull('email_verified_at');
                break;
            case 'new':
                // User baru: registrasi dalam 7 hari terakhir
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
                break;
            case 'premium':
                $query->where(function($q) {
                    $q->where('is_premium', true)
                      ->orWhere('payment_status', 'paid');
                });
                break;
            case 'free':
                $query->where(function($q) {
                    $q->where('is_premium', false)
                      ->orWhere('payment_status', '!=', 'paid');
                });
                break;
            case 'all':
            default:
                // All users (no filter)
                break;
        }

        $users = $query->get(['id', 'email', 'name', 'created_at', 'email_verified_at']);

        if ($users->isEmpty()) {
            return back()->with('error', 'Tidak ada user yang ditemukan untuk target yang dipilih.');
        }

        $totalUsers = $users->count();
        $successCount = 0;
        $failCount = 0;
        $errors = [];

        // Send emails synchronously
        foreach ($users as $user) {
            try {
                switch ($emailType) {
                    case 'ai_analyzer':
                        Mail::to($user->email)->send(new AiAnalyzerFreeTrialAnnouncementMail($user));
                        break;
                    case 'job_reminder':
                        Mail::to($user->email)->send(new JobApplicationReminderMail($user));
                        break;
                    case 'monthly_motivation':
                        Mail::to($user->email)->send(new MonthlyMotivationMail($user));
                        break;
                    case 'welcome':
                        Mail::to($user->email)->send(new WelcomeMail($user));
                        break;
                    case 'verification':
                        // Kirim verification email untuk user yang belum terverifikasi
                        if (!$user->hasVerifiedEmail()) {
                            $user->sendEmailVerificationNotification();
                        }
                        break;
                    case 'custom':
                        Mail::to($user->email)->send(new CustomEmailBlastMail(
                            $user,
                            $request->custom_subject,
                            $request->custom_content,
                            $request->custom_button_text,
                            $request->custom_button_url
                        ));
                        break;
                }
                $successCount++;
            } catch (\Exception $e) {
                $failCount++;
                $errors[] = "Failed to send to {$user->email}: " . $e->getMessage();
                Log::error("Email blast failed for {$user->email}: " . $e->getMessage());
            }
        }

        $failedCount = $totalUsers - $successCount;
        $message = "Email berhasil dikirim ke {$successCount} user";
        if ($failCount > 0) {
            $message .= " ({$failCount} gagal)";
        }

        return back()->with('success', $message)
                    ->with('details', [
                        'total' => $totalUsers,
                        'success' => $successCount,
                        'failed' => $failCount,
                    ]);
    }
}

