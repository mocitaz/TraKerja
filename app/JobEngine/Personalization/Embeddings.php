<?php

namespace App\JobEngine\Personalization;

use App\JobEngine\Ai\AiGateway;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Embeddings for CV-to-job matching (TraKerja 2.0, Fase 6).
 * Vacancies and user profiles are embedded with the same model; vectors are stored as packed float32
 * and only recomputed when the text they were built from changes.
 */
class Embeddings
{
    public function __construct(private AiGateway $ai) {}

    public static function vacancyText(Vacancy $vacancy): string
    {
        return Str::limit(implode('. ', array_filter([
            $vacancy->title,
            $vacancy->sector?->name,
            $vacancy->seniority ? 'Level '.$vacancy->seniority : null,
            $vacancy->skills ? 'Keterampilan: '.implode(', ', $vacancy->skills) : null,
            $vacancy->education_min ? 'Pendidikan minimal '.$vacancy->education_min : null,
            $vacancy->summary,
        ])), 2000, '');
    }

    /** The CV as text; empty when there is too little to match on. */
    public static function profileText(User $user): string
    {
        $user->loadMissing(['profile', 'skills', 'experiences', 'educations']);
        $skills = $user->skills->pluck('skill_name')->filter()->all();
        $experiences = $user->experiences->sortByDesc('start_date')->take(4)
            ->map(fn ($e) => trim($e->position.' di '.$e->company_name.($e->description ? ': '.Str::limit(strip_tags($e->description), 300, '') : '')))->all();
        $education = $user->educations->map(fn ($e) => trim($e->degree.' '.$e->major))->filter()->all();

        if (! $skills && ! $experiences && ! $user->profile?->headline) {
            return '';
        }

        return Str::limit(implode('. ', array_filter([
            $user->profile?->headline,
            $user->profile?->bio ? Str::limit(strip_tags($user->profile->bio), 400, '') : null,
            $skills ? 'Keterampilan: '.implode(', ', $skills) : null,
            $experiences ? 'Pengalaman: '.implode('; ', $experiences) : null,
            $education ? 'Pendidikan: '.implode('; ', $education) : null,
        ])), 4000, '');
    }

    /** Embed visible vacancies that have no vector yet or whose text changed. Returns how many were stored. */
    public function embedVacancies(int $limit = 500, int $batch = 64): int
    {
        $model = config('services.openai.jobs.embedding_model');
        $stored = DB::table('vacancy_embeddings')->pluck('text_hash', 'vacancy_id');
        $todo = [];
        Vacancy::visible()->with('sector')->orderByDesc('id')->chunk(500, function ($vacancies) use (&$todo, $stored, $limit) {
            foreach ($vacancies as $vacancy) {
                $text = self::vacancyText($vacancy);
                $hash = hash('sha256', $text);
                if (($stored[$vacancy->id] ?? null) !== $hash) {
                    $todo[] = [$vacancy->id, $text, $hash];
                }
                if (count($todo) >= $limit) {
                    return false;
                }
            }
        });

        $count = 0;
        foreach (array_chunk($todo, $batch) as $chunk) {
            $vectors = $this->ai->embed('vacancy_embedding', array_column($chunk, 1));
            if ($vectors === null) {
                break; // no key, budget reached, or an API error: try again next run
            }
            foreach ($chunk as $i => [$id, , $hash]) {
                DB::table('vacancy_embeddings')->updateOrInsert(['vacancy_id' => $id], [
                    'model' => $model, 'text_hash' => $hash, 'vector' => self::pack($vectors[$i]), 'created_at' => now(), 'updated_at' => now(),
                ]);
                $count++;
            }
        }

        return $count;
    }

    /** Embed specific vacancies (pipeline step after enrichment). */
    public function embedIds(array $ids): int
    {
        $model = config('services.openai.jobs.embedding_model');
        $rows = Vacancy::with('sector')->whereIn('id', $ids)->get()
            ->map(fn ($v) => [$v->id, self::vacancyText($v)])->filter(fn ($r) => trim($r[1]) !== '')->values();
        if ($rows->isEmpty()) {
            return 0;
        }
        $vectors = $this->ai->embed('vacancy_embedding', $rows->pluck(1)->all());
        if ($vectors === null) {
            return 0;
        }
        foreach ($rows as $i => [$id, $text]) {
            DB::table('vacancy_embeddings')->updateOrInsert(['vacancy_id' => $id], [
                'model' => $model, 'text_hash' => hash('sha256', $text), 'vector' => self::pack($vectors[$i]), 'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        return $rows->count();
    }

    /** The user's profile vector, embedding it when the CV changed. Null when the CV is too thin or AI is unavailable. */
    public function forUser(User $user): ?array
    {
        $text = self::profileText($user);
        if ($text === '') {
            return null;
        }
        $hash = hash('sha256', $text);
        $row = DB::table('user_embeddings')->where('user_id', $user->id)->first();
        if ($row && $row->text_hash === $hash) {
            return self::unpack($row->vector);
        }
        $vector = $this->ai->embed('profile_embedding', [$text])[0] ?? null;
        if ($vector === null) {
            return $row ? self::unpack($row->vector) : null; // keep using the previous CV vector if the API is down
        }
        DB::table('user_embeddings')->updateOrInsert(['user_id' => $user->id], [
            'model' => config('services.openai.jobs.embedding_model'), 'text_hash' => $hash, 'vector' => self::pack($vector),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        return $vector;
    }

    public static function pack(array $vector): string
    {
        return pack('g*', ...$vector);
    }

    public static function unpack(string $blob): array
    {
        return array_values(unpack('g*', $blob));
    }

    public static function cosine(array $a, array $b): float
    {
        $dot = $na = $nb = 0.0;
        foreach ($a as $i => $x) {
            $y = $b[$i] ?? 0.0;
            $dot += $x * $y;
            $na += $x * $x;
            $nb += $y * $y;
        }

        return $na > 0 && $nb > 0 ? $dot / sqrt($na * $nb) : 0.0;
    }
}
