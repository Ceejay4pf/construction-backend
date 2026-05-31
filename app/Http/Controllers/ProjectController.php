<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // GET /api/projects
    public function index()
    {
        $projects = Project::with('floors')->get();
        return response()->json(['success' => true, 'data' => $projects]);
    }

    // POST /api/projects
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string',
            'location'   => 'required|string',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date',
            'budget'     => 'required|numeric',
        ]);

        $project = Project::create($request->all());
        return response()->json(['success' => true, 'data' => $project], 201);
    }

    // GET /api/projects/{id}
    public function show($id)
    {
        $project = Project::with(['floors', 'materials', 'payments'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $project]);
    }

    // PUT /api/projects/{id}
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->update($request->all());
        return response()->json(['success' => true, 'data' => $project]);
    }

    // DELETE /api/projects/{id}
    public function destroy($id)
    {
        Project::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Project deleted.']);
    }
}