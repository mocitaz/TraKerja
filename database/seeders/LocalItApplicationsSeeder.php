<?php

namespace Database\Seeders;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalItApplicationsSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local') || config('database.default') !== 'sqlite') {
            throw new \RuntimeException('Seeder ini hanya untuk SQLite lokal.');
        }

        $user = User::where('email', 'user@trakerja.test')->firstOrFail();
        $roles = [
            'Frontend Developer', 'Backend Developer', 'Full Stack Developer',
            'Mobile Developer', 'Android Developer', 'iOS Developer',
            'Software Engineer', 'QA Engineer', 'QA Automation Engineer',
            'DevOps Engineer', 'Cloud Engineer', 'Site Reliability Engineer',
            'Data Analyst', 'Data Engineer', 'Data Scientist',
            'Machine Learning Engineer', 'Cybersecurity Analyst', 'SOC Analyst',
            'IT Support', 'Network Engineer', 'System Administrator',
            'Database Administrator', 'UI/UX Designer', 'Product Designer',
            'Technical Business Analyst', 'IT Project Manager', 'Scrum Master',
            'ERP Developer', 'Game Developer', 'Technical Writer',
        ];
        $prefixes = ['Nusa', 'Aruna', 'Cakra', 'Bumi', 'Sagara', 'Lentera', 'Awan', 'Raya', 'Mitra', 'Karya'];
        $suffixes = ['Digital', 'Teknologi', 'Sistem', 'Informatika', 'Solusi', 'Data'];
        $locations = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang', 'Tangerang', 'Bekasi', 'Depok', 'Bali', 'Remote'];
        $platforms = ['LinkedIn', 'JobStreet', 'Kalibrr', 'Glints', 'Indeed', 'Other'];
        $stages = ['Applied', 'Follow Up', 'Assessment Test', 'Psychotest', 'HR - Interview', 'User - Interview', 'Presentation Round', 'Offering'];
        $levels = ['Full Time', 'Full Time', 'Full Time', 'Contract', 'Intern', 'Freelance'];
        $statuses = array_merge(array_fill(0, 100, 'On Process'), array_fill(0, 20, 'Accepted'), array_fill(0, 30, 'Rejected'));
        shuffle($statuses);
        $pick = fn (array $values) => $values[array_rand($values)];
        $created = 0;

        // Keep fixture creation from issuing activity rewards for 150 synthetic applications.
        JobApplication::withoutEvents(function () use ($user, $roles, $prefixes, $suffixes, $locations, $platforms, $stages, $levels, $statuses, $pick, &$created) {
            DB::transaction(function () use ($user, $roles, $prefixes, $suffixes, $locations, $platforms, $stages, $levels, $statuses, $pick, &$created) {
                foreach ($statuses as $index => $status) {
                    $stage = $status === 'Accepted' ? 'Offering' : $pick($stages);
                    $date = now()->subDays(random_int(1, 90))->startOfDay();
                    $interview = $status === 'On Process' && in_array($stage, ['HR - Interview', 'User - Interview'], true);
                    $application = JobApplication::firstOrCreate([
                        'user_id' => $user->id,
                        'platform_link' => sprintf('https://jobs.example.test/dummy-it/%03d', $index + 1),
                    ], [
                        'company_name' => 'PT '.$pick($prefixes).' '.$pick($suffixes),
                        'position' => $pick($roles),
                        'location' => $pick($locations),
                        'platform' => $pick($platforms),
                        'status' => $status,
                        'application_status' => $status,
                        'recruitment_stage' => $stage,
                        'career_level' => $pick($levels),
                        'application_date' => $date,
                        'interview_date' => $interview ? now()->addDays(random_int(1, 14))->setTime(random_int(9, 16), 0) : null,
                        'interview_type' => $interview ? 'Video' : null,
                        'interview_location' => $interview ? 'Pertemuan online dummy' : null,
                        'interview_notes' => $interview ? 'Jadwal simulasi untuk pengujian tracker.' : null,
                        'notes' => 'Data dummy IT untuk pengujian lokal. Perusahaan, lowongan dan proses rekrutmen fiktif.',
                        'is_pinned' => $index < 5,
                        'is_archived' => false,
                    ]);
                    $created += (int) $application->wasRecentlyCreated;
                }
            });
        });

        $this->command?->info("{$created} lamaran dummy IT ditambahkan untuk {$user->email}.");
    }
}
