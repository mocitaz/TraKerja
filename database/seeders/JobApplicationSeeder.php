<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobApplication;
use App\Models\User;
use Carbon\Carbon;

class JobApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (assuming there's at least one user)
        $user = User::first();
        
        if (!$user) {
            $this->command->info('No user found. Please create a user first.');
            return;
        }

        $dummyData = [
            // Recent applications with various statuses
            [
                'company_name' => 'PT Bank Tabungan Negara (Persero)',
                'position' => 'Officer Development Program',
                'location' => 'Jakarta Pusat, DKI Jakarta',
                'platform' => 'Website Company',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'HR - Interview',
                'career_level' => 'Full Time',
                'platform_link' => 'https://btn.co.id/careers',
                'application_date' => Carbon::now()->subDays(2),
                'notes' => 'Very interested in this position. Good company culture.'
            ],
            [
                'company_name' => 'Tokopedia',
                'position' => 'Frontend Developer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'LinkedIn',
                'status' => 'Interview',
                'application_status' => 'On Process',
                'recruitment_stage' => 'User - Interview',
                'career_level' => 'Full Time',
                'platform_link' => 'https://linkedin.com/jobs/view/123456',
                'application_date' => Carbon::now()->subDays(5),
                'notes' => 'Technical interview scheduled for next week.'
            ],
            [
                'company_name' => 'Gojek',
                'position' => 'Data Analyst',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'JobStreet',
                'status' => 'Applied',
                'application_status' => 'Declined',
                'recruitment_stage' => 'Not Processed',
                'career_level' => 'Full Time',
                'platform_link' => 'https://jobstreet.co.id/job/123456',
                'application_date' => Carbon::now()->subDays(7),
                'notes' => 'Did not meet the requirements. Will try again next year.'
            ],
            [
                'company_name' => 'Shopee',
                'position' => 'Product Manager',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'LinkedIn',
                'status' => 'Accepted',
                'application_status' => 'Accepted',
                'recruitment_stage' => 'Offering',
                'career_level' => 'Full Time',
                'platform_link' => 'https://linkedin.com/jobs/view/789012',
                'application_date' => Carbon::now()->subDays(10),
                'notes' => 'Got the offer! Starting next month.'
            ],
            [
                'company_name' => 'Traveloka',
                'position' => 'Backend Developer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'Website Company',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Assessment Test',
                'career_level' => 'Full Time',
                'platform_link' => 'https://traveloka.com/careers',
                'application_date' => Carbon::now()->subDays(3),
                'notes' => 'Completed the coding test. Waiting for results.'
            ],
            [
                'company_name' => 'Grab',
                'position' => 'UX Designer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'LinkedIn',
                'status' => 'Interview',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Presentation Round',
                'career_level' => 'Full Time',
                'platform_link' => 'https://linkedin.com/jobs/view/345678',
                'application_date' => Carbon::now()->subDays(8),
                'notes' => 'Portfolio review completed. Presentation next week.'
            ],
            [
                'company_name' => 'Bukalapak',
                'position' => 'Marketing Specialist',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'JobStreet',
                'status' => 'Applied',
                'application_status' => 'Declined',
                'recruitment_stage' => 'Not Processed',
                'career_level' => 'Full Time',
                'platform_link' => 'https://jobstreet.co.id/job/456789',
                'application_date' => Carbon::now()->subDays(12),
                'notes' => 'Position was filled internally.'
            ],
            [
                'company_name' => 'OVO',
                'position' => 'Mobile Developer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'Website Company',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Psychotest',
                'career_level' => 'Full Time',
                'platform_link' => 'https://ovo.id/careers',
                'application_date' => Carbon::now()->subDays(4),
                'notes' => 'Psychotest scheduled for tomorrow.'
            ],
            [
                'company_name' => 'Dana',
                'position' => 'DevOps Engineer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'LinkedIn',
                'status' => 'Interview',
                'application_status' => 'On Process',
                'recruitment_stage' => 'LGD',
                'career_level' => 'Full Time',
                'platform_link' => 'https://linkedin.com/jobs/view/567890',
                'application_date' => Carbon::now()->subDays(6),
                'notes' => 'Group discussion round completed. Waiting for next stage.'
            ],
            [
                'company_name' => 'Blibli',
                'position' => 'Business Analyst',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'JobStreet',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Follow Up',
                'career_level' => 'Full Time',
                'platform_link' => 'https://jobstreet.co.id/job/678901',
                'application_date' => Carbon::now()->subDays(1),
                'notes' => 'Just applied. Waiting for response.'
            ],
            // Additional data for better chart visualization
            [
                'company_name' => 'Microsoft Indonesia',
                'position' => 'Software Engineer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'LinkedIn',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'HR - Interview',
                'career_level' => 'Full Time',
                'platform_link' => 'https://linkedin.com/jobs/view/111222',
                'application_date' => Carbon::now()->subDays(9),
                'notes' => 'First round interview completed successfully.'
            ],
            [
                'company_name' => 'Google Indonesia',
                'position' => 'Product Manager',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'Website Company',
                'status' => 'Applied',
                'application_status' => 'Declined',
                'recruitment_stage' => 'Not Processed',
                'career_level' => 'Full Time',
                'platform_link' => 'https://careers.google.com',
                'application_date' => Carbon::now()->subDays(15),
                'notes' => 'Very competitive position. Will try again with more experience.'
            ],
            [
                'company_name' => 'Facebook Indonesia',
                'position' => 'Data Scientist',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'LinkedIn',
                'status' => 'Interview',
                'application_status' => 'On Process',
                'recruitment_stage' => 'User - Interview',
                'career_level' => 'Full Time',
                'platform_link' => 'https://linkedin.com/jobs/view/333444',
                'application_date' => Carbon::now()->subDays(11),
                'notes' => 'Technical interview with the team lead.'
            ],
            [
                'company_name' => 'Amazon Indonesia',
                'position' => 'Cloud Solutions Architect',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'Website Company',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Assessment Test',
                'career_level' => 'Full Time',
                'platform_link' => 'https://amazon.jobs',
                'application_date' => Carbon::now()->subDays(13),
                'notes' => 'AWS certification required. Studying for it.'
            ],
            [
                'company_name' => 'Netflix Indonesia',
                'position' => 'Content Manager',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'JobStreet',
                'status' => 'Applied',
                'application_status' => 'Accepted',
                'recruitment_stage' => 'Offering',
                'career_level' => 'Full Time',
                'platform_link' => 'https://jobstreet.co.id/job/555666',
                'application_date' => Carbon::now()->subDays(20),
                'notes' => 'Amazing opportunity! Starting next quarter.'
            ],
            // Intern positions for career level diversity
            [
                'company_name' => 'StartupXYZ',
                'position' => 'Marketing Intern',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'LinkedIn',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'HR - Interview',
                'career_level' => 'Intern',
                'platform_link' => 'https://linkedin.com/jobs/view/777888',
                'application_date' => Carbon::now()->subDays(5),
                'notes' => 'Great learning opportunity for fresh graduate.'
            ],
            [
                'company_name' => 'TechCorp',
                'position' => 'Management Trainee',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'Website Company',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Assessment Test',
                'career_level' => 'MT',
                'platform_link' => 'https://techcorp.com/careers',
                'application_date' => Carbon::now()->subDays(7),
                'notes' => 'Management trainee program for 2 years.'
            ],
            [
                'company_name' => 'FreelanceHub',
                'position' => 'Web Developer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'LinkedIn',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Follow Up',
                'career_level' => 'Freelance',
                'platform_link' => 'https://linkedin.com/jobs/view/999000',
                'application_date' => Carbon::now()->subDays(3),
                'notes' => 'Remote work opportunity. Flexible schedule.'
            ],
            [
                'company_name' => 'ContractCorp',
                'position' => 'UI/UX Designer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'JobStreet',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Presentation Round',
                'career_level' => 'Contract',
                'platform_link' => 'https://jobstreet.co.id/job/111222',
                'application_date' => Carbon::now()->subDays(6),
                'notes' => '6-month contract with possibility of extension.'
            ],
            [
                'company_name' => 'ConsultingFirm',
                'position' => 'Business Consultant',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'Website Company',
                'status' => 'Applied',
                'application_status' => 'Declined',
                'recruitment_stage' => 'Not Processed',
                'career_level' => 'Full Time',
                'platform_link' => 'https://consultingfirm.com/careers',
                'application_date' => Carbon::now()->subDays(14),
                'notes' => 'Requires more consulting experience.'
            ]
        ];

        foreach ($dummyData as $data) {
            JobApplication::create([
                'user_id' => $user->id,
                'company_name' => $data['company_name'],
                'position' => $data['position'],
                'location' => $data['location'],
                'platform' => $data['platform'],
                'status' => $data['status'],
                'application_status' => $data['application_status'],
                'recruitment_stage' => $data['recruitment_stage'],
                'career_level' => $data['career_level'],
                'platform_link' => $data['platform_link'],
                'application_date' => $data['application_date'],
                'notes' => $data['notes'],
            ]);
        }

        $this->command->info('Created ' . count($dummyData) . ' dummy job applications.');
    }
}