<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ============================================
        // 1. STATISTIK TUGAS (CASE-INSENSITIVE)
        // ============================================
        $totalTasks = Task::count();
        
        // Hitung tugas selesai (support multiple status variants)
        $completedTasks = Task::whereRaw('LOWER(status) IN (?, ?, ?, ?)', 
            ['done', 'completed', 'selesai', 'finish'])->count();
        
        // Hitung tugas in progress
        $inProgressTasks = Task::whereRaw('LOWER(status) IN (?, ?)', 
            ['in_progress', 'in progress'])->count();
        
        // Hitung tugas to do
        $toDoTasks = Task::whereRaw('LOWER(status) IN (?, ?, ?)', 
            ['to_do', 'todo', 'to do'])->count();
        
        // Hitung tugas terlambat (overdue)
        $overdueTasks = Task::whereRaw('LOWER(status) NOT IN (?, ?, ?, ?)', 
                ['done', 'completed', 'selesai', 'finish'])
            ->where('deadline', '<', now())
            ->whereNotNull('deadline')
            ->count();
        
        // Hitung persentase progres
        $progressPercentage = $totalTasks > 0 
            ? round(($completedTasks / $totalTasks) * 100) 
            : 0;
        
        // ============================================
        // 2. STATISTIK PROYEK
        // ============================================
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'active')->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $onHoldProjects = Project::where('status', 'on_hold')->count();
        
        // ============================================
        // 3. STATISTIK USER
        // ============================================
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();
        $adminUsers = User::where('role', 'admin')->count();
        $regularUsers = User::where('role', 'user')->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // ============================================
        // 4. STATISTIK TUGAS BERDASARKAN PRIORITAS
        // ============================================
        $tasksByPriority = [
            'Low' => Task::whereRaw('LOWER(priority) = ?', ['low'])->count(),
            'Medium' => Task::whereRaw('LOWER(priority) = ?', ['medium'])->count(),
            'High' => Task::whereRaw('LOWER(priority) = ?', ['high'])->count(),
            'Critical' => Task::whereRaw('LOWER(priority) = ?', ['critical'])->count(),
        ];
        
        // ============================================
        // 5. KUMPULKAN SEMUA STATISTIK
        // ============================================
        $stats = [
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'inProgressTasks' => $inProgressTasks,
            'toDoTasks' => $toDoTasks,
            'overdueTasks' => $overdueTasks,
            'progressPercentage' => $progressPercentage,
            'totalProjects' => $totalProjects,
            'activeProjects' => $activeProjects,
            'completedProjects' => $completedProjects,
            'onHoldProjects' => $onHoldProjects,
        ];
        
        $userStats = [
            'total' => $totalUsers,
            'active' => $activeUsers,
            'inactive' => $inactiveUsers,
            'admins' => $adminUsers,
            'users' => $regularUsers,
            'new_this_month' => $newUsersThisMonth,
        ];
        
        // ============================================
        // 6. DATA UNTUK GRAFIK & TABEL
        // ============================================
        
        // Aktivitas terbaru (10 terakhir)
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();
        
        // Tugas terbaru (10 terakhir)
        $recentTasks = Task::with(['project', 'user'])
            ->latest()
            ->limit(10)
            ->get();
        
        // Proyek terbaru (5 terakhir)
        $recentProjects = Project::with('user')
            ->latest()
            ->limit(5)
            ->get();
        
        // Tugas yang akan segera deadline (dalam 7 hari)
        $upcomingDeadlines = Task::with(['project', 'user'])
            ->whereRaw('LOWER(status) NOT IN (?, ?, ?, ?)', 
                ['done', 'completed', 'selesai', 'finish'])
            ->where('deadline', '>=', now())
            ->where('deadline', '<=', now()->addDays(7))
            ->orderBy('deadline', 'asc')
            ->limit(5)
            ->get();
        
        // ============================================
        // 7. RETURN VIEW
        // ============================================
        return view('admin.dashboard', compact(
            'stats',
            'userStats',
            'tasksByPriority',
            'recentActivities',
            'recentTasks',
            'recentProjects',
            'upcomingDeadlines'
        ));
    }
}