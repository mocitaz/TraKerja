<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Setting;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $logo
 * @property string $role
 * @property bool $is_premium
 * @property \Illuminate\Support\Carbon|null $premium_purchased_at
 * @property string|null $payment_status
 * @property int $registered_phase
 * @property array|null $grandfathered_benefits
 * @property int $cv_exports_this_month
 * @property \Illuminate\Support\Carbon|null $last_export_reset
 * 
 * @method \Illuminate\Database\Eloquent\Relations\HasMany jobApplications()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany goals()
 * @method \Illuminate\Database\Eloquent\Relations\HasOne profile()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany experiences()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany educations()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany organizations()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany skills()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany achievements()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany projects()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany payments()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany cvTemplates()
 * @method bool isAdmin()
 * @method bool isPremium()
 * @method bool hasActivePayment()
 * @method bool hasGrandfatheredBenefit(string $benefit)
 * @method int getCvTemplatesCount()
 * @method int getPremiumPrice()
 * @method bool canAccessFeature(string $feature)
 * @method mixed getFeatureLimit(string $feature)
 * @method void checkExportReset()
 * @method bool incrementExportCount()
 * @method mixed getRemainingExports()
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Valid payment status values
     */
    const PAYMENT_STATUS_FREE = 'free';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_EXPIRED = 'expired';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'linkedin_id',
        'password',
        'email_verified_at',
        'logo',
        'role',
        'is_admin',
        'is_premium',
        'premium_purchased_at',
        'payment_status',
        'registered_phase',
        'grandfathered_benefits',
        'cv_exports_this_month',
        'last_export_reset',
        'cv_generated_this_month',
        'last_cv_generation_reset',
        'ai_analyzer_count_this_month',
        'last_ai_analyzer_reset',
        'last_verification_reminder_sent_at',
        'verification_reminder_count',
        'has_used_ai_analyzer_trial',
        'ai_analyzer_trial_used_at',
        'portfolio_slug',
        'is_portfolio_published',
        'portfolio_theme',
        'portfolio_custom_domain',
        'ai_credits',
        'cl_credits',
        'photo_credits',
        'xp',
        'level',
        'auto_archive_rejected',
        'email_notifications_enabled',
        'notify_goal_reminders',
        'notify_interview_reminders',
        'notify_goal_achieved',
    ];

    /**
     * Level thresholds for gamification
     */
    public const LEVEL_THRESHOLDS = [
        1 => 0,
        2 => 100,
        3 => 300,
        4 => 600,
        5 => 1000,
    ];

    /**
     * Level Titles
     */
    public const LEVEL_TITLES = [
        1 => 'Rookie Applicant',
        2 => 'Proactive Seeker',
        3 => 'Interview Challenger',
        4 => 'Top Candidate',
        5 => 'Offer Magnet',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_premium' => 'boolean',
            'auto_archive_rejected' => 'boolean',
            'premium_purchased_at' => 'datetime',
            'registered_phase' => 'integer',
            'grandfathered_benefits' => 'array',
            'cv_exports_this_month' => 'integer',
            'last_export_reset' => 'date',
            'cv_generated_this_month' => 'integer',
            'last_cv_generation_reset' => 'date',
            'ai_analyzer_count_this_month' => 'integer',
            'last_ai_analyzer_reset' => 'date',
            'last_verification_reminder_sent_at' => 'datetime',
            'verification_reminder_count' => 'integer',
            'has_used_ai_analyzer_trial' => 'boolean',
            'ai_analyzer_trial_used_at' => 'datetime',
            'photo_credits' => 'integer',
            'email_notifications_enabled' => 'boolean',
            'notify_goal_reminders' => 'boolean',
            'notify_interview_reminders' => 'boolean',
            'notify_goal_achieved' => 'boolean',
        ];
    }

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Validate payment_status before saving
        static::saving(function ($user) {
            $validStatuses = [self::PAYMENT_STATUS_FREE, self::PAYMENT_STATUS_PAID, self::PAYMENT_STATUS_EXPIRED];
            
            if ($user->payment_status && !in_array($user->payment_status, $validStatuses)) {
                // Auto-fix invalid payment_status to 'free'
                $user->payment_status = self::PAYMENT_STATUS_FREE;
            }
            
            // If payment_status is null, set to 'free'
            if (empty($user->payment_status)) {
                $user->payment_status = self::PAYMENT_STATUS_FREE;
            }
        });
    }

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->logo) {
            if (str_starts_with($this->logo, 'http')) {
                return $this->logo;
            }
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->logo)) {
                return \Illuminate\Support\Facades\Storage::url($this->logo);
            }
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }

    /**
     * Get the job applications for the user.
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Get the user activity logs.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(UserActivity::class);
    }

    /**
     * Get the user goals.
     */
    public function goals(): HasMany
    {
        return $this->hasMany(UserGoal::class);
    }

    /**
     * Get the user profile.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get the user experiences.
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(UserExperience::class);
    }

    /**
     * Get the user educations.
     */
    public function educations(): HasMany
    {
        return $this->hasMany(UserEducation::class);
    }

    /**
     * Get the user organizations.
     */
    public function organizations(): HasMany
    {
        return $this->hasMany(UserOrganization::class);
    }

    /**
     * Get the user skills.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    /**
     * Get the user achievements.
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    /**
     * Get the user projects.
     */
    public function projects()
    {
        return $this->hasMany(UserProject::class);
    }

    public function coverLetters()
    {
        return $this->hasMany(CoverLetter::class);
    }

    public function aiPhotos(): HasMany
    {
        return $this->hasMany(AiPhoto::class);
    }

    /**
     * Get the user payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the user CV templates.
     */
    public function cvTemplates(): HasMany
    {
        return $this->hasMany(CvTemplate::class);
    }

    /**
     * Get the AI analyzer results for the user.
     */
    public function aiAnalyzerResults(): HasMany
    {
        return $this->hasMany(AiAnalyzerResult::class);
    }

    /**
     * Get the email address that should be used for verification.
     */
    public function getEmailForVerification(): string
    {
        return $this->email;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is premium.
     */
    public function isPremium(): bool
    {
        return $this->is_premium && $this->payment_status === self::PAYMENT_STATUS_PAID;
    }

    /**
     * Check if user has active payment.
     */
    public function hasActivePayment(): bool
    {
        return $this->payments()->where('status', 'success')->exists();
    }

    /**
     * Check if user has grandfathered benefit
     * 
     * @param string $benefit Benefit identifier (e.g., 'cv_templates_3_free')
     * @return bool
     */
    public function hasGrandfatheredBenefit(string $benefit): bool
    {
        if (!$this->grandfathered_benefits) {
            return false;
        }
        
        return in_array($benefit, $this->grandfathered_benefits);
    }
    
    /**
     * Get CV templates count for this user
     * 
     * FREE MODE: Semua user dapat akses semua template (5 templates)
     * PREMIUM MODE: Free tier = 1 template, Premium = 5 templates
     * 
     * @return int Number of CV templates available
     */
    public function getCvTemplatesCount(): int
    {
        // FREE MODE: Unlock semua template untuk semua user
        if (!Setting::isMonetizationEnabled()) {
            return 4;
        }
        
        // PREMIUM MODE: Premium users get all 4 templates
        if ($this->isPremium()) {
            return 4;
        }
        
        // PREMIUM MODE: Free tier users only get 2 template
        return 2;
    }
    
    /**
     * Get premium price for this user (with grandfather discount if applicable)
     * 
     * @return int Price in IDR
     */
    public function getPremiumPrice(): int
    {
        $basePrice = Setting::get('premium_price', 199000);
        
        // Phase 1 users get 50% discount forever
        if ($this->registered_phase == 1 || $this->hasGrandfatheredBenefit('premium_discount_50')) {
            return (int) ($basePrice * 0.5);
        }
        
        // Check for active promotional discount
        if (Setting::get('premium_discount_active', false)) {
            $discount = Setting::get('premium_discount_percent', 0);
            return (int) ($basePrice * (1 - $discount / 100));
        }
        
        return (int) $basePrice;
    }
    
    /**
     * Check if user can access feature
     * 
     * FREE MODE: Semua user dapat akses semua fitur
     * PREMIUM MODE: Free tier dibatasi, Premium unlimited
     * 
     * @param string $feature Feature name
     * @return bool
     */
    public function canAccessFeature(string $feature): bool
    {
        // FREE MODE: Unlock semua untuk semua user
        if (!Setting::isMonetizationEnabled()) {
            return true;
        }
        
        // PREMIUM MODE: Premium users get full access
        if ($this->is_premium && $this->payment_status === self::PAYMENT_STATUS_PAID) {
            return true;
        }
        
        // PREMIUM MODE: Free tier limited access
        return Setting::canAccess($feature, $this);
    }
    
    /**
     * Get feature limit for this user
     * 
     * @param string $feature Feature name (e.g., 'cv_exports', 'cv_templates')
     * @return mixed "unlimited" or integer limit
     */
    public function getFeatureLimit(string $feature)
    {
        return Setting::getLimit($feature, $this);
    }
    
    /**
     * Check and reset monthly export counter if needed
     * 
     * @return void
     */
    public function checkExportReset(): void
    {
        $now = now();
        $lastReset = $this->last_export_reset;
        
        // Reset if it's a new month or never reset before
        if (!$lastReset || $lastReset->month !== $now->month || $lastReset->year !== $now->year) {
            $this->update([
                'cv_exports_this_month' => 0,
                'last_export_reset' => $now->toDateString()
            ]);
        }
    }
    
    /**
     * Increment CV export counter for free tier limits
     * 
     * FREE MODE: Tidak perlu tracking (unlimited)
     * PREMIUM MODE: Track untuk free tier, unlimited untuk premium
     * 
     * @return bool True if increment successful, false if limit reached
     */
    public function incrementExportCount(): bool
    {
        // FREE MODE: Unlimited exports untuk semua
        if (!Setting::isMonetizationEnabled()) {
            return true;
        }
        
        // PREMIUM MODE: Premium users have unlimited exports
        if ($this->is_premium && $this->payment_status === self::PAYMENT_STATUS_PAID) {
            return true;
        }
        
        // PREMIUM MODE: Check free tier limit
        // Check and reset counter if needed
        $this->checkExportReset();
        
        // Get export limit for free tier
        $limit = $this->getFeatureLimit('cv_exports');
        
        // Check if limit reached
        if ($this->cv_exports_this_month >= $limit) {
            return false;
        }
        
        // Increment counter
        $this->increment('cv_exports_this_month');
        return true;
    }
    
    /**
     * Get remaining CV exports this month
     * 
     * FREE MODE: Unlimited untuk semua user
     * PREMIUM MODE: Limited untuk free tier, unlimited untuk premium
     * 
     * @return mixed "unlimited" or integer remaining
     */
    public function getRemainingExports()
    {
        // FREE MODE: Unlimited untuk semua
        if (!Setting::isMonetizationEnabled()) {
            return 'unlimited';
        }
        
        // PREMIUM MODE: Premium users have unlimited
        if ($this->is_premium && $this->payment_status === self::PAYMENT_STATUS_PAID) {
            return 'unlimited';
        }
        
        // PREMIUM MODE: Free tier users have limits
        // Check and reset if needed
        $this->checkExportReset();
        
        $limit = $this->getFeatureLimit('cv_exports');
        
        return max(0, $limit - $this->cv_exports_this_month);
    }
    
    /**
     * Generate and send OTP for email verification
     */
    public function sendOtpVerification(): void
    {
        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $this->otp_code = $otp;
        $this->otp_expires_at = now()->addMinutes(10);
        $this->save();
        \Mail::to($this->email)->send(new \App\Mail\OtpVerificationMail($this, $otp));
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(string $otp): bool
    {
        if ($this->otp_code === $otp && $this->otp_expires_at && now()->lessThanOrEqualTo($this->otp_expires_at)) {
            $this->otp_code = null;
            $this->otp_expires_at = null;
            $this->email_verified_at = now();
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Generate and send OTP for password reset
     */
    public function sendOtpPasswordReset(): void
    {
        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $this->otp_code = $otp;
        $this->otp_expires_at = now()->addMinutes(10);
        $this->save();
        \Mail::to($this->email)->send(new \App\Mail\OtpVerificationMail($this, $otp));
    }

    /**
     * Check if user can access email notifications (premium restriction)
     */
    public function canAccessEmailNotifications(): bool
    {
        return $this->canAccessFeature('email_notifications');
    }

    /**
     * Check if user can access AI Analyzer
     */
    public function canAccessAiAnalyzer(): bool
    {
        return $this->canAccessAiAnalyzerWithLimit();
    }

    public function useAiAnalyzerTrial(): void
    {
        $this->update([
            'has_used_ai_analyzer_trial' => true,
            'ai_analyzer_trial_used_at' => now(),
        ]);
    }

    /**
     * Check if user has used AI Analyzer trial - Deprecated
     */
    public function hasUsedAiAnalyzerTrial(): bool
    {
        return $this->ai_credits <= 0;
    }
    
    /**
     * Get total job applications count for this user
     */
    public function getJobApplicationsCount(): int
    {
        return $this->jobApplications()->count();
    }
    
    /**
     * Check if user can create new job application
     * 
     * FREE MODE: Unlimited
     * PREMIUM MODE: Free tier = 50, Premium = unlimited
     */
    public function canCreateJobApplication(): bool
    {
        // FREE MODE: Unlimited
        if (!Setting::isMonetizationEnabled()) {
            return true;
        }
        
        // PREMIUM MODE: Premium users unlimited
        if ($this->isPremium()) {
            return true;
        }
        
        // PREMIUM MODE: Check free tier limit
        $limit = $this->getFeatureLimit('job_applications');
        $currentCount = $this->getJobApplicationsCount();
        
        return $currentCount < $limit;
    }
    
    /**
     * Get remaining job applications slots
     */
    public function getRemainingJobApplications()
    {
        // FREE MODE: Unlimited
        if (!Setting::isMonetizationEnabled()) {
            return 'unlimited';
        }
        
        // PREMIUM MODE: Premium users unlimited
        if ($this->isPremium()) {
            return 'unlimited';
        }
        
        // PREMIUM MODE: Calculate remaining
        $limit = $this->getFeatureLimit('job_applications');
        $currentCount = $this->getJobApplicationsCount();
        
        return max(0, $limit - $currentCount);
    }
    
    /**
     * Check and reset monthly CV generation counter if needed
     */
    public function checkCvGenerationReset(): void
    {
        $now = now();
        $lastReset = $this->last_cv_generation_reset;
        
        // Reset if it's a new month or never reset before
        if (!$lastReset || $lastReset->month !== $now->month || $lastReset->year !== $now->year) {
            $this->update([
                'cv_generated_this_month' => 0,
                'last_cv_generation_reset' => $now->toDateString()
            ]);
        }
    }
    
    /**
     * Increment CV generation counter for free tier limits
     * 
     * FREE MODE: Unlimited
     * PREMIUM MODE: Track untuk free tier (max 3), unlimited untuk premium
     */
    public function incrementCvGenerationCount(): bool
    {
        // FREE MODE: Unlimited
        if (!Setting::isMonetizationEnabled()) {
            return true;
        }
        
        // PREMIUM MODE: Premium users unlimited
        if ($this->isPremium()) {
            return true;
        }
        
        // PREMIUM MODE: Check free tier limit
        $this->checkCvGenerationReset();
        
        $limit = $this->getFeatureLimit('cv_generated');
        
        if ($this->cv_generated_this_month >= $limit) {
            return false;
        }
        
        $this->increment('cv_generated_this_month');
        return true;
    }
    
    /**
     * Get remaining CV generations this month
     */
    public function getRemainingCvGenerations()
    {
        // FREE MODE: Unlimited
        if (!Setting::isMonetizationEnabled()) {
            return 'unlimited';
        }
        
        // PREMIUM MODE: Premium users unlimited
        if ($this->isPremium()) {
            return 'unlimited';
        }
        
        // PREMIUM MODE: Calculate remaining
        $this->checkCvGenerationReset();
        
        $limit = $this->getFeatureLimit('cv_generated');
        
        return max(0, $limit - $this->cv_generated_this_month);
    }
    
    /**
     * Increment AI Analyzer counter (Deduct credit)
     * 
     * FREE MODE: Unlimited
     * PREMIUM MODE: Deduct from ai_credits balance
     */
    public function incrementAiAnalyzerCount(): bool
    {
        // FREE MODE: Unlimited
        if (!Setting::isMonetizationEnabled()) {
            return true;
        }
        
        // Check if balance > 0
        if ($this->ai_credits <= 0) {
            return false;
        }
        
        $this->decrement('ai_credits');
        return true;
    }
    
    /**
     * Get remaining AI Analyzer uses (credit balance)
     */
    public function getRemainingAiAnalyzer()
    {
        // FREE MODE: Unlimited
        if (!Setting::isMonetizationEnabled()) {
            return 'unlimited';
        }
        
        return max(0, $this->ai_credits);
    }
    
    /**
     * Check if user has enough AI credits
     */
    public function canAccessAiAnalyzerWithLimit(): bool
    {
        // FREE MODE: Unlimited
        if (!Setting::isMonetizationEnabled()) {
            return true;
        }
        
        return $this->ai_credits > 0;
    }

    /**
     * Add AI credits to user balance (e.g. from Add-on Top-up or Premium upgrade)
     * 
     * @param int $amount Number of credits to add
     * @return void
     */
    public function addAiCredits(int $amount): void
    {
        if ($amount > 0) {
            $this->increment('ai_credits', $amount);
        }
    }

    /**
     * Increment Cover Letter counter (Deduct credit)
     */
    public function incrementCoverLetterCount(): bool
    {
        // FREE MODE: Unlimited
        if (!Setting::isMonetizationEnabled()) {
            return true;
        }
        
        // Check if balance > 0
        if ($this->cl_credits <= 0) {
            return false;
        }
        
        $this->decrement('cl_credits');
        return true;
    }
    
    /**
     * Get remaining Cover Letter uses (credit balance)
     */
    public function getRemainingCoverLetter()
    {
        // FREE MODE: Unlimited
        if (!Setting::isMonetizationEnabled()) {
            return 'unlimited';
        }
        
        return max(0, $this->cl_credits);
    }
    
    /**
     * Check if user has enough Cover Letter credits
     */
    public function canAccessCoverLetterWithLimit(): bool
    {
        // FREE MODE: Unlimited
        if (!Setting::isMonetizationEnabled()) {
            return true;
        }
        
        return $this->cl_credits > 0;
    }

    /**
     * Add Cover Letter credits to user balance (e.g. from Add-on Top-up or Premium upgrade)
     */
    public function addClCredits(int $amount): void
    {
        if ($amount > 0) {
            $this->increment('cl_credits', $amount);
        }
    }

    /**
     * Add XP for gamification and handle level ups
     */
    public function addXP(int $amount): void
    {
        $newXp = $this->xp + $amount;
        $newLevel = 1;

        foreach (self::LEVEL_THRESHOLDS as $lvl => $threshold) {
            if ($newXp >= $threshold) {
                $newLevel = $lvl;
            }
        }

        $this->update([
            'xp' => $newXp,
            'level' => $newLevel
        ]);
    }

    /**
     * Deduct XP for gamification and handle level downs
     */
    public function deductXP(int $amount): void
    {
        $newXp = max(0, $this->xp - $amount);
        $newLevel = 1;

        foreach (self::LEVEL_THRESHOLDS as $lvl => $threshold) {
            if ($newXp >= $threshold) {
                $newLevel = $lvl;
            }
        }

        $this->update([
            'xp' => $newXp,
            'level' => $newLevel
        ]);
    }

    /**
     * Get User's Level Title
     */
    public function getLevelTitleAttribute(): string
    {
        return self::LEVEL_TITLES[$this->level] ?? 'Job Seeker';
    }

    /**
     * Check if user can access AI Photo
     */
    public function canAccessPhotoWithLimit(): bool
    {
        if (!Setting::isMonetizationEnabled()) {
            return true;
        }
        return $this->photo_credits > 0;
    }

    /**
     * Increment AI Photo counter (Deduct credit)
     */
    public function incrementPhotoCount(): bool
    {
        if (!Setting::isMonetizationEnabled()) {
            return true;
        }
        
        if ($this->photo_credits <= 0) {
            return false;
        }
        
        $this->decrement('photo_credits');
        return true;
    }

    /**
     * Get remaining Photo uses (credit balance)
     */
    public function getRemainingPhoto()
    {
        if (!Setting::isMonetizationEnabled()) {
            return 'unlimited';
        }
        return max(0, $this->photo_credits);
    }
    
    /**
     * Add Photo credits to user balance
     */
    public function addPhotoCredits(int $amount): void
    {
        if ($amount > 0) {
            $this->increment('photo_credits', $amount);
        }
    }
}

