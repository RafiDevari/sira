<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $applications = Application::all();
        $projects = Project::with('lead', 'application')->get();
        $sprints = Sprint::with('project')->get();

        return view('dashboard', compact('applications', 'projects', 'sprints'));
    }
}
