<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    public function index()
    {
        // Analytics cards are now handled by Livewire component
        // No need to pass data to view
        return view('tracker.index');
    }
}