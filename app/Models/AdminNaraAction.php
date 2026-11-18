<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A change Nara Admin proposed. It runs only after the admin who asked approves it, and only while it is fresh. */
class AdminNaraAction extends Model
{
    /** Minutes a proposal stays approvable. */
    public const TTL_MINUTES = 30;

    protected $fillable = ['user_id', 'type', 'params', 'summary', 'status', 'result', 'decided_at'];

    protected $casts = ['params' => 'array', 'result' => 'array', 'decided_at' => 'datetime'];

    public function isStale(): bool
    {
        return $this->created_at->lt(now()->subMinutes(self::TTL_MINUTES));
    }

    /** Shape the chat card needs. @return array<string, mixed> */
    public function card(): array
    {
        $status = $this->status === 'pending' && $this->isStale() ? 'expired' : $this->status;

        return ['id' => $this->id, 'type' => $this->type, 'summary' => $this->summary, 'status' => $status,
            'details' => \App\Services\AdminNara\AdminNaraActions::describe($this->type, (array) $this->params),
            'message' => $this->result['message'] ?? null];
    }
}
