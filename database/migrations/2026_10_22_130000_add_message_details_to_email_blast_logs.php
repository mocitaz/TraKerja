<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('email_blast_logs', function (Blueprint $table) {
            $table->string('template_name')->nullable();
            $table->string('subject')->nullable();
            $table->string('language', 2)->nullable();
            $table->string('banner', 50)->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('email_blast_logs', fn (Blueprint $table) => $table->dropColumn(['template_name', 'subject', 'language', 'banner']));
    }
};
