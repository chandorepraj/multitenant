<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Str;
use App\Http\Requests\InvitationStoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(InvitationStoreRequest $request): RedirectResponse
    {
        $tenantId = session('tenant_id');
        $user = User::where('email', $request->email)->first();
        if (
            $user &&
            $user->tenants()
                ->where('tenant_id', $tenantId)
                ->exists()
        ) {
            return back()->withErrors([
                'email' => 'This user is already a member of your organization.',
            ]);
        }
        //
        $validatedData = $request->validated();
        $postData = array_merge($validatedData, [
            'tenant_id' => $tenantId,
            'is_valid' =>1,
            'token' => Str::uuid()
        ]);

        $invitation = Invitation::create($postData);

        //send email
        Mail::to($invitation->email)
    ->send(new InvitationMail($invitation->token));
    
        return redirect()->route('users.index')->with('success', 'Invitation sent successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invitation $invitation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invitation $invitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invitation $invitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitation $invitation)
    {
        //
        $invitation->delete();
        return response()->json([
            'message' => 'Invitation deleted'
        ]);
    }
    public function register(string $token)
    {
        //check for valid token if valid show registration form
        $invitation = Invitation::where(['token'=>$token,'is_valid'=>1])
        ->firstOrFail();

        return view('auth.invitation-register', compact('invitation'));
    }
    public function accept_invitation(Request $request, String $token)
    {
        //register user came from invitation
        //check for valid token
        $invitation = Invitation::where(['token'=>$token,'is_valid'=>1])
        ->firstOrFail();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'tenant_name' => ['required', 'string', 'max:255', 'unique:tenants,name'],

        ]);
        //new user if not exists else only mapping
         $user = User::firstOrCreate(
            ['email' => $invitation->email],
            [
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]
        );
        DB::table('tenant_user')->updateOrInsert(
            [
                'tenant_id' => $invitation->tenant_id,
                'user_id' => $user->id,
            ],
            [
                'role' => $invitation->role,
                'joined_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $invitation->update([
        'is_valid' => 0,
    ]);

        Auth::login($user);

        session([
            'tenant_id' => $invitation->tenant_id,
        ]);

        return redirect()->route('dashboard');
    }
}
