<?php

namespace App\JobEngine\Promotion;

use App\JobEngine\Ai\AiGateway;
use App\Models\Vacancy;
use Illuminate\Support\Str;

/**
 * Copy for a job promotion on social media (TraKerja 2.0): a short headline for the pamphlet and one
 * caption per platform, each within that platform's limit and tone. One AI call; rule-based fallback.
 */
class PromoWriter
{
    /** Threads' limit, kept for callers that only deal with Threads. */
    public const CAPTION_LIMIT = 500;

    /**
     * label, character limit, how the link is added ('url' = appended, 'bio' = "link in bio"), characters the
     * platform counts for a URL (X shortens every link to 23).
     */
    public const PLATFORMS = [
        'threads' => ['Threads', 500, 'url', null],
        'instagram' => ['Instagram', 2200, 'bio', null],
        'linkedin' => ['LinkedIn', 3000, 'url', null],
        'x' => ['X', 280, 'url', 23],
        'whatsapp' => ['WhatsApp / Telegram', 1500, 'url', null],
    ];

    /** One-letter platform codes used in short links (trakerja.com/l/{id}/{code}). */
    public const SHORT_CODES = ['t' => 'threads', 'i' => 'instagram', 'l' => 'linkedin', 'x' => 'x', 'w' => 'whatsapp'];

    public function __construct(private AiGateway $ai) {}

    /** @return array{headline: string, highlights: array<int, string>, captions: array<string, string>, caption: string, ai: bool} */
    public function write(Vacancy $v): array
    {
        $v->loadMissing(['company', 'region', 'sector']);
        // AI only writes the role description in plain, professional Indonesian; the facts (location, salary,
        // deadline) and the layout of each caption come from the data and fixed templates, so every post looks
        // the same and nothing is invented. Uses the stronger fallback model (~$0.001 per run).
        $answer = $this->ai->structured('promo_copy_multi', self::INSTRUCTIONS, $this->brief($v), self::SCHEMA, [], true);
        $ai = is_array($answer) && trim((string) ($answer['intro'] ?? '')) !== '';
        $copy = $ai ? $this->sanitize($answer) : $this->fallbackCopy($v);

        $captions = [];
        foreach (array_keys(self::PLATFORMS) as $key) {
            $captions[$key] = $this->compose($v, $key, $copy);
        }

        return [
            'headline' => Str::limit(trim((string) ($ai && ! empty($answer['headline']) ? $answer['headline'] : $v->title)), 80, ''),
            'highlights' => array_slice(($ai ? $copy['qualifications'] : []) ?: $this->fallbackHighlights($v), 0, 5),
            'captions' => $captions,
            'caption' => $captions['threads'],
            'ai' => $ai,
        ];
    }

    /** Short link such as https://trakerja.com/l/158/t; it redirects to the job page with UTM tags for the platform. */
    public static function link(Vacancy $v, string $platform = 'threads'): string
    {
        $code = array_search($platform, self::SHORT_CODES, true) ?: 't';

        return route('loker.short', ['id' => $v->id, 'platform' => $code]);
    }

    /** Fit a caption to its platform: limit, link handling and the right UTM source. */
    public static function finish(string $caption, Vacancy $v, string $platform): string
    {
        [, $limit, $mode, $urlCost] = self::PLATFORMS[$platform] ?? self::PLATFORMS['threads'];
        if ($mode === 'bio') {
            return self::withLink($caption, '', $limit);
        }

        return self::withLink($caption, self::link($v, $platform), $limit, $urlCost);
    }

