<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;

class MaterialController extends Controller {
    public function index($projectId) {
        return response()->json(Material::where('project_id', $projectId)->get());
    }

    public function store(Request $request) {
        try {
            $data = $request->all();
            $data['total_cost'] = floatval($request->quantity) * floatval($request->unit_price);
            $data['stock_remaining'] = floatval($request->quantity);
            $data['status'] = 'in_stock';
            $material = Material::create($data);
            return response()->json($material, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            $material = Material::findOrFail($id);
            $data = $request->all();
            if(isset($data['quantity']) && isset($data['unit_price'])) {
                $data['total_cost'] = floatval($data['quantity']) * floatval($data['unit_price']);
            }
            $material->update($data);
            return response()->json($material);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id) {
        try {
            Material::findOrFail($id)->delete();
            return response()->json(['message' => 'Material deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}