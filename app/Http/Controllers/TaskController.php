<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Sprint;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'sprint_id' => 'required|exists:sprints,id',
            'status' => 'required',
            'user_id' => 'nullable|exists:users,id',
        ]);

        Task::create($request->all());
        $sprint = Sprint::find($request->sprint_id);
        return redirect()->route('projects.show', $sprint->id_project);
    }

    public function updateUser(Request $request, Task $task)
    {
        $task->user_id = $request->user_id;
        $task->save();

        $sprint = $task->sprint;
        return redirect()->route('projects.show', $sprint->id_project);
    }

}
