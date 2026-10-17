<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Smalot\PdfParser\Parser;

class ResumeParserController extends Controller
{
    private const SECTIONS = ['experiences', 'educations', 'projects', 'skills'];
    private const PARSER_VERSION = 2;

    public function importPdf(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|max:10240',
        ]);

        $file = $request->file('resume');
        if (strtolower($file->getClientOriginalExtension()) !== 'pdf') {
            return response()->json([
                'success' => false,
                'message' => __('Format file harus berupa PDF.'),
            ], 422);
        }

        $user = Auth::user();

        try {
            $path = $request->file('resume')->getRealPath();

            // Initialize Smalot PDF Parser
            $parser = new Parser();
            $pdf = $parser->parseFile($path);
            $text = $pdf->getText();

            // Strip zero-width characters and normalize text
            $text = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $text);
            $text = str_replace("\r", "", $text);
            $lines = explode("\n", $text);
            $lines = array_map('trim', $lines);
            $lines = array_values(array_filter($lines));

            $experiences = [];
            $projects = [];
            $education = [];
            $skills = [];
            $currentSection = null;

            $monthPattern = '(?:Jan(?:uary)?|Feb(?:ruary)?|Mar(?:ch)?|Apr(?:il)?|May|Jun(?:e)?|Jul(?:y)?|Aug(?:ust)?|Sep(?:tember)?|Oct(?:ober)?|Nov(?:ember)?|Dec(?:ember)?)';
            $dateRangeRegex = '/\b'.$monthPattern.'\s+\d{4}\s*-\s*(?:'.$monthPattern.'\s+\d{4}|Present)\b/i';

            // Pre-identify company names to prevent them from being appended to descriptions
            $companyIndices = [];
            for ($i = 0; $i < count($lines); $i++) {
                if (isset($lines[$i]) && preg_match($dateRangeRegex, $lines[$i])) {
                    if ($i > 0) {
                        $companyIndices[$i - 1] = true;
                    }
                }
            }

            $tempCompany = '';
            $tempPosition = '';
            $tempStart = '';
            $tempEnd = '';
            $tempCurrent = false;
            $tempBullets = [];

            for ($i = 0; $i < count($lines); $i++) {
                $line = $lines[$i];
                if (empty($line)) continue;
                $upperLine = strtoupper($line);

                // Section transitions detection
                if ($upperLine === 'WORK EXPERIENCES' || $upperLine === 'WORK EXPERIENCE' || $upperLine === 'EXPERIENCE') {
                    if ($tempPosition) {
                        $experiences[] = [
                            'company_name' => $tempCompany,
                            'position' => $tempPosition,
                            'start_date' => $tempStart,
                            'end_date' => $tempEnd,
                            'is_current' => $tempCurrent,
                            'description' => implode("\n", $tempBullets)
                        ];
                        $tempPosition = '';
                        $tempBullets = [];
                    }
                    $currentSection = 'experience';
                    continue;
                } elseif ($upperLine === 'PROJECT EXPERIENCES' || $upperLine === 'PROJECTS' || $upperLine === 'PROJECT EXPERIENCE') {
                    if ($tempPosition) {
                        $experiences[] = [
                            'company_name' => $tempCompany,
                            'position' => $tempPosition,
                            'start_date' => $tempStart,
                            'end_date' => $tempEnd,
                            'is_current' => $tempCurrent,
                            'description' => implode("\n", $tempBullets)
                        ];
                        $tempPosition = '';
                        $tempBullets = [];
                    }
                    $currentSection = 'projects';
                    continue;
                } elseif ($upperLine === 'EDUCATION' || $upperLine === 'EDUCATIONS') {
                    if ($tempPosition) {
                        $experiences[] = [
                            'company_name' => $tempCompany,
                            'position' => $tempPosition,
                            'start_date' => $tempStart,
                            'end_date' => $tempEnd,
                            'is_current' => $tempCurrent,
                            'description' => implode("\n", $tempBullets)
                        ];
                        $tempPosition = '';
                        $tempBullets = [];
                    }
                    $currentSection = 'education';
                    continue;
                } elseif ($upperLine === 'ADDITIONAL' || $upperLine === 'SKILLS' || $upperLine === 'SKILL') {
                    if ($tempPosition) {
                        $experiences[] = [
                            'company_name' => $tempCompany,
                            'position' => $tempPosition,
                            'start_date' => $tempStart,
                            'end_date' => $tempEnd,
                            'is_current' => $tempCurrent,
                            'description' => implode("\n", $tempBullets)
                        ];
                        $tempPosition = '';
                        $tempBullets = [];
                    }
                    $currentSection = 'skills';
                    continue;
                }

                if ($currentSection === 'experience') {
                    if (preg_match($dateRangeRegex, $line, $matches)) {
                        if ($tempPosition) {
                            $experiences[] = [
                                'company_name' => $tempCompany,
                                'position' => $tempPosition,
                                'start_date' => $tempStart,
                                'end_date' => $tempEnd,
                                'is_current' => $tempCurrent,
                                'description' => implode("\n", $tempBullets)
                            ];
                            $tempBullets = [];
                        }

                        $dateRange = $matches[0];
                        $posPart = trim(str_replace($dateRange, '', $line));
                        $tempPosition = $posPart;
                        $tempCompany = isset($lines[$i - 1]) && !$this->isSectionHeading($lines[$i - 1]) ? $lines[$i - 1] : '';

                        $dates = explode('-', $dateRange);
                        $startStr = trim($dates[0]);
                        $endStr = isset($dates[1]) ? trim($dates[1]) : 'Present';

                        $tempStart = date('Y-m-d', strtotime($startStr));
                        if (strtolower($endStr) === 'present') {
                            $tempEnd = null;
                            $tempCurrent = true;
                        } else {
                            $tempEnd = date('Y-m-d', strtotime($endStr));
                            $tempCurrent = false;
                        }
                    } elseif (preg_match('/^[●○•\-]/u', $line)) {
                        $cleanBullet = preg_replace('/^[●○•\-]\s*/u', '', $line);
                        $tempBullets[] = '- ' . $cleanBullet;
                    } else {
                        if (isset($companyIndices[$i])) {
                            continue;
                        }
                        if (!empty($tempBullets)) {
                            $lastIndex = count($tempBullets) - 1;
                            $tempBullets[$lastIndex] .= "\n" . $line;
                        }
                    }
                } elseif ($currentSection === 'projects') {
                    if (preg_match('/^[●○•\-]/u', $line)) {
                        if (!empty($projects)) {
                            $lastProjectIndex = count($projects) - 1;
                            $cleanBullet = preg_replace('/^[●○•\-]\s*/u', '', $line);
                            $projects[$lastProjectIndex]['description'][] = '- ' . $cleanBullet;
                        }
                    } else {
                        $isNewProject = false;
                        if (preg_match('/^[a-z]/', $line)) {
                            $isNewProject = false;
                        } elseif (empty($projects)) {
                            $isNewProject = true;
                        } else {
                            $lastProj = $projects[count($projects) - 1];
                            if (!empty($lastProj['description'])) {
                                $nextLine = '';
                                for ($j = $i + 1; $j < count($lines); $j++) {
                                    if (isset($lines[$j]) && !empty($lines[$j])) {
                                        $nextLine = $lines[$j];
                                        break;
                                    }
                                }
                                if (preg_match('/^[●○•\-]/u', $nextLine)) {
                                    $isNewProject = true;
                                }
                            }
                        }

                        if ($isNewProject) {
                            $projects[] = [
                                'name' => $line,
                                'description' => []
                            ];
                        } else {
                            if (!empty($projects)) {
                                $lastProjIdx = count($projects) - 1;
                                if (!empty($projects[$lastProjIdx]['description'])) {
                                    $lastBulletIdx = count($projects[$lastProjIdx]['description']) - 1;
                                    $projects[$lastProjIdx]['description'][$lastBulletIdx] .= "\n" . $line;
                                } else {
                                    $projects[$lastProjIdx]['name'] .= ' ' . $line;
                                }
                            }
                        }
                    }
                } elseif ($currentSection === 'education') {
                    if (str_contains($line, 'University') || str_contains($line, 'Institute') || str_contains($line, 'School') || str_contains($upperLine, 'TELKOM')) {
                        $degree = '';
                        $year = '';
                        $consumed = 0;
                        for ($offset = 1; $offset <= 2; $offset++) {
                            $next = $lines[$i + $offset] ?? '';
                            if ($next === '' || $this->isSectionHeading($next)) break;
                            if (preg_match('/\b\d{4}\b/', $next)) $year = $next;
                            elseif ($degree === '') $degree = $next;
                            else break;
                            $consumed++;
                        }

                        $education[] = [
                            'institution' => $line,
                            'degree' => $degree,
                            'year' => $year
                        ];
                        $i += $consumed;
                    }
                } elseif ($currentSection === 'skills') {
                    $cleanLine = preg_replace('/^[●○•\-\s○]/u', '', $line);
                    if (str_contains($cleanLine, ':')) {
                        $parts = explode(':', $cleanLine);
                        $cleanLine = isset($parts[1]) ? trim($parts[1]) : $cleanLine;
                    }

                    if (str_contains($cleanLine, ',')) {
                        $parts = explode(',', $cleanLine);
                        foreach ($parts as $p) {
                            $skill = trim($p);
                            if (strlen($skill) > 1 && strlen($skill) < 40 && !preg_match('/(email|phone|website|address|contact|Technical Skills)/i', $skill)) {
                                $skills[] = $skill;
                            }
                        }
                    } else {
                        $skill = trim($cleanLine);
                        if (strlen($skill) > 1 && strlen($skill) < 40 && !preg_match('/(email|phone|website|address|contact|Technical Skills)/i', $skill)) {
                            $skills[] = $skill;
                        }
                    }
                }
            }

            // Commit final work experience if exists
            if ($tempPosition) {
                $experiences[] = [
                    'company_name' => $tempCompany,
                    'position' => $tempPosition,
                    'start_date' => $tempStart,
                    'end_date' => $tempEnd,
                    'is_current' => $tempCurrent,
                    'description' => implode("\n", $tempBullets)
                ];
            }

            $data = array_fill_keys(self::SECTIONS, []);

            // Save parsed Experiences
            foreach ($experiences as $index => $exp) {
                $data['experiences'][] = [
                    'position' => substr($exp['position'], 0, 100),
                    'company_name' => substr($exp['company_name'], 0, 100),
                    'location' => null,
                    'start_date' => $exp['start_date'],
                    'end_date' => $exp['end_date'],
                    'is_current' => $exp['is_current'],
                    'description' => $exp['description'],
                    'display_order' => $index + 1
                ];
            }

            // Save parsed Projects
            foreach ($projects as $index => $proj) {
                $cleanedName = $proj['name'];
                if (str_contains($cleanedName, ' | ')) {
                    $parts = explode(' | ', $cleanedName);
                    $cleanedName = trim($parts[0]);
                }

                $data['projects'][] = [
                    'project_name' => substr($cleanedName, 0, 100),
                    'role' => null,
                    'start_date' => null,
                    'is_ongoing' => false,
                    'description' => implode("\n", $proj['description']),
                    'display_order' => $index + 1
                ];
            }

            // Save parsed Educations
            foreach ($education as $index => $edu) {
                $startEduDate = null;
                $endEduDate = null;
                $isEduCurrent = false;

                if (preg_match($dateRangeRegex, $edu['year'], $eduYearMatches)) {
                    [$start, $end] = array_map('trim', explode('-', $eduYearMatches[0], 2));
                    $startEduDate = date('Y-m-d', strtotime($start));
                    if (strtolower($end) === 'present') {
                        $isEduCurrent = true;
                    } else {
                        $endEduDate = date('Y-m-d', strtotime($end));
                    }
                } elseif (preg_match('/\bPresent\b/i', $edu['year'])) {
                    $isEduCurrent = true;
                }

                $data['educations'][] = [
                    'institution_name' => substr($edu['institution'], 0, 100),
                    'degree' => substr($edu['degree'], 0, 100),
                    'major' => '',
                    'start_date' => $startEduDate,
                    'end_date' => $endEduDate,
                    'is_current' => $isEduCurrent,
                    'display_order' => $index + 1
                ];
            }

            // Save parsed Skills
            $skills = array_unique($skills);
            foreach (array_slice($skills, 0, 12) as $index => $skillName) {
                $data['skills'][] = [
                    'skill_name' => substr($skillName, 0, 50),
                    'category' => null,
                    'proficiency_level' => null,
                    'years_of_experience' => null,
                    'display_order' => $index + 1
                ];
            }

            if (!array_filter($data)) {
                return response()->json(['success' => false,
                    'message' => __('Tidak ada bagian CV yang berhasil dibaca. Data lama tetap aman. Gunakan PDF dengan teks yang dapat dipilih atau isi CV secara manual.'),
                ], 422);
            }

            if (!$this->validImportData($data)) {
                return response()->json(['success' => false,
                    'message' => __('Hasil pembacaan CV belum valid. Data lama tidak diubah. Periksa isi PDF atau masukkan data secara manual.'),
                ], 422);
            }

            $snapshot = $this->snapshot($user);
            $token = Crypt::encryptString(json_encode([
                'parser_version' => self::PARSER_VERSION,
                'user_id' => $user->id, 'expires_at' => now()->addMinutes(15)->timestamp,
                'baseline' => $snapshot['hash'], 'data' => $data,
            ], JSON_THROW_ON_ERROR));

            return response()->json([
                'success' => true,
                'stage' => 'preview', 'data' => $data, 'token' => $token,
                'existing_counts' => $snapshot['counts'],
                'education_date_hints' => array_column($education, 'year'),
                'message' => __('Periksa hasil pembacaan sebelum mengganti data. Bagian yang tidak terbaca akan tetap dipertahankan.'),
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => __('Gagal membaca file PDF. Data lama tidak diubah. Coba PDF lain atau isi CV secara manual.'),
            ], 500);
        }
    }

    public function confirmImport(Request $request)
    {
        $request->validate(['token' => 'required|string|max:2000000', 'confirm_replace' => 'required|accepted']);
        $user = Auth::user();

        try {
            $draft = json_decode(Crypt::decryptString($request->input('token')), true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($draft) || ($draft['user_id'] ?? null) !== $user->id
                || ($draft['parser_version'] ?? null) !== self::PARSER_VERSION
                || ($draft['expires_at'] ?? 0) <= now()->timestamp
                || !is_string($draft['baseline'] ?? null)
                || !$this->validImportData($draft['data'] ?? [])) {
                throw new \UnexpectedValueException('Invalid import preview.');
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false,
                'message' => __('Preview tidak valid atau sudah kedaluwarsa. Unggah PDF kembali. Data lama tidak diubah.'),
            ], 422);
        }

        $completionRules = [];
        foreach ($draft['data']['educations'] as $index => $entry) {
            if (!$entry['start_date']) $completionRules['education_start_dates.'.$index] = 'required|date_format:Y-m-d';
        }
        $completion = Validator::make($request->all(), $completionRules);
        if ($completion->fails()) {
            return response()->json(['success' => false, 'requires_completion' => true,
                'message' => __('Lengkapi tanggal mulai pendidikan yang tidak terbaca sebelum menyimpan. Data lama tidak diubah.'),
            ], 422);
        }
        foreach ($draft['data']['educations'] as $index => &$entry) {
            if (!$entry['start_date']) $entry['start_date'] = $request->input('education_start_dates.'.$index);
        }
        unset($entry);

        try {
            return DB::transaction(function () use ($user, $draft) {
                // Serialize confirmations for this user; do not hold a lock while parsing PDFs.
                $user->newQuery()->whereKey($user->id)->lockForUpdate()->firstOrFail();
                if (!hash_equals($draft['baseline'], $this->snapshot($user, true)['hash'])) {
                    return response()->json(['success' => false,
                        'message' => __('Data CV berubah setelah preview dibuat. Unggah PDF kembali agar perubahan terbaru tidak tertimpa.'),
                    ], 409);
                }

                foreach (self::SECTIONS as $section) {
                    if (empty($draft['data'][$section])) continue;
                    $user->{$section}()->delete();
                    foreach ($draft['data'][$section] as $entry) {
                        $user->{$section}()->create($entry);
                    }
                }

                return response()->json(['success' => true, 'stage' => 'saved',
                    'message' => __('Impor CV berhasil disimpan. Bagian yang tidak terbaca tetap dipertahankan.'),
                ]);
            });
        } catch (\Exception $e) {
            report($e);
            return response()->json(['success' => false,
                'message' => __('Impor gagal disimpan. Seluruh perubahan dibatalkan dan data CV sebelumnya tetap utuh.'),
            ], 500);
        }
    }

    private function snapshot($user, bool $lock = false): array
    {
        $rows = [];
        $counts = [];
        foreach (self::SECTIONS as $section) {
            $query = $user->{$section}()->orderBy('id');
            if ($lock) $query->lockForUpdate();
            $rows[$section] = $query->get()->map(fn ($entry) => $entry->getAttributes())->all();
            $counts[$section] = count($rows[$section]);
        }

        return ['hash' => hash('sha256', json_encode($rows, JSON_THROW_ON_ERROR)), 'counts' => $counts];
    }

    private function validImportData(array $data): bool
    {
        if (!array_filter($data) || array_keys($data) !== self::SECTIONS) return false;

        return !Validator::make($data, [
            'experiences' => 'present|array|max:100', 'educations' => 'present|array|max:100',
            'projects' => 'present|array|max:100', 'skills' => 'present|array|max:12',
            'experiences.*.position' => 'required|string|max:100',
            'experiences.*.company_name' => 'nullable|string|max:100',
            'experiences.*.start_date' => 'required|date_format:Y-m-d',
            'experiences.*.end_date' => 'nullable|date_format:Y-m-d',
            'experiences.*.description' => 'nullable|string|max:20000',
            'projects.*.project_name' => 'required|string|max:100',
            'projects.*.start_date' => 'nullable|date_format:Y-m-d',
            'projects.*.description' => 'nullable|string|max:20000',
            'educations.*.institution_name' => 'required|string|max:100',
            'educations.*.degree' => 'nullable|string|max:100',
            'educations.*.start_date' => 'nullable|date_format:Y-m-d',
            'educations.*.end_date' => 'nullable|date_format:Y-m-d',
            'skills.*.skill_name' => 'required|string|max:50',
        ])->fails();
    }

    private function isSectionHeading(string $line): bool
    {
        return in_array(strtoupper($line), ['WORK EXPERIENCES', 'WORK EXPERIENCE', 'EXPERIENCE',
            'PROJECT EXPERIENCES', 'PROJECTS', 'PROJECT EXPERIENCE', 'EDUCATION', 'EDUCATIONS',
            'ADDITIONAL', 'SKILLS', 'SKILL'], true);
    }
}
