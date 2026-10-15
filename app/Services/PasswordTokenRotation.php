<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class PasswordTokenRotation
{
    public function update(User $user, string $password): void
    {
        $user->getConnection()->transaction(function () use ($user,$password) {
            $user->newQuery()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            $user->forceFill(['password'=>Hash::make($password),'remember_token'=>Str::random(60)])->save();
            $user->tokens()->delete();
        });
    }
}
