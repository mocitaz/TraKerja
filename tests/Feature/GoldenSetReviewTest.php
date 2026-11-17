<?php

namespace Tests\Feature;

use App\Livewire\Admin\GoldenSetReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class GoldenSetReviewTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_admin' => true]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Storage::disk('local')->put('golden/labels/dealls.json', json_encode([
            [
                'id' => 'dealls-1', 'source' => 'dealls', 'url' => 'https://dealls.com/loker/a', 'reviewed' => false,
                'label' => ['title' => 'Staff Admin Gudang', 'company' => 'Sumber Rejeki', 'employment_type' => 'contract', 'is_internship' => false, 'sector' => null, 'city' => null, 'salary_min' => 4500000],
                'suggestion' => ['sector' => 'Sektor Transportasi, Logistik, dan Pergudangan', 'city' => 'Bekasi', 'seniority' => 'entry'],
            ],
            [
                'id' => 'dealls-2', 'source' => 'dealls', 'url' => 'https://dealls.com/loker/b', 'reviewed' => false,
                'label' => ['title' => 'Kasir', 'company' => null, 'employment_type' => null, 'is_internship' => false],
            ],
        ]));
        Storage::disk('local')->put('golden/raw/dealls/dealls-1.json', json_encode(['raw' => ['description' => '<p>Dibutuhkan admin gudang.</p>']]));
        Storage::disk('local')->put('golden/labels/_duplicates.json', json_encode([
            ['ids' => ['dealls-1', 'dealls-2'], 'key' => 'x', 'is_duplicate' => null, 'reviewed' => false],
        ]));
    }

    public function test_non_admin_cannot_open_the_page(): void
    {
        $this->actingAs(User::factory()->create())->get(route('admin.golden-set'))->assertForbidden();
    }

    public function test_reviewer_can_use_suggestions_and_save_a_label(): void
    {
        $component = Livewire::actingAs($this->admin())->test(GoldenSetReview::class)
            ->assertSet('currentId', 'dealls-1')
            ->assertSee('Dibutuhkan admin gudang.')
            ->call('fillEmptyFromSuggestion')
            ->assertSet('form.city', 'Bekasi')
            ->assertSet('form.sector', 'Sektor Transportasi, Logistik, dan Pergudangan')
            ->assertSet('form.salary_min', '4500000') // existing value kept
            ->set('form.salary_max', 'Rp 5.200.000')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('currentId', 'dealls-2');

        $saved = json_decode(Storage::disk('local')->get('golden/labels/dealls.json'), true)[0];
        $this->assertTrue($saved['reviewed']);
        $this->assertSame('Bekasi', $saved['label']['city']);
        $this->assertSame(5200000, $saved['label']['salary_max']);
        $this->assertSame('entry', $saved['label']['seniority']);
        $this->assertArrayHasKey('suggestion', $saved, 'the AI suggestion stays next to the label');
    }

    public function test_required_fields_and_enums_are_enforced(): void
    {
        Livewire::actingAs($this->admin())->test(GoldenSetReview::class)
            ->call('select', 'dealls-2')
            ->set('form.work_mode', 'wfh')
            ->call('save')
            ->assertHasErrors(['form.company', 'form.employment_type', 'form.work_mode'])
            ->assertSet('currentId', 'dealls-2');

        $this->assertFalse(json_decode(Storage::disk('local')->get('golden/labels/dealls.json'), true)[1]['reviewed']);
    }

    public function test_duplicate_pairs_can_be_confirmed(): void
    {
        Livewire::actingAs($this->admin())->test(GoldenSetReview::class)
            ->set('tab', 'duplicates')
            ->assertSee('Ya, loker yang sama')
            ->call('markDuplicate', 0, false);

        $pair = json_decode(Storage::disk('local')->get('golden/labels/_duplicates.json'), true)[0];
        $this->assertTrue($pair['reviewed']);
        $this->assertFalse($pair['is_duplicate']);
    }
}
