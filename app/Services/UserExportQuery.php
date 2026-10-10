<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserExportQuery
{
    public static function build(array $filters): Builder
    {
        $query = User::where('role', '!=', 'admin');
        if ($search = $filters['search'] ?? '') {
            $query->where(fn ($q) => $q->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%'));
        }
        if (($filters['premium'] ?? '') === 'premium') $query->where('is_premium', true);
        if (($filters['premium'] ?? '') === 'free') $query->where('is_premium', false);
        if (($filters['ai_trial'] ?? '') === 'used') $query->where('has_used_ai_analyzer_trial', true);
        if (($filters['ai_trial'] ?? '') === 'not_used') $query->where(fn ($q) => $q->where('has_used_ai_analyzer_trial', false)->orWhereNull('has_used_ai_analyzer_trial'));
        return $query;
    }
}
