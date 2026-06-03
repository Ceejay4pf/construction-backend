<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoqItem;

class BoqController extends Controller {
    public function index($projectId) {
        return response()->json(BoqItem::where('project_id', $projectId)->get());
    }

    public function store(Request $request) {
        try {
            $data = $request->all();
            $data['total_amount'] = floatval($request->quantity) * floatval($request->unit_rate);
            $boq = BoqItem::create($data);
            return response()->json($boq, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            $boq = BoqItem::findOrFail($id);
            $data = $request->all();
            if(isset($data['quantity']) && isset($data['unit_rate'])) {
                $data['total_amount'] = floatval($data['quantity']) * floatval($data['unit_rate']);
            }
            $boq->update($data);
            return response()->json($boq);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id) {
        try {
            BoqItem::findOrFail($id)->delete();
            return response()->json(['message' => 'BOQ item deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}