<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('portfolio_theme')->default('slate')->after('is_portfolio_published');
            $table->string('portfolio_custom_domain')->nullable()->after('portfolio_theme');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['portfolio_theme', 'portfolio_custom_domain']);
        });
    }
};
