<?php

namespace Database\Seeders;

use App\Models\CoverLetter;
use App\Models\JobApplication;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserEducation;
use App\Models\UserExperience;
use App\Models\UserGoal;
use App\Models\UserOrganization;
use App\Models\UserProfile;
use App\Models\UserProject;
use App\Models\UserSkill;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

/**
 * Local-only showcase account: premium, full CV, and a varied job tracker.
 *
 * Login: demo.premium@trakerja.test / password
 *
 * php artisan db:seed --class=DemoPremiumShowcaseSeeder
 */
class DemoPremiumShowcaseSeeder extends Seeder
{
    private const EMAIL = 'demo.premium@trakerja.test';

    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->error('DemoPremiumShowcaseSeeder is local-only.');
            return;
        }

        // Never let seeding reach real SMTP or the Telegram channel.
        Mail::fake();
        Http::fake();

        mt_srand(20261117);
        Model::unguard();

        User::where('email', self::EMAIL)->first()?->delete();

        $user = User::create([
            'name' => 'Nadia Putri Rahmawati',
            'email' => self::EMAIL,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'user',
            'is_premium' => true,
            'payment_status' => User::PAYMENT_STATUS_PAID,
            'premium_purchased_at' => now()->subMonths(3),
            'premium_until' => null,
            'registered_phase' => 1,
            'grandfathered_benefits' => ['cv_templates_3_free', 'premium_discount_50'],
            'ai_credits' => 25,
            'cl_credits' => 10,
            'photo_credits' => 5,
            'xp' => 2480,
            'level' => 7,
            'portfolio_slug' => 'nadia-putri',
            'is_portfolio_published' => true,
            'locale' => 'id',
            'last_activity_at' => now(),
        ]);

        Payment::create([
            'user_id' => $user->id,
            'order_id' => 'DEMO-' . now()->subMonths(3)->format('Ymd') . '-0001',
            'amount' => (int) config('pakasir.premium_price', 9999),
            'payment_channel_code' => 'QRIS',
            'payment_channel_name' => 'QRIS',
            'payment_category' => 'qris',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '081298765432',
            'status' => 'SUCCESS',
            'request_at' => now()->subMonths(3),
            'paid_at' => now()->subMonths(3)->addMinutes(2),
            'notes' => 'Demo premium purchase (seeded)',
            'pakasir_is_sandbox' => true,
        ]);

        $this->seedProfile($user);
        $this->seedCv($user);
        $this->seedApplications($user);
        $this->seedGoalsAndLetters($user);

        Model::reguard();

        $this->command?->info('Demo premium account ready: ' . self::EMAIL . ' (' . $user->jobApplications()->count() . ' applications)');
    }

    private function seedProfile(User $user): void
    {
        UserProfile::create([
            'user_id' => $user->id,
            'full_name' => 'Nadia Putri Rahmawati',
            'headline' => 'Product-minded Frontend Engineer · React, TypeScript, Design Systems',
            'phone_number' => '081298765432',
            'domicile' => 'Jakarta Selatan, DKI Jakarta',
            'bio' => "Frontend engineer dengan 5+ tahun pengalaman membangun produk digital berskala nasional di sektor fintech dan e-commerce. Terbiasa bekerja dekat dengan tim desain dan produk untuk menerjemahkan riset pengguna menjadi antarmuka yang cepat, aksesibel, dan mudah dirawat.\n\nFokus saat ini: design system, performa web, dan mentoring engineer junior.",
            'linkedin_url' => 'https://www.linkedin.com/in/nadia-putri-demo',
            'github_url' => 'https://github.com/nadia-putri-demo',
            'portfolio_url' => 'https://nadiaputri.dev',
            'website_url' => 'https://medium.com/@nadiaputri',
            'additional_links' => [
                ['name' => 'Dribbble', 'url' => 'https://dribbble.com/nadiaputri'],
            ],
        ]);
    }

    private function seedCv(User $user): void
    {
        $experiences = [
            ['Senior Frontend Engineer', 'Kredivo Group', 'Jakarta', 'Full Time', '2023-03-01', null,
                "• Memimpin migrasi dashboard merchant dari Vue 2 ke React + TypeScript untuk 40.000+ merchant aktif.\n• Membangun design system internal (60+ komponen) yang dipakai 6 tim produk, memangkas waktu pengembangan fitur baru ±30%.\n• Menurunkan LCP halaman checkout dari 4,1 detik menjadi 1,8 detik melalui code splitting dan optimasi gambar.\n• Mentor untuk 3 engineer junior; menyusun panduan code review frontend."],
            ['Frontend Engineer', 'Bukalapak', 'Jakarta', 'Full Time', '2021-01-01', '2023-02-28',
                "• Mengembangkan fitur Mitra Bukalapak (aplikasi warung) yang digunakan 15 juta+ mitra.\n• Menerapkan A/B testing pada alur top-up pulsa yang meningkatkan konversi 12%.\n• Berkolaborasi dengan tim QA untuk membangun suite end-to-end test menggunakan Cypress."],
            ['Web Developer', 'Sagara Technology', 'Bandung', 'Full Time', '2019-07-01', '2020-12-31',
                "• Membangun 10+ website dan dashboard untuk klien BUMN dan startup menggunakan Laravel dan Vue.\n• Mengintegrasikan payment gateway Midtrans dan Xendit."],
            ['Frontend Developer Intern', 'Dicoding Indonesia', 'Bandung', 'Internship', '2018-07-01', '2018-12-31',
                "• Membantu pengembangan platform kelas online dan memperbaiki 50+ bug UI.\n• Menulis dokumentasi komponen untuk tim internal."],
        ];
        foreach ($experiences as $i => [$position, $company, $location, $type, $start, $end, $desc]) {
            UserExperience::create([
                'user_id' => $user->id, 'position' => $position, 'company_name' => $company,
                'location' => $location, 'employment_type' => $type,
                'start_date' => $start, 'end_date' => $end, 'is_current' => $end === null,
                'description' => $desc, 'display_order' => $i,
            ]);
        }

        UserEducation::create([
            'user_id' => $user->id, 'institution_name' => 'Institut Teknologi Bandung',
            'degree' => 'Bachelor', 'major' => 'Teknik Informatika', 'gpa' => '3.72/4.00',
            'location' => 'Bandung', 'start_date' => '2015-08-01', 'end_date' => '2019-07-31',
            'description' => "Tugas akhir: Sistem rekomendasi lowongan kerja berbasis content-based filtering.\nAsisten praktikum Pemrograman Web (2017–2018).",
            'display_order' => 0,
        ]);
        UserEducation::create([
            'user_id' => $user->id, 'institution_name' => 'SMA Negeri 3 Bandung',
            'degree' => 'High School', 'major' => 'IPA', 'gpa' => '91,5',
            'location' => 'Bandung', 'start_date' => '2012-07-01', 'end_date' => '2015-06-30',
            'display_order' => 1,
        ]);

        $skills = [
            ['React', 'Frameworks', 'Expert', 5], ['Next.js', 'Frameworks', 'Advanced', 3],
            ['Vue.js', 'Frameworks', 'Advanced', 4], ['Laravel', 'Frameworks', 'Intermediate', 3],
            ['TypeScript', 'Technical', 'Expert', 4], ['JavaScript', 'Technical', 'Expert', 6],
            ['HTML & CSS', 'Technical', 'Expert', 7], ['Tailwind CSS', 'Technical', 'Advanced', 3],
            ['Web Performance', 'Technical', 'Advanced', 3], ['Accessibility (WCAG)', 'Technical', 'Advanced', 2],
            ['PostgreSQL', 'Databases', 'Intermediate', 3], ['Redis', 'Databases', 'Beginner', 1],
            ['Figma', 'Tools & Software', 'Advanced', 4], ['Git & GitHub Actions', 'Tools & Software', 'Advanced', 6],
            ['Storybook', 'Tools & Software', 'Advanced', 3], ['Cypress', 'Tools & Software', 'Intermediate', 2],
            ['Kepemimpinan Tim', 'Soft Skills', 'Advanced', 3], ['Komunikasi Lintas Fungsi', 'Soft Skills', 'Expert', 5],
            ['Mentoring', 'Soft Skills', 'Advanced', 2],
            ['Bahasa Indonesia', 'Languages', 'Expert', 25], ['English (IELTS 7.5)', 'Languages', 'Advanced', 10],
            ['Japanese (JLPT N4)', 'Languages', 'Beginner', 2],
        ];
        foreach ($skills as $i => [$name, $category, $level, $years]) {
            UserSkill::create([
                'user_id' => $user->id, 'skill_name' => $name, 'category' => $category,
                'proficiency_level' => $level, 'years_of_experience' => $years, 'display_order' => $i,
            ]);
        }

        $orgs = [
            ['Google Developer Group Jakarta', 'Co-organizer', 'Jakarta', '2022-01-01', null, 'Mengkurasi pembicara dan mengelola meetup bulanan dengan 300+ peserta per acara.'],
            ['Women Who Code Jakarta', 'Mentor', 'Jakarta', '2021-06-01', null, 'Membimbing 20+ perempuan yang beralih karier ke bidang frontend.'],
            ['HMIF ITB', 'Kepala Divisi Teknologi', 'Bandung', '2017-03-01', '2018-03-01', 'Memimpin 12 anggota membangun sistem informasi himpunan dan website acara Arkavidia.'],
        ];
        foreach ($orgs as $i => [$name, $role, $loc, $start, $end, $desc]) {
            UserOrganization::create([
                'user_id' => $user->id, 'organization_name' => $name, 'role' => $role, 'location' => $loc,
                'start_date' => $start, 'end_date' => $end, 'is_current' => $end === null,
                'description' => $desc, 'display_order' => $i,
            ]);
        }

        $projects = [
            ['Kanvas UI — Open-source Design System', 'Creator & Maintainer', '2022-05-01', null, true,
                'Library komponen React aksesibel dengan 1.2k bintang GitHub dan dipakai 30+ startup lokal.',
                'https://kanvas-ui.dev', 'https://github.com/nadia-putri-demo/kanvas-ui', ['React', 'TypeScript', 'Storybook', 'Radix UI']],
            ['Dashboard Merchant Analytics', 'Lead Frontend', '2023-06-01', '2024-02-01', false,
                'Dashboard real-time untuk memantau transaksi dan cicilan merchant, termasuk ekspor laporan PDF.',
                null, null, ['Next.js', 'Recharts', 'TanStack Query']],
            ['Jadwal Sholat PWA', 'Solo Developer', '2020-03-01', '2020-05-01', false,
                'Progressive Web App jadwal sholat offline-first dengan 50k+ pengguna aktif saat Ramadan.',
                'https://jadwalsholat.app', 'https://github.com/nadia-putri-demo/jadwal-sholat', ['Vue.js', 'Workbox', 'IndexedDB']],
        ];
        foreach ($projects as $i => [$name, $role, $start, $end, $ongoing, $desc, $url, $repo, $tech]) {
            UserProject::create([
                'user_id' => $user->id, 'project_name' => $name, 'role' => $role,
                'start_date' => $start, 'end_date' => $end, 'is_ongoing' => $ongoing,
                'description' => $desc, 'project_url' => $url, 'repository_url' => $repo,
                'technologies' => $tech, 'display_order' => $i,
            ]);
        }

        $achievements = [
            ['AWS Certified Cloud Practitioner', 'Amazon Web Services', '2024-04-10', 'Sertifikasi dasar layanan dan arsitektur cloud AWS.', 'https://aws.amazon.com/verification'],
            ['Speaker — JSConf Asia 2023', 'JSConf Asia', '2023-11-18', 'Membawakan talk "Design System yang Bertahan Lebih dari Satu Redesign".', null],
            ['Juara 1 Hackathon Bank Indonesia', 'Bank Indonesia', '2022-08-20', 'Prototype aplikasi literasi keuangan untuk UMKM berbasis QRIS.', null],
            ['Meta Front-End Developer Professional Certificate', 'Coursera', '2021-09-01', null, 'https://coursera.org/verify/professional-cert/DEMO'],
        ];
        foreach ($achievements as $i => [$title, $issuer, $date, $desc, $cert]) {
            UserAchievement::create([
                'user_id' => $user->id, 'title' => $title, 'issuer' => $issuer, 'date' => $date,
                'description' => $desc, 'certificate_url' => $cert, 'display_order' => $i,
            ]);
        }
    }

    private function seedApplications(User $user): void
    {
        $companies = [
            ['Gojek', 'Jakarta Selatan'], ['Tokopedia', 'Jakarta Selatan'], ['Traveloka', 'Tangerang'],
            ['Shopee Indonesia', 'Jakarta Barat'], ['Grab Indonesia', 'Jakarta Selatan'], ['Bank Jago', 'Jakarta Selatan'],
            ['Xendit', 'Jakarta Selatan'], ['Ruangguru', 'Jakarta Selatan'], ['Blibli', 'Jakarta Barat'],
            ['Tiket.com', 'Jakarta Barat'], ['eFishery', 'Bandung'], ['Mekari', 'Jakarta Selatan'],
            ['Amartha', 'Jakarta Selatan'], ['Flip', 'Jakarta Selatan'], ['Pintu', 'Jakarta Selatan'],
            ['Stockbit', 'Jakarta Barat'], ['Astra Digital', 'Jakarta Utara'], ['Telkom Indonesia', 'Bandung'],
            ['Bank Mandiri', 'Jakarta Pusat'], ['BCA Digital', 'Jakarta Pusat'], ['DANA Indonesia', 'Jakarta Selatan'],
            ['OVO', 'Jakarta Selatan'], ['Halodoc', 'Jakarta Selatan'], ['Sayurbox', 'Jakarta Barat'],
            ['Kitabisa', 'Jakarta Selatan'], ['Glints', 'Remote'], ['Canva', 'Remote'], ['Atlassian', 'Remote'],
            ['GoTo Financial', 'Jakarta Selatan'], ['Unilever Indonesia', 'Tangerang'], ['Pertamina', 'Jakarta Pusat'],
            ['Agoda', 'Remote'], ['Sea Labs', 'Singapore'], ['Accenture Indonesia', 'Jakarta Selatan'],
            ['Deloitte Digital', 'Jakarta Selatan'], ['Kopi Kenangan', 'Jakarta Selatan'],
        ];
        $positions = [
            ['Senior Frontend Engineer', 'Full Time'], ['Frontend Engineer', 'Full Time'], ['Lead Frontend Engineer', 'Full Time'],
            ['Software Engineer, Web', 'Full Time'], ['Fullstack Engineer', 'Full Time'], ['UI Engineer', 'Contract'],
            ['Design System Engineer', 'Full Time'], ['Frontend Consultant', 'Freelance'], ['Product Engineer', 'Full Time'],
            ['Engineering Manager, Frontend', 'Full Time'], ['IT Management Trainee', 'MT'], ['React Native Developer', 'Contract'],
        ];
        $platforms = ['LinkedIn', 'JobStreet', 'Glints', 'Kalibrr', 'Company Website', 'Referral', 'Karir.com', 'Indeed', 'KitaLulus', 'LinkedIn', 'LinkedIn', 'Glints'];

        // [status, stage, weight]: a realistic funnel, most still in early stages.
        $funnel = [
            ['On Process', 'Applied', 18], ['On Process', 'Follow Up', 7], ['On Process', 'Assessment Test', 5],
            ['On Process', 'Psychotest', 3], ['On Process', 'HR - Interview', 6], ['On Process', 'User - Interview', 5],
            ['On Process', 'LGD', 2], ['On Process', 'Presentation Round', 2], ['On Process', 'Offering', 2],
            ['Accepted', 'Offering', 1], ['Rejected', 'Applied', 6], ['Rejected', 'HR - Interview', 4],
            ['Rejected', 'User - Interview', 3], ['Rejected', 'Not Processed', 4],
        ];

        $rows = [];
        foreach ($funnel as [$status, $stage, $count]) {
            for ($n = 0; $n < $count; $n++) {
                $rows[] = [$status, $stage];
            }
        }
        shuffle($rows);

        $interviewTypes = ['Online', 'Onsite', 'Video', 'Phone', 'Panel'];
        $interviewPlatforms = ['Google Meet', 'Zoom', 'Microsoft Teams'];

        foreach ($rows as $i => [$status, $stage]) {
            [$company, $location] = $companies[$i % count($companies)];
            [$position, $level] = $positions[mt_rand(0, count($positions) - 1)];
            $platform = $platforms[mt_rand(0, count($platforms) - 1)];
            $appliedAt = Carbon::today()->subDays(mt_rand(1, 150));

            $data = [
                'user_id' => $user->id,
                'company_name' => $company,
                'position' => $position,
                'location' => $location,
                'platform' => $platform,
                'platform_link' => $platform === 'Referral' ? '' : 'https://example.com/jobs/' . (100000 + $i),
                'career_level' => $level,
                'application_date' => $appliedAt->toDateString(),
                'application_status' => $status,
                'recruitment_stage' => $stage,
                'notes' => $this->noteFor($status, $stage, $company),
                'is_pinned' => in_array($stage, ['Offering', 'User - Interview'], true) && $status === 'On Process',
            ];

            if (in_array($stage, ['HR - Interview', 'User - Interview', 'Psychotest', 'Assessment Test', 'LGD', 'Presentation Round'], true)) {
                $type = $interviewTypes[mt_rand(0, count($interviewTypes) - 1)];
                // Active pipelines get upcoming slots so the calendar/reminders have content.
                $when = $status === 'On Process'
                    ? Carbon::now()->addDays(mt_rand(1, 14))->setTime(mt_rand(9, 16), [0, 30][mt_rand(0, 1)])
                    : $appliedAt->copy()->addDays(mt_rand(7, 20))->setTime(10, 0);
                $data += [
                    'interview_date' => $when,
                    'interview_type' => $type,
                    'interview_platform' => in_array($type, ['Onsite', 'Phone'], true) ? null : $interviewPlatforms[mt_rand(0, 2)],
                    'interview_location' => $type === 'Onsite' ? "Kantor {$company}, {$location}" : null,
                    'interview_notes' => "Siapkan studi kasus terkait {$position}. Pelajari produk dan value perusahaan.",
                ];
            }

            JobApplication::create($data);
        }
    }

    private function noteFor(string $status, string $stage, string $company): string
    {
        return match (true) {
            $status === 'Accepted' => "Offer diterima! Gaji sesuai ekspektasi, mulai kerja bulan depan. Kirim dokumen onboarding ke HR {$company}.",
            $status === 'Rejected' && $stage === 'Not Processed' => 'Tidak ada respons lebih dari 30 hari.',
            $status === 'Rejected' => "Ditolak setelah tahap {$stage}. Minta feedback ke recruiter.",
            $stage === 'Offering' => 'Sedang negosiasi offer: tanyakan detail benefit, WFH policy, dan budget training.',
            $stage === 'Follow Up' => 'Sudah follow-up via email ke recruiter, tunggu balasan 3–5 hari kerja.',
            $stage === 'Applied' => 'CV dan portfolio sudah dikirim.',
            default => "Lolos ke tahap {$stage}. Cek email secara berkala.",
        };
    }

    private function seedGoalsAndLetters(User $user): void
    {
        $monday = Carbon::now()->startOfWeek();
        foreach (range(0, 5) as $w) {
            $start = $monday->copy()->subWeeks($w);
            UserGoal::create([
                'user_id' => $user->id,
                'start_date' => $start->toDateString(),
                'end_date' => $start->copy()->endOfWeek()->toDateString(),
                'target_applied_weekly' => 10,
                'target_followup_weekly' => 5,
                'is_achieved' => $w > 0 && $w % 2 === 1,
            ]);
        }

        foreach ([['Gojek', 'Senior Frontend Engineer', 'id', 'professional'], ['Canva', 'Frontend Engineer', 'en', 'enthusiastic']] as [$company, $title, $lang, $tone]) {
            CoverLetter::create([
                'user_id' => $user->id,
                'company_name' => $company,
                'job_title' => $title,
                'job_description' => "We are looking for a {$title} experienced with React, TypeScript, and design systems.",
                'language' => $lang,
                'tone' => $tone,
                'content' => $lang === 'id'
                    ? "Yth. Tim Rekrutmen {$company},\n\nSaya ingin melamar posisi {$title}. Selama lima tahun terakhir saya membangun antarmuka berskala jutaan pengguna di Kredivo dan Bukalapak, termasuk design system yang dipakai enam tim produk.\n\nSaya yakin pengalaman ini relevan untuk tim {$company}. Terima kasih atas waktu dan pertimbangannya.\n\nHormat saya,\nNadia Putri Rahmawati"
                    : "Dear {$company} Hiring Team,\n\nI'm excited to apply for the {$title} role. Over the past five years I've shipped interfaces used by millions at Kredivo and Bukalapak, and I maintain Kanvas UI, an accessible open-source design system.\n\nI'd love to bring that craft to {$company}.\n\nBest regards,\nNadia Putri Rahmawati",
            ]);
        }
    }
}
