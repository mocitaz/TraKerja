<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('category_field')->nullable()->after('description');
            $table->string('category_major')->nullable()->after('category_field');
            $table->string('work_type')->nullable()->after('category_major');
            
            // Index columns for fast search filtering
            $table->index(['category_field', 'category_major', 'work_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropIndex(['category_field', 'category_major', 'work_type']);
            $table->dropColumn(['category_field', 'category_major', 'work_type']);
        });
    }
};
