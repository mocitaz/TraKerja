<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class LanguageCatalogueTest extends TestCase
{
    public function test_catalogues_have_matching_keys_and_preserve_placeholders(): void
    {
        $root = dirname(__DIR__, 2);
        $en = json_decode(file_get_contents($root.'/lang/en.json'), true, 512, JSON_THROW_ON_ERROR);
        $id = json_decode(file_get_contents($root.'/lang/id.json'), true, 512, JSON_THROW_ON_ERROR);
        $englishKeys = array_keys($en); $indonesianKeys = array_keys($id); sort($englishKeys); sort($indonesianKeys);
        $this->assertSame($englishKeys, $indonesianKeys);
        foreach (['en' => $en, 'id' => $id] as $locale => $catalog) {
            foreach ($catalog as $source => $translation) {
                $this->assertIsString($translation, $locale.': '.$source);
                $this->assertNotSame('', trim($translation), $locale.': '.$source);
                preg_match_all('/:[A-Za-z][A-Za-z0-9_]*/', $source, $expected);
                preg_match_all('/:[A-Za-z][A-Za-z0-9_]*/', $translation, $actual);
                $expected = array_unique($expected[0]); $actual = array_unique($actual[0]); sort($expected); sort($actual);
                $this->assertSame($expected, $actual, $locale.': '.$source);
            }
        }
    }
}
