<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\BugTask;
use App\Models\FeatureTask;
use Illuminate\Http\Request;
use Exception;

class TaskController extends Controller
{
    // Menampilkan halaman form untuk membuat tugas baru
    public function create(Request $request)
    {
        // Mengambil ID proyek dari URL
        $projectId = $request->query('project');
        
        // Mencari data proyek berdasarkan ID
        $project = \App\Models\Project::findOrFail($projectId);

        // Membuka halaman form 'tasks.create' dan membawa data proyek
        return view('tasks.create', compact('project'));
    }
    // Menyimpan tugas baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:Bug,Feature',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'deadline' => 'required|date|after_or_equal:today'
        ], [
            'deadline.after_or_equal' => 'Peringatan: Tenggat waktu tidak boleh di masa lalu! Pilih tanggal hari ini atau ke depannya.'
        ]);

        try {
            // INHERITANCE DALAM AKSI:
            // Menyimpan ke class turunan yang berbeda berdasarkan input 'type'
            if ($request->type === 'Bug') {
                BugTask::create($request->all());
            } else {
                FeatureTask::create($request->all());
            }

            return redirect()->route('projects.show', $request->project_id)->with('success', 'Tugas baru berhasil ditambahkan!');
        } catch (Exception $e) {
            // Menangkap error jika deadline di masa lalu (dari fungsi Enkapsulasi Model)
            return back()->with('error', $e->getMessage());
        }
    }

    // Mengubah status tugas (To Do -> In Progress -> Done)
    public function updateStatus(Request $request, Task $task)
    {
        try {
            // ENKAPSULASI DALAM AKSI:
            // Kita tidak mengubah $task->status secara langsung, melainkan lewat method khusus
            $task->changeStatus($request->status);
            
            // POLIMORFISME DALAM AKSI:
            // Mengambil pesan notifikasi yang berbeda tergantung priority
            $pesanNotifikasi = $task->getNotificationRule();

            return back()->with('success', "Status diperbarui! Aturan Notifikasi: " . $pesanNotifikasi);
        } catch (Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}