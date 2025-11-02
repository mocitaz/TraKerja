<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Setting;
use App\Notifications\CustomResetPassword;
use App\Notifications\CustomVerifyEmail;

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
    use HasFactory, Notifiable;

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
        'password',
        'logo',
        'role',
        'is_admin',
        'is_premium',
        'premium_purchased_at',
        'premium_until',
        'payment_status',
        'registered_phase',
        'grandfathered_benefits',
        'cv_exports_this_month',
        'last_export_reset',
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
            'premium_purchased_at' => 'datetime',
            'premium_until' => 'datetime',
            'registered_phase' => 'integer',
            'grandfathered_benefits' => 'array',
            'cv_exports_this_month' => 'integer',
            'last_export_reset' => 'date',
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
     * Get the job applications for the user.
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
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
    public function projects(): HasMany
    {
        return $this->hasMany(UserProject::class);
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
            return 5;
        }
        
        // PREMIUM MODE: Premium users get all 5 templates
        if ($this->is_premium && $this->payment_status === self::PAYMENT_STATUS_PAID) {
            return 5;
        }
        
        // PREMIUM MODE: Free tier users only get 1 template
        return 1;
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
     * Send the email verification notification with custom subject/layout.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmail());
    }

    /**
     * Send the password reset notification with custom subject/layout.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPassword($token));
    }
}
