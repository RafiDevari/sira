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

}
