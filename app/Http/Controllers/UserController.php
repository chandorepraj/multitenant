<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Invitation;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $this->authorize('viewAny', User::class);

        $tenant = Tenant::findOrFail(session('tenant_id'));

        $users = $tenant->users()->paginate(2);

        $invitations = Invitation::where(['tenant_id'=>session('tenant_id'),'is_valid'=>1])->paginate(2);

        return view('users.index', compact('users','invitations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function update_tenant(Request $request)
    {
        //update session for selected tenant id and then logged in to system
        if(!empty($request->tenant_id))
        {
            session(['tenant_id'=>$request->tenant_id]);
            return redirect(route('dashboard'));
        }
        else
             return redirect(route('tenants.select'));
    }
    public function update_role(Request $request):RedirectResponse
    {
        $this->authorize('update', $request);
        //update role;
        DB::table('tenant_user')
        ->where(['user_id'=>$request->user_id,'tenant_id'=>session('tenant_id')])
        ->update([
            'role' => $request->role
        ]);
        return redirect(route('users.index'));
    }
    public function team_delete(int $user_id):RedirectResponse
    {
        $member = User::findOrFail($user_id);
        $this->authorize('delete', $member);
       
        //delete member
        DB::table('tenant_user')
        ->where(['user_id'=>$user_id,'tenant_id'=>session('tenant_id')])
        ->delete();
        return redirect(route('users.index'));
    }
}
