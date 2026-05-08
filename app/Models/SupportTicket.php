<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'subject',
        'message',
        'status',
        'admin_reply',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    /**
     * Get the user who submitted the ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get readable category name.
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'technical_issue' => 'Technical Issue',
            'payment_billing' => 'Payment & Billing',
            'feature_request' => 'Feature Request',
            'general_feedback' => 'General Feedback',
            default => 'Other',
        };
    }

    /**
     * Check if the ticket has been replied to.
     */
    public function isReplied(): bool
    {
        return !empty($this->admin_reply);
    }
}
