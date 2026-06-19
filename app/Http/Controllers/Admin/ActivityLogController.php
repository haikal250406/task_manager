<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');
        
        // === FILTER BY USER ===
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // === FILTER BY ACTION ===
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        // === FILTER BY MODEL TYPE ===
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }
        
        // === FILTER BY DATE RANGE ===
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // === SEARCH IN DESCRIPTION ===
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('description', 'like', "%{$search}%");
        }
        
        // === SORTING ===
        $logs = $query->latest()->paginate(20)->withQueryString();
        
        // === GET FILTER OPTIONS ===
        $users = User::orderBy('name')->get();
        $actions = ActivityLog::distinct()->pluck('action');
        $modelTypes = ActivityLog::distinct()->pluck('model_type');
        
        return view('admin.activity-logs.index', compact(
            'logs',
            'users',
            'actions',
            'modelTypes'
        ));
    }
    
    /**
     * Display the specified activity log
     */
    public function show(ActivityLog $log)
    {
        $log->load('user');
        
        return view('admin.activity-logs.show', compact('log'));
    }
    
    /**
     * Remove the specified activity log (for cleanup)
     */
    public function destroy(ActivityLog $log)
    {
        $log->delete();
        
        return back()->with('success', 'Activity log berhasil dihapus!');
    }
    
    /**
     * Clear all activity logs older than specified days
     */
    public function clearOld(Request $request)
    {
        $days = $request->input('days', 30);
        
        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();
        
        return back()->with('success', "{$deleted} activity logs lama berhasil dihapus!");
    }
}