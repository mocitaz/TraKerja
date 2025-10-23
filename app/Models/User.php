<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
        'is_premium',
        'premium_purchased_at',
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
            'is_premium' => 'boolean',
            'premium_purchased_at' => 'datetime',
            'registered_phase' => 'integer',
            'grandfathered_benefits' => 'array',
            'cv_exports_this_month' => 'integer',
            'last_export_reset' => 'date',
        ];
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
        return $this->is_premium && $this->payment_status === 'paid';
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
     * Get CV templates count for this user (respecting grandfathered benefits)
     * 
     * @return int Number of CV templates available
     */
    public function getCvTemplatesCount(): int
    {
        // Premium users always get all 5 templates
        if ($this->is_premium) {
            return 5;
        }
        
        // Phase 1 users (early adopters) get 3 templates FREE forever
        if ($this->registered_phase == 1 || $this->hasGrandfatheredBenefit('cv_templates_3_free')) {
            return 3;
        }
        
        // Everyone else gets 1 (free tier)
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
     * Check if user can access feature (respecting phase and grandfather rights)
     * 
     * @param string $feature Feature name
     * @return bool
     */
    public function canAccessFeature(string $feature): bool
    {
        // Premium users always get access to everything
        if ($this->is_premium) {
            return true;
        }
        
        // Check grandfathered benefits
        if ($this->hasGrandfatheredBenefit($feature)) {
            return true;
        }
        
        // Check phase-based access
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
     * @return bool True if increment successful, false if limit reached
     */
    public function incrementExportCount(): bool
    {
        // Premium users have unlimited exports
        if ($this->is_premium) {
            return true;
        }
        
        // Check and reset counter if needed
        $this->checkExportReset();
        
        // Get export limit
        $limit = $this->getFeatureLimit('cv_exports');
        
        // Unlimited exports
        if ($limit === 'unlimited') {
            return true;
        }
        
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
     * @return mixed "unlimited" or integer remaining
     */
    public function getRemainingExports()
    {
        // Premium users have unlimited
        if ($this->is_premium) {
            return 'unlimited';
        }
        
        // Check and reset if needed
        $this->checkExportReset();
        
        $limit = $this->getFeatureLimit('cv_exports');
        
        if ($limit === 'unlimited') {
            return 'unlimited';
        }
        
        return max(0, $limit - $this->cv_exports_this_month);
    }
}
