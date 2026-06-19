<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'app_name',
                'value' => 'Task Manager',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Nama Aplikasi',
                'description' => 'Nama aplikasi yang ditampilkan di halaman',
                'is_editable' => true,
                'order' => 1,
            ],
            [
                'key' => 'app_description',
                'value' => 'Aplikasi Manajemen Tugas Kolaboratif',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Deskripsi Aplikasi',
                'description' => 'Deskripsi singkat aplikasi',
                'is_editable' => true,
                'order' => 2,
            ],
            [
                'key' => 'items_per_page',
                'value' => '15',
                'type' => 'integer',
                'group' => 'general',
                'label' => 'Item per Halaman',
                'description' => 'Jumlah item yang ditampilkan per halaman',
                'is_editable' => true,
                'order' => 3,
            ],
            
            // Upload Settings
            [
                'key' => 'max_upload_size',
                'value' => '2048',
                'type' => 'integer',
                'group' => 'upload',
                'label' => 'Ukuran Upload Maks (KB)',
                'description' => 'Ukuran maksimum file yang bisa diupload',
                'is_editable' => true,
                'order' => 1,
            ],
            [
                'key' => 'allowed_extensions',
                'value' => json_encode(['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']),
                'type' => 'json',
                'group' => 'upload',
                'label' => 'Ekstensi File yang Diizinkan',
                'description' => 'Daftar ekstensi file yang boleh diupload',
                'is_editable' => true,
                'order' => 2,
            ],
            
            // Notification Settings
            [
                'key' => 'enable_email_notification',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'label' => 'Aktifkan Notifikasi Email',
                'description' => 'Kirim notifikasi via email untuk tugas baru',
                'is_editable' => true,
                'order' => 1,
            ],
            [
                'key' => 'notification_email',
                'value' => 'noreply@taskmanager.com',
                'type' => 'string',
                'group' => 'notification',
                'label' => 'Email Pengirim Notifikasi',
                'description' => 'Email yang digunakan untuk mengirim notifikasi',
                'is_editable' => true,
                'order' => 2,
            ],
            
            // Security Settings
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => 'integer',
                'group' => 'security',
                'label' => 'Maksimal Percobaan Login',
                'description' => 'Jumlah percobaan login sebelum akun dikunci',
                'is_editable' => true,
                'order' => 1,
            ],
            [
                'key' => 'lockout_duration',
                'value' => '15',
                'type' => 'integer',
                'group' => 'security',
                'label' => 'Durasi Lockout (Menit)',
                'description' => 'Durasi akun dikunci setelah gagal login',
                'is_editable' => true,
                'order' => 2,
            ],
            [
                'key' => 'password_min_length',
                'value' => '8',
                'type' => 'integer',
                'group' => 'security',
                'label' => 'Panjang Minimum Password',
                'description' => 'Panjang minimum password untuk user',
                'is_editable' => true,
                'order' => 3,
            ],
        ];

        foreach ($settings as $setting) {
            // === GUNAKAN updateOrCreate AGAR AMAN DARI DUPLIKASI ===
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ System settings berhasil dibuat!');
    }
}