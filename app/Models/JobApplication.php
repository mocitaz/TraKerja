<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'position',
        'location',
        'platform',
        'status',
        'application_status',
        'recruitment_stage',
        'career_level',
        'platform_link',
        'application_date',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($jobApplication) {
            // Ensure location is not empty
            if (empty($jobApplication->location)) {
                throw new \Exception('Location is required');
            }
        });
        
        static::updating(function ($jobApplication) {
            // Ensure location is not empty
            if (empty($jobApplication->location)) {
                throw new \Exception('Location is required');
            }
        });
    }

    protected $casts = [
        'application_date' => 'date',
    ];

    /**
     * Get the user that owns the job application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
