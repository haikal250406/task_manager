<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class SettingsController extends Controller
{
    /**
     * Display settings page grouped by category
     */
    public function index()
    {
        try {
            $settings = SystemSetting::orderBy('group')
                ->orderBy('order')
                ->get()
                ->groupBy('group');
            
            return view('admin.settings.index', compact('settings'));
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memuat pengaturan: ' . $e->getMessage());
        }
    }
    
    /**
     * Update system settings (bulk update)
     */
    public function update(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:1000',
        ], [
            'settings.required' => 'Data pengaturan wajib diisi',
            'settings.array' => 'Format data pengaturan tidak valid',
        ]);
        
        DB::beginTransaction();
        
        try {
            $oldValues = [];
            $newValues = [];
            $updatedCount = 0;
            
            foreach ($validated['settings'] as $key => $value) {
                // Cari setting berdasarkan key
                $setting = SystemSetting::where('key', $key)->first();
                
                // Skip jika setting tidak ada atau tidak bisa diedit
                if (!$setting || !$setting->is_editable) {
                    continue;
                }
                
                // Simpan nilai lama untuk logging
                $oldValues[$key] = $setting->value;
                
                // Konversi value sesuai tipe data
                $convertedValue = $this->convertValueByType($value, $setting->type);
                
                // Update setting
                $setting->update([
                    'value' => $convertedValue,
                ]);
                
                // Simpan nilai baru untuk logging
                $newValues[$key] = $convertedValue;
                $updatedCount++;
            }
            
            // Log activity jika ada perubahan
            if (!empty($oldValues)) {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'update',
                    'model_type' => SystemSetting::class,
                    'model_id' => null,
                    'description' => "Admin mengupdate {$updatedCount} pengaturan sistem",
                    'old_values' => $oldValues,
                    'new_values' => $newValues,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                ]);
            }
            
            DB::commit();
            
            return back()->with('success', "Pengaturan sistem berhasil disimpan! ({$updatedCount} pengaturan diupdate)");
            
        } catch (Exception $e) {
            DB::rollBack();
            
            // Log error
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'error',
                'model_type' => SystemSetting::class,
                'description' => 'Gagal mengupdate pengaturan: ' . $e->getMessage(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return back()->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage())
                        ->withInput();
        }
    }
    
    /**
     * Show edit form for specific setting
     */
    public function edit(SystemSetting $setting)
    {
        if (!$setting->is_editable) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Setting ini tidak dapat diedit!');
        }
        
        return view('admin.settings.edit', compact('setting'));
    }
    
    /**
     * Update specific setting (single update)
     */
    public function updateSetting(Request $request, SystemSetting $setting)
    {
        if (!$setting->is_editable) {
            return back()->with('error', 'Setting ini tidak dapat diedit!');
        }
        
        $validated = $request->validate([
            'value' => 'nullable|string|max:1000',
        ], [
            'value.max' => 'Nilai maksimal 1000 karakter',
        ]);
        
        try {
            $oldValue = $setting->value;
            $newValue = $this->convertValueByType($validated['value'] ?? '', $setting->type);
            
            $setting->update(['value' => $newValue]);
            
            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'update',
                'model_type' => SystemSetting::class,
                'model_id' => $setting->id,
                'description' => "Admin mengupdate setting: {$setting->label}",
                'old_values' => ['value' => $oldValue],
                'new_values' => ['value' => $newValue],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return back()->with('success', "Setting '{$setting->label}' berhasil diupdate!");
            
        } catch (Exception $e) {
            return back()->with('error', 'Gagal mengupdate setting: ' . $e->getMessage());
        }
    }
    
    /**
     * Reset setting to default value
     */
    public function reset(Request $request, SystemSetting $setting)
    {
        if (!$setting->is_editable) {
            return back()->with('error', 'Setting ini tidak dapat direset!');
        }
        
        try {
            $oldValue = $setting->value;
            
            // Default values berdasarkan key
            $defaultValues = [
                'app_name' => 'Task Manager',
                'app_description' => 'Aplikasi Manajemen Tugas Kolaboratif',
                'items_per_page' => '15',
                'max_upload_size' => '2048',
                'allowed_extensions' => json_encode(['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']),
                'enable_email_notification' => '1',
                'notification_email' => 'noreply@taskmanager.com',
                'max_login_attempts' => '5',
                'lockout_duration' => '15',
                'password_min_length' => '8',
            ];
            
            $defaultValue = $defaultValues[$setting->key] ?? '';
            
            $setting->update(['value' => $defaultValue]);
            
            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'update',
                'model_type' => SystemSetting::class,
                'model_id' => $setting->id,
                'description' => "Admin mereset setting ke default: {$setting->label}",
                'old_values' => ['value' => $oldValue],
                'new_values' => ['value' => $defaultValue],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return back()->with('success', "Setting '{$setting->label}' berhasil direset ke default!");
            
        } catch (Exception $e) {
            return back()->with('error', 'Gagal mereset setting: ' . $e->getMessage());
        }
    }
    
    /**
     * Reset ALL settings to default values
     */
    public function resetAll(Request $request)
    {
        // Konfirmasi keamanan
        if (!$request->has('confirm') || $request->confirm !== 'yes') {
            return back()->with('error', 'Konfirmasi reset tidak valid!');
        }
        
        DB::beginTransaction();
        
        try {
            $defaultValues = [
                'app_name' => 'Task Manager',
                'app_description' => 'Aplikasi Manajemen Tugas Kolaboratif',
                'items_per_page' => '15',
                'max_upload_size' => '2048',
                'allowed_extensions' => json_encode(['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']),
                'enable_email_notification' => '1',
                'notification_email' => 'noreply@taskmanager.com',
                'max_login_attempts' => '5',
                'lockout_duration' => '15',
                'password_min_length' => '8',
            ];
            
            $oldValues = [];
            $newValues = [];
            $resetCount = 0;
            
            foreach ($defaultValues as $key => $defaultValue) {
                $setting = SystemSetting::where('key', $key)->first();
                
                if ($setting && $setting->is_editable) {
                    $oldValues[$key] = $setting->value;
                    $setting->update(['value' => $defaultValue]);
                    $newValues[$key] = $defaultValue;
                    $resetCount++;
                }
            }
            
            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'update',
                'model_type' => SystemSetting::class,
                'description' => "Admin mereset SEMUA pengaturan ke default ({$resetCount} setting)",
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return back()->with('success', "Semua pengaturan berhasil direset ke default! ({$resetCount} setting)");
            
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mereset pengaturan: ' . $e->getMessage());
        }
    }
    
    /**
     * Helper: Konversi value sesuai tipe data
     */
    private function convertValueByType($value, $type)
    {
        return match($type) {
            'boolean' => $value ? '1' : '0',
            'integer' => (string) intval($value),
            'float' => (string) floatval($value),
            'json', 'array' => is_array($value) ? json_encode($value) : $value,
            default => (string) $value,
        };
    }
}