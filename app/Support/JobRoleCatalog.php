<?php

namespace App\Support;

use Illuminate\Support\Str;

final class JobRoleCatalog
{
    private static ?array $fields = null;
    private static ?array $index = null;

    public static function all(): array
    {
        if (self::$fields !== null) {
            return self::$fields;
        }

        $directory = dirname(__DIR__, 2).'/resources/data/';
        $fields = json_decode(file_get_contents($directory.'job-roles.json'), true, 512, JSON_THROW_ON_ERROR);
        $specialists = json_decode(file_get_contents($directory.'job-role-specialists.json'), true, 512, JSON_THROW_ON_ERROR);
        $specialistTitles = [];
        foreach ($specialists as $field) {
            foreach ($field['roles'] as $title) {
                $specialistTitles[self::normalize($title)] = true;
            }
        }

        // Move existing titles into their more specific profession without duplicating matches.
        foreach ($fields as &$field) {
            $field['roles'] = array_values(array_filter(
                $field['roles'],
                fn (string $title) => !isset($specialistTitles[self::normalize($title)])
            ));
        }
        unset($field);

        return self::$fields = array_merge($fields, $specialists);
    }

    public static function resolve(?string $title): array
    {
        $normalized = self::normalize($title ?? '');
        if (self::$index === null) {
            self::$index = [];
            foreach (self::all() as $id => $field) {
                foreach ($field['roles'] as $role) {
                    self::$index[] = ['id' => $id, 'label' => $field['label'], 'role' => $role, 'phrase' => self::normalize($role)];
                }
            }
            // A specific phrase must win over a shorter role inside it.
            usort(self::$index, fn (array $a, array $b) => strlen($b['phrase']) <=> strlen($a['phrase']));
        }

        if ($normalized !== '') {
            foreach (self::$index as $entry) {
                if (str_contains(' '.$normalized.' ', ' '.$entry['phrase'].' ')) {
                    return ['id' => $entry['id'], 'label' => $entry['label'], 'matched_role' => $entry['role'], 'asset' => 'images/role-avatars/'.$entry['id'].'.svg'];
                }
            }
        }

        return ['id' => 'general', 'label' => 'General role', 'matched_role' => null, 'asset' => 'images/role-avatars/general.svg'];
    }

    private static function normalize(string $title): string
    {
        $title = Str::ascii(mb_strtolower($title));
        $title = str_replace(['c++', 'c#', '.net'], ['cpp', 'csharp', 'dotnet'], $title);
        return trim(preg_replace('/[^a-z0-9]+/', ' ', $title));
    }
}
