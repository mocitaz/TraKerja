<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Support\JobApplicationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class JobApplicationImportExportController extends Controller
{
    /**
     * Define expected CSV columns (matching template)
     * Required columns marked with * in comments
     */
    private const REQUIRED_COLUMNS = [
        'company_name',      // *
        'position',          // *
        'location',          // *
        'platform',          // *
        'application_status', // *
        'recruitment_stage', // *
        'career_level',      // *
        'application_date',  // *
    ];

    private const OPTIONAL_COLUMNS = [
        'platform_link',
        'notes',
        'interview_date',
        'interview_type',
        'interview_location',
        'interview_notes',
        'interview_platform',
    ];

    private const ALL_COLUMNS = [
        'company_name', 'position', 'location', 'platform',
        'application_status', 'recruitment_stage', 'career_level',
        'application_date', 'platform_link', 'notes',
        'interview_date', 'interview_type', 'interview_location', 'interview_notes', 'interview_platform'
    ];

    /**
     * Valid values for enum-like fields
     */
    private const VALID_APPLICATION_STATUS = JobApplicationStatus::OPTIONS;
    private const VALID_RECRUITMENT_STAGE = JobApplicationStatus::STAGES;
    private const VALID_CAREER_LEVEL = ['Intern', 'Full Time', 'Contract', 'MT', 'Freelance'];
    private const VALID_INTERVIEW_TYPE = \App\Support\SelectionSchedule::IMPORT_TYPES;

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $output = fopen('php://temp', 'r+');

        // Add BOM for UTF-8 encoding in Excel
        fwrite($output, "\xEF\xBB\xBF");

        // Column Headers (baris 1)
        $headers = [
            'Company Name *',
            'Position *',
            'Location *',
            'Platform *',
            'Application Status *',
            'Recruitment Stage *',
            'Career Level *',
            'Application Date *',
            'Platform Link',
            'Notes',
            'Interview Date',
            'Interview Type',
            'Interview Location',
            'Interview Notes',
            'Interview Platform'
        ];

        fputcsv($output, $headers);

        // Example Row (baris 2)
        $example = [
            'PT Contoh Perusahaan',
            'Software Engineer',
            'Jakarta',
            'LinkedIn',
            'On Process',
            'HR - Interview',
            'Full Time',
            '2024-01-15',
            'https://linkedin.com/jobs/example',
            'Contoh catatan aplikasi',
            '2024-01-20 14:00',
            'Online',
            'https://example.com/meeting',
            'Contoh instruksi seleksi',
            'Zoom'
        ];

        fputcsv($output, $example);

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        $timestamp = date('Y-m-d_H-i-s');
        $filename = "TraKerja_Import_Template_{$timestamp}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];

        return response($csvContent, 200, $headers);
    }

    /**
     * Export job applications to CSV (template format)
     */
    public function exportToCsv()
    {
        $query = JobApplication::where('user_id', Auth::id());
        if ($query->count() > \App\Services\DataExportService::QUEUE_THRESHOLD) {
            $export = app(\App\Services\DataExportService::class)->queue(Auth::user(), 'jobs');
            return redirect()->route('data-exports.show', $export->id);
        }
        return response()->streamDownload(function () use ($query) {
            $output = fopen('php://output', 'wb');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, \App\Support\JobApplicationCsv::HEADERS, ',', '"', '');
            foreach ($query->lazyById(100) as $job) {
                fputcsv($output, \App\Support\JobApplicationCsv::row($job), ',', '"', '');
            }
            fclose($output);
        }, 'TraKerja_Data_'.now()->format('Y-m-d_H-i-s').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8', 'Cache-Control' => 'private, no-store']);
    }

    /**
     * Show import form
     */
    public function showImportForm()
    {
        return view('jobs.import');
    }

    /**
     * Import job applications from CSV
     */
    public function previewImport(Request $request)
    {
        $this->authorizeImport();
        $parsed = $this->parseUpload($request);
        return response()->json([
            'valid_count' => count($parsed['rows']), 'failed_count' => count($parsed['errors']),
            'total_count' => count($parsed['rows']) + count($parsed['errors']),
            'rows' => array_slice($parsed['rows'], 0, 10), 'errors' => $parsed['errors'],
            'columns' => $parsed['columns'],
        ]);
    }

    public function importFromCsv(Request $request)
    {
        $user = Auth::user();
        if (\App\Models\Setting::isMonetizationEnabled() && !$user->isPremium()) {
            return redirect()->route('payment.premium')->with('error', __('Bulk CSV import is a Premium feature.'));
        }
        $this->authorizeImport();
        $parsed = $this->parseUpload($request);
        $now = now();
        \Illuminate\Support\Facades\DB::transaction(function () use ($parsed, $now) {
            foreach (array_chunk($parsed['rows'], 500) as $chunk) {
                $chunk = array_map(fn ($row) => $row + ['created_at' => $now, 'updated_at' => $now], $chunk);
                JobApplication::insert($chunk);
            }
        });
        $count = count($parsed['rows']);
        $summary = ['imported_count' => $count, 'failed_count' => count($parsed['errors']), 'errors' => $parsed['errors']];
        if (!$count) {
            return redirect()->back()->withErrors(['csv_file' => __('No valid rows found to import.')])
                ->with('import_summary', $summary);
        }
        return redirect()->back()->with('success', "Successfully imported {$count} job application(s).")
            ->with('import_summary', $summary);
    }

    private function authorizeImport(): void
    {
        abort_unless(Auth::check(), 401);
        abort_if(Auth::user()->isAdmin(), 403, 'Admin cannot import CSV');
        abort_if(\App\Models\Setting::isMonetizationEnabled() && !Auth::user()->isPremium(), 403, 'Bulk CSV import is a Premium feature.');
    }

    private function parseUpload(Request $request): array
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt|max:2048']);
        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');
        if (!$handle) throw ValidationException::withMessages(['csv_file' => __('Failed to read CSV file.')]);
        try {
            if (fread($handle, 3) !== "\xEF\xBB\xBF") rewind($handle);
            $headers = fgetcsv($handle, 0, ',', '"', '');
            if (!$headers) throw ValidationException::withMessages(['csv_file' => __('CSV file is empty.')]);
            $headers = array_map(fn ($header) => trim(str_replace('*', '', $header ?? '')), $headers);
            $columns = $this->normalizeHeaders($headers);
            $validation = $this->validateHeaders($columns);
            if (!$validation['valid']) throw ValidationException::withMessages(['csv_file' => $validation['message']]);
            if (count(array_unique($columns)) !== count($columns)) {
                throw ValidationException::withMessages(['csv_file' => __('CSV contains duplicate columns.')]);
            }
            $rows = []; $errors = []; $number = 1;
            while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
                $number++;
                if (!array_filter($row, fn ($cell) => trim((string) $cell) !== '')) continue;
                if (count($row) !== count($columns)) {
                    $errors[] = ['row' => $number, 'errors' => ['Column count does not match the header.']];
                    continue;
                }
                $data = array_combine($columns, $row);
                if (($data['csv_encoding'] ?? '') === 'trakerja-v1') {
                    foreach (self::ALL_COLUMNS as $column) {
                        $value = $data[$column] ?? '';
                        if (str_starts_with($value, "'") && (str_starts_with(substr($value, 1), "'") || preg_match('/^[\s]*[=+@-]/u', substr($value, 1)))) {
                            $data[$column] = substr($value, 1);
                        }
                    }
                }
                $result = $this->importRow($data, $number);
                if ($result['success']) $rows[] = $result['data'];
                else $errors[] = ['row' => $number, 'errors' => $result['errors']];
            }
            return ['rows' => $rows, 'errors' => $errors, 'columns' => $columns];
        } finally {
            fclose($handle);
        }
    }

    /**
     * Normalize header names (case insensitive, handle variations)
     */
    private function normalizeHeaders(array $headers): array
    {
        $normalized = [];

        // Mapping variations to standard column names
        $columnMapping = [
            // Required columns
            'company name' => 'company_name',
            'company' => 'company_name',
            'position' => 'position',
            'job title' => 'position',
            'location' => 'location',
            'platform' => 'platform',
            'application status' => 'application_status',
            'status' => 'application_status',
            'recruitment stage' => 'recruitment_stage',
            'stage' => 'recruitment_stage',
            'career level' => 'career_level',
            'career' => 'career_level',
            'application date' => 'application_date',
            'date' => 'application_date',
            'applied date' => 'application_date',

            // Optional columns
            'platform link' => 'platform_link',
            'link' => 'platform_link',
            'notes' => 'notes',
            'csv encoding' => 'csv_encoding',
            'interview date' => 'interview_date',
            'interview_platform' => 'interview_platform',
            'interview platform' => 'interview_platform',
            'interview_type' => 'interview_type',
            'interview type' => 'interview_type',
            'interview location' => 'interview_location',
            'interview_location' => 'interview_location',
            'interview notes' => 'interview_notes',
            'interview_notes' => 'interview_notes',
        ];

        foreach ($headers as $header) {
            $normalizedKey = strtolower(str_replace('_', ' ', trim($header)));
            // Remove special characters and normalize spaces
            $normalizedKey = preg_replace('/[^a-z0-9\s]/', '', $normalizedKey);
            $normalizedKey = preg_replace('/\s+/', ' ', trim($normalizedKey));

            if (isset($columnMapping[$normalizedKey])) {
                $normalized[] = $columnMapping[$normalizedKey];
            } else {
                // Keep original if no mapping found
                $normalized[] = $normalizedKey;
            }
        }

        return $normalized;
    }

    /**
     * Validate CSV headers match template
     */
    private function validateHeaders(array $headers): array
    {
        $missingRequired = [];

        foreach (self::REQUIRED_COLUMNS as $requiredColumn) {
            if (!in_array($requiredColumn, $headers)) {
                $missingRequired[] = $requiredColumn;
            }
        }

        if (!empty($missingRequired)) {
            $missingColumns = implode(', ', $missingRequired);
            return [
                'valid' => false,
                'message' => "Missing required columns: {$missingColumns}. Please download the template and use the correct format."
            ];
        }

        return ['valid' => true];
    }

    /**
     * Import a single row
     */
    private function importRow(array $rowData, int $rowNumber): array
    {
        $errors = [];

        // Prepare data for validation
        $data = [
            'company_name' => $rowData['company_name'] ?? '',
            'position' => $rowData['position'] ?? '',
            'location' => $rowData['location'] ?? '',
            'platform' => $rowData['platform'] ?? '',
            'application_status' => $rowData['application_status'] ?? '',
            'recruitment_stage' => $rowData['recruitment_stage'] ?? '',
            'career_level' => $rowData['career_level'] ?? '',
            'application_date' => $rowData['application_date'] ?? '',
            'platform_link' => $rowData['platform_link'] ?? null,
            'notes' => $rowData['notes'] ?? null,
            'interview_date' => $rowData['interview_date'] ?? null,
            'interview_platform' => $rowData['interview_platform'] ?? null,
            'interview_type' => $rowData['interview_type'] ?? null,
            'interview_location' => $rowData['interview_location'] ?? null,
            'interview_notes' => $rowData['interview_notes'] ?? null,
        ];

        $data = array_replace($data, JobApplicationStatus::normalize($data['application_status'], $data['recruitment_stage']));

        $limits = Validator::make($data, [
            'company_name' => 'string|max:255', 'position' => 'string|max:255',
            'location' => 'string|max:255', 'platform' => 'string|max:255',
            'platform_link' => 'nullable|url|max:255', 'interview_location' => 'nullable|string|max:255',
            'interview_type' => 'nullable|string|max:50',
            'interview_platform' => ['nullable', \Illuminate\Validation\Rule::in(\App\Support\SelectionSchedule::PLATFORMS)],
        ]);
        $errors = $limits->errors()->all();
        foreach (['notes', 'interview_notes'] as $field) {
            if (strlen($data[$field] ?? '') > 65535) $errors[] = "{$field} exceeds the database text limit.";
        }

        // Validate required fields
        if (trim($data['company_name']) === '') {
            $errors[] = 'Company Name is required';
        }
        if (trim($data['position']) === '') {
            $errors[] = 'Position is required';
        }
        if (trim($data['location']) === '') {
            $errors[] = 'Location is required';
        }
        if (trim($data['platform']) === '') {
            $errors[] = 'Platform is required';
        }
        if (empty($data['application_status'])) {
            $errors[] = 'Application Status is required';
        }
        if (empty($data['recruitment_stage'])) {
            $errors[] = 'Recruitment Stage is required';
        }
        if (empty($data['application_date'])) {
            $errors[] = 'Application Date is required';
        }

        // Validate enum values
        if (!empty($data['application_status']) && !in_array($data['application_status'], self::VALID_APPLICATION_STATUS)) {
            $errors[] = "Application Status must be one of: " . implode(', ', self::VALID_APPLICATION_STATUS);
        }
        if (!empty($data['recruitment_stage']) && !in_array($data['recruitment_stage'], self::VALID_RECRUITMENT_STAGE)) {
            $errors[] = "Recruitment Stage must be one of: " . implode(', ', self::VALID_RECRUITMENT_STAGE);
        }
        if (!empty($data['career_level']) && !in_array($data['career_level'], self::VALID_CAREER_LEVEL)) {
            $errors[] = "Career Level must be one of: " . implode(', ', self::VALID_CAREER_LEVEL);
        }
        if (!empty($data['interview_type']) && !in_array($data['interview_type'], self::VALID_INTERVIEW_TYPE)) {
            $errors[] = "Interview Type must be one of: " . implode(', ', self::VALID_INTERVIEW_TYPE);
        }

        // Parse dates
        $applicationDate = $this->parseDate($data['application_date']);
        if (!$applicationDate) {
            $errors[] = 'Application Date format is invalid. Use YYYY-MM-DD or DD/MM/YYYY';
        }

        $interviewDate = null;
        $canSchedule = \App\Support\JobApplicationStatus::canSchedule($data['recruitment_stage']);
        if ($canSchedule && !empty($data['interview_date'])) {
            $interviewDate = $this->parseDateTime($data['interview_date']);
            if (!$interviewDate) {
                $errors[] = 'Interview Date format is invalid. Use YYYY-MM-DD HH:MM or DD/MM/YYYY HH:MM';
            }
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        // Prepare data for bulk insert
        return [
            'success' => true,
            'data' => [
                'user_id' => Auth::id(),
                'company_name' => $data['company_name'],
                'position' => $data['position'],
                'location' => $data['location'],
                'platform' => $data['platform'],
                'application_status' => $data['application_status'],
                'recruitment_stage' => $data['recruitment_stage'],
                'career_level' => $data['career_level'],
                'application_date' => $applicationDate,
                'platform_link' => $data['platform_link'] ?? '',
                'notes' => $data['notes'] === '' ? null : $data['notes'],
                'interview_date' => $interviewDate,
                ... (\App\Support\SelectionSchedule::platformAvailable() ? ['interview_platform' => $canSchedule && \App\Support\SelectionSchedule::online($data['interview_type']) ? ($data['interview_platform'] ?: null) : null] : []),
                'interview_type' => !$canSchedule || $data['interview_type'] === '' ? null : $data['interview_type'],
                'interview_location' => !$canSchedule || $data['interview_location'] === '' ? null : $data['interview_location'],
                'interview_notes' => !$canSchedule || $data['interview_notes'] === '' ? null : $data['interview_notes'],
                'status' => $data['application_status'], // For backward compatibility
            ]
        ];
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($dateString): ?\Carbon\Carbon
    {
        if (empty($dateString)) {
            return null;
        }

        // Try different date formats
        $formats = [
            'Y-m-d',        // 2024-01-15
            'd/m/Y',        // 15/01/2024
            'd-m-Y',        // 15-01-2024
            'Y/m/d',        // 2024/01/15
        ];

        foreach ($formats as $format) {
            try {
                $date = \Carbon\Carbon::createFromFormat('!'.$format, trim($dateString));
                if ($date && $date->format($format) === trim($dateString)) {
                    return $date;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }

    /**
     * Parse datetime from various formats
     */
    private function parseDateTime($dateTimeString): ?\Carbon\Carbon
    {
        if (empty($dateTimeString)) {
            return null;
        }

        // Try different datetime formats
        $formats = [
            'Y-m-d H:i',        // 2024-01-20 14:00
            'Y-m-d H:i:s',      // 2024-01-20 14:00:00
            'd/m/Y H:i',        // 20/01/2024 14:00
            'd/m/Y H:i:s',      // 20/01/2024 14:00:00
            'd-m-Y H:i',        // 20-01-2024 14:00
            'Y/m/d H:i',        // 2024/01/20 14:00
        ];

        foreach ($formats as $format) {
            try {
                $date = \Carbon\Carbon::createFromFormat('!'.$format, trim($dateTimeString));
                if ($date && $date->format($format) === trim($dateTimeString)) {
                    return $date;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }

    /**
     * Clean text for CSV export
     */
    private function cleanText($text)
    {
        if (empty($text)) {
            return '';
        }

        // Remove or replace problematic characters
        $text = str_replace(["\r\n", "\r", "\n"], ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text); // Replace multiple spaces with single space
        $text = trim($text);

        // Prevent CSV/Excel Injection (formula injection)
        if (preg_match('/^[=\-+\@\t\r\n]/', $text)) {
            $text = "'" . $text;
        }

        // Remove any remaining control characters except tab
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);

        return $text;
    }

    /**
     * Return import error response
     */
    private function importError(string $message)
    {
        return redirect()->back()
            ->withErrors(['csv_file' => $message])
            ->with('download_template', true);
    }
}
