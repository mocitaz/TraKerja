<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SummaryWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_periods_include_the_first_day_and_archived_records_but_exclude_future_and_other_owners(): void
    {
        $this->travelTo(now('Asia/Jakarta')->setDate(2026, 10, 9)->setTime(12, 0));
        $user = User::factory()->create();
        foreach (['2026-10-03', '2026-10-02', '2026-09-10', '2026-09-09', '2026-10-10'] as $date) {
            JobApplication::factory()->create([
                'user_id' => $user->id, 'application_date' => $date, 'is_archived' => true,
                'application_status' => 'Rejected', 'recruitment_stage' => 'Applied',
                'platform' => 'LinkedIn', 'interview_date' => null,
            ]);
        }
        JobApplication::factory()->create(['application_date' => '2026-10-03', 'platform' => 'Secret owner source']);
        foreach (['weekly' => 1, 'monthly' => 3, 'all' => 4] as $filter => $expected) {
            $this->actingAs($user)->get(route('summary', ['timeFilter' => $filter]))
                ->assertOk()->assertViewHas('applicationsCount', $expected)
                ->assertViewHas('summary', fn ($data) => $data['archived'] === $expected && $data['statuses']['Rejected'] === $expected)
                ->assertViewHas('timelineData', fn ($data) => $data['applications']->sum() === $expected)
                ->assertDontSee('Secret owner source');
        }
    }

    public function test_all_time_totals_and_twelve_month_activity_use_distinct_windows_without_month_overflow(): void
    {
        $this->travelTo(now('Asia/Jakarta')->setDate(2026, 3, 31)->setTime(12, 0));
        $user = User::factory()->create();
        foreach (['2025-03-31', '2025-04-01', '2026-02-28', '2026-03-31'] as $date) {
            JobApplication::factory()->create(['user_id' => $user->id, 'application_date' => $date, 'interview_date' => null]);
        }
        $this->actingAs($user)->get(route('summary'))->assertOk()->assertViewHas('applicationsCount', 4)
            ->assertViewHas('timelineData', fn ($data) => count(array_unique($data['labels'])) === 12
                && $data['labels'][0] === 'Apr 2025' && $data['labels'][11] === 'Mar 2026'
                && $data['applications']->sum() === 3 && $data['applications'][10] === 1 && $data['applications'][11] === 1);
    }

    public function test_offers_are_a_separate_metric_and_interviews_use_scheduled_dates(): void
    {
        $this->travelTo(now('Asia/Jakarta')->setDate(2026, 10, 9)->setTime(12, 0));
        $user = User::factory()->create();
        JobApplication::factory()->create(['user_id' => $user->id, 'application_date' => '2026-10-03', 'application_status' => 'On Process', 'recruitment_stage' => 'Offering', 'platform' => 'LinkedIn', 'interview_date' => null]);
        JobApplication::factory()->create(['user_id' => $user->id, 'application_date' => '2026-10-04', 'application_status' => 'Accepted', 'recruitment_stage' => 'Offering', 'platform' => 'LinkedIn', 'interview_date' => null]);
        JobApplication::factory()->create(['user_id' => $user->id, 'application_date' => '2026-09-01', 'application_status' => 'On Process', 'recruitment_stage' => 'HR - Interview', 'platform' => 'JobStreet', 'interview_date' => '2026-10-09 10:00:00']);
        $this->actingAs($user)->get(route('summary', ['timeFilter' => 'weekly']))->assertOk()
            ->assertViewHas('summary', fn ($data) => $data['total'] === 2 && $data['offers'] === 2
                && $data['statuses']['On Process'] === 1 && $data['statuses']['Accepted'] === 1
                && $data['sources']->first()['total'] === 2)
            ->assertViewHas('timelineData', fn ($data) => $data['applications']->sum() === 2 && $data['interviews']->sum() === 1)
            ->assertSee('LinkedIn: 2 applications, 100 percent');
    }

    public function test_explorer_keeps_all_categories_and_groups_chart_overflow_without_losing_volume(): void
    {
        $owner = User::factory()->create();
        foreach (range(0, 8) as $index) {
            JobApplication::factory()->create([
                'user_id' => $owner->id, 'platform' => 'Source '.$index,
                'application_date' => today()->subDay(), 'interview_date' => null,
            ]);
        }
        JobApplication::factory()->create([
            'user_id' => $owner->id, 'platform' => 'Source 0',
            'application_date' => today()->subDay(), 'interview_date' => null,
        ]);
        $response = $this->actingAs($owner)->get(route('summary'))->assertOk()
            ->assertSee('Other Sources: 2 applications, 20 percent')
            ->assertSee('All sources');
        $document = new \DOMDocument();
        @$document->loadHTML($response->getContent());
        $xpath = new \DOMXPath($document);
        $segments = $xpath->query('//div[@id="summary-panel-sources"]//circle[@stroke-dasharray]');
        $this->assertCount(8, $segments);
        $share = 0;
        foreach ($segments as $segment) {
            $share += (float) explode(' ', $segment->getAttribute('stroke-dasharray'))[0];
        }
        $this->assertEquals(100, $share);
        $this->assertCount(9, $xpath->query('//div[@id="summary-panel-sources"]//details[contains(@class,"summary-chart-data")]//tbody/tr'));
    }

    public function test_location_map_combines_city_labels_and_keeps_unmatched_counts_off_map(): void
    {
        $rows = collect([
            ['name' => 'Semarang', 'total' => 3, 'interviews' => 1, 'accepted' => 1],
            ['name' => 'Kota Semarang, Jawa Tengah', 'total' => 2, 'interviews' => 0, 'accepted' => 1],
            ['name' => 'Bali', 'total' => 4, 'interviews' => 2, 'accepted' => 0],
            ['name' => 'Remote', 'total' => 6, 'interviews' => 1, 'accepted' => 0],
            ['name' => 'Unknown town', 'total' => 1, 'interviews' => 0, 'accepted' => 0],
        ]);
        $map = \App\Support\SummaryLocationMap::fromRows($rows);
        $this->assertCount(2, $map['mapped']);
        $this->assertSame(5, $map['maximum']);
        $this->assertSame('#496b60', $map['mapped'][0]['color']);
        $this->assertNotSame($map['mapped'][0]['color'], $map['mapped'][1]['color']);
        $this->assertNotEmpty($map['mapped'][0]['path']);
        $this->assertCount(4, explode(' ', $map['mapped'][0]['view']));
        $this->assertSame(31.3, $map['mapped'][0]['share']);
        $this->assertSame(5, $map['mapped'][0]['total']);
        $this->assertSame(2, $map['mapped'][0]['accepted']);
        $this->assertCount(2, $map['mapped'][0]['labels']);
        $this->assertEqualsWithDelta(328.36, $map['mapped'][0]['x'], .02);
        $this->assertSame('Denpasar', $map['mapped'][1]['name']);
        $this->assertSame(['Remote', 'Unknown town'], array_column($map['unmapped'], 'name'));
        $this->assertEquals(16, collect($map['mapped'])->sum('total') + collect($map['unmapped'])->sum('total'));
    }

    public function test_location_explorer_uses_city_areas_without_point_markers(): void
    {
        $owner = User::factory()->create();
        foreach (['Jakarta', 'Timika'] as $location) {
            JobApplication::factory()->create([
                'user_id' => $owner->id, 'location' => $location,
                'application_date' => today()->subDay(),
            ]);
        }
        $response = $this->actingAs($owner)->get(route('summary'))->assertOk()
            ->assertSee('Outside the map')->assertSee('Timika');
        $document = new \DOMDocument();
        @$document->loadHTML($response->getContent());
        $xpath = new \DOMXPath($document);
        $atlas = "//div[contains(@class, 'summary-region-atlas')]";
        $this->assertSame(0, $xpath->query($atlas."//*[local-name()='circle']")->length);
        $this->assertGreaterThan(0, $xpath->query($atlas."//*[local-name()='path' and contains(@class, 'summary-map-city')]")->length);
    }

    public function test_locations_without_boundaries_stay_in_the_unplotted_totals(): void
    {
        $map = \App\Support\SummaryLocationMap::fromRows(collect([
            ['name' => 'Timika', 'total' => 3, 'interviews' => 1, 'accepted' => 0],
            ['name' => 'Jakarta', 'total' => 2, 'interviews' => 0, 'accepted' => 1],
        ]));
        $this->assertCount(1, $map['mapped']);
        $this->assertSame('Jakarta', $map['mapped'][0]['name']);
        $this->assertSame(40.0, $map['mapped'][0]['share']);
        $this->assertSame('Timika', $map['unmapped'][0]['name']);
        $this->assertSame(5, collect($map['mapped'])->sum('total') + collect($map['unmapped'])->sum('total'));
        $this->assertNotEmpty($map['districts']);
        [$x, $y, $width, $height] = array_map('floatval', explode(' ', $map['mapped'][0]['view']));
        $this->assertGreaterThan(0, $width);
        $this->assertGreaterThan(0, $height);
    }

    public function test_empty_summary_and_premium_access_keep_honest_states(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Setting::set('monetization_enabled', false);
        $this->actingAs($user)->get(route('summary', ['timeFilter' => 'invalid']))->assertOk()
            ->assertViewHas('timeFilter', 'all')->assertSee('No activity in this window.')
            ->assertSee('Not recorded yet')->assertSee('No records in this period.');
        Setting::set('monetization_enabled', true);
        $this->actingAs($user)->get(route('summary'))->assertOk()->assertSee('Explore Premium')
            ->assertDontSee('id="summary-activity-title"', false);
    }
}
