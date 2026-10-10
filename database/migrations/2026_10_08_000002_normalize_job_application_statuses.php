<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('job_applications')->where(function ($query) {
            $query->whereNull('application_status')->orWhere('application_status', '');
        })->update(['application_status' => DB::raw('status')]);
        DB::table('job_applications')->where('application_status', 'Withdrawn')
            ->update(['application_status' => 'Rejected', 'recruitment_stage' => 'Not Processed']);
        DB::table('job_applications')->where('application_status', 'Declined')->update(['application_status' => 'Rejected']);
        foreach (['Applied' => 'Applied', 'Pending' => 'Applied', 'Interview' => 'HR - Interview', 'Offering' => 'Offering', 'Offered' => 'Offering'] as $status => $stage) {
            DB::table('job_applications')->where('application_status', $status)
                ->where(function ($query) {
                    $query->whereNull('recruitment_stage')->orWhereIn('recruitment_stage', ['', 'Applied']);
                })->update(['recruitment_stage' => $stage]);
            DB::table('job_applications')->where('application_status', $status)->update(['application_status' => 'On Process']);
        }
        DB::table('job_applications')->whereIn('application_status', ['On Process', 'Accepted', 'Rejected'])
            ->update(['status' => DB::raw('application_status')]);
    }

    public function down(): void
    {
        // Original aliases cannot be recovered after normalization.
    }
};
