<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Things Nara remembers about a user's job search ("remote frontend, around Rp10 jt, Jakarta"); kept until forgotten. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nara_memories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category', 20)->default('other'); // role, location, salary, work_mode, other
            $table->string('fact', 200);
            $table->timestamps();
            $table->unique(['user_id', 'fact']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nara_memories');
    }
};
