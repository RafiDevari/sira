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
        // Handle search and filter
        $search = $request->input('search');
        $type = $request->input('type');

        $projects = Project::with('lead')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('nama', 'like', "%$search%")
                        ->orWhere('key', 'like', "%$search%");
                });
            })
            ->when($type, fn($q) => $q->where('tipe', $type))
                ->get();

        // Handle sorting
        $sort = $request->input('sort');
        // $allTypes = Project::select('tipe')->distinct()->pluck('tipe');

        // Sort projects based on the sort parameter
        if ($sort) {
            [$key, $direction] = explode(':', $sort);
            $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

            $projects = match ($key) {
                'name'      => $direction === 'asc' ? $projects->sortBy('nama') : $projects->sortByDesc('nama'),
                'key'       => $direction === 'asc' ? $projects->sortBy('key') : $projects->sortByDesc('key'),
                'type'      => $direction === 'asc' ? $projects->sortBy('tipe') : $projects->sortByDesc('tipe'),
                'lead'      => $direction === 'asc' ? $projects->sortBy(fn($p) => $p->lead->name ?? '') : $projects->sortByDesc(fn($p) => $p->lead->name ?? ''),
                'created_at', 'deadline', 'waktu_selesai' => $direction === 'asc'
                    ? $projects->sortBy('waktu_selesai')
                    : $projects->sortByDesc('waktu_selesai'),
                default => $projects,
            };
        }

        return view('projects.index', [
            'projects' => $projects,
            'users' => User::all(),
            'applications' => Application::all(),
            'search' => $search,
            // 'allTypes' => $allTypes, 
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

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'key' => 'required',
            'deskripsi' => 'required',
            'id_lead' => 'required|exists:users,id',
            'id_aplikasi' => 'required|exists:applications,id',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'tipe' => 'required',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully!');
    }


    
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function show($id, Request $request)
    {
        $project = Project::with([
            'sprints.tasks.user',
            'sprints.tasks.attachments.user'
        ])->findOrFail($id);

        $users = User::all();

        $search = $request->input('search');
        $type = $request->input('type');
            
        $project->sprints->each(function ($sprint) use ($search, $type) {
            $sprint->tasks = $sprint->tasks->filter(function ($task) use ($search, $type) {
                $matchesSearch = $search ? (
                    str_contains(strtolower($task->name), strtolower($search)) ||
                    str_contains(strtolower($task->key), strtolower($search))
                ) : true;

                $matchesType = $type ? strtolower($task->type) === strtolower($type) : true;

                return $matchesSearch && $matchesType;
            })->values();
        });

        return view('projects.show', compact('project', 'users'));
    }


}
