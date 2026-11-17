<?php

namespace App\Livewire\Admin;

use App\Console\Commands\CheckGoldenJobs;
use App\Helpers\CategoryHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Review screen for the golden set (TraKerja 2.0 Blueprint, Fase 0). A reviewer compares the
 * draft taken from structured data with the AI suggestion, sets the correct value and marks the
 * sample reviewed. Only reviewed labels are used to measure extractors.
 *
 * Data lives in storage/app/private/golden on the machine that collected it (local only).
 */
class GoldenSetReview extends Component
{
    public const FIELDS = [
        'title' => 'Judul', 'company' => 'Perusahaan', 'company_domain' => 'Domain perusahaan',
        'city' => 'Kota', 'province' => 'Provinsi', 'country' => 'Negara (ISO)', 'location_text' => 'Lokasi (teks)',
        'employment_type' => 'Tipe kerja', 'work_mode' => 'Mode kerja', 'seniority' => 'Level',
        'education_min' => 'Pendidikan min.', 'is_internship' => 'Magang?', 'sector' => 'Sektor',
        'salary_min' => 'Gaji min', 'salary_max' => 'Gaji maks', 'salary_currency' => 'Mata uang',
        'salary_period' => 'Periode gaji', 'date_posted' => 'Tanggal posting', 'valid_through' => 'Batas lamar',
        'is_open' => 'Masih buka?',
    ];

    private const INTEGER_FIELDS = ['salary_min', 'salary_max'];

    private const BOOLEAN_FIELDS = ['is_internship', 'is_open'];

    private const DATE_FIELDS = ['date_posted', 'valid_through'];

    #[Url]
    public string $tab = 'labels';

    #[Url]
    public string $source = '';

    #[Url]
    public string $status = 'pending';

    #[Url(as: 'sample')]
    public ?string $currentId = null;

    public array $form = [];

    public string $notes = '';

