<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scope untuk pesan belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    // Mark as read
    public function markAsRead(): void
    {
        $this->update(['status' => 'read']);
    }
}