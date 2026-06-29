<?php

namespace App\Console\Commands;

use App\Models\JobPosting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ScraperStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check database stats: duplicates and jobs count per sector/major';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("==============================================");
        $this->info("      TraKerja Database Statistics Report     ");
        $this->info("==============================================");

        $totalJobs = JobPosting::count();
        $this->info("Total Loker Terdaftar: {$totalJobs}");

        // 1. Check duplicates by Unique Hash
        $duplicatesHashCount = DB::table('job_postings')
            ->select('unique_hash', DB::raw('count(*) as count'))
            ->groupBy('unique_hash')
            ->having('count', '>', 1)
            ->get()
            ->count();

        // 2. Check duplicates by Title & Company Name
        $duplicatesTitleCompany = DB::table('job_postings')
            ->select('title', 'company_name', DB::raw('count(*) as count'))
            ->groupBy('title', 'company_name')
            ->having('count', '>', 1)
            ->get()
            ->sum(function ($item) {
                return $item->count - 1;
            });

        $this->warn("Loker Duplikat (Hash Unik Sama): {$duplicatesHashCount}");
        $this->warn("Loker Duplikat (Nama & Perusahaan Sama): {$duplicatesTitleCompany}");

        $this->newLine();
        $this->info("----------------------------------------------");
        $this->info("    Penyebaran Loker per Sektor / Jurusan     ");
        $this->info("----------------------------------------------");

        $sectors = JobPosting::select('category_field', 'category_major', DB::raw('count(*) as total'))
            ->groupBy('category_field', 'category_major')
            ->orderBy('category_field')
            ->orderByDesc('total')
            ->get();

        if ($sectors->isEmpty()) {
            $this->comment("Belum ada data loker terklasifikasi.");
        } else {
            $rows = [];
            foreach ($sectors as $sector) {
                $rows[] = [
                    $sector->category_field ?: 'Tidak Diketahui',
                    $sector->category_major ?: 'Tidak Diketahui',
                    $sector->total
                ];
            }

            $this->table(
                ['Sektor / Bidang', 'Rumpun Jurusan', 'Jumlah Loker'],
                $rows
            );
        }

        $this->info("==============================================");

        return Command::SUCCESS;
    }
}
