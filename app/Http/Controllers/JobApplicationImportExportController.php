<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
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
    ];

    private const ALL_COLUMNS = [
        'company_name', 'position', 'location', 'platform', 
        'application_status', 'recruitment_stage', 'career_level',
        'application_date', 'platform_link', 'notes',
        'interview_date', 'interview_type', 'interview_location', 'interview_notes'
    ];

    /**
     * Valid values for enum-like fields
     */
    private const VALID_APPLICATION_STATUS = ['On Process', 'Declined', 'Accepted'];
    private const VALID_RECRUITMENT_STAGE = [
        'Applied', 'Follow Up', 'Assessment Test', 'Psychotest',
        'HR - Interview', 'User - Interview', 'LGD',
        'Presentation Round', 'Offering', 'Not Processed'
    ];
    private const VALID_CAREER_LEVEL = ['Intern', 'Full Time', 'Contract', 'MT', 'Freelance'];
    private const VALID_INTERVIEW_TYPE = ['Phone', 'Video', 'In-person', 'Panel'];

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
            'Interview Notes'
        ];
        
        fputcsv($output, $headers);
        
        // Example Row (baris 2)
        $example = [
            'PT Contoh Perusahaan',
            'Software Engineer',
            'Jakarta',
            'LinkedIn',
            'On Process',
            'Applied',
            'Full Time',
            '2024-01-15',
            'https://linkedin.com/jobs/example',
            'Contoh catatan aplikasi',
            '2024-01-20 14:00',
            'Video',
            'Zoom Meeting',
            'Contoh catatan interview'
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
        $jobApplications = JobApplication::where('user_id', Auth::id())
            ->orderBy('application_date', 'desc')
            ->get();

        $output = fopen('php://temp', 'r+');
        
        // Add BOM for UTF-8 encoding
        fwrite($output, "\xEF\xBB\xBF");
        
        // Export Header Information
        $exportInfo = [
            ['TraKerja - Job Application Export'],
            ['Generated on: ' . date('d F Y, H:i:s')],
            ['User: ' . Auth::user()->name],
            ['Total Applications: ' . $jobApplications->count()],
            [''],
            ['EXPORT DATA:'],
            ['']
        ];
        
        foreach ($exportInfo as $line) {
            fputcsv($output, $line);
        }
        
        // Column Headers
        $headers = [
            'Company Name',
            'Position',
            'Location',
            'Platform',
            'Application Status',
            'Recruitment Stage',
            'Career Level',
            'Application Date',
            'Platform Link',
            'Notes',
            'Interview Date',
            'Interview Type',
            'Interview Location',
            'Interview Notes',
            'Created Date',
            'Last Updated'
        ];
        
        fputcsv($output, $headers);
        
        // Data rows with enhanced formatting
        foreach ($jobApplications as $index => $application) {
            $row = [
                $this->cleanText($application->company_name ?? ''),
                $this->cleanText($application->position ?? ''),
                $this->cleanText($application->location ?? ''),
                $this->cleanText($application->platform ?? ''),
                $this->cleanText($application->application_status ?? ''),
                $this->cleanText($application->recruitment_stage ?? ''),
                $this->cleanText($application->career_level ?? ''),
                $application->application_date ? $application->application_date->format('Y-m-d') : '',
                $application->platform_link ?? '',
                $this->cleanText($application->notes ?? ''),
                $application->interview_date ? $application->interview_date->format('Y-m-d H:i') : '',
                $this->cleanText($application->interview_type ?? ''),
                $this->cleanText($application->interview_location ?? ''),
                $this->cleanText($application->interview_notes ?? ''),
                $application->created_at ? $application->created_at->format('Y-m-d H:i:s') : '',
                $application->updated_at ? $application->updated_at->format('Y-m-d H:i:s') : ''
            ];
            
            fputcsv($output, $row);
        }
        
        // Export Summary
        $summary = [
            [''],
            ['EXPORT SUMMARY:'],
            ['Total Applications: ' . $jobApplications->count()]
        ];
        
        // Status breakdown
        $statusCounts = $jobApplications->groupBy('application_status')->map->count();
        if ($statusCounts->isNotEmpty()) {
            $summary[] = [''];
            $summary[] = ['Status Breakdown:'];
            foreach ($statusCounts as $status => $count) {
                $summary[] = [$status . ': ' . $count . ' applications'];
            }
        }
        
        // Platform breakdown
        $platformCounts = $jobApplications->groupBy('platform')->map->count();
        if ($platformCounts->isNotEmpty()) {
            $summary[] = [''];
            $summary[] = ['Platform Breakdown:'];
            foreach ($platformCounts as $platform => $count) {
                $summary[] = [$platform . ': ' . $count . ' applications'];
            }
        }
        
        // Career level breakdown
        $careerCounts = $jobApplications->groupBy('career_level')->map->count();
        if ($careerCounts->isNotEmpty()) {
            $summary[] = [''];
            $summary[] = ['Career Level Breakdown:'];
            foreach ($careerCounts as $level => $count) {
                $summary[] = [$level . ': ' . $count . ' applications'];
            }
        }
        
        $summary[] = [''];
        $summary[] = ['END OF EXPORT'];
        $summary[] = ['Generated by TraKerja - Your Job Application Tracker'];
        $summary[] = ['Visit: https://trakerja.com for more features'];
        
        foreach ($summary as $line) {
            fputcsv($output, $line);
        }
        
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        $userName = preg_replace('/[^a-zA-Z0-9_-]/', '_', Auth::user()->name);
        $userName = substr($userName, 0, 20);
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "TraKerja_Export_{$userName}_{$timestamp}.csv";

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
     * Show import form
     */
    public function showImportForm()
    {
        return view('jobs.import');
    }

    /**
     * Import job applications from CSV
     */
    public function importFromCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // Max 10MB
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        // Read CSV file
        $csvData = [];
        if (($handle = fopen($path, 'r')) !== false) {
            // Skip BOM if present
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }
            
            // Read headers (first line)
            $headers = fgetcsv($handle);
            
            if (!$headers) {
                return $this->importError('CSV file is empty or invalid format.');
            }
            
            // Clean headers (remove * and trim)
            $headers = array_map(function($header) {
                return trim(str_replace('*', '', trim($header)));
            }, $headers);
            
            // Normalize header names (case insensitive, handle spaces/underscores)
            $normalizedHeaders = $this->normalizeHeaders($headers);
            
            // Validate headers match template
            $validationResult = $this->validateHeaders($normalizedHeaders);
            if (!$validationResult['valid']) {
                return redirect()->back()
                    ->withErrors(['csv_file' => $validationResult['message']])
                    ->with('download_template', true);
            }
            
            // Read data rows
            $rowNumber = 1; // Starting from 1 (header is row 0)
            $importedCount = 0;
            $errorRows = [];
            
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Map CSV row to array using normalized headers
                $rowData = [];
                foreach ($normalizedHeaders as $index => $columnName) {
                    $rowData[$columnName] = isset($row[$index]) ? trim($row[$index]) : '';
                }
                
                // Validate and import row
                $result = $this->importRow($rowData, $rowNumber);
                if ($result['success']) {
                    $importedCount++;
                } else {
                    $errorRows[] = [
                        'row' => $rowNumber,
                        'errors' => $result['errors']
                    ];
                }
            }
            
            fclose($handle);
            
            if ($importedCount > 0) {
                $message = "Successfully imported {$importedCount} job application(s).";
                if (!empty($errorRows)) {
                    $message .= " " . count($errorRows) . " row(s) had errors.";
                }
                
                return redirect()->back()->with('success', $message);
            } else {
                return redirect()->back()
                    ->withErrors(['csv_file' => 'No valid rows found to import. Please check your CSV format.'])
                    ->with('download_template', true);
            }
        }
        
        return redirect()->back()
            ->withErrors(['csv_file' => 'Failed to read CSV file.'])
            ->with('download_template', true);
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
            'interview date' => 'interview_date',
            'interview_type' => 'interview_type',
            'interview type' => 'interview_type',
            'interview location' => 'interview_location',
            'interview_location' => 'interview_location',
            'interview notes' => 'interview_notes',
            'interview_notes' => 'interview_notes',
        ];
        
        foreach ($headers as $header) {
            $normalizedKey = strtolower(trim($header));
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
            'interview_type' => $rowData['interview_type'] ?? null,
            'interview_location' => $rowData['interview_location'] ?? null,
            'interview_notes' => $rowData['interview_notes'] ?? null,
        ];
        
        // Validate required fields
        if (empty($data['company_name'])) {
            $errors[] = 'Company Name is required';
        }
        if (empty($data['position'])) {
            $errors[] = 'Position is required';
        }
        if (empty($data['location'])) {
            $errors[] = 'Location is required';
        }
        if (empty($data['platform'])) {
            $errors[] = 'Platform is required';
        }
        if (empty($data['application_status'])) {
            $errors[] = 'Application Status is required';
        }
        if (empty($data['recruitment_stage'])) {
            $errors[] = 'Recruitment Stage is required';
        }
        if (empty($data['career_level'])) {
            $errors[] = 'Career Level is required';
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
        if (!empty($data['interview_date'])) {
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
        
        // Create job application
        try {
            JobApplication::create([
                'user_id' => Auth::id(),
                'company_name' => $data['company_name'],
                'position' => $data['position'],
                'location' => $data['location'],
                'platform' => $data['platform'],
                'application_status' => $data['application_status'],
                'recruitment_stage' => $data['recruitment_stage'],
                'career_level' => $data['career_level'],
                'application_date' => $applicationDate,
                'platform_link' => $data['platform_link'] ?: null,
                'notes' => $data['notes'] ?: null,
                'interview_date' => $interviewDate,
                'interview_type' => $data['interview_type'] ?: null,
                'interview_location' => $data['interview_location'] ?: null,
                'interview_notes' => $data['interview_notes'] ?: null,
                'status' => $data['application_status'], // For backward compatibility
            ]);
            
            return ['success' => true];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => ['Database error: ' . $e->getMessage()]
            ];
        }
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
                $date = \Carbon\Carbon::createFromFormat($format, trim($dateString));
                if ($date) {
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
                $date = \Carbon\Carbon::createFromFormat($format, trim($dateTimeString));
                if ($date) {
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

