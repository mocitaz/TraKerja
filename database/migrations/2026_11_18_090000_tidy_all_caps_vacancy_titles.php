<?php

use App\JobEngine\Intelligence\TitleCase;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/** Data fix: ALL-CAPS vacancy titles in title case (slugs and URLs stay the same). */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('vacancies')->select('id', 'title')->orderBy('id')->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                $tidy = TitleCase::tidy((string) $row->title);
                if ($tidy !== $row->title) {
                    DB::table('vacancies')->where('id', $row->id)->update(['title' => $tidy]);
                }
            }
        });
    }

    public function down(): void
    {
        // Original casing is still on each listing (vacancy_listings.raw_title).
    }
};
