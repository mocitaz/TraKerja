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

    public function __construct(private AiGateway $ai) {}

    /** @return array{headline: string, highlights: array<int, string>, captions: array<string, string>, caption: string, ai: bool} */
    public function write(Vacancy $v): array
    {
        $v->loadMissing(['company', 'region', 'sector']);
        // Copywriting needs more nuance than data clean-up: use the stronger fallback model (~$0.001 per run).
        $answer = $this->ai->structured('promo_copy_multi', self::INSTRUCTIONS, $this->brief($v), self::SCHEMA, [], true);
        $ai = is_array($answer) && ! empty($answer['captions']['threads']);

        $captions = [];
        foreach (self::PLATFORMS as $key => $meta) {
            $text = $ai ? self::cleanCaption((string) ($answer['captions'][$key] ?? '')) : '';
            $captions[$key] = self::finish($text !== '' ? $text : $this->fallbackCaption($v, $key), $v, $key);
        }
        $highlights = $ai ? array_values(array_filter(array_map(fn ($h) => Str::limit(trim((string) $h), 70, ''), (array) ($answer['highlights'] ?? [])))) : [];

        return [
            'headline' => Str::limit(trim((string) ($ai ? ($answer['headline'] ?: $v->title) : $v->title)), 80, ''),
            'highlights' => array_slice($highlights ?: $this->fallbackHighlights($v), 0, 5),
            'captions' => $captions,
            'caption' => $captions['threads'],
            'ai' => $ai,
        ];
    }

    public static function link(Vacancy $v, string $platform = 'threads'): string
    {
        return $v->public_path.'?utm_source='.$platform.'&utm_medium=social&utm_campaign=promo';
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
            'gaji' => $v->salary_label,
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
            ...array_map(fn ($s) => 'Menguasai '.$s, array_slice((array) $v->skills, 0, 3)),
            $v->seniority === 'entry' ? 'Terbuka untuk fresh graduate' : null,
        ])), 0, 5) ?: ['Lihat persyaratan lengkap di tautan'];
    }

    private function fallbackCaption(Vacancy $v, string $platform = 'threads'): string
    {
        $company = \App\Livewire\Admin\Promotions::tidyTitle((string) ($v->company?->name ?? 'perusahaan ini'));
        $title = \App\Livewire\Admin\Promotions::tidyTitle($v->title);
        $lines = array_filter([
            "💼 {$title} di {$company}",
            $v->location ? "📍 {$v->location}" : null,
            $v->salary_label ? "💰 {$v->salary_label}" : null,
            $v->valid_through ? '🗓️ Lamar sebelum '.$v->valid_through->locale('id')->translatedFormat('d M Y') : null,
        ]);

        return match ($platform) {
            'linkedin' => "{$company} sedang membuka posisi {$title}".($v->location ? " di {$v->location}" : '').".\n\n".implode("\n", $lines)."\n\nDetail dan cara melamar ada di tautan berikut.\n\n#lowongankerja #karier",
            'x' => "Loker {$title} di {$company}".($v->location ? " ({$v->location})" : '').'. #lowongankerja',
            'whatsapp' => "*Loker: {$title}*\n\n".implode("\n", $lines)."\n\nCek detail & lamar di sini:",
            'instagram' => "Info loker buat kamu! 👀\n\n".implode("\n", $lines)."\n\nLink lamar ada di bio ya 👆\n\n#lowongankerja #lokerindonesia #infoloker",
            default => "Info loker buat kamu 👀\n\n".implode("\n", $lines)."\n\nDetail & cara lamarnya ada di link bawah ya 👇\n\n#lowongankerja #lokerindonesia",
        };
    }

    private const INSTRUCTIONS = <<<'TXT'
Kamu social media copywriter TraKerja, akun info loker untuk anak muda Indonesia (fresh graduate dan pekerja muda 18–27 tahun).
Tulis materi promosi SATU lowongan untuk beberapa platform, dari data yang diberikan. Jangan pernah mengarang fakta, benefit, atau janji diterima. Jangan tulis URL.

