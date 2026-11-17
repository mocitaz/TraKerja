<?php

namespace Tests\Unit;

use App\JobEngine\Adapters\HimalayasAdapter;
use App\Models\Vacancy;
use Tests\TestCase;

class HimalayasAdapterTest extends TestCase
{
    public function test_long_country_lists_are_shortened_for_display(): void
    {
        $this->assertSame('Worldwide', HimalayasAdapter::locationLabel([]));
        $this->assertSame('Indonesia, Malaysia', HimalayasAdapter::locationLabel(['Indonesia', ['name' => 'Malaysia']]));
        $this->assertSame('Worldwide', HimalayasAdapter::locationLabel(array_map(fn ($i) => "Country {$i}", range(1, 25))));
    }

    public function test_salary_label_uses_the_stored_period_and_long_locations_are_compacted(): void
    {
        $hourly = new Vacancy(['salary_min' => 50, 'salary_max' => 100, 'salary_currency' => 'USD', 'salary_period' => 'hour']);
        $this->assertSame('USD 50–100/jam', $hourly->salary_label);

        $remote = new Vacancy(['location_text' => 'Afghanistan, Albania, Indonesia, Japan, Kenya']);
        $this->assertSame('Indonesia +4 negara', $remote->location);
    }
}
