<?php

namespace Tests\Feature;

use App\Livewire\JobApplicationForm;
use App\Services\JobListingExtractor;
use PHPUnit\Framework\TestCase;

class JobListingExtractionTest extends TestCase
{
    private function parse(string $html, string $url = 'https://www.linkedin.com/jobs/view/123'): array
    {
        return (new JobListingExtractor)->extract($html, $url);
    }

    public function test_linkedin_guest_page_uses_actual_company_location_and_complete_nested_description(): void
    {
        $r = $this->parse('<meta property="og:site_name" content="LinkedIn"><h1 class="top-card-layout__title">QA Engineer</h1><a class="topcard__org-name-link">PT Example &amp; Co</a><span class="topcard__flavor--bullet">Bandung, Jawa Barat</span><div class="show-more-less-html__markup"><p>Responsibilities</p><div><ul><li>Test APIs</li><li>Write reports</li></ul></div><p>Requirements: SQL and English.</p></div>');
        $this->assertSame('QA Engineer', $r['job_title']);
        $this->assertSame('PT Example & Co', $r['company_name']);
        $this->assertSame('Bandung, Jawa Barat', $r['location']);
        $this->assertStringContainsString('Requirements: SQL and English.', $r['description']);
        $this->assertStringContainsString('• Test APIs', $r['description']);
        $this->assertSame('', $r['career_level']);
    }

    public function test_jobstreet_state_handles_braces_inside_strings_and_maps_work_type(): void
    {
        $job = ['title' => 'Engineer {Backend};', 'advertiser' => ['name' => 'Example Ltd'], 'location' => ['label' => 'Jakarta'], 'content' => '<p>Build APIs</p>', 'workType' => ['label' => 'Contract']];
        $r = $this->parse('<script>window.SEEK_REDUX_DATA = '.json_encode(['jobdetails' => ['result' => ['job' => $job]]]).';window.other={};</script>', 'https://id.jobstreet.com/job/123');
        $this->assertSame('Example Ltd', $r['company_name']);
        $this->assertSame('Engineer {Backend};', $r['job_title']);
        $this->assertSame('Contract', $r['career_level']);
        $this->assertSame('Jakarta', $r['location']);
        $this->assertSame('JobStreet', $r['platform']);
    }

    public function test_glints_next_data_includes_description_and_internship_without_recommended_jobs(): void
    {
        $data = ['props' => ['pageProps' => ['initialData' => ['data' => ['title' => 'Product Design Intern', 'company' => ['name' => 'Example Studio'], 'city' => ['name' => 'Jakarta Selatan'], 'country' => ['name' => 'Indonesia'], 'description' => '<p>Design product interfaces.</p>', 'type' => 'INTERNSHIP']], 'dehydratedState' => ['queries' => [['queryKey' => ['recommendedJobs'], 'state' => ['data' => ['title' => 'Wrong job', 'company' => ['name' => 'Wrong company']]]]]]]]];
        $r = $this->parse('<script id="__NEXT_DATA__" type="application/json">'.json_encode($data).'</script>', 'https://glints.com/id/opportunities/jobs/product-design/123');
        $this->assertSame('Example Studio', $r['company_name']);
        $this->assertSame('Jakarta Selatan, Indonesia', $r['location']);
        $this->assertSame('Intern', $r['career_level']);
        $this->assertSame('Design product interfaces.', $r['description']);
    }

