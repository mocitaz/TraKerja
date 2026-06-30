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
            'Pertanian', 'Agribisnis', 'Peternakan', 'Perkebunan', 'Kehutanan', 'Perikanan', 'Agroteknologi',
            // 2. Pertambangan & Energi
            'Pertambangan', 'Perminyakan', 'Geologist', 'Tambang Batubara', 'HSE Mining', 'K3 Tambang',
            // 3. Industri Pengolahan (Manufaktur)
            'Teknik Mesin', 'Teknik Industri', 'Operator Produksi', 'Quality Control', 'Teknologi Pangan', 'Teknik Kimia', 'Mekanik', 'Teknisi',
            // 4. Energi & Utilitas
            'Teknik Elektro', 'Tenaga Listrik', 'Teknik Lingkungan', 'K3 Industri', 'Safety Officer',
            // 5. Konstruksi & Properti
            'Teknik Sipil', 'Arsitektur', 'Site Manager', 'Drafter', 'Quantity Surveyor', 'Estimator',
            // 6. Perdagangan & Retail
            'Marketing', 'Sales', 'Retail', 'Sales Promotion', 'Brand Ambassador', 'Merchandiser', 'Digital Marketing', 'Social Media',
            // 7. Transportasi & Logistik
            'Logistik', 'Warehouse', 'Supply Chain', 'Procurement', 'Ekspedisi', 'Kurir', 'Admin Logistik',
            // 8. Akomodasi, Kuliner & Pariwisata
            'Hotel', 'Chef', 'Pariwisata', 'Barista', 'Front Office', 'Housekeeping', 'Tour Guide', 'Hospitality',
            // 9. Informasi & Komunikasi (lebih sedikit agar tidak dominan)
            'IT Support', 'Data Analyst', 'UI UX',
            // 10. Keuangan & Perbankan
            'Akuntansi', 'Finance', 'Audit', 'Perpajakan', 'Teller Bank', 'Analis Kredit', 'Asuransi',
            // 11. Hukum & Konsultasi Profesional
            'Legal', 'Notaris', 'Konsultan', 'Business Development',
            // 12. Sumber Daya Manusia & Administrasi
            'HRD', 'Rekrutmen', 'Administrasi', 'Sekretaris', 'General Affairs', 'Staff Umum', 'Resepsionis',
            // 13. Pendidikan & Pelatihan
            'Guru', 'Pengajar', 'Tutor', 'Instruktur', 'Staf Akademik',
            // 14. Kesehatan & Farmasi
            'Perawat', 'Dokter', 'Apoteker', 'Farmasi', 'Bidan', 'Radiologi', 'Analis Laboratorium',
            // 15. Seni, Kreatif & Komunikasi
            'Desain Grafis', 'Fotografer', 'Videografer', 'Copywriter', 'Jurnalis', 'Penyiar',
        ];
        $pagesToCrawl = 2;
        $delaySeconds = 0;

        foreach ($sources as $source) {
            ScrapeJobDetailsJob::logToLiveBuffer("Discovery Engine: Menjadwalkan penelusuran kata kunci untuk " . $source->name);
            echo "Scheduling discovery for: " . $source->name . " (" . $source->target_domain . ")...\n";

            foreach ($keywords as $keyword) {
                // Dispatch each keyword discovery job with a 10 seconds stagger
                DiscoverPlatformKeywordJob::dispatch($source, $keyword, $pagesToCrawl)
                    ->delay(now()->addSeconds($delaySeconds))
                    ->onQueue('discovery');
                
                $delaySeconds += 10;
            }
        }
    }
}
