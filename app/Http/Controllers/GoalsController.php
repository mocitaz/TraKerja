<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoalsController extends Controller
{
    /**
     * Display the goals and cadence management page
     */
    public function index()
    {
        return view('goals.index');
    }
}