    public function test_jsonld_graph_refs_location_arrays_and_remote_work_are_preserved(): void
    {
        $job = ['@type' => ['Thing', 'JobPosting'], 'title' => 'Engineer', 'hiringOrganization' => ['@id' => '#company'], 'jobLocation' => [['address' => ['addressLocality' => 'Bandung', 'addressCountry' => ['name' => 'Indonesia']]], ['address' => ['addressLocality' => 'Jakarta']]], 'description' => '<p>Build things</p>', 'employmentType' => 'FULL_TIME', 'jobLocationType' => 'TELECOMMUTE'];
        $r = $this->parse('<script type="application/ld+json">'.json_encode(['@graph' => [['@id' => '#company', '@type' => 'Organization', 'name' => 'Example'], $job]]).'</script>');
        $this->assertSame('Example', $r['company_name']);
        $this->assertSame('Bandung, Indonesia / Jakarta', $r['location']);
        $this->assertSame('Remote', $r['work_mode']);
        $this->assertSame('Full Time', $r['career_level']);
    }

    public function test_matching_job_is_selected_instead_of_first_related_posting(): void
    {
        $jobs = [['@type' => 'JobPosting', 'url' => 'https://www.linkedin.com/jobs/view/999', 'title' => 'Wrong', 'hiringOrganization' => ['name' => 'Wrong']], ['@type' => 'JobPosting', 'url' => 'https://www.linkedin.com/jobs/view/123', 'title' => 'Right', 'hiringOrganization' => ['name' => 'Right Company']]];
        $r = $this->parse('<script type="application/ld+json">'.json_encode($jobs).'</script>');
        $this->assertSame('Right Company', $r['company_name']);
    }

    public function test_portal_is_never_assumed_to_be_employer_or_location(): void
    {
        $r = $this->parse('<meta property="og:site_name" content="Glints"><title>Engineer at Example | Glints</title>', 'https://glints.com/id/opportunities/jobs/123');
        $this->assertSame('Example', $r['company_name']);
        $this->assertSame('', $r['location']);
        $r = $this->parse('<title>Engineer | LinkedIn</title>');
        $this->assertSame('', $r['company_name']);
        $this->assertTrue($r['partial']);
    }

    public function test_blocked_page_does_not_report_autofill_success(): void
    {
        $r = $this->parse('<title>Just a moment...</title><h1>Security verification</h1>');
        $this->assertFalse($r['success']);
    }

    public function test_linkedin_employment_criteria_does_not_confuse_seniority_with_type(): void
    {
        $r = $this->parse('<h1>Engineer</h1><ul><li class="description__job-criteria-item"><h3 class="description__job-criteria-subheader">Seniority level</h3><span class="description__job-criteria-text">Internship</span></li><li class="description__job-criteria-item"><h3 class="description__job-criteria-subheader">Employment type</h3><span class="description__job-criteria-text">Full-time</span></li></ul>');
        $this->assertSame('Full Time', $r['career_level']);
    }

    public function test_jobstreet_visible_markup_and_html_meta_attribute_order(): void
    {
        $r = $this->parse('<meta content="Engineer | Jobstreet" property="og:title"><h1 data-automation="job-detail-title">Backend Engineer</h1><a data-automation="advertiser-name">Example Company</a><span data-automation="job-detail-location">Surabaya</span><span data-automation="job-detail-work-type">Full time</span><div data-automation="jobAdDetails"><div><p>Build APIs</p></div><p>SQL required</p></div>', 'https://id.jobstreet.com/job/123');
        $this->assertSame('Backend Engineer', $r['job_title']);
        $this->assertSame('Example Company', $r['company_name']);
        $this->assertSame('Full Time', $r['career_level']);
        $this->assertStringContainsString('SQL required', $r['description']);
    }

    public function test_location_autofill_does_not_invent_a_city_and_preserves_manual_location(): void
    {
        $form = new JobApplicationForm;
        $this->assertTrue($form->parseAutofillLocation('Jakarta, Indonesia'));
        $this->assertSame('DKI Jakarta', $form->selectedProvince);
        $this->assertSame('', $form->selectedCity);
        $this->assertFalse($form->parseAutofillLocation('Bandung, Jawa Barat'));
        $form = new JobApplicationForm;
        $this->assertTrue($form->parseAutofillLocation('Jakarta Selatan, Indonesia'));
        $this->assertSame('Jakarta Selatan', $form->selectedCity);
    }

