<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One thing Nara remembers about a user's job search preferences. */
class NaraMemory extends Model
{
    /** Per user; the oldest memory is dropped beyond this. */
    public const MAX = 20;

    public const CATEGORIES = ['role', 'location', 'salary', 'work_mode', 'other'];

    protected $fillable = ['user_id', 'category', 'fact'];

    /** Remember a fact (the same fact is only stored once) and keep the list within MAX. */
    public static function remember(User $user, string $fact, string $category = 'other'): self
    {
        $fact = mb_substr(trim(preg_replace('/\s+/u', ' ', $fact)), 0, 200);
        $category = in_array($category, self::CATEGORIES, true) ? $category : 'other';
        $memory = self::updateOrCreate(['user_id' => $user->id, 'fact' => $fact], ['category' => $category]);
        $cutoff = self::where('user_id', $user->id)->latest('id')->skip(self::MAX)->value('id');
        if ($cutoff) {
            self::where('user_id', $user->id)->where('id', '<=', $cutoff)->delete();
        }

        return $memory;
    }

    /** @return array<int, array{id: int, category: string, fact: string}> */
    public static function listFor(User $user): array
    {
        return self::where('user_id', $user->id)->orderBy('id')->get(['id', 'category', 'fact'])
            ->map(fn ($m) => ['id' => $m->id, 'category' => $m->category, 'fact' => $m->fact])->all();
    }
}
