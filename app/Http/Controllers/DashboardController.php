<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Material;
use App\Models\Payment;
use App\Models\LabourInvoice;
use App\Models\SiteProgress;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_projects'  => Project::count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'total_payments'  => Payment::where('status', 'completed')->sum('amount'),
            'pending_invoices'=> LabourInvoice::where('status', 'pending')->count(),
            'low_materials'   => Material::whereColumn(
                                    'quantity_available', '<', 'quantity_used'
                                 )->count(),
            'recent_progress' => SiteProgress::with(['project', 'floor'])
                                    ->latest()->take(5)->get(),
            'recent_payments' => Payment::with('project')
                                    ->latest()->take(5)->get(),
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }
}