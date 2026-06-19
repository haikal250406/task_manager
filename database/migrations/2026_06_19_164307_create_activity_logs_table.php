<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke user yang melakukan aksi
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('User yang melakukan aksi');
            
            // Jenis aksi
            $table->string('action', 50)
                  ->comment('Jenis aksi: create, update, delete, login, logout, dll');
            
            // Model yang di-aksi (polymorphic)
            $table->string('model_type', 100)
                  ->nullable()
                  ->comment('Tipe model: App\Models\Task, App\Models\Project, dll');
            
            $table->unsignedBigInteger('model_id')
                  ->nullable()
                  ->comment('ID dari model yang di-aksi');
            
            // Deskripsi aktivitas
            $table->text('description')
                  ->nullable()
                  ->comment('Deskripsi detail aktivitas');
            
            // Data lama dan baru (untuk tracking perubahan)
            $table->json('old_values')
                  ->nullable()
                  ->comment('Data sebelum perubahan (JSON)');
            
            $table->json('new_values')
                  ->nullable()
                  ->comment('Data setelah perubahan (JSON)');
            
            // Informasi teknis
            $table->string('ip_address', 45)
                  ->nullable()
                  ->comment('IP address user');
            
            $table->string('user_agent')
                  ->nullable()
                  ->comment('Browser/device user');
            
            $table->string('url')
                  ->nullable()
                  ->comment('URL halaman yang diakses');
            
            $table->string('method', 10)
                  ->nullable()
                  ->comment('HTTP method: GET, POST, PUT, DELETE');
            
            // Timestamps
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};