    /** Keep the caption inside the limit with the link (if any) intact on the last line and hashtags kept. */
    public static function withLink(string $caption, string $link, int $limit = self::CAPTION_LIMIT, ?int $urlCost = null): string
    {
        $caption = preg_replace('/https?:\/\/\S+/u', '', $caption);
        $room = $limit - ($link === '' ? 0 : ($urlCost ?? mb_strlen($link)) + 2); // two newlines before the link
        $caption = trim($caption);
        if (mb_strlen($caption) > $room) {
            // Shorten the body at a sentence or word boundary and keep the trailing hashtags.
            preg_match('/((?:\s*#[\p{L}\p{N}_]+)+)\s*$/u', $caption, $m);
            $tags = trim($m[1] ?? '');
            $body = trim($tags !== '' ? mb_substr($caption, 0, mb_strlen($caption) - mb_strlen($m[0])) : $caption);
            $max = $room - ($tags !== '' ? mb_strlen($tags) + 1 : 0) - 1;
            $cut = mb_substr($body, 0, max(0, $max));
            $sentence = max(mb_strrpos($cut, '. ') ?: 0, mb_strrpos($cut, '! ') ?: 0);
            $body = $sentence > $max * .6 ? mb_substr($cut, 0, $sentence + 1) : rtrim(mb_substr($cut, 0, mb_strrpos($cut, ' ') ?: $max)).'…';
            $caption = trim($body.($tags !== '' ? "\n".$tags : ''));
        }
        if ($link === '') {
            return $caption;
        }

        return ($caption === '' ? '' : $caption."\n\n").$link;
    }

    private function brief(Vacancy $v): string
    {
        $tidy = fn (?string $t) => $t === null ? null : \App\Livewire\Admin\Promotions::tidyTitle($t);
        $education = ['sd' => 'SD', 'smp' => 'SMP', 'sma_smk' => 'SMA/SMK', 'd3' => 'D3', 's1' => 'S1', 's2' => 'S2'];

        return json_encode(array_filter([
            'posisi' => $tidy($v->title),
            'perusahaan' => $tidy($v->company?->name),
            'lokasi' => $v->location,
            'sistem_kerja' => ['onsite' => 'Onsite', 'hybrid' => 'Hybrid', 'remote' => 'Remote'][$v->work_mode] ?? null,
            'tipe' => ['full_time' => 'Full-time', 'part_time' => 'Part-time', 'contract' => 'Kontrak', 'internship' => 'Magang', 'freelance' => 'Freelance'][$v->employment_type] ?? null,
            'level' => $v->seniority,
            'gaji' => $v->salaryLabel('id'),
            'pendidikan_min' => $education[$v->education_min] ?? null,
            'keterampilan' => $v->skills,
            'batas_lamar' => $v->valid_through?->locale('id')->translatedFormat('d M Y'),
            'ringkasan' => Str::limit((string) $v->summary, 900, ''),
        ]), JSON_UNESCAPED_UNICODE);
    }

    /** Drop info lines the model left empty ("🎓 -") and squeeze blank lines. */
    public static function cleanCaption(string $caption): string
    {
        $empty = '/^\s*\S{1,3}\s*[-–—:]?\s*(-|–|—|n\/a)?\s*$/iu';
        $unknown = '/\b(tidak|belum)\s+(disebutkan|diketahui|ada|tersedia|dicantumkan)\b/iu';
        $lines = array_filter(explode("\n", str_replace("\r", '', $caption)), fn ($l) => trim($l) === '' || str_starts_with(trim($l), '#') || (! preg_match($empty, $l) && ! preg_match($unknown, $l)));

        return trim(preg_replace("/\n{3,}/", "\n\n", implode("\n", $lines)));
    }

    private function fallbackHighlights(Vacancy $v): array
    {
        $education = ['sma_smk' => 'Pendidikan minimal SMA/SMK', 'd3' => 'Pendidikan minimal D3', 's1' => 'Pendidikan minimal S1', 's2' => 'Pendidikan minimal S2'];

        return array_slice(array_values(array_filter([
            $education[$v->education_min] ?? null,
            ...array_map(fn ($s) => 'Keterampilan '.Str::lower($s), array_slice((array) $v->skills, 0, 3)),
            $v->seniority === 'entry' ? 'Terbuka untuk fresh graduate' : null,
        ])), 0, 5) ?: ['Lihat persyaratan lengkap di tautan'];
    }

