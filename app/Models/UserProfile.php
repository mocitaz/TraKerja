<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'domicile',
        'bio',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'website_url',
        'additional_links',
    ];

    protected $casts = [
        'additional_links' => 'array', // JSON to array
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all social links as array
     */
    public function getSocialLinksAttribute(): array
    {
        $links = [];
        
        if ($this->linkedin_url) {
            $links[] = ['name' => 'LinkedIn', 'url' => $this->linkedin_url, 'icon' => 'linkedin'];
        }
        if ($this->github_url) {
            $links[] = ['name' => 'GitHub', 'url' => $this->github_url, 'icon' => 'github'];
        }
        if ($this->portfolio_url) {
            $links[] = ['name' => 'Portfolio', 'url' => $this->portfolio_url, 'icon' => 'globe'];
        }
        if ($this->website_url) {
            $links[] = ['name' => 'Website', 'url' => $this->website_url, 'icon' => 'link'];
        }
        
        // Add custom links
        if ($this->additional_links) {
            foreach ($this->additional_links as $link) {
                $links[] = array_merge($link, ['icon' => 'link']);
            }
        }
        
        return $links;
    }
}
