<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === SELALU DIJALANKAN (production & development) ===
        $this->call([
            SystemSettingSeeder::class,
        ]);
        
        // === HANYA DIJALANKAN DI DEVELOPMENT ===
        if (app()->environment('local', 'development')) {
            $this->call([
                AdminUserSeeder::class,
            ]);
        }
    }
}