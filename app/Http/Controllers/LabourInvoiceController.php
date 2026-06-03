<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabourInvoice;

class LabourInvoiceController extends Controller {
    public function index($projectId) {
        return response()->json(LabourInvoice::where('project_id', $projectId)->get());
    }

    public function store(Request $request) {
        try {
            $invoice = LabourInvoice::create([
                'project_id' => $request->project_id,
                'floor_id' => $request->floor_id ?? null,
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
                'description' => $request->description,
                'amount' => $request->amount,
                'paid_amount' => 0,
                'balance' => $request->amount,
                'status' => 'unpaid',
                'due_date' => $request->due_date ?? null
            ]);
            return response()->json($invoice, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            $invoice = LabourInvoice::findOrFail($id);
            $invoice->update($request->all());
            return response()->json($invoice);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id) {
        try {
            LabourInvoice::findOrFail($id)->delete();
            return response()->json(['message' => 'Invoice deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}