<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_blast_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('language', 2);
            $table->string('subject');
            $table->text('content');
            $table->string('button_text', 100)->nullable();
            $table->string('button_url', 500)->nullable();
            $table->text('source_prompt')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at');
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('email_blast_templates'); }
};
