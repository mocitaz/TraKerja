<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UserResume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'file_size' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get human-readable file size string.
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024) . ' KB';
        }

        return $bytes . ' B';
    }

    /**
     * Get uppercase extension (e.g. PDF, DOCX).
     */
    public function getExtensionAttribute(): string
    {
        $ext = pathinfo($this->file_name, PATHINFO_EXTENSION);
        return strtoupper($ext ?: 'FILE');
    }

    /**
     * Check if file is a PDF.
     */
    public function getIsPdfAttribute(): bool
    {
        return strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION)) === 'pdf'
            || $this->file_type === 'application/pdf';
    }

    /**
     * Delete stored file when model is deleted.
     */
    protected static function booted(): void
    {
        static::deleting(function (UserResume $resume) {
            if ($resume->file_path && Storage::disk('local')->exists($resume->file_path)) {
                Storage::disk('local')->delete($resume->file_path);
            }
        });
    }
}
