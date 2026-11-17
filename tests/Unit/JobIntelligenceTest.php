<?php

namespace Tests\Unit;

use App\JobEngine\Adapters\IndonesiaLocation;
use App\JobEngine\Intelligence\ScamDetector;
use App\JobEngine\Intelligence\TextSimilarity;
use PHPUnit\Framework\TestCase;

class JobIntelligenceTest extends TestCase
{
    public function test_title_score_separates_same_jobs_from_different_ones(): void
    {
        $this->assertGreaterThanOrEqual(0.9, TextSimilarity::titleScore('Staff Admin Gudang', 'Admin Gudang'));
        $this->assertGreaterThanOrEqual(0.9, TextSimilarity::titleScore('Sr. Frontend Engineer (React) - REQ12345', 'Senior Frontend Engineer (React)'));
        $this->assertLessThan(0.72, TextSimilarity::titleScore('Data Analyst', 'Data Engineer'));
        $this->assertLessThan(0.72, TextSimilarity::titleScore('Backend Engineer', 'Senior Backend Engineer'), 'different level is a different job');
        $score = TextSimilarity::titleScore('Customer Service', 'Customer Service Officer');
        $this->assertTrue($score >= 0.72 && $score < 0.9, 'gray zone goes to the AI judge');
    }

    public function test_simhash_is_close_for_rewritten_text_and_far_for_unrelated_text(): void
    {
        $text = 'Kami mencari frontend engineer berpengalaman membangun aplikasi web dengan React dan TypeScript untuk tim produk pembayaran kami di Jakarta';
        $rewritten = $text.' Kirim CV segera.';
        $other = 'Dibutuhkan sopir truk berpengalaman untuk pengiriman barang antar kota wilayah Jawa Timur dengan SIM B1 umum aktif';

        $this->assertGreaterThanOrEqual(0.8, TextSimilarity::simhashSimilarity(TextSimilarity::simhash($text), TextSimilarity::simhash($rewritten)));
        $this->assertLessThan(0.7, TextSimilarity::simhashSimilarity(TextSimilarity::simhash($text), TextSimilarity::simhash($other)));
        $this->assertNull(TextSimilarity::simhash('terlalu pendek'));
        $this->assertGreaterThanOrEqual(0, TextSimilarity::simhash($text), 'fits an unsigned BIGINT');
    }

    public function test_scam_rules(): void
    {
        $this->assertSame('suspected', ScamDetector::check('Admin Online', 'Wajib transfer biaya pendaftaran Rp 50.000')['flag']);
        $this->assertSame('suspected', ScamDetector::check('Admin', 'Kerja dari HP, gaji harian 300rb tanpa pengalaman.')['flag']);
        $this->assertSame('none', ScamDetector::check('Staff Akuntansi', 'Minimal S1 Akuntansi, menguasai Excel. Lamar melalui situs resmi.')['flag']);
        $this->assertSame('none', ScamDetector::check('Admin', 'Kirim CV via WhatsApp HRD')['flag'], 'one weak signal alone is not enough');
    }

    public function test_ats_jobs_are_kept_only_when_open_to_indonesia(): void
    {
        $this->assertTrue(IndonesiaLocation::keep('Jakarta, Indonesia', false));
        $this->assertTrue(IndonesiaLocation::keep('', true), 'remote with no country');
        $this->assertTrue(IndonesiaLocation::keep('Remote', true));
        $this->assertFalse(IndonesiaLocation::keep('Bangkok, Bangkok, Thailand', true), 'remote but tied to Thailand');
        $this->assertFalse(IndonesiaLocation::keep('Remote, Singapore', true));
        $this->assertFalse(IndonesiaLocation::keep('Singapore', false));
    }
}
