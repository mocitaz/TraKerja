<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Kelola Loker review queue + Threads promotions (TraKerja 2.0 admin). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vacancies', function (Blueprint $table) {
            $table->timestamp('admin_checked_at')->nullable()->after('scam_flag');
            $table->foreignId('admin_checked_by')->nullable()->after('admin_checked_at')->constrained('users')->nullOnDelete();
        });

        Schema::create('job_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')->constrained('vacancies')->cascadeOnDelete();
            $table->string('headline', 120);
            $table->json('highlights');
            $table->text('caption');
            $table->string('status', 12)->default('draft'); // draft, posted
            $table->timestamp('posted_at')->nullable();
            $table->string('post_url')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_promotions');
        if (Schema::hasColumn('vacancies', 'admin_checked_by')) {
            Schema::table('vacancies', function (Blueprint $table) {
                $table->dropConstrainedForeignId('admin_checked_by');
            });
        }
        if (Schema::hasColumn('vacancies', 'admin_checked_at')) {
            Schema::table('vacancies', fn (Blueprint $table) => $table->dropColumn('admin_checked_at'));
        }
    }
};
