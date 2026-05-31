<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Floor extends Model {
    protected $guarded = [];

    public function project() {
        return $this->belongsTo(Project::class);
    }
    public function boqItems() {
        return $this->hasMany(BoqItem::class);
    }
    public function materials() {
        return $this->hasMany(Material::class);
    }
    public function siteProgress() {
        return $this->hasMany(SiteProgress::class);
    }
    public function labourInvoices() {
        return $this->hasMany(LabourInvoice::class);
    }
}