<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Lead;

class LeadTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected Tenant $tenant;
    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::factory()->create();
    }
    protected function loginUserWithTenant(string $role = 'Admin'): User
    {
        $user = User::factory()->create();

        $user->tenants()->attach($this->tenant->id, [
            'role' => $role,
            'joined_at' => now(),
        ]);

        $this->actingAs($user);

        session(['tenant_id' => $this->tenant->id]);

        return $this->user = $user;
    }
    public function test_guest_cannot_create_lead(): void
    {

        $response = $this->get(route('leads.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
    public function test_user_without_tenant_cannot_create_lead(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('leads.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('tenants.select'));
    }
    public function test_sales_cannot_create_lead():void
    {
        $this->loginUserWithTenant('Sales');
        $data = Lead::factory()->make()->toArray();
        $response = $this->post(route('leads.store'),$data);
        $response->assertForbidden();
    }
    public function test_support_cannot_create_lead():void
    {
        $this->loginUserWithTenant('Support');
        $data = Lead::factory()->make()->toArray();
        $response = $this->post(route('leads.store'),$data);
        $response->assertForbidden();
    }
    public function test_admin_with_tenant_can_create_lead(): void
    {
        $this->loginUserWithTenant();
        $data = Lead::factory()->make()->toArray();
        $response = $this->post(route('leads.store'),$data);
        $response->assertRedirect('leads');
        $this->assertDatabaseHas('leads', [
            'email' => $data['email'],
            'tenant_id' => $this->tenant->id,
        ]);
    }
    public function test_manager_with_tenant_can_create_lead(): void
    {
        $this->loginUserWithTenant('Manager');
        $data = Lead::factory()->make()->toArray();
        $response = $this->post(route('leads.store'),$data);
        $response->assertRedirect('leads');
        $this->assertDatabaseHas('leads', [
            'email' => $data['email'],
            'tenant_id' => $this->tenant->id,
        ]);
    }
    public function test_lead_create_name_required(): void
    {
        $this->loginUserWithTenant('Manager');
        $data = Lead::factory()->make()->toArray();
        $data['name'] = '';
        $response = $this->post(route('leads.store'),$data);
        $response->assertSessionHasErrors('name');
    }
    public function test_guest_cannot_update_lead():void
    {
        $data = Lead::factory()->create();
        $response = $this->get(route('leads.edit',$data));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
    public function test_sales_cannot_update_lead():void
    {
        $this->loginUserWithTenant('Sales');
        $lead = Lead::factory()->create(['tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id, ]);
        $data = Lead::factory()->make()->toArray();
        $response = $this->put(route('leads.update',$lead),$data);
        $response->assertForbidden();

    }
    public function test_support_cannot_update_lead():void
    {
        $this->loginUserWithTenant('Support');
        $lead = Lead::factory()->create(['tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id, ]);
        $data = Lead::factory()->make()->toArray();
        $response = $this->put(route('leads.update',$lead),$data);
        $response->assertForbidden();

    }
     public function test_admin_can_update_lead():void
    {
        $this->loginUserWithTenant();
        $lead = Lead::factory()->create(['tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id, ]);
        $data = Lead::factory()->make()->toArray();
        $response = $this->put(route('leads.update',$lead),$data);
        $response->assertRedirect(route('leads.index'));
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'name' => $data['name'],
        ]);
    }
    public function test_manager_can_update_lead():void
    {
        $this->loginUserWithTenant('Manager');
        $lead = Lead::factory()->create(['tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id, ]);
        $data = Lead::factory()->make()->toArray();
        $response = $this->put(route('leads.update',$lead),$data);
        $response->assertRedirect(route('leads.index'));
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'name' => $data['name'],
        ]);

    }
    public function test_name_required_to_update_lead():void
    {
        $this->loginUserWithTenant('Manager');
        $lead = Lead::factory()->create(['tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id, ]);
        $data = Lead::factory()->make()->toArray();
        $data['name'] = '';
        $response = $this->put(route('leads.update',$lead),$data);
        $response->assertSessionHasErrors('name');

    }
     public function test_user_cannot_update_lead_from_another_tenant(): void
    {
        // Arrange
        $this->loginUserWithTenant('Manager');
        $tenant2 = Tenant::factory()->create();         
        $lead = Lead::factory()->create(['tenant_id' => $tenant2->id,
            'created_by' => $this->user->id, ]);
        $data = Lead::factory()->make()->toArray();      
       
        // Act
        $response = $this->put(route('leads.update', $lead),$data);

        // Assert
        $response->assertForbidden();
        $this->assertDatabaseMissing('leads', [
            'id' => $lead->id,
            'name' => $data['name'],   // or another field you attempted to change
        ]);
    }
    public function test_admin_with_tenant_can_delete_lead(): void
    {
        // Arrange
        $this->loginUserWithTenant('Admin');
        $lead = Lead::factory()->create(['tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id, ]);
        // Act
        $response = $this->delete(route('leads.destroy', $lead));
        //Assert
        $this->assertDatabaseMissing('leads', [
            'id' => $lead->id,
        ]);
    }
    public function test_manager_with_tenant_can_delete_lead(): void
    {
        // Arrange
        $this->loginUserWithTenant('Manager');
        $lead = Lead::factory()->create(['tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id, ]);
        // Act
        $response = $this->delete(route('leads.destroy', $lead));
        //Assert
        $this->assertDatabaseMissing('leads', [
            'id' => $lead->id,
        ]);
    }
    public function test_sales_with_tenant_cannot_delete_lead(): void
    {
        // Arrange
        $this->loginUserWithTenant('Sales');
        $lead = Lead::factory()->create(['tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id, ]);
        // Act
        $response = $this->delete(route('leads.destroy', $lead));
        //Assert
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
        ]);
    }
    public function test_support_with_tenant_cannot_delete_lead(): void
    {
        // Arrange
        $this->loginUserWithTenant('Support');
        $lead = Lead::factory()->create(['tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id, ]);
        // Act
        $response = $this->delete(route('leads.destroy', $lead));
        //Assert
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
        ]);
    }
    public function test_user_cannot_delete_lead_from_another_tenant(): void
    {
        // Arrange
        $this->loginUserWithTenant('Admin');

        $tenant2 = Tenant::factory()->create();         
        $lead = Lead::factory()->create(['tenant_id' => $tenant2->id,
            'created_by' => $this->user->id, ]);
       
        // Act
        $response = $this->delete(route('leads.destroy', $lead));

        // Assert
        $response->assertForbidden();
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            ]);
    }
    public function test_guest_cannot_delete_lead():void
    {
        $lead = Lead::factory()->create();
        $response = $this->delete(route('leads.destroy',$lead));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            ]);
    }
    public function test_guest_cannot_view_lead():void
    {
        $response = $this->get(route('leads.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
    public function test_loggedin_user_without_tenant_cannot_view_lead():void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('leads.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('tenants.select'));
    }
    
}
