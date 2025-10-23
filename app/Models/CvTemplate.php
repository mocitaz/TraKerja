<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'template_key',
        'settings',
        'sections',
        'is_premium_template',
        'is_default',
    ];

    protected $casts = [
        'settings' => 'array',
        'sections' => 'array',
        'is_premium_template' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set as default template for user
     */
    public function setAsDefault(): void
    {
        // Unset other default templates
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }

    /**
     * Get default sections
     */
    public static function getDefaultSections(): array
    {
        return [
            'personal_info' => true,
            'experiences' => true,
            'education' => true,
            'skills' => true,
            'organizations' => false,
            'achievements' => false,
            'projects' => false,
        ];
    }

    /**
     * Get default settings
     */
    public static function getDefaultSettings(): array
    {
        return [
            'color_scheme' => 'blue',
            'font_family' => 'Inter',
            'font_size' => 'medium',
            'layout' => 'single-column',
        ];
    }
}
