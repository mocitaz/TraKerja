<?php

namespace Tests\Unit;

use App\Support\JobRoleCatalog;
use PHPUnit\Framework\TestCase;

class JobRoleCatalogTest extends TestCase
{
    public function test_known_roles_and_localized_titles_choose_their_specialist_illustration(): void
    {
        foreach ([
            'Senior Software Engineer' => 'software', 'iOS Developer Intern' => 'mobile',
            'IT Project Manager' => 'project', 'Data Engineer' => 'data', 'ERP Developer' => 'erp',
            'Scrum Master' => 'agile', 'Senior React Native Developer' => 'mobile',
            'UI/UX Designer' => 'design', 'Senior DevOps Engineer' => 'cloud',
            'Staf Administrasi' => 'admin', 'Dokter' => 'health', 'Pengembang Perangkat Lunak' => 'software',
            'C++ Developer' => 'software', '.NET Developer' => 'software', 'Node.js Developer' => 'backend',
            'Senior JavaScript Developer' => 'frontend', 'SAP ABAP Developer' => 'erp',
        ] as $title => $field) {
            $this->assertSame($field, JobRoleCatalog::resolve($title)['id'], $title);
        }
    }

    public function test_unknown_and_partial_words_do_not_make_up_a_specialization(): void
    {
        foreach (['', null, 'Staff', 'Manager', 'Capital Markets Assistant', 'Computer Enthusiast', 'Designerish', 'Javascripted', '<script>alert(1)</script>'] as $title) {
            $this->assertSame('general', JobRoleCatalog::resolve($title)['id'], (string) $title);
        }
    }

    public function test_catalog_has_over_500_unique_titles_and_every_title_resolves_to_its_own_field(): void
    {
        $seen = [];
        foreach (JobRoleCatalog::all() as $id => $field) {
            $this->assertFileExists(dirname(__DIR__, 2).'/public/images/roles/'.$id.'.svg');
            foreach ($field['roles'] as $title) {
                $key = mb_strtolower($title);
                $this->assertArrayNotHasKey($key, $seen, 'Duplicate title: '.$title);
                $seen[$key] = true;
                $this->assertSame($id, JobRoleCatalog::resolve($title)['id'], $title);
            }
        }
        $this->assertGreaterThan(500, count($seen));
    }
}
