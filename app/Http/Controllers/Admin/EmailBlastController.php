<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailBlastLog;
use App\Mail\AiAnalyzerFreeTrialAnnouncementMail;
use App\Mail\JobApplicationReminderMail;
use App\Mail\MonthlyMotivationMail;
use App\Mail\WelcomeMail;
use App\Mail\CustomEmailBlastMail;
use App\Mail\HiringSeasonAlertMail;
use App\Mail\ReEngagementMail;
use App\Mail\ChromeExtensionPromoMail;
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
            'email_type' => 'required|in:ai_analyzer,job_reminder,monthly_motivation,welcome,verification,verification_reminder,custom,product_update,hiring_season,re_engagement,chrome_extension,ai_photo,follow_up_feature',
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

        // Prevent timeout during bulk email sending
        set_time_limit(0);

        // Send emails synchronously with a small delay to prevent SMTP rate-limiting
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
                    case 'verification_reminder':
                        Mail::to($user->email)->send(new \App\Mail\EmailVerificationReminderMail($user));
                        break;
                    case 'product_update':
                        Mail::to($user->email)->send(new \App\Mail\ProductUpdateMail($user));
                        break;
                    case 'hiring_season':
                        Mail::to($user->email)->send(new HiringSeasonAlertMail($user));
                        break;
                    case 're_engagement':
                        Mail::to($user->email)->send(new ReEngagementMail($user));
                        break;
                    case 'chrome_extension':
                        Mail::to($user->email)->send(new ChromeExtensionPromoMail($user));
                        break;
                    case 'ai_photo':
                        Mail::to($user->email)->send(new \App\Mail\AiPhotoAnnouncementMail($user));
                        break;
                    case 'follow_up_feature':
                        Mail::to($user->email)->send(new \App\Mail\FollowUpFeatureAnnouncementMail($user));
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
                
                // Add 1 second delay between emails to prevent Hostinger SMTP rate-limiting
                sleep(1);
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

    /**
     * Initialize email blast process (returns list of target users)
     */
    public function initProgress(Request $request)
    {
        $validationRules = [
            'email_type' => 'required|in:ai_analyzer,job_reminder,monthly_motivation,welcome,verification,verification_reminder,custom,product_update,hiring_season,re_engagement,chrome_extension,ai_photo,follow_up_feature',
            'target_user' => 'required|in:all,verified,premium,free,new,unverified',
        ];

        if ($request->email_type === 'custom') {
            $validationRules['custom_subject'] = 'required|string|max:255';
            $validationRules['custom_content'] = 'required|string|max:5000';
            $validationRules['custom_button_text'] = 'nullable|string|max:100';
            $validationRules['custom_button_url'] = 'nullable|url|max:500';
        }

        $request->validate($validationRules);

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
                break;
        }

        $users = $query->get(['id', 'email', 'name']);

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada user yang ditemukan untuk target yang dipilih.'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'users' => $users,
            'total' => $users->count()
        ]);
    }

    /**
     * Send email to a single user (AJAX)
     */
    public function sendSingleProgress(Request $request)
    {
        $validationRules = [
            'user_id' => 'required|exists:users,id',
            'email_type' => 'required|in:ai_analyzer,job_reminder,monthly_motivation,welcome,verification,verification_reminder,custom,product_update,hiring_season,re_engagement,chrome_extension,ai_photo,follow_up_feature',
        ];

        if ($request->email_type === 'custom') {
            $validationRules['custom_subject'] = 'required|string|max:255';
            $validationRules['custom_content'] = 'required|string|max:5000';
            $validationRules['custom_button_text'] = 'nullable|string|max:100';
            $validationRules['custom_button_url'] = 'nullable|url|max:500';
        }

        $request->validate($validationRules);

        $user = User::find($request->user_id);
        $emailType = $request->email_type;

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
                    if (!$user->hasVerifiedEmail()) {
                        $user->sendEmailVerificationNotification();
                    }
                    break;
                case 'verification_reminder':
                    Mail::to($user->email)->send(new \App\Mail\EmailVerificationReminderMail($user));
                    break;
                case 'product_update':
                    Mail::to($user->email)->send(new \App\Mail\ProductUpdateMail($user));
                    break;
                case 'hiring_season':
                    Mail::to($user->email)->send(new HiringSeasonAlertMail($user));
                    break;
                case 're_engagement':
                    Mail::to($user->email)->send(new ReEngagementMail($user));
                    break;
                case 'chrome_extension':
                    Mail::to($user->email)->send(new ChromeExtensionPromoMail($user));
                    break;
                case 'ai_photo':
                    Mail::to($user->email)->send(new \App\Mail\AiPhotoAnnouncementMail($user));
                    break;
                case 'follow_up_feature':
                    Mail::to($user->email)->send(new \App\Mail\FollowUpFeatureAnnouncementMail($user));
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

            return response()->json([
                'success' => true,
                'email' => $user->email,
                'name' => $user->name
            ]);
        } catch (\Exception $e) {
            Log::error("Email blast AJAX failed for {$user->email}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'email' => $user->email,
                'name' => $user->name,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store email blast activity log (AJAX)
     */
    public function storeLog(Request $request)
    {
        $request->validate([
            'email_type' => 'required|string',
            'target_audience' => 'required|string',
            'total_target' => 'required|integer',
            'success_count' => 'required|integer',
            'failed_count' => 'required|integer',
            'failed_details' => 'nullable|array',
        ]);

        $log = EmailBlastLog::create([
            'email_type' => $request->email_type,
            'target_audience' => $request->target_audience,
            'total_target' => $request->total_target,
            'success_count' => $request->success_count,
            'failed_count' => $request->failed_count,
            'failed_details' => $request->failed_details,
        ]);

        return response()->json([
            'success' => true,
            'log_id' => $log->id
        ]);
    }

    /**
     * Display the email blast history logs
     */
    public function history()
    {
        $logs = EmailBlastLog::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.email-blast-history', compact('logs'));
    }

    /**
     * Preview the email template (HTML rendering)
     */
    public function preview(Request $request)
    {
        $validationRules = [
            'email_type' => 'required|in:ai_analyzer,job_reminder,monthly_motivation,welcome,verification,verification_reminder,custom,product_update,hiring_season,re_engagement,chrome_extension,ai_photo,follow_up_feature',
        ];

        if ($request->email_type === 'custom') {
            $validationRules['custom_subject'] = 'required|string|max:255';
            $validationRules['custom_content'] = 'required|string|max:5000';
            $validationRules['custom_button_text'] = 'nullable|string|max:100';
            $validationRules['custom_button_url'] = 'nullable|url|max:500';
        }

        $request->validate($validationRules);

        $user = \Illuminate\Support\Facades\Auth::user(); // Use current admin as dummy recipient
        $emailType = $request->email_type;
        $mailable = null;

        try {
            switch ($emailType) {
                case 'ai_analyzer':
                    $mailable = new AiAnalyzerFreeTrialAnnouncementMail($user);
                    break;
                case 'job_reminder':
                    $mailable = new JobApplicationReminderMail($user);
                    break;
                case 'monthly_motivation':
                    $mailable = new MonthlyMotivationMail($user);
                    break;
                case 'welcome':
                    $mailable = new WelcomeMail($user);
                    break;
                case 'verification':
                    // Laravel default notification verification, can't easily preview as Mailable
                    return response('<div style="font-family: sans-serif; padding: 20px;"><h3>Default Laravel Verification Email</h3><p>Email ini adalah email bawaan sistem (Notification). Formatnya bergantung pada template Laravel mail. </p></div>', 200)->header('Content-Type', 'text/html');
                case 'verification_reminder':
                    $mailable = new \App\Mail\EmailVerificationReminderMail($user);
                    break;
                case 'product_update':
                    $mailable = new \App\Mail\ProductUpdateMail($user);
                    break;
                case 'hiring_season':
                    $mailable = new HiringSeasonAlertMail($user);
                    break;
                case 're_engagement':
                    $mailable = new ReEngagementMail($user);
                    break;
                case 'chrome_extension':
                    $mailable = new ChromeExtensionPromoMail($user);
                    break;
                case 'ai_photo':
                    $mailable = new \App\Mail\AiPhotoAnnouncementMail($user);
                    break;
                case 'follow_up_feature':
                    $mailable = new \App\Mail\FollowUpFeatureAnnouncementMail($user);
                    break;
                case 'custom':
                    $mailable = new CustomEmailBlastMail(
                        $user,
                        $request->custom_subject,
                        $request->custom_content,
                        $request->custom_button_text,
                        $request->custom_button_url
                    );
                    break;
            }

            if ($mailable) {
                return response($mailable->render(), 200)->header('Content-Type', 'text/html');
            }

            return response('Preview not available', 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to render preview: ' . $e->getMessage()
            ], 500);
        }
    }
}

