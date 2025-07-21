<?php
namespace App\Http\Controllers;


use App\Models\Project;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $projects = Project::with('lead')
            ->when($search, fn($q) => $q->where('nama', 'like', "%$search%"))
            ->get();

        return view('projects.index', [
            'projects' => $projects,
            'users' => User::all(),
            'applications' => Application::all(),
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'key' => 'required|string',
            'deskripsi' => 'nullable|string',
            'id_lead' => 'required|exists:users,id',
            'id_aplikasi' => 'required|exists:applications,id',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'tipe' => 'required|string',
        ]);

        Project::create($request->only([
            'nama',
            'key',
            'deskripsi',
            'id_lead',
            'id_aplikasi',
            'waktu_mulai',
            'waktu_selesai',
            'tipe',
        ]));
        return redirect()->route('projects.index');
    }

    public function show($id)
    {
        $project = Project::with('sprints.tasks.user')->findOrFail($id);
        $users = User::all();
        return view('projects.show', compact('project', 'users'));
    }

}
