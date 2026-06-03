<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {
    protected $fillable = [
        'name', 'location', 'client_name', 'contractor_name',
        'budget', 'spent', 'total_floors', 'current_floor',
        'status', 'progress', 'start_date', 'end_date'
    ];

    public function floors() {
        return $this->hasMany(Floor::class);
    }
    public function boqItems() {
        return $this->hasMany(BoqItem::class);
    }
    public function labourInvoices() {
        return $this->hasMany(LabourInvoice::class);
    }
    public function payments() {
        return $this->hasMany(Payment::class);
    }
    public function materials() {
        return $this->hasMany(Material::class);
    }
    public function siteProgress() {
        return $this->hasMany(SiteProgress::class);
    }
}