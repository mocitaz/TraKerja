<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobSource;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Read-only vacancy feed for trusted clients such as the asep Telegram bot (TraKerja 2.0, Fase 6).
 * Sync pattern: page through /vacancies?updated_since=… with next_cursor; rows whose status is
 * closed/removed or that carry merged_into tell the client to drop or replace what it has.
 */
class FeedController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'updated_since' => 'nullable|date',
            'cursor' => 'nullable|string|max:200',
            'limit' => 'nullable|integer|min:1|max:100',
            'q' => 'nullable|string|max:80',
            'region' => 'nullable|string|max:80',
            'remote' => 'nullable|boolean',
            'status' => 'nullable|in:active,all',
        ]);
        $limit = (int) ($data['limit'] ?? 50);
        // A sync (updated_since) sees every change, closures included; a plain listing only open jobs.
        $all = ($data['status'] ?? null) === 'all' || isset($data['updated_since']);

        $query = Vacancy::with(['company', 'region.parent', 'sector', 'primaryListing.source', 'listings.source'])
            ->when(! $all, fn ($q) => $q->visible())
            ->when($all, fn ($q) => $q->where('status', '!=', 'archived'))
            ->when($data['updated_since'] ?? null, fn ($q, $since) => $q->where('updated_at', '>', Carbon::parse($since)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s')))
            ->when($data['q'] ?? null, fn ($q, $term) => $q->where(fn ($w) => $w->where('title', 'like', "%{$term}%")
                ->orWhereHas('company', fn ($c) => $c->where('name', 'like', "%{$term}%"))))
            ->when($data['region'] ?? null, fn ($q, $region) => $q->whereHas('region', fn ($r) => $r->where('name', 'like', "%{$region}%")
                ->orWhereHas('parent', fn ($p) => $p->where('name', 'like', "%{$region}%"))))
            ->when($request->boolean('remote'), fn ($q) => $q->where('work_mode', 'remote'));

        if ($cursor = $this->decodeCursor($data['cursor'] ?? null)) {
            [$at, $id] = $cursor;
            $query->where(fn ($q) => $q->where('updated_at', '>', $at)->orWhere(fn ($w) => $w->where('updated_at', $at)->where('id', '>', $id)));
        }

        $rows = $query->orderBy('updated_at')->orderBy('id')->limit($limit + 1)->get();
        $more = $rows->count() > $limit;
        $rows = $rows->take($limit);
        $last = $rows->last();

        return response()->json([
            'data' => $rows->map(fn (Vacancy $v) => self::present($v))->values(),
            'has_more' => $more,
            'next_cursor' => $more && $last ? base64_encode($last->updated_at->format('Y-m-d H:i:s').'|'.$last->id) : null,
            'attribution' => 'Data dikumpulkan TraKerja dari sumber yang tercantum di setiap loker. Tautkan ke apply_url atau url; jangan menyalin ulang ke situs publik.',
        ]);
    }

    public function show(string $publicId): JsonResponse
    {
        $vacancy = Vacancy::with(['company', 'region.parent', 'sector', 'primaryListing.source', 'listings.source'])
            ->where('public_id', strtolower($publicId))->where('status', '!=', 'archived')->firstOrFail();

        return response()->json(['data' => self::present($vacancy)]);
    }

    public function sources(): JsonResponse
    {
        return response()->json(['data' => JobSource::where('is_active', true)->orderBy('name')->get(['slug', 'name', 'base_url', 'health', 'last_success_at'])]);
    }

    public static function present(Vacancy $v): array
    {
        $removed = $v->scam_flag === 'suspected';
        $status = $removed ? 'removed' : $v->status;
        $base = [
            'id' => $v->public_id,
            'status' => $status,
            'merged_into' => $v->merged_into_id ? Vacancy::whereKey($v->merged_into_id)->value('public_id') : null,
            'updated_at' => $v->updated_at?->toIso8601String(),
        ];
        if ($removed || $v->merged_into_id) {
            return $base; // nothing else to show for jobs clients must drop
        }
        $city = $v->region?->level === 'city' ? $v->region : null;

        return $base + [
            'url' => $v->public_path,
            'title' => $v->title,
            'company' => $v->company ? ['name' => $v->company->name, 'slug' => $v->company->slug, 'domain' => $v->company->domain, 'logo_url' => $v->company->logo_url] : null,
            'location' => [
                'text' => $v->location,
                'city' => $city?->name,
                'province' => $city ? $city->parent?->name : $v->region?->name,
                'country' => $v->country ?? ($v->region ? 'ID' : null),
            ],
            'work_mode' => $v->work_mode,
            'employment_type' => $v->employment_type,
            'seniority' => $v->seniority,
            'is_internship' => (bool) $v->is_internship,
            'sector' => $v->sector?->name,
            'skills' => $v->skills ?? [],
            'education_min' => $v->education_min,
            'salary' => $v->salary_min ? ['min' => $v->salary_min, 'max' => $v->salary_max, 'currency' => $v->salary_currency ?? 'IDR', 'period' => $v->salary_period, 'label' => $v->salary_label] : null,
            'summary' => $v->summary,
            'apply_url' => $v->source_url,
            'sources' => $v->listings->filter(fn ($l) => preg_match('#^https?://#i', (string) $l->url))
                ->unique('job_source_id')->map(fn ($l) => ['name' => $l->source?->name, 'url' => $l->url])->values(),
            'date_posted' => $v->date_posted?->toDateString(),
            'valid_through' => $v->valid_through?->toDateString(),
            'first_seen_at' => $v->first_seen_at?->toIso8601String(),
            'last_verified_at' => $v->last_verified_at?->toIso8601String(),
            'closed_at' => $v->closed_at?->toIso8601String(),
        ];
    }

    /** @return array{0: string, 1: int}|null */
    private function decodeCursor(?string $cursor): ?array
    {
        $decoded = $cursor ? base64_decode($cursor, true) : false;
        if (! $decoded || ! preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\|(\d+)$/', $decoded, $m)) {
            return null;
        }

        return [$m[1], (int) $m[2]];
    }
}
