<?php

namespace Tests\Unit;

use App\JobEngine\Adapters\RemoteEligibility;
use App\JobEngine\Normalize\IndonesianText;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class IndonesianTextTest extends TestCase
{
    public static function salaries(): array
    {
        return [
            'jt with decimal comma' => ['Rp 4,5jt - 5,2jt / bln', [4500000, 5200000, 'IDR', 'month']],
            'full rupiah' => ['Rp4.500.000 - Rp5.200.000', [4500000, 5200000, 'IDR', 'month']],
            'juta written once' => ['5-7 juta', [5000000, 7000000, 'IDR', 'month']],
            'daily ribu' => ['Rp 150rb/hari', [150000, null, 'IDR', 'day']],
            'usd monthly' => ['$1,000 - $2,000 per month', [1000, 2000, 'USD', 'month']],
            'yearly' => ['Rp 120.000.000 per tahun', [120000000, null, 'IDR', 'year']],
            'single juta' => ['3,5 juta', [3500000, null, 'IDR', 'month']],
            'negotiable' => ['Gaji kompetitif', null],
            'UMR without a number' => ['UMR Jakarta', null],
        ];
    }

    #[DataProvider('salaries')]
    public function test_salary_text(string $text, ?array $expected): void
    {
        $result = IndonesianText::salary($text);
        $this->assertSame($expected, $result ? [$result['min'], $result['max'], $result['currency'], $result['period']] : null);
    }

    public function test_salary_in_description_needs_a_salary_keyword(): void
    {
        $this->assertSame(4000000, IndonesianText::salaryFromDescription('Benefit: Gaji Rp 4.000.000 - 6.000.000 + insentif. Usia max 30')['min']);
        $this->assertNull(IndonesianText::salaryFromDescription('Usia maksimal 30 tahun, pengalaman 2 tahun, 5 hari kerja'));
    }

    public function test_seniority_only_when_the_title_says_so(): void
    {
        $this->assertSame('mid', IndonesianText::seniority('Business Development Assistant Manager'));
        $this->assertSame('senior', IndonesianText::seniority('Sr. Data Analyst'));
        $this->assertSame('intern', IndonesianText::seniority('Sales Trainee Academy'));
        $this->assertSame('manager', IndonesianText::seniority('Head of Marketing'));
        $this->assertSame('entry', IndonesianText::seniority('Fresh Graduate Program'));
        $this->assertNull(IndonesianText::seniority('Staff Admin Gudang'));
    }

    public function test_education_takes_the_lowest_level_in_an_education_context(): void
    {
        $this->assertSame('sma_smk', IndonesianText::educationMin('Pendidikan minimal SMA/SMK, diutamakan D3'));
        $this->assertSame('s1', IndonesianText::educationMin('Lulusan S1 Teknik Informatika'));
        $this->assertNull(IndonesianText::educationMin('Pengalaman 2 tahun, kartu SD tersedia'));
    }

    public function test_remote_jobs_open_to_applicants_in_indonesia(): void
    {
        foreach (['', 'Worldwide', 'Anywhere in the World', 'APAC', 'Asia', 'Indonesia', 'Southeast Asia'] as $ok) {
            $this->assertTrue(RemoteEligibility::openToIndonesia($ok), $ok);
        }
        foreach (['USA Only', 'Europe', 'UK, Germany', 'Canada'] as $no) {
            $this->assertFalse(RemoteEligibility::openToIndonesia($no), $no);
        }
    }
}
