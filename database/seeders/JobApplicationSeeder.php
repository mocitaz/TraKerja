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
        // Get specific user by email
        $user = User::where('email', 'luthfafiwork@gmail.com')->first();
        
        if (!$user) {
            $this->command->error('User with email luthfafiwork@gmail.com not found.');
            return;
        }

        $this->command->info('Found user: ' . $user->email . ' (ID: ' . $user->id . ')');
        $this->command->info('Creating 30 job applications...');
        
        $this->createJobApplicationsForUser($user);
        
        $this->command->info('Job applications created successfully!');
    }

    /**
     * Create job applications for a specific user
     */
    private function createJobApplicationsForUser(User $user): void
    {

        // 30 Job Applications with various statuses, platforms, and stages
        $dummyData = [
            // On Process - HR Interview (5 applications)
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
                'interview_date' => Carbon::now()->addDays(3),
                'interview_type' => 'In-person',
                'interview_location' => 'Jakarta Pusat Office',
                'notes' => 'Very interested in this position. Good company culture.',
                'is_pinned' => true
            ],
            [
                'company_name' => 'Tokopedia',
                'position' => 'Frontend Developer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'LinkedIn',
                'status' => 'Interview',
                'application_status' => 'On Process',
                'recruitment_stage' => 'HR - Interview',
                'career_level' => 'Full Time',
                'platform_link' => 'https://linkedin.com/jobs/view/123456',
                'application_date' => Carbon::now()->subDays(5),
                'interview_date' => Carbon::now()->addDays(2),
                'interview_type' => 'Video',
                'interview_location' => 'Zoom Meeting',
                'notes' => 'HR interview completed. Waiting for technical round.',
                'is_pinned' => false
            ],
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
                'interview_date' => Carbon::now()->addDays(5),
                'interview_type' => 'Video',
                'interview_location' => 'Microsoft Teams',
                'notes' => 'First round interview scheduled. Very excited!',
                'is_pinned' => true
            ],
            [
                'company_name' => 'PT Astra International',
                'position' => 'Management Trainee',
                'location' => 'Jakarta Barat, DKI Jakarta',
                'platform' => 'Website Company',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'HR - Interview',
                'career_level' => 'MT',
                'platform_link' => 'https://astra.co.id/careers',
                'application_date' => Carbon::now()->subDays(4),
                'interview_date' => Carbon::now()->addDays(4),
                'interview_type' => 'In-person',
                'interview_location' => 'Astra Tower, Jakarta',
                'notes' => 'MT program for 2 years. Great opportunity.',
                'is_pinned' => false
            ],
            [
                'company_name' => 'Shopee',
                'position' => 'Product Manager',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'Glints',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'HR - Interview',
                'career_level' => 'Full Time',
                'platform_link' => 'https://glints.com/jobs/123456',
                'application_date' => Carbon::now()->subDays(6),
                'interview_date' => Carbon::now()->addDays(1),
                'interview_type' => 'Video',
                'interview_location' => 'Google Meet',
                'notes' => 'HR screening completed. Product round next.',
                'is_pinned' => false
            ],
            
            // On Process - User Interview (4 applications)
            [
                'company_name' => 'Gojek',
                'position' => 'Backend Developer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'JobStreet',
                'status' => 'Interview',
                'application_status' => 'On Process',
                'recruitment_stage' => 'User - Interview',
                'career_level' => 'Full Time',
                'platform_link' => 'https://jobstreet.co.id/job/123456',
                'application_date' => Carbon::now()->subDays(7),
                'interview_date' => Carbon::now()->addDays(3),
                'interview_type' => 'Video',
                'interview_location' => 'Zoom',
                'notes' => 'Technical interview with engineering team.',
                'is_pinned' => false
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
                'interview_date' => Carbon::now()->addDays(7),
                'interview_type' => 'Panel',
                'interview_location' => 'Video Call',
                'notes' => 'Panel interview with data science team.',
                'is_pinned' => true
            ],
            [
                'company_name' => 'Traveloka',
                'position' => 'Backend Developer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'Website Company',
                'status' => 'Interview',
                'application_status' => 'On Process',
                'recruitment_stage' => 'User - Interview',
                'career_level' => 'Full Time',
                'platform_link' => 'https://traveloka.com/careers',
                'application_date' => Carbon::now()->subDays(3),
                'interview_date' => Carbon::now()->addDays(2),
                'interview_type' => 'Video',
                'interview_location' => 'Google Meet',
                'notes' => 'Technical interview with backend team lead.',
                'is_pinned' => false
            ],
            [
                'company_name' => 'Dana',
                'position' => 'DevOps Engineer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'LinkedIn',
                'status' => 'Interview',
                'application_status' => 'On Process',
                'recruitment_stage' => 'User - Interview',
                'career_level' => 'Full Time',
                'platform_link' => 'https://linkedin.com/jobs/view/567890',
                'application_date' => Carbon::now()->subDays(6),
                'interview_date' => Carbon::now()->addDays(4),
                'interview_type' => 'Video',
                'interview_location' => 'Zoom',
                'notes' => 'DevOps team interview. Focus on AWS and Kubernetes.',
                'is_pinned' => false
            ],
            
            // On Process - Assessment Test (4 applications)
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
                'notes' => 'AWS certification required. Completed coding assessment.',
                'is_pinned' => false
            ],
            [
                'company_name' => 'Grab',
                'position' => 'Data Analyst',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'JobStreet',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Assessment Test',
                'career_level' => 'Full Time',
                'platform_link' => 'https://jobstreet.co.id/job/234567',
                'application_date' => Carbon::now()->subDays(8),
                'notes' => 'Data analysis test completed. Waiting for results.',
                'is_pinned' => false
            ],
            [
                'company_name' => 'TechCorp',
                'position' => 'Full Stack Developer',
                'location' => 'Bandung, Jawa Barat',
                'platform' => 'Glints',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Assessment Test',
                'career_level' => 'Full Time',
                'platform_link' => 'https://glints.com/jobs/234567',
                'application_date' => Carbon::now()->subDays(7),
                'notes' => 'Technical test completed. React and Node.js focused.',
                'is_pinned' => false
            ],
            [
                'company_name' => 'OVO',
                'position' => 'Mobile Developer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'Website Company',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Assessment Test',
                'career_level' => 'Full Time',
                'platform_link' => 'https://ovo.id/careers',
                'application_date' => Carbon::now()->subDays(4),
                'notes' => 'Mobile development test. React Native and Flutter.',
                'is_pinned' => false
            ],
            
            // On Process - Other Stages (4 applications)
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
                'notes' => 'Just applied. Waiting for response.',
                'is_pinned' => false
            ],
            [
                'company_name' => 'OVO',
                'position' => 'Product Designer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'Website Company',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Psychotest',
                'career_level' => 'Full Time',
                'platform_link' => 'https://ovo.id/careers',
                'application_date' => Carbon::now()->subDays(4),
                'interview_date' => Carbon::now()->addDays(1),
                'interview_type' => 'In-person',
                'interview_location' => 'OVO Office, Jakarta',
                'notes' => 'Psychotest scheduled for tomorrow.',
                'is_pinned' => false
            ],
            [
                'company_name' => 'Dana',
                'position' => 'Product Manager',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'LinkedIn',
                'status' => 'Interview',
                'application_status' => 'On Process',
                'recruitment_stage' => 'LGD',
                'career_level' => 'Full Time',
                'platform_link' => 'https://linkedin.com/jobs/view/567890',
                'application_date' => Carbon::now()->subDays(6),
                'interview_date' => Carbon::now()->addDays(5),
                'interview_type' => 'In-person',
                'interview_location' => 'Dana Office',
                'notes' => 'Group discussion round completed. Waiting for next stage.',
                'is_pinned' => false
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
                'interview_date' => Carbon::now()->addDays(6),
                'interview_type' => 'Video',
                'interview_location' => 'Zoom',
                'notes' => 'Portfolio review completed. Presentation next week.',
                'is_pinned' => false
            ],
            
            // Accepted (3 applications)
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
                'interview_date' => Carbon::now()->subDays(2),
                'interview_type' => 'Video',
                'interview_location' => 'Google Meet',
                'notes' => 'Got the offer! Starting next month. Salary negotiation completed.',
                'is_pinned' => true
            ],
            [
                'company_name' => 'Netflix Indonesia',
                'position' => 'Content Manager',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'JobStreet',
                'status' => 'Accepted',
                'application_status' => 'Accepted',
                'recruitment_stage' => 'Offering',
                'career_level' => 'Full Time',
                'platform_link' => 'https://jobstreet.co.id/job/555666',
                'application_date' => Carbon::now()->subDays(20),
                'interview_date' => Carbon::now()->subDays(5),
                'interview_type' => 'Panel',
                'interview_location' => 'Video Call',
                'notes' => 'Amazing opportunity! Starting next quarter.',
                'is_pinned' => true
            ],
            [
                'company_name' => 'Tokopedia',
                'position' => 'Senior Frontend Developer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'Glints',
                'status' => 'Accepted',
                'application_status' => 'Accepted',
                'recruitment_stage' => 'Offering',
                'career_level' => 'Full Time',
                'platform_link' => 'https://glints.com/jobs/345678',
                'application_date' => Carbon::now()->subDays(15),
                'interview_date' => Carbon::now()->subDays(3),
                'interview_type' => 'Video',
                'interview_location' => 'Zoom',
                'notes' => 'Offer received and accepted! Excited to join the team.',
                'is_pinned' => true
            ],
            
            // Declined - Not Processed (5 applications)
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
                'notes' => 'Did not meet the requirements. Will try again next year.',
                'is_pinned' => false,
                'is_archived' => true,
                'archived_at' => Carbon::now()->subDays(2)
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
                'notes' => 'Position was filled internally.',
                'is_pinned' => false,
                'is_archived' => true,
                'archived_at' => Carbon::now()->subDays(5)
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
                'notes' => 'Very competitive position. Will try again with more experience.',
                'is_pinned' => false,
                'is_archived' => true,
                'archived_at' => Carbon::now()->subDays(8)
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
                'notes' => 'Requires more consulting experience.',
                'is_pinned' => false,
                'is_archived' => true,
                'archived_at' => Carbon::now()->subDays(7)
            ],
            [
                'company_name' => 'StartupXYZ',
                'position' => 'Backend Developer',
                'location' => 'Surabaya, Jawa Timur',
                'platform' => 'LinkedIn',
                'status' => 'Applied',
                'application_status' => 'Declined',
                'recruitment_stage' => 'Not Processed',
                'career_level' => 'Full Time',
                'platform_link' => 'https://linkedin.com/jobs/view/111333',
                'application_date' => Carbon::now()->subDays(18),
                'notes' => 'Company decided not to proceed. No specific reason given.',
                'is_pinned' => false,
                'is_archived' => true,
                'archived_at' => Carbon::now()->subDays(10)
            ],
            
            // Declined - Other Stages (3 applications)
            [
                'company_name' => 'PT Bank Mandiri',
                'position' => 'IT Officer',
                'location' => 'Jakarta Pusat, DKI Jakarta',
                'platform' => 'Website Company',
                'status' => 'Applied',
                'application_status' => 'Declined',
                'recruitment_stage' => 'Assessment Test',
                'career_level' => 'Full Time',
                'platform_link' => 'https://bankmandiri.co.id/careers',
                'application_date' => Carbon::now()->subDays(16),
                'notes' => 'Did not pass the assessment test. Will retry next year.',
                'is_pinned' => false,
                'is_archived' => true,
                'archived_at' => Carbon::now()->subDays(9)
            ],
            [
                'company_name' => 'PT Telkom Indonesia',
                'position' => 'Network Engineer',
                'location' => 'Bandung, Jawa Barat',
                'platform' => 'JobStreet',
                'status' => 'Applied',
                'application_status' => 'Declined',
                'recruitment_stage' => 'HR - Interview',
                'career_level' => 'Full Time',
                'platform_link' => 'https://jobstreet.co.id/job/222333',
                'application_date' => Carbon::now()->subDays(19),
                'interview_date' => Carbon::now()->subDays(10),
                'interview_type' => 'Video',
                'interview_location' => 'Zoom',
                'notes' => 'HR interview completed but did not proceed to next round.',
                'is_pinned' => false,
                'is_archived' => true,
                'archived_at' => Carbon::now()->subDays(6)
            ],
            [
                'company_name' => 'FreelanceHub',
                'position' => 'Web Developer',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'platform' => 'LinkedIn',
                'status' => 'Applied',
                'application_status' => 'Declined',
                'recruitment_stage' => 'Follow Up',
                'career_level' => 'Freelance',
                'platform_link' => 'https://linkedin.com/jobs/view/999000',
                'application_date' => Carbon::now()->subDays(3),
                'notes' => 'Client found another developer. Remote work opportunity missed.',
                'is_pinned' => false,
                'is_archived' => false
            ],
            
            // Additional variety - Intern and Contract (3 applications)
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
                'interview_date' => Carbon::now()->addDays(3),
                'interview_type' => 'Video',
                'interview_location' => 'Google Meet',
                'notes' => 'Great learning opportunity for fresh graduate.',
                'is_pinned' => false
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
                'interview_date' => Carbon::now()->addDays(4),
                'interview_type' => 'Video',
                'interview_location' => 'Zoom',
                'notes' => '6-month contract with possibility of extension.',
                'is_pinned' => false
            ],
            [
                'company_name' => 'PT Indofood',
                'position' => 'Management Trainee',
                'location' => 'Jakarta Barat, DKI Jakarta',
                'platform' => 'Website Company',
                'status' => 'Applied',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Assessment Test',
                'career_level' => 'MT',
                'platform_link' => 'https://indofood.com/careers',
                'application_date' => Carbon::now()->subDays(10),
                'notes' => 'MT program for 18 months. Assessment test completed.',
                'is_pinned' => false
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
                'interview_date' => $data['interview_date'] ?? null,
                'interview_type' => $data['interview_type'] ?? null,
                'interview_location' => $data['interview_location'] ?? null,
                'notes' => $data['notes'],
                'is_pinned' => $data['is_pinned'] ?? false,
                'is_archived' => $data['is_archived'] ?? false,
                'archived_at' => $data['archived_at'] ?? null,
            ]);
        }

        $this->command->info('Created ' . count($dummyData) . ' job applications with various statuses.');
    }
}