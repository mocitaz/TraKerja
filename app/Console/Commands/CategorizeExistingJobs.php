<?php

namespace App\Console\Commands;

use App\Models\JobPosting;
use Illuminate\Console\Command;

class CategorizeExistingJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:categorize-existing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Map existing scraped job postings in database to the 15 Sektor Ekonomi, target majors, and work types';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Scanning job_postings table for uncategorized postings...");

        $postings = JobPosting::whereNull('category_field')
            ->orWhereNull('category_major')
            ->orWhereNull('work_type')
            ->orWhereNull('tech_stack')
            ->get();

        $count = $postings->count();
        $this->comment("Found {$count} postings requiring categorization update.");

        if ($count === 0) {
            $this->info("All postings are already fully categorized!");
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($postings as $posting) {
            $title = $posting->title;
            $description = $posting->description ?? '';
            $searchStr = strtolower($title . ' ' . $description);

            // 1. Categorize Field (Sektor Ekonomi)
            $field = 'Sektor Jasa Profesional, Ilmiah, dan Teknis'; // Default fallback
            
            if (str_contains($searchStr, 'tani') || str_contains($searchStr, 'kebun') || str_contains($searchStr, 'hutan') || str_contains($searchStr, 'nelayan') || str_contains($searchStr, 'perikanan') || str_contains($searchStr, 'kehutanan') || str_contains($searchStr, 'ternak')) {
                $field = 'Sektor Pertanian, Kehutanan, dan Perikanan';
            } elseif (str_contains($searchStr, 'tambang') || str_contains($searchStr, 'galian') || str_contains($searchStr, 'migas') || str_contains($searchStr, 'drilling') || str_contains($searchStr, 'geologist') || str_contains($searchStr, 'minyak') || str_contains($searchStr, 'batubara')) {
                $field = 'Sektor Pertambangan dan Penggalian';
            } elseif (str_contains($searchStr, 'pabrik') || str_contains($searchStr, 'manufaktur') || str_contains($searchStr, 'garmen') || str_contains($searchStr, 'perakitan') || str_contains($searchStr, 'tekstil') || str_contains($searchStr, 'qc') || str_contains($searchStr, 'operator produksi')) {
                $field = 'Sektor Industri Pengolahan (Manufaktur)';
            } elseif (str_contains($searchStr, 'pln') || str_contains($searchStr, 'pdam') || str_contains($searchStr, 'listrik') || str_contains($searchStr, 'utilitas') || str_contains($searchStr, 'gardu') || str_contains($searchStr, 'limbah') || str_contains($searchStr, 'sanitasi')) {
                $field = 'Sektor Pengadaan Listrik, Gas, Air, dan Pengelolaan Sampah';
            } elseif (str_contains($searchStr, 'sipil') || str_contains($searchStr, 'civil') || str_contains($searchStr, 'arsitek') || str_contains($searchStr, 'konstruksi') || str_contains($searchStr, 'bangunan') || str_contains($searchStr, 'properti') || str_contains($searchStr, 'crane')) {
                $field = 'Sektor Konstruksi';
            } elseif (str_contains($searchStr, 'retail') || str_contains($searchStr, 'sales') || str_contains($searchStr, 'marketing') || str_contains($searchStr, 'bengkel') || str_contains($searchStr, 'mekanik') || str_contains($searchStr, 'dagang') || str_contains($searchStr, 'pramuniaga') || str_contains($searchStr, 'suku cadang')) {
                $field = 'Sektor Perdagangan Besar dan Eceran; Reparasi Mobil dan Sepeda Motor';
            } elseif (str_contains($searchStr, 'logistik') || str_contains($searchStr, 'gudang') || str_contains($searchStr, 'warehouse') || str_contains($searchStr, 'kurir') || str_contains($searchStr, 'ekspedisi') || str_contains($searchStr, 'transportasi') || str_contains($searchStr, 'driver') || str_contains($searchStr, 'ojek') || str_contains($searchStr, 'supply chain')) {
                $field = 'Sektor Transportasi, Logistik, dan Pergudangan';
            } elseif (str_contains($searchStr, 'hotel') || str_contains($searchStr, 'resepsionis') || str_contains($searchStr, 'koki') || str_contains($searchStr, 'chef') || str_contains($searchStr, 'barista') || str_contains($searchStr, 'katering') || str_contains($searchStr, 'restoran') || str_contains($searchStr, 'culinary') || str_contains($searchStr, 'hospitality')) {
                $field = 'Sektor Penyediaan Akomodasi dan Penyediaan Makan Minum (Hospitality)';
            } elseif (str_contains($searchStr, 'software') || str_contains($searchStr, 'developer') || str_contains($searchStr, 'programmer') || str_contains($searchStr, 'data scientist') || str_contains($searchStr, 'network') || str_contains($searchStr, 'laravel') || str_contains($searchStr, 'react') || str_contains($searchStr, 'vue') || str_contains($searchStr, 'golang') || str_contains($searchStr, 'seo') || str_contains($searchStr, 'content creator') || str_contains($searchStr, 'media') || str_contains($searchStr, 'it') || str_contains($searchStr, 'information technology')) {
                $field = 'Sektor Informasi dan Komunikasi (TIK)';
            } elseif (str_contains($searchStr, 'bank') || str_contains($searchStr, 'teller') || str_contains($searchStr, 'akuntan') || str_contains($searchStr, 'finance') || str_contains($searchStr, 'keuangan') || str_contains($searchStr, 'asuransi') || str_contains($searchStr, 'saham') || str_contains($searchStr, 'agen properti') || str_contains($searchStr, 'real estate')) {
                $field = 'Sektor Keuangan, Asuransi, dan Real Estat';
            } elseif (str_contains($searchStr, 'pns') || str_contains($searchStr, 'pppk') || str_contains($searchStr, 'tni') || str_contains($searchStr, 'polri') || str_contains($searchStr, 'pemerintah') || str_contains($searchStr, 'birokrasi') || str_contains($searchStr, 'goverment')) {
                $field = 'Sektor Administrasi Pemerintahan, Pertahanan, dan Jaminan Sosial Wajib';
            } elseif (str_contains($searchStr, 'guru') || str_contains($searchStr, 'dosen') || str_contains($searchStr, 'tutor') || str_contains($searchStr, 'pendidikan') || str_contains($searchStr, 'sekolah') || str_contains($searchStr, 'bimbel') || str_contains($searchStr, 'edutech')) {
                $field = 'Sektor Jasa Pendidikan';
            } elseif (str_contains($searchStr, 'dokter') || str_contains($searchStr, 'perawat') || str_contains($searchStr, 'medis') || str_contains($searchStr, 'apoteker') || str_contains($searchStr, 'bidan') || str_contains($searchStr, 'klinik') || str_contains($searchStr, 'sosial')) {
                $field = 'Sektor Kesehatan Manusia dan Kegiatan Sosial';
            } elseif (str_contains($searchStr, 'musisi') || str_contains($searchStr, 'aktor') || str_contains($searchStr, 'film') || str_contains($searchStr, 'hiburan') || str_contains($searchStr, 'rekreasi') || str_contains($searchStr, 'seni') || str_contains($searchStr, 'atlet') || str_contains($searchStr, 'sutradara')) {
                $field = 'Sektor Jasa Kesenian, Hiburan, dan Rekreasi';
            }

            // 2. Categorize Major (Jurusan)
            $major = 'Semua Jurusan IT';
            if (str_contains($searchStr, 'informatika') || str_contains($searchStr, 'computer science') || str_contains($searchStr, 'it') || str_contains($searchStr, 'rpl') || str_contains($searchStr, 'rekayasa perangkat lunak')) {
                $major = 'Teknik Informatika';
            } elseif (str_contains($searchStr, 'sistem informasi') || str_contains($searchStr, 'information system')) {
                $major = 'Sistem Informasi';
            } elseif (str_contains($searchStr, 'matematika') || str_contains($searchStr, 'statistika') || str_contains($searchStr, 'mathematics') || str_contains($searchStr, 'statistics')) {
                $major = 'Matematika / Statistika';
            } elseif (str_contains($searchStr, 'teknik elektro') || str_contains($searchStr, 'electrical engineering')) {
                $major = 'Teknik Elektro';
            }

            // 3. Categorize Work Type
            $workType = 'Onsite';
            if (str_contains($searchStr, 'remote') || str_contains($searchStr, 'wfh') || str_contains($searchStr, 'work from home') || str_contains($searchStr, 'dirumah')) {
                $workType = 'Remote';
            } elseif (str_contains($searchStr, 'hybrid') || str_contains($searchStr, 'wfo/wfh')) {
                $workType = 'Hybrid';
            }

            // 4. Extract Tech Stack
            $techStackKeywords = [
                'React', 'Vue', 'Angular', 'Svelte', 'JavaScript', 'TypeScript', 'Node.js', 'Express',
                'PHP', 'Laravel', 'Symfony', 'Golang', 'Python', 'Django', 'Flask', 'Ruby', 'Rails',
                'Java', 'Kotlin', 'Swift', 'Flutter', 'React Native', 'MySQL', 'PostgreSQL', 
                'MongoDB', 'Redis', 'Docker', 'Kubernetes', 'AWS', 'Git', 'CI/CD', 
                'DevOps', 'QA', 'Selenium', 'Cypress', 'Figma', 'Tailwind', 'Bootstrap'
            ];
            
            $detectedStack = [];
            foreach ($techStackKeywords as $tech) {
                $pattern = '/\b' . preg_quote(strtolower($tech), '/') . '\b/i';
                if (str_contains($tech, '.')) {
                    $pattern = '/' . preg_quote(strtolower($tech), '/') . '/i';
                }
                if (preg_match($pattern, $searchStr)) {
                    $detectedStack[] = $tech;
                }
            }
            $detectedStack = array_values(array_unique($detectedStack));

            $posting->update([
                'category_field' => $field,
                'category_major' => $major,
                'work_type' => $workType,
                'tech_stack' => $detectedStack,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Categorization complete! Successfully updated {$count} postings.");

        return Command::SUCCESS;
    }
}
