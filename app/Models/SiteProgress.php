<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteProgress extends Model {
    protected $fillable = [
        'project_id', 'floor_id', 'activity',
        'description', 'progress_percentage',
        'workers_present', 'stage', 'notes', 'date'
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
    public function floor() {
        return $this->belongsTo(Floor::class);
    }
}