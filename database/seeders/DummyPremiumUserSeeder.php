<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserExperience;
use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\UserOrganization;
use App\Models\UserAchievement;
use App\Models\UserProject;
use App\Models\JobApplication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyPremiumUserSeeder extends Seeder
{
    public function run()
    {
        // Cleanup
        $existingUser = User::where('email', 'budi.premium@trakerja.com')->first();
        if ($existingUser) {
            $existingUser->delete();
        }

        // 1. User
        $user = User::create([
            'name' => 'Budi Premium TraKerja',
            'email' => 'budi.premium@trakerja.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 2. Profile
        UserProfile::create([
            'user_id' => $user->id,
            'phone_number' => '081234567890',
            'domicile' => 'Jakarta Selatan',
            'bio' => "**Full Stack Developer** dengan keahlian di **Laravel, React, dan AWS**.\n\n• Memiliki pengalaman 8 tahun.\n• Ahli dalam optimasi performa aplikasi.",
        ]);

        // 3. Experiences
        UserExperience::create([
            'user_id' => $user->id,
            'position' => 'Senior Technical Lead',
            'company_name' => 'Tech Giant Indonesia',
            'location' => 'Jakarta',
            'start_date' => Carbon::parse('2021-01-01'),
            'is_current' => true,
            'description' => "**Key Responsibilities:**\n• Memimpin tim developer.\n• [Estimasi: 2 Bulan] Migrasi infrastruktur ke Cloud.",
        ]);

        UserExperience::create([
            'user_id' => $user->id,
            'position' => 'Web Developer',
            'company_name' => 'Startup Lokal',
            'location' => 'Bandung',
            'start_date' => Carbon::parse('2018-01-01'),
            'end_date' => Carbon::parse('2020-12-31'),
            'is_current' => false,
            'description' => "• Membangun fitur e-commerce.\n• Integrasi gateway pembayaran.",
        ]);

        // 4. Educations
        UserEducation::create([
            'user_id' => $user->id,
            'institution_name' => 'Institut Teknologi Bandung',
            'degree' => 'Bachelor of Computer Science',
            'major' => 'Informatics Engineering',
            'location' => 'Bandung',
            'start_date' => Carbon::parse('2014-08-01'),
            'end_date' => Carbon::parse('2018-04-01'),
            'gpa' => 3.85,
        ]);

        // 5. Skills
        $skills = ['Laravel', 'React', 'Node.js', 'AWS', 'Docker', 'PostgreSQL'];
        foreach ($skills as $skill) {
            UserSkill::create([
                'user_id' => $user->id,
                'skill_name' => $skill,
                'category' => 'Technical',
                'proficiency_level' => 'Expert',
                'years_of_experience' => 5
            ]);
        }

        // 6. Organizations
        UserOrganization::create([
            'user_id' => $user->id,
            'organization_name' => 'GDG Jakarta',
            'role' => 'Lead Organizer',
            'start_date' => Carbon::parse('2020-01-01'),
            'is_current' => true,
            'description' => "Mengelola komunitas developer terbesar di Jakarta.",
        ]);

        // 7. Projects
        UserProject::create([
            'user_id' => $user->id,
            'project_name' => 'TraKerja Dashboard',
            'role' => 'Lead Developer',
            'description' => "Membangun dashboard analitik dengan visualisasi funnel yang modern.",
            'technologies' => ['Laravel', 'Livewire', 'Tailwind CSS'],
            'start_date' => Carbon::parse('2023-01-01'),
        ]);

        // 8. Achievements
        UserAchievement::create([
            'user_id' => $user->id,
            'title' => 'Employee of the Year',
            'issuer' => 'Tech Giant Inc',
            'date' => Carbon::parse('2022-12-01'),
            'description' => 'Penghargaan atas dedikasi luar biasa dalam pengembangan produk.',
        ]);

        // 9. Job Applications (50)
        $companies = ['Google', 'Meta', 'Amazon', 'Apple', 'Netflix', 'Shopee', 'Tokopedia', 'Gojek', 'Traveloka', 'Grab'];
        $stages = ['Applied', 'Interview', 'Accepted', 'Rejected', 'Pending'];

        for ($i = 1; $i <= 50; $i++) {
            $status = $stages[array_rand($stages)];
            
            // Funnel Logic
            if ($i > 35) $status = 'Rejected';
            if ($i < 15) $status = 'Interview';
            if ($i < 5) $status = 'Accepted';

            JobApplication::create([
                'user_id' => $user->id,
                'company_name' => $companies[array_rand($companies)] . " $i",
                'position' => 'Senior Developer',
                'location' => 'Jakarta / Remote',
                'application_date' => now()->subDays(rand(1, 60)),
                'status' => $status,
                'application_status' => $status,
                'recruitment_stage' => $status,
                'platform' => 'LinkedIn',
                'platform_link' => 'https://linkedin.com/jobs/view/' . rand(100000, 999999),
                'notes' => "**Catatan Update**\n• Status: $status\n• [Estimasi: 3 Hari] Cek email untuk kelanjutan.",
            ]);
        }
    }
}
