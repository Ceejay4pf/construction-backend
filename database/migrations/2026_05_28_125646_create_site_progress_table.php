<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('site_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->string('activity');
            $table->text('description')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->integer('workers_present')->default(0);
            $table->string('stage')->default('excavation');
            $table->text('notes')->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('site_progress');
    }
};