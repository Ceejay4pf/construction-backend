<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteProgress;
use App\Models\Floor;

class SiteProgressController extends Controller {
    public function index($projectId) {
        return response()->json(SiteProgress::where('project_id', $projectId)->orderBy('date', 'desc')->get());
    }

    public function store(Request $request) {
        try {
            $progress = SiteProgress::create([
                'project_id' => $request->project_id,
                'floor_id' => $request->floor_id ?? null,
                'activity' => $request->activity,
                'description' => $request->description ?? null,
                'progress_percentage' => $request->progress_percentage,
                'workers_present' => $request->workers_present ?? 0,
                'stage' => $request->stage ?? 'excavation',
                'notes' => $request->notes ?? null,
                'date' => $request->date
            ]);

            if($request->floor_id) {
                $floor = Floor::find($request->floor_id);
                if($floor) {
                    $floor->update([
                        'progress' => $request->progress_percentage,
                        'stage' => $request->stage ?? $floor->stage
                    ]);
                }
            }

            return response()->json($progress, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}