    public function test_dealls_nested_detail_envelope_preserves_remote_type_and_requirements(): void
    {
        $job = ['title' => 'Support Specialist', 'company' => ['name' => 'Example Support'], 'location' => ['city' => 'Bandung', 'country' => 'Indonesia'],
            'employmentType' => 'CONTRACT', 'workArrangementOption' => 'REMOTE', 'description' => '<p>Help customers.</p>', 'requirements' => '<ul><li>English required</li></ul>'];
        $next = ['props' => ['pageProps' => ['dehydratedState' => ['queries' => [
            ['queryKey' => ['jobDetail', '123'], 'state' => ['data' => ['data' => ['job' => $job]]]],
            ['queryKey' => ['similarJobs'], 'state' => ['data' => ['title' => 'Wrong', 'company' => ['name' => 'Wrong']]]],
        ]]]]];
        $r = $this->parse('<script id="__NEXT_DATA__">'.json_encode($next).'</script>', 'https://dealls.com/loker/support-specialist');
        $this->assertSame('Dealls', $r['platform']);
        $this->assertSame('Example Support', $r['company_name']);
        $this->assertSame('Bandung, Indonesia', $r['location']);
        $this->assertSame('Remote', $r['work_mode']);
        $this->assertSame('Contract', $r['career_level']);
        $this->assertStringContainsString('English required', $r['description']);
    }

    public function test_talentics_quoted_vue_keys_extract_more_than_title_and_location(): void
    {
        $job = ['title' => 'Operations Intern', 'location' => 'Surabaya', 'employment_type' => 'internship',
            'description' => '<p>Manage daily operations.</p>', 'qualifications' => 'Excel skills required'];
        $html = '<script>new Vue({el: "#job-detail-page", data: {"job": '.json_encode($job).', "organization": {"company_name":"Example Operations"}}});</script>';
        $r = $this->parse($html, 'https://talentics.id/jobs/operations-intern');
        $this->assertSame('Talentics', $r['platform']);
        $this->assertSame('Example Operations', $r['company_name']);
        $this->assertSame('Intern', $r['career_level']);
        $this->assertSame('Surabaya', $r['location']);
        $this->assertStringContainsString('Excel skills required', $r['description']);
    }

    public function test_kalibrr_detail_payload_preserves_job_description_and_qualifications(): void
    {
        $job = ['name' => 'Data Analyst', 'companyInfo' => ['name' => 'Example Analytics'],
            'googleLocation' => ['formattedAddress' => 'Makati, Philippines'], 'job_description' => '<p>Analyze data.</p>',
            'minimum_qualifications' => 'SQL and statistics', 'employment_type' => 'full_time'];
        $next = ['props' => ['pageProps' => ['job' => $job]]];
        $r = $this->parse('<script id="__NEXT_DATA__">'.json_encode($next).'</script>', 'https://www.kalibrr.com/c/example/jobs/123/data-analyst');
        $this->assertSame('Kalibrr', $r['platform']);
        $this->assertSame('Example Analytics', $r['company_name']);
        $this->assertSame('Makati, Philippines', $r['location']);
        $this->assertSame('Full Time', $r['career_level']);
        $this->assertStringContainsString('SQL and statistics', $r['description']);
    }

    public function test_duplicate_detail_payload_is_not_mistaken_for_ambiguous_jobs(): void
    {
        $job = ['title' => 'Engineer', 'company' => ['name' => 'Example']];
        $next = ['props' => ['pageProps' => ['job' => $job, 'dehydratedState' => ['queries' => [
            ['queryKey' => ['jobDetail'], 'state' => ['data' => $job]],
        ]]]]];
        $r = $this->parse('<script id="__NEXT_DATA__">'.json_encode($next).'</script>', 'https://dealls.com/loker/engineer');
        $this->assertSame('Example',$r['company_name']);
    }
}
