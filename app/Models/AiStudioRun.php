<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiStudioRun extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['payload', 'source_path', 'processing_token'];
    protected $casts = ['payload' => 'encrypted:array', 'processing_expires_at' => 'datetime'];

    public function workspaceUrl(): string
    {
        return route('ai-studio.index', $this->status === 'completed'
            ? ['task' => $this->task, 'id' => $this->result_id]
            : ['task' => $this->task, 'run' => $this->id]);
    }
}
