<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

use Auth;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = ActivityLog::where('tenant_id',session('tenant_id'))
    ->with('user')->latest()->paginate(2);
        return view('activitylog.index', compact('activities'));
    }

}
