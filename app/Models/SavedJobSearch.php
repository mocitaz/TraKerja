<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedJobSearch extends Model
{
    protected $fillable = ['user_id', 'name', 'filters', 'fingerprint', 'alert_channel', 'alert_frequency', 'last_alerted_at'];

    protected $casts = ['filters' => 'array', 'last_alerted_at' => 'datetime'];

    /** Alerts are opt-in per search: 'none' (off) or 'email' (in-app, plus email when the user allows it). */
    public function alertsOn(): bool
    {
        return in_array($this->alert_channel, ['email', 'inapp'], true);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
