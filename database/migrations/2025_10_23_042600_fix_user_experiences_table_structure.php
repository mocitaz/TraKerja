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
        Schema::table('user_experiences', function (Blueprint $table) {
            // Rename 'role' to 'position' for better clarity
            $table->renameColumn('role', 'position');
        });

        Schema::table('user_experiences', function (Blueprint $table) {
            // Add employment_type column after position
            $table->string('employment_type', 50)->nullable()->after('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_experiences', function (Blueprint $table) {
            // Remove employment_type column
            $table->dropColumn('employment_type');
        });

        Schema::table('user_experiences', function (Blueprint $table) {
            // Rename back 'position' to 'role'
            $table->renameColumn('position', 'role');
        });
    }
};
