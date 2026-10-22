<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationIncident extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['resolved_at' => 'datetime'];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function affectedUser()
    {
        return $this->belongsTo(User::class, 'affected_user_id');
    }

    public function events()
    {
        return $this->hasMany(OperationEvent::class, 'incident_id')->orderByDesc('id');
    }
}
