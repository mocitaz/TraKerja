<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The CV form accepts free-text GPA ("3.8/4.0", "3,75", "85/100"),
     * which a DECIMAL(3,2) column truncates and rejects in strict mode.
     */
    public function up(): void
    {
        Schema::table('user_educations', function (Blueprint $table) {
            $table->string('gpa', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('user_educations', function (Blueprint $table) {
            $table->decimal('gpa', 3, 2)->nullable()->change();
        });
    }
};
