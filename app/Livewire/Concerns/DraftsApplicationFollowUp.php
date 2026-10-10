<?php

namespace App\Livewire\Concerns;

use App\Models\JobApplication;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait DraftsApplicationFollowUp
{
    public $showFollowUpModal = false;
    public $followUpDraft = '';
    public $followUpJobId = null;
    public $isGeneratingFollowUp = false;

    public function closeFollowUpModal()
    {
        $this->showFollowUpModal = false;
        $this->followUpJobId = null;
        $this->followUpDraft = '';
    }

    public function generateFollowUp($jobId)
    {
        $this->followUpJobId = $jobId;
        $job = JobApplication::where('id', $jobId)->where('user_id', auth()->id())->first();
        if (!$job || !$job->isGhosted()) {
            $this->closeFollowUpModal();
            return;
        }

        $this->isGeneratingFollowUp = true;

        $prompt = "CRITICAL INSTRUCTION: DO NOT WRITE A COVER LETTER. Write a highly professional follow-up email to HR. Context: The applicant applied for this position on {$job->application_date->format('d M Y')} and wants to ask about the current status. The email should politely ask for a status update. Use formal Indonesian language. Keep it concise, empathetic, and professional.";
        
        $context = [
            'experiences' => [
                [
                    'company_name' => $job->company_name,
                    'position' => $job->position,
                    'description' => "Applied on " . $job->application_date->format('d M Y') . ". Application stage: " . ($job->recruitment_stage ?: $job->application_status),
                ]
            ]
        ];

        $aiPayload = [
            'company_name' => $job->company_name,
            'job_title' => $job->position,
            'job_description' => $prompt,
            'language' => 'id',
            'tone' => 'professional',
            'length' => 'short',
            'highlight_focus' => 'Requesting status update on application',
            'candidate_context' => $context,
        ];

        try {
            $response = Http::timeout(60)->post('https://ai-analyzer-seven.vercel.app/generate-cl', $aiPayload);

            if ($response->successful()) {
                $responseBody = $response->json();
                $this->followUpDraft = $responseBody['cover_letter'] ?? $responseBody['result'] ?? '';
                // Open modal AFTER draft is ready
                $this->showFollowUpModal = true;
            } else {
                $this->followUpDraft = "Sorry, failed to generate email draft. Please try again later.";
                $this->showFollowUpModal = true;
            }
        } catch (\Exception $e) {
            Log::error('AI Follow Up Error: ' . $e->getMessage());
            $this->followUpDraft = "An error occurred while contacting the AI server.";
            $this->showFollowUpModal = true;
        }

        $this->isGeneratingFollowUp = false;
    }
}
