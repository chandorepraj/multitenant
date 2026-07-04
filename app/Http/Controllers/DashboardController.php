<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Task;
use Auth;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $tenantId = session('tenant_id');
        if(Auth::user()->current_role === "Admin")
        {
            $pending_task = Task::where(['tenant_id'=>$tenantId,'status'=>'Pending'])->count();
            $todaysTasks = Task::where('tenant_id', session('tenant_id'))
                            ->whereDate('due_date', today())
                            ->where('status', 'Pending')
                            ->orderBy('due_date')
                            ->get();
        } 
        else  
        {
            $pending_task =Task::where(['tenant_id'=>$tenantId,'status'=>'Pending'])->count();
            $todaysTasks = Task::where('tenant_id', session('tenant_id'))
                            ->whereDate('due_date', today())
                            ->where('assigned_to',Auth::user()-id)
                            ->where('status', 'Pending')
                            ->orderBy('due_date')
                            ->get();
        }   

        $stats = [
            'customers' => Customer::where('tenant_id', $tenantId)->count(),
            'leads' => Lead::where('tenant_id', $tenantId)->count(),
            'users' => DB::table('tenant_user')
                ->where('tenant_id', $tenantId)
                ->count(),
            'pending_tasks' => $pending_task    
        ];
        $activities = ActivityLog::where('tenant_id',session('tenant_id'))
    ->with('user')->latest()->take(5)->get();
        return view('dashboard', compact('stats','activities','todaysTasks'));
    }
}
