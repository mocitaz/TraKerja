<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'order_id',
        'amount',
        'currency',
        'payment_gateway',
        'payment_method',
        'payment_channel',
        'status',
        'paid_at',
        'expired_at',
        'payment_details',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'payment_details' => 'array',
    ];

    /**
     * Get the user that owns the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if payment is successful
     */
    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is expired
     */
    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }
        
        if ($this->expired_at && now()->isAfter($this->expired_at)) {
            return true;
        }
        
        return false;
    }

    /**
     * Mark payment as success
     */
    public function markAsSuccess(): void
    {
        $this->update([
            'status' => 'success',
            'paid_at' => now(),
        ]);

        // Update user premium status
        $this->user->update([
            'is_premium' => true,
            'premium_purchased_at' => now(),
            'payment_status' => 'paid',
        ]);
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed(): void
    {
        $this->update([
            'status' => 'failed',
        ]);
    }

    /**
     * Generate unique order ID
     */
    public static function generateOrderId(): string
    {
        return 'TRK-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
    }

    /**
     * Scope for successful payments
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute(): string
    {
        if ($this->currency === 'IDR') {
            return 'Rp ' . number_format($this->amount, 0, ',', '.');
        }
        
        return $this->currency . ' ' . number_format($this->amount, 2);
    }
}
