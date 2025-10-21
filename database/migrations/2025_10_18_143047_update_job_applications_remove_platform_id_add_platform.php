<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['platform_id']);
            $table->dropColumn('platform_id');
            $table->string('platform')->default('JobStreet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreignId('platform_id')->constrained('custom_platforms')->onDelete('cascade');
            $table->dropColumn('platform');
        });
    }
};
