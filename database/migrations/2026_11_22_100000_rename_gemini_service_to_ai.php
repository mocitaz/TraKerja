<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/** Gemini was replaced by OpenAI: incidents and service checks now use the provider-neutral "ai" service. */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('operation_incidents')->where('service', 'gemini')->update(['service' => 'ai']);
        DB::table('operation_service_checks')->where('service', 'gemini')->update(['service' => 'ai']);
    }

    public function down(): void
    {
        DB::table('operation_incidents')->where('service', 'ai')->update(['service' => 'gemini']);
        DB::table('operation_service_checks')->where('service', 'ai')->update(['service' => 'gemini']);
    }
};
