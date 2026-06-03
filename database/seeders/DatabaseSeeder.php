<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Project;
use App\Models\Floor;
use App\Models\BoqItem;
use App\Models\LabourInvoice;
use App\Models\Material;
use App\Models\SiteProgress;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // Clear all tables first
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('notifications')->truncate();
        DB::table('site_progress')->truncate();
        DB::table('materials')->truncate();
        DB::table('labour_invoices')->truncate();
        DB::table('payments')->truncate();
        DB::table('boq_items')->truncate();
        DB::table('floors')->truncate();
        DB::table('projects')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create users
        User::create(['name'=>'Admin User','email'=>'admin@gmail.com','password'=>Hash::make('1234'),'role'=>'admin']);
        User::create(['name'=>'Contractor','email'=>'contractor@gmail.com','password'=>Hash::make('1234'),'role'=>'contractor']);
        User::create(['name'=>'Client','email'=>'client@gmail.com','password'=>Hash::make('1234'),'role'=>'client']);
        User::create(['name'=>'QS Engineer','email'=>'qs@gmail.com','password'=>Hash::make('1234'),'role'=>'qs']);
        User::create(['name'=>'Foreman','email'=>'foreman@gmail.com','password'=>Hash::make('1234'),'role'=>'foreman']);

        // Create sample project
        $project = Project::create([
            'name' => 'Nairobi Heights Apartments',
            'location' => 'Westlands, Nairobi',
            'client_name' => 'John Kamau',
            'contractor_name' => 'BuildRight Ltd',
            'budget' => 15000000,
            'spent' => 4500000,
            'total_floors' => 5,
            'current_floor' => 2,
            'status' => 'active',
            'progress' => 35,
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31'
        ]);

        // Create floors
        $stages = ['foundation','slab','walling','roofing','finishing'];
        $statuses = ['completed','current','locked','locked','locked'];
        $progresses = [100, 60, 0, 0, 0];
        for($i = 1; $i <= 5; $i++) {
            Floor::create([
                'project_id' => $project->id,
                'floor_number' => $i,
                'name' => 'Floor ' . $i,
                'status' => $statuses[$i-1],
                'progress' => $progresses[$i-1],
                'stage' => $stages[$i-1]
            ]);
        }

        // BOQ items
        BoqItem::create([
            'project_id' => $project->id,
            'description' => 'Foundation Labour',
            'unit' => 'LS',
            'quantity' => 1,
            'unit_rate' => 500000,
            'total_amount' => 500000,
            'actual_cost' => 500000,
            'status' => 'approved',
            'type' => 'labour'
        ]);
        BoqItem::create([
            'project_id' => $project->id,
            'description' => 'Slab Labour Floor 2',
            'unit' => 'LS',
            'quantity' => 1,
            'unit_rate' => 350000,
            'total_amount' => 350000,
            'actual_cost' => 0,
            'status' => 'pending',
            'type' => 'labour'
        ]);

        // Labour invoice
        LabourInvoice::create([
            'project_id' => $project->id,
            'invoice_number' => 'INV-001',
            'description' => 'Foundation Labour Payment',
            'amount' => 500000,
            'paid_amount' => 300000,
            'balance' => 200000,
            'status' => 'partial',
            'due_date' => '2026-06-01'
        ]);

        // Materials
        Material::create([
            'project_id' => $project->id,
            'name' => 'Cement',
            'supplier' => 'Bamburi Cement',
            'unit' => 'Bags',
            'quantity' => 500,
            'unit_price' => 650,
            'total_cost' => 325000,
            'stock_remaining' => 120,
            'low_stock_alert' => 50,
            'status' => 'in_stock',
            'delivery_date' => '2026-05-01'
        ]);
        Material::create([
            'project_id' => $project->id,
            'name' => 'Steel Bars',
            'supplier' => 'Steel Masters',
            'unit' => 'Tonnes',
            'quantity' => 10,
            'unit_price' => 120000,
            'total_cost' => 1200000,
            'stock_remaining' => 3,
            'low_stock_alert' => 5,
            'status' => 'low_stock',
            'delivery_date' => '2026-05-10'
        ]);

        // Site progress
        SiteProgress::create([
            'project_id' => $project->id,
            'activity' => 'Slab Casting Floor 2',
            'description' => 'Casting of floor 2 slab in progress',
            'progress_percentage' => 60,
            'workers_present' => 15,
            'stage' => 'slab',
            'notes' => 'Weather good, work on schedule',
            'date' => now()->toDateString()
        ]);

        // Notifications
        Notification::create([
            'title' => 'Low Stock Alert',
            'message' => 'Steel Bars stock is running low - only 3 tonnes remaining',
            'type' => 'warning',
            'role' => 'contractor',
            'project_id' => $project->id,
            'is_read' => false
        ]);
        Notification::create([
            'title' => 'Invoice Pending Payment',
            'message' => 'Foundation Labour invoice has a balance of KES 200,000',
            'type' => 'info',
            'role' => 'client',
            'project_id' => $project->id,
            'is_read' => false
        ]);
    }
}