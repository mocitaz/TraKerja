<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobApplication;
use App\Models\User;
use Carbon\Carbon;

class SmurfJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (assuming you have at least one user)
        $user = User::first();
        
        if (!$user) {
            $this->command->error('No user found. Please create a user first.');
            return;
        }

        $smurfJobs = [
            [
                'user_id' => $user->id,
                'company_name' => 'Smurf Village Tech',
                'position' => 'Frontend Developer',
                'location' => 'Smurf Village, Magic Forest',
                'platform' => 'SmurfJobs',
                'application_status' => 'On Process',
                'recruitment_stage' => 'HR - Interview',
                'career_level' => 'Mid Level',
                'application_date' => Carbon::parse('2024-01-15'),
                'notes' => 'Excited to work with Papa Smurf\'s team! Love the magical tech stack they use.',
                'platform_link' => 'https://smurfjobs.com/frontend-dev',
            ],
            [
                'user_id' => $user->id,
                'company_name' => 'Gargamel Analytics',
                'position' => 'Data Scientist',
                'location' => 'Gargamel\'s Castle, Dark Forest',
                'platform' => 'EvilJobs',
                'application_status' => 'Declined',
                'recruitment_stage' => 'Assessment Test',
                'career_level' => 'Senior Level',
                'application_date' => Carbon::parse('2024-01-10'),
                'notes' => 'Failed the evil algorithm test. Gargamel was not impressed with my smurf detection models.',
                'platform_link' => 'https://eviljobs.com/data-scientist',
            ],
            [
                'user_id' => $user->id,
                'company_name' => 'Smurfette Cosmetics',
                'position' => 'UI/UX Designer',
                'location' => 'Beauty Valley, Smurf Land',
                'platform' => 'DesignJobs',
                'application_status' => 'Accepted',
                'recruitment_stage' => 'Offering',
                'career_level' => 'Mid Level',
                'application_date' => Carbon::parse('2024-01-20'),
                'notes' => 'Got the job! Will be designing beautiful interfaces for smurf beauty products. So excited!',
                'platform_link' => 'https://designjobs.com/ui-ux-smurfette',
            ],
            [
                'user_id' => $user->id,
                'company_name' => 'Brainy Smurf Research Lab',
                'position' => 'Machine Learning Engineer',
                'location' => 'Science Lab, Smurf Village',
                'platform' => 'TechJobs',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Technical Interview',
                'career_level' => 'Senior Level',
                'application_date' => Carbon::parse('2024-01-25'),
                'notes' => 'Interview with Brainy Smurf was intense! He asked about quantum computing and smurf DNA analysis.',
                'platform_link' => 'https://techjobs.com/ml-engineer-brainy',
            ],
            [
                'user_id' => $user->id,
                'company_name' => 'Hefty Smurf Construction',
                'position' => 'DevOps Engineer',
                'location' => 'Construction Site, Smurf Village',
                'platform' => 'BuildJobs',
                'application_status' => 'On Process',
                'recruitment_stage' => 'Follow Up',
                'career_level' => 'Mid Level',
                'application_date' => Carbon::parse('2024-01-30'),
                'notes' => 'Hefty Smurf wants me to help build robust infrastructure for their construction projects. Strong muscles required!',
                'platform_link' => 'https://buildjobs.com/devops-hefty',
            ],
        ];

        foreach ($smurfJobs as $jobData) {
            JobApplication::create($jobData);
        }

        $this->command->info('5 Smurf job applications created successfully!');
    }
}
