<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Menghitung total tugas, TAPI hanya yang proyeknya masih aktif (belum di-soft delete)
$totalTasks = Task::whereHas('project')->count();

// 2. Menghitung tugas selesai, TAPI hanya yang proyeknya masih aktif
$completedTasks = Task::whereHas('project')->where('status', 'done')->count();

// 3. Menghitung persentase progres (kodenya tetap sama seperti yang kamu miliki)
if ($totalTasks > 0) {
    $progressPercentage = round(($completedTasks / $totalTasks) * 100);
} else {
    $progressPercentage = 0;
}
        
        // Rumus Persentase Progres
        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // 2. Mengambil Peringatan Tugas Terlambat (Overdue)
        // Syarat overdue: Tanggal deadline lebih kecil dari hari ini, DAN statusnya belum "Done"
        $overdueTasks = Task::with('project')
                            ->whereDate('deadline', '<', Carbon::today())
                            ->where('status', '!=', 'Done')
                            ->get();

        return view('dashboard', compact( 
            'totalTasks', 
            'completedTasks', 
            'progressPercentage', 
            'overdueTasks'
        ));
    }
}