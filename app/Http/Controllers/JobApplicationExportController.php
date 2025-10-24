<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JobApplicationExportController extends Controller
{
    /**
     * Export job applications to CSV
     */
    public function exportToCsv()
    {
        // Get all job applications for the authenticated user
        $jobApplications = JobApplication::where('user_id', auth()->id())
            ->orderBy('application_date', 'desc')
            ->get();

        // Create CSV content
        $csvContent = $this->generateCsvContent($jobApplications);

        // Generate professional filename
        $userName = auth()->user()->name;
        $userName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $userName);
        $userName = substr($userName, 0, 20); // Limit length
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "TraKerja_JobApplications_{$userName}_{$timestamp}.csv";

        // Set headers for CSV download
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
     * Generate CSV content from job applications
     */
    private function generateCsvContent($jobApplications)
    {
        $output = fopen('php://temp', 'r+');
        
        // Add BOM for proper UTF-8 encoding in Excel
        fwrite($output, "\xEF\xBB\xBF");
        
        // Add metadata header
        $metadata = [
            'TraKerja - Job Application Export',
            'Generated on: ' . date('d F Y, H:i:s'),
            'Total Applications: ' . $jobApplications->count(),
            'User: ' . auth()->user()->name,
            '', // Empty line for separation
        ];
        
        foreach ($metadata as $line) {
            fputcsv($output, [$line]);
        }
        
        // CSV Headers with better organization
        $csvHeaders = [
            'No',
            'Company Name',
            'Position',
            'Location',
            'Platform',
            'Application Status',
            'Recruitment Stage',
            'Career Level',
            'Platform Link',
            'Application Date',
            'Notes',
            'Created Date',
            'Last Updated'
        ];
        
        fputcsv($output, $csvHeaders);
        
        // Add data rows with improved formatting
        foreach ($jobApplications as $index => $application) {
            $row = [
                $index + 1,
                $this->cleanText($application->company_name),
                $this->cleanText($application->position),
                $this->cleanText($application->location),
                $this->cleanText($application->platform),
                $this->cleanText($application->application_status),
                $this->cleanText($application->recruitment_stage),
                $this->cleanText($application->career_level),
                $application->platform_link,
                $application->application_date ? $application->application_date->format('d/m/Y') : 'N/A',
                $this->cleanText($application->notes),
                $application->created_at ? $application->created_at->format('d/m/Y H:i') : 'N/A',
                $application->updated_at ? $application->updated_at->format('d/m/Y H:i') : 'N/A'
            ];
            
            fputcsv($output, $row);
        }
        
        // Add summary footer
        fputcsv($output, ['']); // Empty line
        fputcsv($output, ['Export Summary:']);
        fputcsv($output, ['Total Applications', $jobApplications->count()]);
        
        // Status breakdown
        $statusCounts = $jobApplications->groupBy('application_status')->map->count();
        foreach ($statusCounts as $status => $count) {
            fputcsv($output, ['Status: ' . $status, $count]);
        }
        
        // Platform breakdown
        $platformCounts = $jobApplications->groupBy('platform')->map->count();
        fputcsv($output, ['']); // Empty line
        fputcsv($output, ['Platform Breakdown:']);
        foreach ($platformCounts as $platform => $count) {
            fputcsv($output, ['Platform: ' . $platform, $count]);
        }
        
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);
        
        return $csvContent;
    }
    
    /**
     * Clean text for CSV export
     */
    private function cleanText($text)
    {
        if (empty($text)) {
            return 'N/A';
        }
        
        // Remove or replace problematic characters
        $text = str_replace(["\r\n", "\r", "\n"], ' ', $text);
        $text = trim($text);
        
        return $text;
    }

    /**
     * Get export statistics
     */
    public function getExportStats()
    {
        $totalApplications = JobApplication::where('user_id', auth()->id())->count();
        
        $statusCounts = JobApplication::where('user_id', auth()->id())
            ->selectRaw('application_status, COUNT(*) as count')
            ->groupBy('application_status')
            ->get();

        return response()->json([
            'total_applications' => $totalApplications,
            'status_breakdown' => $statusCounts
        ]);
    }
}
