<?php

namespace App\Jobs;

use App\Models\ScraperSource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DiscoverLinksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public bool $force;

    public function __construct(bool $force = false)
    {
        $this->force = $force;
        $this->queue = 'discovery';
    }

    public function handle()
    {
        $sources = ScraperSource::where('is_active', true)->get();

        if (!$this->force) {
            $sources = $sources->filter(fn($source) => $source->isDue());
            if ($sources->isEmpty()) {
                ScrapeJobDetailsJob::logToLiveBuffer("Discovery Engine: Tidak ada platform yang jatuh tempo jadwal scraping.");
                echo "No active scraper sources are due for crawling.\n";
                return;
            }
        }

        ScrapeJobDetailsJob::logToLiveBuffer("Discovery Engine: Memulai penelusuran tautan lowongan kerja untuk " . $sources->count() . " platform aktif.", 'success');
        echo "Processing " . $sources->count() . " active scraper sources...\n";

        // Multi-keyword configurations representing all 15 Sektor Ekonomi for diverse coverage
        $keywords = [
            // 1. Pertanian, Kehutanan, Perikanan
            'Pertanian', 'Agribisnis', 'Peternakan', 'Kehutanan',
            // 2. Pertambangan
            'Pertambangan', 'Perminyakan', 'Geologist',
            // 3. Industri Pengolahan (Manufaktur)
            'Teknik Mesin', 'Teknik Industri', 'Operator Produksi', 'Teknologi Pangan',
            // 4. Pengadaan Energi & Limbah
            'Teknik Lingkungan', 'Tenaga Listrik',
            // 5. Konstruksi
            'Teknik Sipil', 'Arsitektur',
            // 6. Perdagangan & Retail
            'Marketing', 'Sales', 'Retail',
            // 7. Transportasi & Logistik
            'Logistik', 'Warehouse', 'Supply Chain',
            // 8. Akomodasi & Kuliner (Hospitality)
            'Hotel', 'Chef', 'Pariwisata', 'Barista',
            // 9. Informasi & Komunikasi (TIK)
            'Programmer', 'IT Support', 'DKV', 'Software',
            // 10 & 11. Keuangan, Hukum, & Profesional
            'Akuntansi', 'Finance', 'Legal', 'HRD', 'Psikologi',
            // 12 & 13. Pemerintahan & Pendidikan
            'Administrasi', 'Guru', 'Pendidikan',
            // 14 & 15. Kesehatan, Seni & Kesenian
            'Perawat', 'Farmasi', 'Desain', 'Olahraga'
        ];
        $pagesToCrawl = 1;
        $delaySeconds = 0;

        foreach ($sources as $source) {
            ScrapeJobDetailsJob::logToLiveBuffer("Discovery Engine: Menjelajah " . $source->name);
            echo "Running discovery for: " . $source->name . " (" . $source->target_domain . ")...\n";
            $originalSeedUrl = $source->seed_url;
            $discoveredCount = 0;

            foreach ($keywords as $keyword) {
                for ($page = 1; $page <= $pagesToCrawl; $page++) {
                    
                    // Generate dynamic seed URL for pages and keywords
                    if (str_contains($source->target_domain, 'linkedin.com')) {
                        $start = ($page - 1) * 25;
                        $source->seed_url = "https://id.linkedin.com/jobs/search?keywords=" . urlencode($keyword) . "&start=" . $start;
                    } elseif (str_contains($source->target_domain, 'kalibrr.com')) {
                        $source->seed_url = "https://www.kalibrr.com/job-board/te/" . urlencode(strtolower($keyword)) . "/" . $page;
                    } elseif (str_contains($source->target_domain, 'jobstreet.co.id')) {
                        $source->seed_url = "https://www.jobstreet.co.id/id/" . urlencode(strtolower($keyword)) . "-jobs?page=" . $page;
                    }

                    ScrapeJobDetailsJob::logToLiveBuffer(" -> Mencari kata kunci [" . $keyword . "] Halaman " . $page);
                    echo "  -> Discovery Query: [" . $keyword . "] page " . $page . "\n";
                    $discoveredUrls = $source->executeDiscovery();
                    $discoveredCount += count($discoveredUrls);

                    foreach ($discoveredUrls as $url) {
                        // Stagger execution delay (e.g. 4 seconds increment) to prevent anti-bot blocking
                        ScrapeJobDetailsJob::dispatch($url, $source)
                            ->delay(now()->addSeconds($delaySeconds))
                            ->onQueue('extraction');
                        
                        $delaySeconds += 4;
                    }
                }
            }

            // Restore original seed url structure
            $source->seed_url = $originalSeedUrl;
            $source->updateLastRun();

            ScrapeJobDetailsJob::logToLiveBuffer("Discovery Engine: Selesai! Berhasil mengantrekan " . $discoveredCount . " tautan detail lowongan untuk " . $source->name, 'success');
            echo "Discovered and queued total of " . $discoveredCount . " job details URLs for " . $source->name . ".\n";
        }
    }
}
