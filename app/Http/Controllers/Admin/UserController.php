<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // === SEARCH ===
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // === FILTER ROLE ===
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // === FILTER STATUS ===
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // === SORTING ===
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);
        
        // === PAGINATION ===
        $users = $query->paginate(15)->withQueryString();
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }
    
    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'string', 'min:8', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,user',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
        ]);
        
        // Hash password
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');
        $validated['email_verified_at'] = now();
        
        // Create user
        $user = User::create($validated);
        
        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'model_type' => User::class,
            'model_id' => $user->id,
            'description' => "Admin membuat user baru: {$user->name} ({$user->email})",
            'new_values' => $user->only(['name', 'email', 'role', 'is_active']),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' berhasil ditambahkan!");
    }
    
    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Get user's activity logs
        $userActivities = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->limit(20)
            ->get();
        
        // Get user's projects
        $userProjects = $user->projects()->withCount('tasks')->latest()->limit(10)->get();
        
        // Get user's tasks
        $userTasks = $user->tasks()->with('project')->latest()->limit(10)->get();
        
        return view('admin.users.show', compact('user', 'userActivities', 'userProjects', 'userTasks'));
    }
    
    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        // Prevent editing self
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri!');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', 'string', 'min:8', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,user',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
        ]);
        
        // Store old values for logging
        $oldValues = $user->only(['name', 'email', 'role', 'is_active', 'phone']);
        
        // Update user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->role = $validated['role'];
        $user->is_active = $request->has('is_active');
        
        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model_type' => User::class,
            'model_id' => $user->id,
            'description' => "Admin mengupdate user: {$user->name}",
            'old_values' => $oldValues,
            'new_values' => $user->only(['name', 'email', 'role', 'is_active', 'phone']),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' berhasil diupdate!");
    }
    
    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }
        
        // Prevent deleting last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir!');
        }
        
        $userName = $user->name;
        $oldValues = $user->toArray();
        
        // Delete user (soft delete jika menggunakan SoftDeletes)
        $user->delete();
        
        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'model_type' => User::class,
            'model_id' => $user->id,
            'description' => "Admin menghapus user: {$userName}",
            'old_values' => $oldValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', "User '{$userName}' berhasil dihapus!");
    }
    
    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        // Prevent deactivating self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri!');
        }
        
        $oldStatus = $user->is_active;
        $user->is_active = !$user->is_active;
        $user->save();
        
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model_type' => User::class,
            'model_id' => $user->id,
            'description' => "Admin {$status} user: {$user->name}",
            'old_values' => ['is_active' => $oldStatus],
            'new_values' => ['is_active' => $user->is_active],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return back()->with('success', "User '{$user->name}' berhasil {$status}!");
    }
    
    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model_type' => User::class,
            'model_id' => $user->id,
            'description' => "Admin mereset password user: {$user->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return back()->with('success', "Password untuk '{$user->name}' berhasil direset!");
    }
}