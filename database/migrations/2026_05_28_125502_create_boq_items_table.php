<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('boq_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->string('description');
            $table->string('unit');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('unit_rate', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('actual_cost', 15, 2)->default(0);
            $table->string('status')->default('pending');
            $table->string('type')->default('labour');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('boq_items');
    }
};