<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total tugas
        $totalTasks = Task::count();
        
        // Hitung tugas selesai (case-insensitive untuk berbagai variasi status)
        $completedTasks = Task::whereRaw('LOWER(status) IN (?, ?, ?)', ['done', 'completed', 'selesai'])->count();
        
        // Hitung tugas pending (yang belum selesai)
        $pendingTasks = Task::whereRaw('LOWER(status) NOT IN (?, ?, ?)', ['done', 'completed', 'selesai'])->count();
        
        // Hitung tugas terlambat (overdue)
        $overdueTasks = Task::whereRaw('LOWER(status) NOT IN (?, ?, ?)', ['done', 'completed', 'selesai'])
                           ->where('deadline', '<', now())
                           ->count();
        
        // Hitung proyek aktif
        $activeProjects = Project::where('status', 'active')->count();
        
        // Hitung persentase progres
        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        // Data untuk recent projects & tasks
        $recentProjects = Project::latest()->limit(5)->get();
        $recentTasks = Task::with('project')->latest()->limit(5)->get();

        return view('dashboard', compact(
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'overdueTasks',
            'activeProjects',
            'progressPercentage',
            'recentProjects',
            'recentTasks'
        ));
    }
}