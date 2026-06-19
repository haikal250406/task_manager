<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // === SUPER ADMIN (gunakan updateOrCreate agar aman dari duplikasi) ===
        User::updateOrCreate(
            ['email' => 'admin@taskmanager.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => Carbon::now(),
                'last_login_at' => null,
                'phone' => '081234567890',
                'avatar' => null,
            ]
        );

        // === TEAM MEMBERS ===
        $users = [
            ['name' => 'M. Haikal', 'email' => 'haikal@taskmanager.com'],
            ['name' => 'Salman Al Farisi', 'email' => 'salman@taskmanager.com'],
            ['name' => 'Erliadi', 'email' => 'erliadi@taskmanager.com'],
            ['name' => 'Jefri Mulya Pratama', 'email' => 'jefri@taskmanager.com'],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make('password123'),
                    'role' => 'user',
                    'is_active' => true,
                    'email_verified_at' => Carbon::now(),
                    'last_login_at' => null,
                ])
            );
        }

        $this->command->info('✅ Admin dan users berhasil dibuat!');
        $this->command->info('📧 Login: admin@taskmanager.com / password123');
    }
}