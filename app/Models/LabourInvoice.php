<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabourInvoice extends Model {
    protected $fillable = [
        'project_id', 'floor_id', 'invoice_number',
        'description', 'amount', 'paid_amount',
        'balance', 'status', 'due_date'
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
    public function floor() {
        return $this->belongsTo(Floor::class);
    }
    public function payments() {
        return $this->hasMany(Payment::class);
    }
}