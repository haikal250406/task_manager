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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            
            // Key setting (unique)
            $table->string('key', 100)
                  ->unique()
                  ->comment('Key setting: app_name, max_upload_size, dll');
            
            // Value setting
            $table->text('value')
                  ->nullable()
                  ->comment('Value dari setting');
            
            // Tipe data value
            $table->enum('type', ['string', 'boolean', 'integer', 'float', 'json', 'array'])
                  ->default('string')
                  ->comment('Tipe data value');
            
            // Group setting
            $table->string('group', 50)
                  ->default('general')
                  ->comment('Grup setting: general, notification, security, email, dll');
            
            // Label untuk tampilan
            $table->string('label')
                  ->nullable()
                  ->comment('Label tampilan: Nama Aplikasi, Ukuran Upload Maks, dll');
            
            // Deskripsi setting
            $table->text('description')
                  ->nullable()
                  ->comment('Deskripsi fungsi setting');
            
            // Apakah setting bisa diedit
            $table->boolean('is_editable')
                  ->default(true)
                  ->comment('Apakah setting bisa diedit via UI');
            
            // Urutan tampilan
            $table->integer('order')
                  ->default(0)
                  ->comment('Urutan tampilan di form');
            
            // Timestamps
            $table->timestamps();
            
            // Index
            $table->index(['group', 'order']);
            $table->index('is_editable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};