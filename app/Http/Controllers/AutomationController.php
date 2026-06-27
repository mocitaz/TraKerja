<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\JobApplication;

class AutomationController extends Controller
{
    /**
     * Show combined automation and CSV tools page
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin() || $user->role === 'admin') {
            return redirect()->route('admin.index');
        }

        // Active tab detection (?tab=extension or ?tab=csv)
        $activeTab = $request->query('tab', 'extension');
        if (!in_array($activeTab, ['extension', 'csv'])) {
            $activeTab = 'extension';
        }

        // Gather statistics
        $extensionSavedCount = JobApplication::where('user_id', $user->id)
            ->where('notes', 'like', '%Chrome Extension%')
            ->count();

        $totalJobsCount = JobApplication::where('user_id', $user->id)->count();

        // Check premium monetization status
        $isMonetizationEnabled = Setting::isMonetizationEnabled();
        $isPremium = $user->isPremium();
        $hasAccess = $isMonetizationEnabled ? $isPremium : true;

        return view('automation.index', compact(
            'activeTab',
            'hasAccess',
            'isPremium',
            'isMonetizationEnabled',
            'extensionSavedCount',
            'totalJobsCount'
        ));
    }
}
