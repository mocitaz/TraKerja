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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('xp')->default(0)->after('last_activity_at');
            $table->unsignedInteger('level')->default(1)->after('xp');
        });

        // Retroactive XP Backfill for existing users
        // This ensures old users get their deserved XP and levels based on existing data
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            $xp = 0;
            
            // +10 XP for each job application
            $totalJobs = $user->jobApplications()->count();
            $xp += $totalJobs * 10;
            
            // +50 XP for reaching any interview stage
            $interviewStages = ['HR - Interview', 'User - Interview', 'Psychotest', 'Assessment Test', 'Presentation Round', 'LGD'];
            $interviewCount = $user->jobApplications()->whereIn('recruitment_stage', $interviewStages)->count();
            $xp += $interviewCount * 50;

            // +100 XP for getting an offer
            $offeredCount = $user->jobApplications()->where('recruitment_stage', 'Offered')->count();
            $xp += $offeredCount * 100;

            // Calculate level
            $level = 1;
            if ($xp >= 1000) $level = 5;
            elseif ($xp >= 600) $level = 4;
            elseif ($xp >= 300) $level = 3;
            elseif ($xp >= 100) $level = 2;

            $user->update([
                'xp' => $xp,
                'level' => $level
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['xp', 'level']);
        });
    }
};
