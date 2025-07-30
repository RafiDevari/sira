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
            'type' => 'required|in:Bug,Feature,Task,Story,Request',
        ]);

        Task::create($request->all());
        $sprint = Sprint::find($request->sprint_id);
        return redirect()->route('projects.show', $sprint->id_project);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'name' => $request->name,
        ]);

        return back();
    }

    public function delete(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return back();
    }

    public function updateUser(Request $request, $id)
    {
        $task = Task::findOrFail($id); // <-- loads the existing Task

        $task->user_id = $request->user_id;
        $task->save();

        $sprint = $task->sprint;
        return redirect()->route('projects.show', $sprint->id_project);
    }


    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->status = $request->status;
        $task->save();

        return redirect()->back();
    }
    public function updateType(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:Bug,Feature,Task,Story,Request',
        ]);

        $task = Task::findOrFail($id);
        $task->type = $request->type;
        $task->save();

        return back(); // or use redirect()->route(...) if needed
    }



}
