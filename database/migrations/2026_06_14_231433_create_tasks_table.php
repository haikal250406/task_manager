<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
        $table->string('title');
        $table->text('description')->nullable();
        
        // Kolom 'type' ini akan kita gunakan untuk INHERITANCE (BugTask atau FeatureTask)
        $table->string('type'); 
        
        // Kolom 'status' untuk alur tugas: To Do -> In Progress -> Done
        $table->enum('status', ['To Do', 'In Progress', 'Done'])->default('To Do');
        
        // Kolom 'priority' untuk POLIMORFISME (aturan notifikasi yang berbeda)
        $table->enum('priority', ['Low', 'Medium', 'High', 'Critical']);
        
        // Kolom 'deadline' untuk ENKAPSULASI validasi tanggal
        $table->date('deadline');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
