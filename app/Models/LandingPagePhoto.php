<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPagePhoto extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the URL for the photo
     */
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
