<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Cover letters and recruiter messages can be revised in the chat; each revision is a new version of the first draft. */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['cover_letters', 'outreach_drafts'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->unsignedBigInteger('root_id')->nullable()->index();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('revision_note', 300)->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['cover_letters', 'outreach_drafts'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropIndex(['root_id']);
                $table->dropColumn(['root_id', 'parent_id', 'revision_note']);
            });
        }
    }
};
