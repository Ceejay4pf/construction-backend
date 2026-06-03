<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\LabourInvoice;

class PaymentController extends Controller {
    public function index($projectId) {
        return response()->json(Payment::where('project_id', $projectId)->get());
    }

    public function store(Request $request) {
        try {
            $payment = Payment::create([
                'project_id' => $request->project_id,
                'labour_invoice_id' => $request->labour_invoice_id ?? null,
                'phone_number' => $request->phone_number,
                'amount' => $request->amount,
                'status' => 'pending'
            ]);
            return response()->json($payment, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function callback(Request $request) {
        try {
            $data = $request->all();
            $checkoutId = $data['Body']['stkCallback']['CheckoutRequestID'] ?? null;
            if($checkoutId) {
                $payment = Payment::where('checkout_request_id', $checkoutId)->first();
                if($payment) {
                    $resultCode = $data['Body']['stkCallback']['ResultCode'];
                    if($resultCode == 0) {
                        $items = $data['Body']['stkCallback']['CallbackMetadata']['Item'];
                        $receipt = collect($items)->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;
                        $payment->update([
                            'status' => 'completed',
                            'mpesa_receipt' => $receipt
                        ]);
                        if($payment->labour_invoice_id) {
                            $invoice = LabourInvoice::find($payment->labour_invoice_id);
                            if($invoice) {
                                $invoice->paid_amount += $payment->amount;
                                $invoice->balance = $invoice->amount - $invoice->paid_amount;
                                $invoice->status = $invoice->balance <= 0 ? 'paid' : 'partial';
                                $invoice->save();
                            }
                        }
                    } else {
                        $payment->update(['status' => 'failed']);
                    }
                }
            }
            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}