    /**
     * One caption in the house style: no slang, no emoji, facts as "•" bullets, link at the end.
     * Optional parts (role description, duties, "cocok untuk") are dropped one by one until the caption fits.
     *
     * @param  array{intro: string, responsibilities: array<int, string>, qualifications: array<int, string>, audience: string, field_tag: string}  $copy
     */
    private function compose(Vacancy $v, string $platform, array $copy): string
    {
        [, $limit, $mode, $urlCost] = self::PLATFORMS[$platform];
        $title = \App\Livewire\Admin\Promotions::tidyTitle($v->title);
        $company = \App\Livewire\Admin\Promotions::tidyTitle((string) ($v->company?->name ?: 'Perusahaan'));
        $facts = $this->facts($v);
        $bullets = fn (array $items) => implode("\n", array_map(fn ($i) => '• '.$i, $items));
        $city = $this->cityTag($v);
        $field = $copy['field_tag'];

        $drops = [[], ['responsibilities'], ['responsibilities', 'audience'], ['responsibilities', 'audience', 'intro'], ['responsibilities', 'audience', 'intro', 'qualifications']];
        foreach ($drops as $drop) {
            $c = array_merge($copy, array_fill_keys($drop, []));
            $intro = is_string($c['intro']) ? $c['intro'] : '';
            $audience = is_string($c['audience']) ? $c['audience'] : '';
            $duties = (array) $c['responsibilities'];
            $quals = (array) $c['qualifications'];

            $parts = match ($platform) {
                'linkedin' => [
                    "{$company} sedang membuka posisi {$title}".($v->work_mode === 'remote' ? ' (remote)' : ($v->location ? " untuk penempatan {$v->location}" : '')).'.',
                    $intro,
                    $duties ? "Tanggung jawab utama:\n".$bullets($duties) : '',
                    $quals ? "Kualifikasi:\n".$bullets($quals) : '',
                    $facts ? "Detail posisi:\n".$bullets($facts) : '',
                    $audience,
                    "Informasi lengkap dan cara melamar:\n{{link}}",
                    $this->tags(['#lowongankerja', '#karier', $city, $field]),
                ],
                'instagram' => [
                    "Lowongan Kerja: {$title}\n{$company}",
                    $intro,
                    $facts ? $bullets($facts) : '',
                    $quals ? "Kualifikasi:\n".$bullets($quals) : '',
                    $audience,
                    'Informasi lengkap dan cara melamar tersedia melalui tautan di bio.',
                    $this->tags(['#lowongankerja', '#infoloker', '#lokerindonesia', $city, $field, '#karier']),
                ],
                'x' => [
                    "Lowongan {$title} di {$company}".($v->location ? ", {$v->location}" : '').($v->salary_min ? '. Gaji '.$v->salaryLabel('id') : '').'.',
                    $this->tags(['#lowongankerja', $city ?: $field]),
                ],
                'whatsapp' => [
                    "*Lowongan Kerja: {$title}*\n{$company}",
                    $intro,
                    $facts ? $bullets($facts) : '',
                    $quals ? "*Kualifikasi*\n".$bullets($quals) : '',
                    "Informasi lengkap dan cara melamar:\n{{link}}",
                ],
                default => [ // Threads
                    "Lowongan Kerja: {$title}\n{$company}",
                    $intro,
                    $facts ? $bullets($facts) : '',
                    "Informasi lengkap dan cara melamar:\n{{link}}",
                    $this->tags(['#lowongankerja', $city ?: $field]),
                ],
            };
            $text = trim(implode("\n\n", array_filter($parts, fn ($p) => trim((string) $p) !== '')));

            if ($mode === 'bio') {
                $out = $text;
                $length = mb_strlen($out);
            } elseif (str_contains($text, '{{link}}')) {
                $out = str_replace('{{link}}', self::link($v, $platform), $text);
                $length = mb_strlen($text) - 8 + ($urlCost ?? mb_strlen(self::link($v, $platform)));
            } else {
                $out = self::finish($text, $v, $platform); // X: link last
                $length = $urlCost ? mb_strlen($text) + 2 + $urlCost : mb_strlen($out);
            }
            if ($length <= $limit) {
                return $out;
            }
        }

        return self::finish(str_replace("Informasi lengkap dan cara melamar:\n{{link}}", '', $text), $v, $platform);
    }