Gaji: tulis persis seperti di data. Jangan tambah "gaji pokok", "komisi", atau "bonus" kalau tidak tertulis. Kalau sebuah data tidak ada, hapus barisnya (jangan tulis "-" atau "tidak disebutkan").
Tagar kota: tanpa "Kota"/"Kabupaten" (Surakarta = #lokersolo, Kabupaten Tangerang = #lokertangerang, remote = #lokerremote).

Gaya Gen Z (Threads, Instagram, X): santai, hangat, relatable, kayak teman spill info loker, pakai "kamu". Boleh 1–2 kata gaul yang wajar (gas, spill, cocok banget), jangan cringe. Hindari bahasa kaku: "Kami membuka kesempatan", "Bergabunglah", "Ayo bergabung", "tantangan nyata".

captions.threads (maks 380 karakter):
Hook 1 baris yang bikin berhenti scroll (boleh 1 emoji)
[baris kosong]
Info satu baris per poin dengan emoji: 💼 posisi di perusahaan · 📍 lokasi + sistem kerja · 💰 gaji · 🎓 pendidikan · 🗓️ batas lamar (hanya yang ada datanya)
[baris kosong]
CTA: "Detail & cara lamarnya ada di link bawah ya 👇"
[baris kosong]
2–3 tagar (umum, kota, bidang).

captions.instagram (maks 900 karakter): struktur sama dengan Threads tapi hook boleh 2 baris dan ada 1 baris tambahan "Cocok buat kamu yang …" berdasarkan data. CTA: "Link lamar ada di bio ya 👆" (link tidak bisa diklik di caption Instagram). Akhiri dengan 5–8 tagar relevan.

captions.linkedin (maks 900 karakter): profesional, ramah, tanpa bahasa gaul dan tanpa emoji berlebihan (boleh emoji penanda poin). Buka dengan kalimat yang menyebut perusahaan dan posisi, lalu poin info, lalu 1 kalimat tentang siapa yang cocok, lalu "Detail dan cara melamar ada di tautan berikut." Akhiri dengan 3 tagar (#lowongankerja #karier + bidang).

captions.x (maks 200 karakter, karena link ditambahkan otomatis): 1–2 kalimat super ringkas berisi posisi, perusahaan, lokasi, gaji bila ada, plus 1–2 tagar. Gaya santai.

captions.whatsapp (maks 600 karakter, untuk WhatsApp/Telegram): pakai *teks tebal* gaya WhatsApp untuk judul. Baris pertama "*Loker: posisi – perusahaan*", lalu poin info dengan emoji, lalu "Cek detail & lamar di sini:" (link ditambahkan otomatis). Tanpa tagar.

headline: nama posisi yang ringkas dan rapi (maks 60 karakter). Kalau data HURUF BESAR SEMUA, ubah ke huruf judul biasa dan pertahankan singkatan.
highlights: 3 poin kualifikasi singkat dari data (maks 60 karakter), tanpa titik di akhir.

Contoh gaya Threads (jangan disalin isinya):
Lagi cari kerja yang bisa langsung gas ketemu orang? 🚀

💼 Sales Executive di Maju Jaya
📍 Bandung · Onsite
💰 Rp4–6 jt/bulan
🗓️ Lamar sebelum 20 Des 2026

Detail & cara lamarnya ada di link bawah ya 👇

#lowongankerja #lokerbandung #lokersales
TXT;

    private const SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => ['headline', 'highlights', 'captions'],
        'properties' => [
            'headline' => ['type' => 'string'],
            'highlights' => ['type' => 'array', 'items' => ['type' => 'string']],
            'captions' => [
                'type' => 'object',
                'additionalProperties' => false,
                'required' => ['threads', 'instagram', 'linkedin', 'x', 'whatsapp'],
                'properties' => [
                    'threads' => ['type' => 'string'],
                    'instagram' => ['type' => 'string'],
                    'linkedin' => ['type' => 'string'],
                    'x' => ['type' => 'string'],
                    'whatsapp' => ['type' => 'string'],
                ],
            ],
        ],
    ];
}
