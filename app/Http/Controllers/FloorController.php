<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function index($projectId)
    {
        $floors = Floor::where('project_id', $projectId)->get();
        return response()->json(['success' => true, 'data' => $floors]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id'   => 'required|exists:projects,id',
            'floor_number' => 'required|integer',
            'name'         => 'required|string',
            'status'       => 'in:pending,in_progress,completed',
        ]);

        $floor = Floor::create($request->all());
        return response()->json(['success' => true, 'data' => $floor], 201);
    }

    public function update(Request $request, $id)
    {
        $floor = Floor::findOrFail($id);
        $floor->update($request->all());
        return response()->json(['success' => true, 'data' => $floor]);
    }

    public function destroy($id)
    {
        Floor::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Floor deleted.']);
    }
}