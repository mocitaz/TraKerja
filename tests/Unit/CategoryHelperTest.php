<?php

namespace Tests\Unit;

use App\Helpers\CategoryHelper;
use Tests\TestCase;

class CategoryHelperTest extends TestCase
{
    /** @test */
    public function it_lists_all_15_sektors()
    {
        $sektors = CategoryHelper::getSektorList();
        $this->assertCount(15, $sektors);
        $this->assertContains('Sektor Informasi dan Komunikasi (TIK)', $sektors);
        $this->assertContains('Sektor Pertanian, Kehutanan, dan Perikanan', $sektors);
    }

    /** @test */
    public function it_retrieves_majors_for_a_specific_sektor()
    {
        $majors = CategoryHelper::getMajorsForSektor('Sektor Informasi dan Komunikasi (TIK)');
        $this->assertContains('Teknik Informatika', $majors);
        $this->assertContains('Sistem Informasi', $majors);
        $this->assertNotContains('Teknik Pertambangan', $majors);
    }

    /** @test */
    public function it_classifies_jobs_into_correct_sektor_and_major_based_on_keywords()
    {
        // Test case 1: TIK - Software Engineer
        $result = CategoryHelper::classify('Backend Software Engineer (Laravel / PHP)', 'We are looking for a backend developer...');
        $this->assertEquals('Sektor Informasi dan Komunikasi (TIK)', $result['sektor']);
        $this->assertEquals('Teknik Informatika', $result['jurusan']);

        // Test case 2: Pertambangan - Geologist
        $result = CategoryHelper::classify('Junior Geologist', 'Requires knowledge in geological exploration and drilling...');
        $this->assertEquals('Sektor Pertambangan dan Penggalian', $result['sektor']);
        $this->assertEquals('Teknik Geologi', $result['jurusan']);

        // Test case 3: Pertanian - Agronomy
        $result = CategoryHelper::classify('Staff Agronomi Kebun', 'Memahami budidaya tanaman dan ilmu tanah...');
        $this->assertEquals('Sektor Pertanian, Kehutanan, dan Perikanan', $result['sektor']);
        $this->assertEquals('Agroteknologi', $result['jurusan']);
    }
}
