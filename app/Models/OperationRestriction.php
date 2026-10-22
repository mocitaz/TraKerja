<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OperationRestriction extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['expires_at' => 'datetime', 'released_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('released_at')->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }

    public static function blocks(User $user, string $scope): bool
    {
        return ! $user->isAdmin() && static::where('user_id', $user->id)->active()->whereIn('scope', array_unique(['account', $scope]))->exists();
    }
}