    /** Fact bullets from the data only. @return array<int, string> */
    private function facts(Vacancy $v): array
    {
        $mode = ['onsite' => 'Onsite', 'hybrid' => 'Hybrid', 'remote' => 'Remote'][$v->work_mode] ?? null;
        $type = ['full_time' => 'Full-time', 'part_time' => 'Part-time', 'contract' => 'Kontrak', 'internship' => 'Magang', 'freelance' => 'Freelance'][$v->employment_type] ?? null;
        $level = ['intern' => 'Magang', 'entry' => 'Entry level', 'mid' => 'Mid level', 'senior' => 'Senior level', 'lead' => 'Lead', 'manager' => 'Manager'][$v->seniority] ?? null;
        $education = ['sd' => 'SD', 'smp' => 'SMP', 'sma_smk' => 'SMA/SMK', 'd3' => 'D3', 's1' => 'S1', 's2' => 'S2'][$v->education_min] ?? null;
        $place = $v->location ?: ($v->work_mode === 'remote' ? 'Remote' : null);

        return array_values(array_filter([
            $place ? 'Lokasi: '.$place.($mode && $mode !== $place ? " ({$mode})" : '') : null,
            ($type || $level) ? 'Tipe: '.implode(' · ', array_filter([$type, $level === 'Magang' && $type === 'Magang' ? null : $level])) : null,
            $v->salary_min ? 'Gaji: '.$v->salaryLabel('id') : null,
            $education ? 'Pendidikan: minimal '.$education : null,
            $v->valid_through ? 'Batas lamaran: '.$v->valid_through->locale('id')->translatedFormat('d F Y') : null,
        ]));
    }

    /** "#lokerbandung" from the region or location; remote jobs get "#lokerremote". */
    private function cityTag(Vacancy $v): string
    {
        if ($v->work_mode === 'remote') {
            return '#lokerremote';
        }
        $name = (string) ($v->region?->name ?: Str::before((string) $v->location, ','));
        $name = trim(preg_replace('/^(Kota Administrasi|Kota|Kabupaten|Kab\.|Provinsi|DKI|Daerah Istimewa)\s+/iu', '', $name));
        $name = ['surakarta' => 'solo', 'yogyakarta' => 'jogja', 'jakarta selatan' => 'jakarta', 'jakarta pusat' => 'jakarta', 'jakarta barat' => 'jakarta', 'jakarta timur' => 'jakarta', 'jakarta utara' => 'jakarta'][mb_strtolower($name)] ?? $name;
        $slug = preg_replace('/[^a-z0-9]/', '', Str::lower(Str::ascii($name)));

        return $slug !== '' && mb_strlen($slug) <= 20 ? '#loker'.$slug : '';
    }

    private function tags(array $tags): string
    {
        return implode(' ', array_unique(array_filter($tags)));
    }

    /** Clean the AI parts: no emoji, no exclamation marks, no slang; drop anything that breaks the house style. */
    private function sanitize(array $a): array
    {
        $clean = function ($s, int $max) {
            $s = preg_replace('/[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{FE0F}\x{200D}]/u', '', (string) $s);
            $s = trim(preg_replace('/\s+/u', ' ', str_replace('!', '.', $s)), " \t\n-•*");
            $s = preg_replace('/\.{2,}/', '.', $s);
            if ($s === '' || preg_match('/\b(gas|gaskeun|spill|kuy|yuk|guys|bestie|cuy|sob|bro|sis|buruan|jangan sampai|kesempatan emas|wajib banget)\b/iu', $s)) {
                return '';
            }

            return Str::limit($s, $max, '');
        };
        $list = fn ($items, int $max, int $n) => array_values(array_slice(array_filter(array_map(fn ($i) => rtrim($clean($i, $max), '.'), (array) $items)), 0, $n));
        $tag = Str::lower((string) ($a['field_tag'] ?? ''));

        return [
            'intro' => $clean($a['intro'] ?? '', 240),
            'responsibilities' => $list($a['responsibilities'] ?? [], 80, 3),
            'qualifications' => $list($a['qualifications'] ?? [], 70, 4),
            'audience' => $clean($a['audience'] ?? '', 140),
            'field_tag' => preg_match('/^#loker[a-z0-9]{2,20}$/', $tag) ? $tag : '',
        ];
    }

