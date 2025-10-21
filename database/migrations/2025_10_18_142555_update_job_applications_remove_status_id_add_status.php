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
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
            $table->string('status')->default('Applied');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreignId('status_id')->constrained('custom_statuses')->onDelete('cascade');
            $table->dropColumn('status');
        });
    }
};
