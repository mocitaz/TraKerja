<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_id',
        'yukk_transaction_code',
        'yukk_token',
        'amount',
        'payment_channel_code',
        'payment_channel_name',
        'payment_category',
        'va_number',
        'va_account_id',
        'va_expired_at',
        'customer_name',
        'customer_email',
        'customer_phone',
        'status',
        'request_at',
        'paid_at',
        'expired_at',
        'redirect_url',
        'callback_url',
        'notification_url',
        'notes',
        'metadata',
        'webhook_data',
    ];

    protected $casts = [
        'amount' => 'integer',
        'request_at' => 'datetime',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'va_expired_at' => 'datetime',
        'metadata' => 'array',
        'webhook_data' => 'array',
    ];

    /**
     * Get the user that owns the payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }

    /**
     * Check if payment is waiting for customer action
     */
    public function isWaiting(): bool
    {
        return $this->status === 'WAITING';
    }

    /**
     * Check if payment is successful
     */
    public function isSuccess(): bool
    {
        return $this->status === 'SUCCESS';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'FAILED';
    }

    /**
     * Check if payment is canceled
     */
    public function isCanceled(): bool
    {
        return $this->status === 'CANCELED';
    }

    /**
     * Check if payment is expired
     */
    public function isExpired(): bool
    {
        return $this->status === 'EXPIRED';
    }

    /**
     * Mark payment as waiting
     */
    public function markAsWaiting(): void
    {
        $this->update(['status' => 'WAITING']);
    }

    /**
     * Mark payment as success
     */
    public function markAsSuccess(): void
    {
        $this->update([
            'status' => 'SUCCESS',
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed(): void
    {
        $this->update(['status' => 'FAILED']);
    }

    /**
     * Mark payment as canceled
     */
    public function markAsCanceled(): void
    {
        $this->update(['status' => 'CANCELED']);
    }

    /**
     * Mark payment as expired
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => 'EXPIRED']);
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get successful payments
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'SUCCESS');
    }

    /**
     * Scope to get pending/waiting payments
     */
    public function scopePendingOrWaiting($query)
    {
        return $query->whereIn('status', ['PENDING', 'WAITING']);
    }

    /**
     * Format amount as currency
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodAttribute(): string
    {
        return $this->payment_channel_name ?? $this->payment_channel_code ?? 'Unknown';
    }
}