    /** Without AI: use the summary's first sentence only when it reads as Indonesian and adds something new. */
    private function fallbackCopy(Vacancy $v): array
    {
        $first = trim((string) Str::of((string) $v->summary)->before('. ')->limit(220, ''));
        $english = preg_match_all('/\b(the|and|to|is|are|with|for|of|our|you|we)\b/i', $first) >= 2;
        $skills = array_slice(array_filter(array_map('trim', (array) $v->skills)), 0, 4);

        return [
            'intro' => $first !== '' && ! $english && ! preg_match('/^(lowongan|loker|dicari)\b/iu', $first) ? rtrim($first, '.').'.' : '',
            'responsibilities' => [],
            'qualifications' => $skills ? ['Keterampilan: '.implode(', ', $skills)] : [],
            'audience' => $v->seniority === 'entry' ? 'Posisi ini terbuka untuk lulusan baru.' : '',
            'field_tag' => '',
        ];
    }

    private const INSTRUCTIONS = <<<'TXT'
Kamu editor konten rekrutmen untuk TraKerja, platform informasi lowongan kerja di Indonesia.
Tugasmu menulis bagian deskriptif sebuah unggahan lowongan. Data fakta (lokasi, gaji, tipe kerja, batas lamaran) dan format unggahan sudah disusun sistem, jadi JANGAN menulis ulang fakta tersebut.

Semua teks WAJIB berbahasa Indonesia, termasuk jika data ringkasan berbahasa Inggris (terjemahkan maknanya, jangan disalin).

Gaya bahasa:
- Bahasa Indonesia baku yang luwes dan profesional, seperti newsletter karier atau halaman karier perusahaan.
- Kalimat jelas dan ringkas, sapaan netral (boleh "kamu" seperlunya, tidak wajib).
- DILARANG: emoji, tanda seru, bahasa gaul (gas, spill, kuy, guys, bestie), kata hiperbola ("kesempatan emas", "jangan sampai ketinggalan", "buruan"), janji diterima, dan klaim yang tidak ada di data.
- Hanya gunakan informasi dari data. Jika data tidak cukup untuk sebuah bagian, isi string kosong atau array kosong.

Isi:
- headline: nama posisi yang rapi (maks 60 karakter). Jika HURUF BESAR SEMUA, ubah ke huruf judul dan pertahankan singkatan (IT, HR, PT).
- intro: 1–2 kalimat (maks 220 karakter) yang menjelaskan inti peran ini berdasarkan ringkasan, misalnya apa yang dikerjakan dan dengan siapa. Jangan dibuka dengan nama perusahaan atau "Kami".
- responsibilities: 0–3 tanggung jawab utama, masing-masing maks 70 karakter, diawali kata kerja (Mengelola, Menyusun, Melayani), tanpa titik.
- qualifications: 2–4 kualifikasi singkat dari data (pengalaman, keterampilan, sertifikasi, kemampuan bahasa), masing-masing maks 60 karakter, tanpa titik. Jangan tulis pendidikan minimal karena sudah tampil di detail posisi. Tulis sebagai frasa yang wajar, misalnya "Pengalaman minimal 2 tahun di bidang sales" atau "Terbiasa menggunakan Microsoft Excel".
- audience: 1 kalimat (maks 120 karakter) diawali "Posisi ini cocok untuk", berdasarkan level, pendidikan, atau keterampilan. Kosongkan jika tidak ada dasar datanya.
- field_tag: satu tagar bidang berformat #loker + bidang, huruf kecil tanpa spasi (contoh: #lokersales, #lokerit, #lokerakuntansi, #lokeradmin). Kosongkan jika ragu.

Contoh intro yang baik: "Bertanggung jawab memperluas jaringan pelanggan layanan internet rumah dan menjaga hubungan dengan pelanggan yang sudah ada."
Contoh yang buruk: "Siap gas jadi sales kece? 🚀 Ini kesempatan emas buat kamu!"
TXT;

    private const SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => ['headline', 'intro', 'responsibilities', 'qualifications', 'audience', 'field_tag'],
        'properties' => [
            'headline' => ['type' => 'string'],
            'intro' => ['type' => 'string'],
            'responsibilities' => ['type' => 'array', 'items' => ['type' => 'string']],
            'qualifications' => ['type' => 'array', 'items' => ['type' => 'string']],
            'audience' => ['type' => 'string'],
            'field_tag' => ['type' => 'string'],
        ],
    ];
}