    public string $flash = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);
        $this->selectFirstIfNeeded();
    }

    public function updatedSource(): void
    {
        $this->currentId = null;
        $this->selectFirstIfNeeded();
    }

    public function updatedStatus(): void
    {
        $this->currentId = null;
        $this->selectFirstIfNeeded();
    }

    public function select(string $id): void
    {
        $this->currentId = $id;
        $this->flash = '';
        $this->loadForm();
    }

    public function useSuggestion(string $field): void
    {
        $row = $this->currentRow();
        if ($row && array_key_exists($field, $row['suggestion'] ?? [])) {
            $this->form[$field] = $this->toFormValue($field, $row['suggestion'][$field]);
        }
    }

    /** Fill only the fields that are still empty with the AI suggestion. */
    public function fillEmptyFromSuggestion(): void
    {
        $row = $this->currentRow();
        foreach ($row['suggestion'] ?? [] as $field => $value) {
            if (array_key_exists($field, self::FIELDS) && ($this->form[$field] ?? '') === '') {
                $this->form[$field] = $this->toFormValue($field, $value);
            }
        }
    }

    public function save(bool $next = true): void
    {
        $row = $this->currentRow();
        if (! $row) {
            return;
        }

        $label = [];
        $errors = [];
        foreach (array_keys(self::FIELDS) as $field) {
            [$value, $error] = $this->fromFormValue($field, $this->form[$field] ?? '');
            $label[$field] = $value;
            if ($error) {
                $errors["form.{$field}"] = $error;
            }
        }
        foreach (CheckGoldenJobs::REQUIRED_WHEN_REVIEWED as $field) {
            if ($label[$field] === null) {
                $errors["form.{$field}"] = 'Wajib diisi sebelum ditandai direview.';
            }
        }
        if ($errors) {
            $this->resetErrorBag();
            foreach ($errors as $key => $message) {
                $this->addError($key, $message);
            }

            return;
        }

        $this->updateRow($row['source'], $row['id'], function (array $stored) use ($label) {
            $stored['label'] = $label;
            $stored['reviewed'] = true;
            $stored['review_notes'] = trim($this->notes);
            $stored['reviewed_at'] = now()->toIso8601String();
            $stored['reviewed_by'] = Auth::id();

            return $stored;
        });

        $this->resetErrorBag();
        $this->flash = 'Tersimpan: '.$row['id'];

        if ($next) {
            $this->goToNext($row['id']);
        }
    }

    public function skip(): void
    {
        if ($this->currentId) {
            $this->goToNext($this->currentId);
        }
    }

    public function markDuplicate(int $index, bool $isDuplicate): void
    {
        $disk = Storage::disk('local');
        $path = 'golden/labels/_duplicates.json';
        $pairs = $disk->exists($path) ? json_decode($disk->get($path), true) ?? [] : [];
        if (! isset($pairs[$index])) {
            return;
        }
        $pairs[$index]['is_duplicate'] = $isDuplicate;
        $pairs[$index]['reviewed'] = true;
        $pairs[$index]['reviewed_by'] = Auth::id();
        $disk->put($path, json_encode($pairs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function render()
    {
        $rows = $this->rows();
        $filtered = $this->filtered($rows);
        $current = $this->currentRow($rows);

        return view('livewire.admin.golden-set-review', [
            'rows' => $filtered,
            'current' => $current,
            'description' => $current ? $this->description($current) : '',
            'sources' => collect($rows)->groupBy('source')->map(fn ($g) => [
                'total' => $g->count(),
                'reviewed' => $g->where('reviewed', true)->count(),
            ])->sortKeys(),
            'totals' => ['all' => count($rows), 'reviewed' => collect($rows)->where('reviewed', true)->count()],
            'duplicates' => $this->duplicates(),
            'options' => [
                'employment_type' => CheckGoldenJobs::ENUMS['employment_type'],
                'work_mode' => CheckGoldenJobs::ENUMS['work_mode'],
                'seniority' => CheckGoldenJobs::ENUMS['seniority'],
                'education_min' => CheckGoldenJobs::ENUMS['education_min'],
                'sector' => array_merge(CategoryHelper::getSektors(), ['Lainnya']),
                'salary_period' => ['hour', 'day', 'week', 'month', 'year'],
            ],
            'fields' => self::FIELDS,
        ]);
    }

    // ------------------------------------------------------------------ data

    private function rows(): array
    {
        $disk = Storage::disk('local');
        $rows = [];
        foreach ($disk->files('golden/labels') as $file) {
            if (str_starts_with(basename($file), '_')) {
                continue;
            }
            foreach (json_decode($disk->get($file), true) ?? [] as $row) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    private function filtered(array $rows): array
    {
        return array_values(array_filter($rows, fn ($r) => ($this->source === '' || $r['source'] === $this->source)
            && match ($this->status) {
                'pending' => ! ($r['reviewed'] ?? false),
                'reviewed' => (bool) ($r['reviewed'] ?? false),
                default => true,
            }));
    }

    private function currentRow(?array $rows = null): ?array
    {
        if (! $this->currentId) {
            return null;
        }

        return collect($rows ?? $this->rows())->firstWhere('id', $this->currentId);
    }

    private function selectFirstIfNeeded(): void
    {
        if (! $this->currentId) {
            $this->currentId = $this->filtered($this->rows())[0]['id'] ?? null;
        }
        $this->loadForm();
    }

    private function goToNext(string $afterId): void
    {
        $ids = array_column($this->filtered($this->rows()), 'id');
        $position = array_search($afterId, $ids, true);
        $this->currentId = $position === false ? ($ids[0] ?? null) : ($ids[$position + 1] ?? $ids[0] ?? null);
        if ($this->currentId === $afterId && $this->status === 'pending') {
            $this->currentId = null;
        }
        $this->loadForm();
    }

    private function loadForm(): void
    {
        $row = $this->currentRow();
        $this->form = [];
        foreach (array_keys(self::FIELDS) as $field) {
            $this->form[$field] = $this->toFormValue($field, $row['label'][$field] ?? null);
        }
        $this->notes = (string) ($row['review_notes'] ?? '');
    }

    private function updateRow(string $source, string $id, callable $change): void
    {
        $disk = Storage::disk('local');
        $path = "golden/labels/{$source}.json";
        $rows = json_decode($disk->get($path), true) ?? [];
        foreach ($rows as $i => $row) {
            if ($row['id'] === $id) {
                $rows[$i] = $change($row);
            }
        }
        $disk->put($path, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private function duplicates(): array
    {
        $disk = Storage::disk('local');
        $path = 'golden/labels/_duplicates.json';
        if (! $disk->exists($path)) {
            return [];
        }
        $byId = collect($this->rows())->keyBy('id');

        return collect(json_decode($disk->get($path), true) ?? [])->map(fn ($pair) => $pair + [
            'items' => collect($pair['ids'])->map(fn ($id) => $byId->get($id))->filter()->values()->all(),
        ])->all();
    }

    private function description(array $row): string
    {
        $raw = json_decode(Storage::disk('local')->get("golden/raw/{$row['source']}/{$row['id']}.json") ?? '{}', true)['raw'] ?? [];
        $text = '';
        foreach (['description', 'descriptionPlain', 'content', 'jobDescription', 'job.description', 'job.qualifications'] as $key) {
            $value = Arr::get($raw, $key);
            if (is_string($value) && $value !== '') {
                $text .= "\n\n".$value;
            }
        }
        $text = html_entity_decode(html_entity_decode($text));
        $text = preg_replace('#<\s*(br|/p|/li|/h\d)\s*/?>#i', "\n", $text);

        return Str::limit(trim(preg_replace("/\n{3,}/", "\n\n", strip_tags($text))), 4000);
    }

    // ------------------------------------------------------------------ value conversion

    private function toFormValue(string $field, mixed $value): string
    {
        return match (true) {
            $value === null => '',
            in_array($field, self::BOOLEAN_FIELDS, true) => $value ? '1' : '0',
            default => (string) $value,
        };
    }

    /** @return array{0: mixed, 1: ?string} value and validation error */
    private function fromFormValue(string $field, string $value): array
    {
        $value = trim($value);
        if ($value === '') {
            return [null, null];
        }
        if (in_array($field, self::BOOLEAN_FIELDS, true)) {
            return [$value === '1', null];
        }
        if (in_array($field, self::INTEGER_FIELDS, true)) {
            $digits = preg_replace('/[^\d]/', '', $value);

            return $digits === '' ? [null, 'Isi angka.'] : [(int) $digits, null];
        }
        if (in_array($field, self::DATE_FIELDS, true)) {
            return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) ? [$value, null] : [$value, 'Format YYYY-MM-DD.'];
        }
        if (isset(CheckGoldenJobs::ENUMS[$field]) && ! in_array($value, CheckGoldenJobs::ENUMS[$field], true)) {
            return [$value, 'Pilih dari daftar.'];
        }
        if ($field === 'country') {
            $value = strtoupper($value);

            return preg_match('/^[A-Z]{2}$/', $value) ? [$value, null] : [$value, 'Kode 2 huruf, contoh ID.'];
        }

        return [$value, null];
    }
}
