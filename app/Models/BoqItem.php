<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoqItem extends Model {
    protected $guarded = [];

    public function project() {
        return $this->belongsTo(Project::class);
    }
    public function floor() {
        return $this->belongsTo(Floor::class);
    }
}