<?php

namespace Tests\Feature;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ModalAccessibilityTest extends TestCase
{
    public function test_modal_renders_requested_width_and_distinct_accessible_names(): void
    {
        $html = Blade::render('<x-modal name="first" maxWidth="sm" label="First dialog"><button>OK</button></x-modal><x-modal name="second" maxWidth="xl" labelledby="second-title"><h2 id="second-title">Second dialog</h2></x-modal>');
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $dialogs = $xpath->query('//*[@role="dialog"]');
        $this->assertCount(2, $dialogs);
        $this->assertSame('true', $dialogs[0]->getAttribute('aria-modal'));
        $this->assertSame('First dialog', $dialogs[0]->getAttribute('aria-label'));
        $this->assertSame('second-title', $dialogs[1]->getAttribute('aria-labelledby'));
        $this->assertNotSame($dialogs[0]->getAttribute('id'), $dialogs[1]->getAttribute('id'));
        $this->assertStringContainsString('sm:max-w-sm', $dialogs[0]->getElementsByTagName('div')[2]->getAttribute('class'));
        $this->assertStringContainsString('sm:max-w-xl', $dialogs[1]->getElementsByTagName('div')[2]->getAttribute('class'));
    }
}
