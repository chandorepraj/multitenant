<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        //if user belongs to more than 1 tenant select tenant before logged in
        $tenants = auth()->user()->tenants;
        if($tenants->count()== 1)
        {
            $tenant = auth()->user()
            ->tenants()
            ->first();
            if ($tenant) {
                session([
                    'tenant_id' => $tenant->id,
                ]);
            }
            
            return redirect()->intended(route('dashboard', absolute: false));
        } 
        else
        {
            return redirect()->route('tenants.select');
            
        }  

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
