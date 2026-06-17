<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
        // Menampilkan semua proyek
    public function index(Request $request)
    {
        // Menangkap kata kunci dari kotak pencarian
        $search = $request->query('search');

        // Mengambil proyek, jika ada pencarian maka filter berdasarkan nama
        $projects = Project::withCount('tasks')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
            })
            ->get(); 

        return view('projects.index', compact('projects', 'search'));
    }
        // Menampilkan halaman form buat proyek
    public function create()
    {
        return view('projects.create');
    }
        // Menyimpan data proyek baru ke database
    public function store(Request $request)
    {
        // Validasi input agar nama proyek tidak boleh kosong
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Project::create($request->all());

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dibuat!');
    }

        // Menampilkan detail proyek beserta Papan Kanban (Tasks) di dalamnya
    public function show(Project $project)
    {
        // Memuat tugas-tugas yang terkait dengan proyek ini
        $tasks = $project->tasks; 
        return view('projects.show', compact('project', 'tasks'));
    }
    public function destroy(Project $project)
    {
        $project->delete(); # ini tidak akan menghapus permanen
        return back()->with('success', 'Proyek berhasil dipindahkan ke Tong Sampah.');
    }
}