<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model {
    protected $fillable = [
        'project_id', 'floor_id', 'name', 'supplier',
        'unit', 'quantity', 'unit_price', 'total_cost',
        'stock_remaining', 'low_stock_alert', 'status', 'delivery_date'
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
    public function floor() {
        return $this->belongsTo(Floor::class);
    }
}