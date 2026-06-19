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
        // Pastikan parameter proyek tersedia sebelum membuka form pembuatan tugas
        $projectId = $request->query('project');

        if (! $projectId) {
            return redirect()->route('projects.index')->with('error', 'Pilih proyek terlebih dahulu sebelum menambah tugas.');
        }

        $project = \App\Models\Project::find($projectId);

        if (! $project) {
            return redirect()->route('projects.index')->with('error', 'Proyek yang diminta tidak ditemukan. Silakan pilih proyek lain.');
        }

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
    
    // Fungsi untuk mengubah status tugas di Kanban
    public function updateStatus(\Illuminate\Http\Request $request, \App\Models\Task $task)
    {
        // Validasi
        $request->validate([
            'status' => 'required|in:To Do,In Progress,Done'
        ]);

        // Simpan perubahan ke database
        $task->update([
            'status' => $request->status
        ]);

        // Kembali ke halaman Kanban
        return back()->with('success', 'Status berhasil dipindah!');
    }
}