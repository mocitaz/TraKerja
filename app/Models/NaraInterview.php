<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** One structured interview practice session: a plan of question types, and a scored record of each answer. */
class NaraInterview extends Model
{
    public const MIN_QUESTIONS = 3;

    public const MAX_QUESTIONS = 8;

    protected $fillable = ['user_id', 'vacancy_id', 'role', 'company', 'total', 'plan', 'answers', 'status'];

    protected $casts = ['plan' => 'array', 'answers' => 'array'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function activeFor(User $user): ?self
    {
        return self::where('user_id', $user->id)->where('status', 'active')->latest('id')->first();
    }

    /** Question types for a session of $count questions: behavioural first, then technical, then motivation. @return array<int, string> */
    public static function planFor(int $count): array
    {
        $count = max(self::MIN_QUESTIONS, min(self::MAX_QUESTIONS, $count));
        $behavioral = (int) ceil($count * .4);
        $motivation = $count >= 5 ? 1 : 0;

        return [...array_fill(0, $behavioral, 'behavioral'), ...array_fill(0, $count - $behavioral - $motivation, 'technical'), ...array_fill(0, $motivation, 'motivation')];
    }

    public function answered(): int
    {
        return count((array) $this->answers);
    }

    public function average(): ?float
    {
        $scores = array_column((array) $this->answers, 'score');

        return $scores ? round(array_sum($scores) / count($scores), 1) : null;
    }

    /** Store (or replace) the scored answer for question $n. */
    public function recordAnswer(int $n, array $data): void
    {
        $answers = collect((array) $this->answers)->reject(fn ($a) => ($a['n'] ?? null) === $n)->push(['n' => $n, 'type' => $this->plan[$n - 1] ?? 'technical'] + $data)->sortBy('n')->values()->all();
        $this->update(['answers' => $answers]);
    }

    /** What the chat shows and the model needs: how far the session is. @return array<string, mixed> */
    public function progress(): array
    {
        return array_filter([
            'role' => $this->role, 'company' => $this->company, 'total' => $this->total, 'answered' => $this->answered(),
            'next_question_number' => $this->status === 'active' ? min($this->total, $this->answered() + 1) : null,
            'next_question_type' => $this->status === 'active' ? ($this->plan[$this->answered()] ?? null) : null,
            'average_score' => $this->average(), 'status' => $this->status,
        ], fn ($v) => $v !== null);
    }
}
