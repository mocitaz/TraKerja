<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One message in an admin's conversation with Nara Admin. */
class AdminNaraMessage extends Model
{
    public const UPDATED_AT = null;

    public const KEEP = 120;

    public const CONTEXT = 16;

    protected $fillable = ['user_id', 'role', 'content', 'page'];

    /** @return array<int, array{role: string, content: string}> last CONTEXT messages, oldest first */
    public static function contextFor(User $admin): array
    {
        return self::where('user_id', $admin->id)->latest('id')->limit(self::CONTEXT)->get(['role', 'content'])
            ->reverse()->map(fn ($m) => ['role' => $m->role, 'content' => $m->content])->values()->all();
    }

    public static function record(User $admin, string $message, string $reply, ?string $page): self
    {
        self::create(['user_id' => $admin->id, 'role' => 'user', 'content' => $message, 'page' => $page]);
        $saved = self::create(['user_id' => $admin->id, 'role' => 'model', 'content' => $reply, 'page' => $page]);
        $cutoff = self::where('user_id', $admin->id)->latest('id')->skip(self::KEEP)->value('id');
        if ($cutoff) {
            self::where('user_id', $admin->id)->where('id', '<=', $cutoff)->delete();
        }

        return $saved;
    }
}
