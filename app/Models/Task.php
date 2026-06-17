<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Exception;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    // Menentukan tabel secara eksplisit karena nanti ada class turunan
    protected $table = 'tasks'; 

    protected $fillable = [
        'project_id', 'title', 'description', 'type', 'status', 'priority', 'deadline'
    ];

    // ==========================================
    // 1. ENKAPSULASI (Validasi Deadline)
    // ==========================================
    // Mencegah user memasukkan tanggal deadline di masa lalu
    public function setDeadlineAttribute($value)
    {
        if (Carbon::parse($value)->isPast()) {
            throw new Exception("Deadline tidak boleh diatur ke masa lalu!");
        }
        $this->attributes['deadline'] = $value;
    }

    // ==========================================
    // 2. ENKAPSULASI (Validasi Transisi Status)
    // ==========================================
    // Memastikan status hanya bisa berubah sesuai alur: To Do -> In Progress -> Done
    public function changeStatus($newStatus)
    {
        $validTransitions = [
            'To Do' => ['In Progress'],
            'In Progress' => ['Done', 'To Do'], // Bisa mundur ke To Do jika ada revisi
            'Done' => [] // Jika sudah Done, tidak bisa diubah lagi
        ];

        if (!in_array($newStatus, $validTransitions[$this->status])) {
            throw new Exception("Transisi status dari {$this->status} ke {$newStatus} tidak valid.");
        }

        $this->update(['status' => $newStatus]);
    }

    // ==========================================
    // 3. POLIMORFISME (Aturan Notifikasi)
    // ==========================================
    // Fungsi ini akan memiliki respons berbeda tergantung tingkat prioritas
    public function getNotificationRule()
    {
        return match($this->priority) {
            'Low' => 'Kirim email ringkasan mingguan.',
            'Medium' => 'Kirim notifikasi di dasbor setiap hari.',
            'High' => 'Kirim email peringatan dan notifikasi dasbor segera!',
            'Critical' => 'Kirim peringatan SMS dan email ke seluruh anggota tim sekarang!',
            default => 'Tidak ada aturan khusus.',
        };
    }
    
    // Relasi: Satu Task dimiliki oleh satu Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}