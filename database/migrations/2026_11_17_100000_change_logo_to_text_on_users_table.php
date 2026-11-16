<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * OAuth providers (Google, LinkedIn) return avatar URLs longer than 255
     * characters, which overflowed the VARCHAR(255) column on sign-up.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('logo')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('logo')->nullable()->change();
        });
    }
};
