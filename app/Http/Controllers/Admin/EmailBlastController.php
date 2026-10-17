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
use App\Mail\MaintenanceCompletedMail;
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
        return view('admin.email-blast', ['audienceCounts' => collect(['all', 'verified', 'unverified', 'premium', 'free', 'new'])->mapWithKeys(fn ($key) => [$key => $this->audienceQuery($key)->count()])->all()]);
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
            'email_type' => 'required|in:ai_analyzer,job_reminder,monthly_motivation,welcome,verification,verification_reminder,custom,product_update,hiring_season,re_engagement,chrome_extension,ai_photo,follow_up_feature,idul_adha,waisak,pancasila,sumpah_pemuda,pahlawan,guru_nasional,hari_ibu,natal,kemerdekaan_ri,maulid_nabi,tahun_baru_islam,maintenance_completed,new_vibe,brand_transformation',
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
        $query = $this->audienceQuery($targetUser);

        $users = $query->get(['id', 'email', 'name', 'created_at', 'email_verified_at']);

        if ($users->isEmpty()) {
            return back()->with('error', __('Tidak ada user yang ditemukan untuk target yang dipilih.'));
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
                        Mail::to($user)->send(new AiAnalyzerFreeTrialAnnouncementMail($user));
                        break;
                    case 'job_reminder':
                        Mail::to($user)->send(new JobApplicationReminderMail($user));
                        break;
                    case 'monthly_motivation':
                        Mail::to($user)->send(new MonthlyMotivationMail($user));
                        break;
                    case 'welcome':
                        Mail::to($user)->send(new WelcomeMail($user));
                        break;
                    case 'verification':
                        // Kirim verification email untuk user yang belum terverifikasi
                        if (!$user->hasVerifiedEmail()) {
                            $user->sendEmailVerificationNotification();
                        }
                        break;
                    case 'verification_reminder':
                        Mail::to($user)->send(new \App\Mail\EmailVerificationReminderMail($user));
                        break;
                    case 'product_update':
                        Mail::to($user)->send(new \App\Mail\ProductUpdateMail($user));
                        break;
                    case 'new_vibe':
                        Mail::to($user)->send(new \App\Mail\NewVibeMail($user));
                        break;
                    case 'brand_transformation':
                        Mail::to($user)->send(new \App\Mail\BrandTransformationMail($user));
                        break;
                    case 'hiring_season':
                        Mail::to($user)->send(new HiringSeasonAlertMail($user));
                        break;
                    case 're_engagement':
                        Mail::to($user)->send(new ReEngagementMail($user));
                        break;
                    case 'chrome_extension':
                        Mail::to($user)->send(new ChromeExtensionPromoMail($user));
                        break;
                    case 'ai_photo':
                        Mail::to($user)->send(new \App\Mail\AiPhotoAnnouncementMail($user));
                        break;
                    case 'follow_up_feature':
                        Mail::to($user)->send(new \App\Mail\FollowUpFeatureAnnouncementMail($user));
                        break;
                    case 'idul_adha':
                        Mail::to($user)->send(new \App\Mail\IdulAdhaMail($user));
                        break;
                    case 'waisak':
                        Mail::to($user)->send(new \App\Mail\WaisakMail($user));
                        break;
                    case 'pancasila':
                        Mail::to($user)->send(new \App\Mail\PancasilaMail($user));
                        break;
                    case 'sumpah_pemuda':
                        Mail::to($user)->send(new \App\Mail\SumpahPemudaMail($user));
                        break;
                    case 'pahlawan':
                        Mail::to($user)->send(new \App\Mail\PahlawanMail($user));
                        break;
                    case 'guru_nasional':
                        Mail::to($user)->send(new \App\Mail\GuruNasionalMail($user));
                        break;
                    case 'hari_ibu':
                        Mail::to($user)->send(new \App\Mail\HariIbuMail($user));
                        break;
                    case 'natal':
                        Mail::to($user)->send(new \App\Mail\NatalMail($user));
                        break;
                    case 'kemerdekaan_ri':
                        Mail::to($user)->send(new \App\Mail\KemerdekaanRiMail($user));
                        break;
                    case 'maulid_nabi':
                        Mail::to($user)->send(new \App\Mail\MaulidNabiMail($user));
                        break;
                    case 'tahun_baru_islam':
                        Mail::to($user)->send(new \App\Mail\TahunBaruIslamMail($user));
                        break;
                    case 'maintenance_completed':
                        Mail::to($user)->send(new MaintenanceCompletedMail($user));
                        break;
                    case 'custom':
                        Mail::to($user)->send(new CustomEmailBlastMail(
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
            'email_type' => 'required|in:ai_analyzer,job_reminder,monthly_motivation,welcome,verification,verification_reminder,custom,product_update,hiring_season,re_engagement,chrome_extension,ai_photo,follow_up_feature,idul_adha,waisak,pancasila,sumpah_pemuda,pahlawan,guru_nasional,hari_ibu,natal,kemerdekaan_ri,maulid_nabi,tahun_baru_islam,maintenance_completed,new_vibe,brand_transformation',
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
        $query = $this->audienceQuery($targetUser);
        if ($request->email_type === 'verification') {
            $query->whereNull('email_verified_at');
        }

        $users = $query->get(['id', 'email', 'name']);

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => __('Tidak ada user yang ditemukan untuk target yang dipilih.')
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
            'email_type' => 'required|in:ai_analyzer,job_reminder,monthly_motivation,welcome,verification,verification_reminder,custom,product_update,hiring_season,re_engagement,chrome_extension,ai_photo,follow_up_feature,idul_adha,waisak,pancasila,sumpah_pemuda,pahlawan,guru_nasional,hari_ibu,natal,kemerdekaan_ri,maulid_nabi,tahun_baru_islam,maintenance_completed,new_vibe,brand_transformation',
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
                    Mail::to($user)->send(new AiAnalyzerFreeTrialAnnouncementMail($user));
                    break;
                case 'job_reminder':
                    Mail::to($user)->send(new JobApplicationReminderMail($user));
                    break;
                case 'monthly_motivation':
                    Mail::to($user)->send(new MonthlyMotivationMail($user));
                    break;
                case 'welcome':
                    Mail::to($user)->send(new WelcomeMail($user));
                    break;
                case 'verification':
                    if (!$user->hasVerifiedEmail()) {
                        $user->sendEmailVerificationNotification();
                    }
                    break;
                case 'verification_reminder':
                    Mail::to($user)->send(new \App\Mail\EmailVerificationReminderMail($user));
                    break;
                case 'product_update':
                    Mail::to($user)->send(new \App\Mail\ProductUpdateMail($user));
                    break;
                case 'new_vibe':
                    Mail::to($user)->send(new \App\Mail\NewVibeMail($user));
                    break;
                case 'brand_transformation':
                    Mail::to($user)->send(new \App\Mail\BrandTransformationMail($user));
                    break;
                case 'hiring_season':
                    Mail::to($user)->send(new HiringSeasonAlertMail($user));
                    break;
                case 're_engagement':
                    Mail::to($user)->send(new ReEngagementMail($user));
                    break;
                case 'chrome_extension':
                    Mail::to($user)->send(new ChromeExtensionPromoMail($user));
                    break;
                case 'ai_photo':
                    Mail::to($user)->send(new \App\Mail\AiPhotoAnnouncementMail($user));
                    break;
                case 'follow_up_feature':
                    Mail::to($user)->send(new \App\Mail\FollowUpFeatureAnnouncementMail($user));
                    break;
                case 'idul_adha':
                    Mail::to($user)->send(new \App\Mail\IdulAdhaMail($user));
                    break;
                case 'waisak':
                    Mail::to($user)->send(new \App\Mail\WaisakMail($user));
                    break;
                case 'pancasila':
                    Mail::to($user)->send(new \App\Mail\PancasilaMail($user));
                    break;
                case 'sumpah_pemuda':
                    Mail::to($user)->send(new \App\Mail\SumpahPemudaMail($user));
                    break;
                case 'pahlawan':
                    Mail::to($user)->send(new \App\Mail\PahlawanMail($user));
                    break;
                case 'guru_nasional':
                    Mail::to($user)->send(new \App\Mail\GuruNasionalMail($user));
                    break;
                case 'hari_ibu':
                    Mail::to($user)->send(new \App\Mail\HariIbuMail($user));
                    break;
                case 'natal':
                    Mail::to($user)->send(new \App\Mail\NatalMail($user));
                    break;
                case 'kemerdekaan_ri':
                    Mail::to($user)->send(new \App\Mail\KemerdekaanRiMail($user));
                    break;
                case 'maulid_nabi':
                    Mail::to($user)->send(new \App\Mail\MaulidNabiMail($user));
                    break;
                case 'tahun_baru_islam':
                    Mail::to($user)->send(new \App\Mail\TahunBaruIslamMail($user));
                    break;
                case 'maintenance_completed':
                    Mail::to($user)->send(new MaintenanceCompletedMail($user));
                    break;
                case 'custom':
                    Mail::to($user)->send(new CustomEmailBlastMail(
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
        $audience = request('audience', 'all-segments');
        $search = mb_substr((string) request('search', ''), 0, 100);
        $query = EmailBlastLog::query()->when($search !== '', fn ($q) => $q->where('email_type', 'like', '%'.$search.'%'));
        if (in_array($audience, ['all', 'verified', 'unverified', 'premium', 'free', 'new'], true)) {
            $query->where('target_audience', $audience);
        }
        return view('admin.email-blast-history', [
            'logs' => $query->latest()->orderByDesc('id')->paginate(15)->withQueryString(),
            'search' => $search, 'audience' => $audience,
            'totalCampaigns' => EmailBlastLog::count(),
            'totalTarget' => EmailBlastLog::sum('total_target'),
            'totalSuccess' => EmailBlastLog::sum('success_count'),
            'totalFailed' => EmailBlastLog::sum('failed_count'),
        ]);
    }

    private function audienceQuery(string $target)
    {
        $query = User::where('role', '!=', 'admin');
        $activePremium = User::where('is_premium', true)->where('payment_status', 'paid')
            ->where(fn ($q) => $q->whereNull('premium_until')->orWhere('premium_until', '>', now()))->select('id');
        return match ($target) {
            'verified' => $query->whereNotNull('email_verified_at'),
            'unverified' => $query->whereNull('email_verified_at'),
            'new' => $query->where('created_at', '>=', now()->subDays(7)),
            'premium' => $query->whereIn('id', $activePremium),
            'free' => $query->whereNotIn('id', $activePremium),
            default => $query,
        };
    }

    /**
     * Preview the email template (HTML rendering)
     */
    public function preview(Request $request)
    {
        $validationRules = [
            'email_type' => 'required|in:ai_analyzer,job_reminder,monthly_motivation,welcome,verification,verification_reminder,custom,product_update,hiring_season,re_engagement,chrome_extension,ai_photo,follow_up_feature,idul_adha,waisak,pancasila,sumpah_pemuda,pahlawan,guru_nasional,hari_ibu,natal,kemerdekaan_ri,maulid_nabi,tahun_baru_islam,maintenance_completed,new_vibe,brand_transformation',
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
                    return response((new \Illuminate\Auth\Notifications\VerifyEmail)->toMail($user)->render(), 200)->header('Content-Type', 'text/html');
                case 'verification_reminder':
                    $mailable = new \App\Mail\EmailVerificationReminderMail($user);
                    break;
                case 'product_update':
                    $mailable = new \App\Mail\ProductUpdateMail($user);
                    break;
                case 'new_vibe':
                    $mailable = new \App\Mail\NewVibeMail($user);
                    break;
                case 'brand_transformation':
                    $mailable = new \App\Mail\BrandTransformationMail($user);
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
                case 'idul_adha':
                    $mailable = new \App\Mail\IdulAdhaMail($user);
                    break;
                case 'waisak':
                    $mailable = new \App\Mail\WaisakMail($user);
                    break;
                case 'pancasila':
                    $mailable = new \App\Mail\PancasilaMail($user);
                    break;
                case 'sumpah_pemuda':
                    $mailable = new \App\Mail\SumpahPemudaMail($user);
                    break;
                case 'pahlawan':
                    $mailable = new \App\Mail\PahlawanMail($user);
                    break;
                case 'guru_nasional':
                    $mailable = new \App\Mail\GuruNasionalMail($user);
                    break;
                case 'hari_ibu':
                    $mailable = new \App\Mail\HariIbuMail($user);
                    break;
                case 'natal':
                    $mailable = new \App\Mail\NatalMail($user);
                    break;
                case 'kemerdekaan_ri':
                    $mailable = new \App\Mail\KemerdekaanRiMail($user);
                    break;
                case 'maulid_nabi':
                    $mailable = new \App\Mail\MaulidNabiMail($user);
                    break;
                case 'tahun_baru_islam':
                    $mailable = new \App\Mail\TahunBaruIslamMail($user);
                    break;
                case 'maintenance_completed':
                    $mailable = new MaintenanceCompletedMail($user);
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

