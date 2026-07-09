<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;


class DashboardTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }
    public function test_loggedin_user_without_tenant_redirect_to_tenant_selection(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('tenants.select'));
    }
    public function test_loggedin_user_with_tenant_redirect_to_dashboard(): void
    {
        $user = User::factory()->create(); //create user
        $tenant = Tenant::factory()->create();//create tenant
        $user->tenants()->attach($tenant->id, [
            'role' => 'Admin',
            'joined_at' => now(),
        ]);//attach user to tenant with role
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
        $response = $this->get('/dashboard');//visit dashboard
        $response->assertStatus(200);
    }
}
