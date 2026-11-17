<?php

namespace App\Support;

use App\Models\Setting;
use App\Models\User;

/**
 * Where a signed-in user lands (TraKerja 2.0, Fase 6): Explore once it is live, the dashboard while
 * Explore is in maintenance, the admin index for admins.
 */
class WorkspaceHome
{
    public static function exploreLive(): bool
    {
        return ! Setting::get('explore_maintenance', ! app()->environment('testing'));
    }

    public static function path(?User $user = null): string
    {
        $user ??= auth()->user();
        if ($user && $user->role === 'admin') {
            return route('admin.index', absolute: false);
        }

        return self::exploreLive() ? route('jobs.explore', absolute: false) : route('dashboard', absolute: false);
    }
}
