<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardAction extends Model
{
    protected $fillable = ['user_id', 'job_application_id', 'action', 'context', 'completed_at', 'snoozed_until'];

    protected $casts = ['completed_at' => 'datetime', 'snoozed_until' => 'datetime'];
}
