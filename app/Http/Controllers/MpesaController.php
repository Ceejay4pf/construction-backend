<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\LabourInvoice;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class MpesaController extends Controller {
    private function getAccessToken() {
        $consumerKey = env('MPESA_CONSUMER_KEY');
        $consumerSecret = env('MPESA_CONSUMER_SECRET');
        $credentials = base64_encode($consumerKey . ':' . $consumerSecret);

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $credentials
        ])->get('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');

        $data = $response->json();
        
        if(!isset($data['access_token'])) {
            throw new \Exception('Failed to get access token: ' . json_encode($data));
        }
        
        return $data['access_token'];
    }

    public function stkPush(Request $request) {
        try {
            $accessToken = $this->getAccessToken();
            $shortcode = env('MPESA_SHORTCODE');
            $passkey = env('MPESA_PASSKEY');
            $timestamp = Carbon::now()->format('YmdHis');
            $password = base64_encode($shortcode . $passkey . $timestamp);
            
            $phone = $request->phone;
            if(substr($phone, 0, 1) === '0') {
                $phone = '254' . substr($phone, 1);
            }
            if(substr($phone, 0, 4) !== '2547') {
                $phone = '254' . $phone;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', [
                'BusinessShortCode' => $shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => intval($request->amount),
                'PartyA' => $phone,
                'PartyB' => $shortcode,
                'PhoneNumber' => $phone,
                'CallBackURL' => env('MPESA_CALLBACK_URL'),
                'AccountReference' => 'CSMS-' . $request->project_id,
                'TransactionDesc' => 'Labour Payment'
            ]);

            $data = $response->json();

            if(isset($data['CheckoutRequestID'])) {
                $payment = Payment::create([
                    'project_id' => $request->project_id,
                    'labour_invoice_id' => $request->labour_invoice_id ?? null,
                    'phone_number' => $phone,
                    'amount' => $request->amount,
                    'checkout_request_id' => $data['CheckoutRequestID'],
                    'merchant_request_id' => $data['MerchantRequestID'],
                    'status' => 'pending'
                ]);

                return response()->json([
                    'message' => 'STK Push sent successfully',
                    'checkout_request_id' => $data['CheckoutRequestID'],
                    'payment_id' => $payment->id
                ]);
            }

            return response()->json([
                'message' => 'STK Push failed',
                'data' => $data
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
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