<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $fillable = [
        'project_id', 'labour_invoice_id', 'phone_number',
        'amount', 'mpesa_receipt', 'checkout_request_id',
        'merchant_request_id', 'status', 'result_description'
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
    public function labourInvoice() {
        return $this->belongsTo(LabourInvoice::class);
    }
}