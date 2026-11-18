<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** One message in a user's conversation with Nara; replies can carry a 👍 / 👎 rating. */
class NaraMessage extends Model
{
    /** Messages kept per user; older ones are pruned when new ones arrive. */
    public const KEEP = 200;

    /** Messages sent to the model as conversation history. */
    public const CONTEXT = 20;

    protected $fillable = ['user_id', 'role', 'content', 'mode', 'has_jobs', 'rating', 'rated_at'];

    protected $casts = ['has_jobs' => 'boolean', 'rating' => 'integer', 'rated_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Conversation history for the model: the last CONTEXT messages, oldest first. @return array<int, array{role: string, content: string}> */
    public static function contextFor(User $user): array
    {
        return self::where('user_id', $user->id)->latest('id')->limit(self::CONTEXT)->get(['role', 'content'])
            ->reverse()->map(fn ($m) => ['role' => $m->role, 'content' => $m->content])->values()->all();
    }

    /** Store one exchange and return the reply; prunes the oldest messages beyond KEEP. */
    public static function record(User $user, string $message, string $reply, string $mode): self
    {
        self::create(['user_id' => $user->id, 'role' => 'user', 'content' => $message, 'mode' => $mode]);
        $saved = self::create(['user_id' => $user->id, 'role' => 'model', 'content' => $reply, 'mode' => $mode, 'has_jobs' => str_contains($reply, '[job:')]);
        $cutoff = self::where('user_id', $user->id)->latest('id')->skip(self::KEEP)->value('id');
        if ($cutoff) {
            self::where('user_id', $user->id)->where('id', '<=', $cutoff)->delete();
        }

        return $saved;
    }
}
