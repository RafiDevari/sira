<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sprint;

class SprintController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'id_project' => 'required|exists:projects,id',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'status' => 'required',
        ]);

        
        Sprint::create($request->all());
        return redirect()->route('projects.show', $request->id_project);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'id_project' => 'required|exists:projects,id',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
        ]);

        $sprint = Sprint::findOrFail($id);
        $sprint->update($request->all());

        return redirect()->route('projects.show', $request->id_project)->with('success', 'Sprint updated successfully.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $sprint = Sprint::findOrFail($id);
        $sprint->status = $request->status;
        $sprint->save();

        return back(); // or redirect to another page if needed
    }


    public function destroy($id)
    {
        $sprint = Sprint::findOrFail($id);
        $projectId = $sprint->id_project;
        $sprint->delete();

        return redirect()->route('projects.show', $projectId)->with('success', 'Sprint deleted successfully.');
    }



}
