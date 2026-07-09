<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_loggedin_user_with_tenant_can_create_customer(): void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $user->tenants()->attach($tenant->id,['role'=>'Admin','joined_at' => now()]);
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
         // Act
        $response = $this->post(route('customers.store'), [
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',

        ]);
        // Assert
        $response->assertRedirect(route('customers.index'));

        $this->assertDatabaseHas('customers', [
            'tenant_id' => $tenant->id,
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
        ]);
    }
    public function test_guest_cannot_create_customer():void
    {
        $response = $this->get('customers/create');
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
    public function test_sales_cannot_create_customer():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $user->tenants()->attach($tenant->id,['role'=>'Sales','joined_at' => now()]);
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
         // Act
        $response = $this->post(route('customers.store'), [
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',

        ]);
        $response->assertForbidden();

        $this->assertDatabaseCount('customers', 0);
    }
    public function test_support_cannot_create_customer():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $user->tenants()->attach($tenant->id,['role'=>'Support','joined_at' => now()]);
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
         // Act
        $response = $this->post(route('customers.store'), [
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',

        ]);
        $response->assertForbidden();

        $this->assertDatabaseCount('customers', 0);
    }
    public function test_loggedin_user_without_tenant_cannot_create_customer():void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('customers/create');
        $response->assertStatus(302);
        $response->assertRedirect(route('tenants.select'));
    }
    public function test_create_customer_name_required():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $this->actingAs($user);
        $user->tenants()->attach($tenant->id,['role'=>'Admin','joined_at'=>now()]);
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
         // Act
        $response = $this->post(route('customers.store'), [
            'name' => '',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',

        ]);
         $response->assertSessionHasErrors('name');
    }
    public function test_create_customer_email_required():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $this->actingAs($user);
        $user->tenants()->attach($tenant->id,['role'=>'Admin','joined_at'=>now()]);
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
         // Act
        $response = $this->post(route('customers.store'), [
            'name' => 'ABC PVT LMT',
            'email' => '',
            'phone' => '9876543210',
            'company' => 'ABC Company',

        ]);
         $response->assertSessionHasErrors('email');
    }
    public function test_create_customer_unique_email_required():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $this->actingAs($user);
        $user->tenants()->attach($tenant->id,['role'=>'Admin','joined_at'=>now()]);
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
        Customer::create(
                    [
                'name' => 'Existing Customer',
                'email' => 'abc@test.com',
                'phone' => '9876543210',
                'company' => 'ABC Company',
                'tenant_id' => $tenant->id,
                'created_by' => $user->id,
            ]
        );
         // Act
        $response = $this->post(route('customers.store'), [
            'name' => 'ABC PVT LMT',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',

        ]);
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('customers', 1);

    }
    public function test_same_email_is_allowed_in_different_tenants(): void
    {
        // Arrange
        $user = User::factory()->create();

        $tenant1 = Tenant::factory()->create();
        $tenant2 = Tenant::factory()->create();

        $user->tenants()->attach([
            $tenant1->id => [
                'role' => 'Admin',
                'joined_at' => now(),
            ],
            $tenant2->id => [
                'role' => 'Admin',
                'joined_at' => now(),
            ],
        ]);

        $this->actingAs($user);

        // Existing customer in Tenant 1
        Customer::create([
            'name' => 'Customer One',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant1->id,
            'created_by' => $user->id,
        ]);

        // Switch to Tenant 2
        session([
            'tenant_id' => $tenant2->id,
        ]);

        // Act
        $response = $this->post(route('customers.store'), [
            'name' => 'Customer Two',
            'email' => 'abc@test.com',
            'phone' => '9999999999',
            'company' => 'XYZ Company',
        ]);

        // Assert
        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseCount('customers', 2);
    }
    //update tests
    public function test_loggedin_user_with_tenant_can_update_customer(): void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $user->tenants()->attach($tenant->id,['role'=>'Admin','joined_at' => now()]);
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant->id,
            'created_by'=>$user->id
        ]);
         // Act
        $response = $this->put(route('customers.update',$customer), [
            'name' => 'ABCD Pvt Ltd',
            'email' => 'abcd@test.com',
            'phone' => '9876543211',
            'company' => 'ABCD Company',

        ]);
        // Assert
        $response->assertRedirect(route('customers.index'));

        $this->assertDatabaseHas('customers', [
            'tenant_id' => $tenant->id,
            'name' => 'ABCD Pvt Ltd',
            'email' => 'abcd@test.com',
        ]);
    }
    public function test_guest_cannot_update_customer():void
    {
        $response = $this->get('customers/edit');
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
    public function test_sales_cannot_update_customer():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $user->tenants()->attach($tenant->id,['role'=>'Sales','joined_at' => now()]);
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant->id,
            'created_by'=>$user->id
        ]);
         // Act
        $response = $this->put(route('customers.update',$customer), [
            'name' => 'ABCD Pvt Ltd',
            'email' => 'abcd@test.com',
            'phone' => '9876543211',
            'company' => 'ABCD Company',

        ]);
        // Assert
        $response->assertForbidden();

        $this->assertDatabaseMissing('customers', [
            'tenant_id' => $tenant->id,
            'name' => 'ABCD Pvt Ltd',
            'email' => 'abcd@test.com',
        ]);
    }
    public function test_support_cannot_update_customer():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $user->tenants()->attach($tenant->id,['role'=>'Support','joined_at' => now()]);
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant->id,
            'created_by'=>$user->id
        ]);
         // Act
        $response = $this->put(route('customers.update',$customer), [
            'name' => 'ABCD Pvt Ltd',
            'email' => 'abcd@test.com',
            'phone' => '9876543211',
            'company' => 'ABCD Company',

        ]);
        // Assert
        $response->assertForbidden();

        $this->assertDatabaseMissing('customers', [
            'tenant_id' => $tenant->id,
            'name' => 'ABCD Pvt Ltd',
            'email' => 'abcd@test.com',
        ]);
    }
    public function test_loggedin_user_without_tenant_cannot_update_customer():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $this->actingAs($user);// login
        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant->id,
            'created_by'=>$user->id
        ]);
         // Act
        $response = $this->put(route('customers.update',$customer), [
            'name' => 'ABCD Pvt Ltd',
            'email' => 'abcd@test.com',
            'phone' => '9876543211',
            'company' => 'ABCD Company',

        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('tenants.select'));
    }
    public function test_update_customer_name_required():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $this->actingAs($user);
        $user->tenants()->attach($tenant->id,['role'=>'Admin','joined_at'=>now()]);
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant->id,
            'created_by'=>$user->id
        ]);
         // Act
        $response = $this->put(route('customers.update',$customer), [
            'name' => '',
            'email' => 'abcd@test.com',
            'phone' => '9876543211',
            'company' => 'ABCD Company',

        ]);
         $response->assertSessionHasErrors('name');
    }
    public function test_update_customer_email_required():void
    {
            $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $this->actingAs($user);
        $user->tenants()->attach($tenant->id,['role'=>'Admin','joined_at'=>now()]);
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant->id,
            'created_by'=>$user->id
        ]);
         // Act
        $response = $this->put(route('customers.update',$customer), [
            'name' => 'ABCD PVT MT',
            'email' => '',
            'phone' => '9876543211',
            'company' => 'ABCD Company',

        ]);
        $response->assertSessionHasErrors('email');
    }
    public function test_update_customer_unique_email_required():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $this->actingAs($user);
        $user->tenants()->attach($tenant->id,['role'=>'Admin','joined_at'=>now()]);
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
        Customer::create(
                    [
                'name' => 'Existing Customer1',
                'email' => 'existing@test.com',
                'phone' => '9876543210',
                'company' => 'Existing Company',
                'tenant_id' => $tenant->id,
                'created_by' => $user->id,
            ]
        );
        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant->id,
            'created_by'=>$user->id
        ]);
         // Act
        $response = $this->put(route('customers.update',$customer), [
            'name' => 'ABCD PVT MT',
            'email' => 'existing@test.com',
            'phone' => '9876543211',
            'company' => 'ABCD Company',

        ]);
        $response->assertSessionHasErrors('email');
    }
    public function test_customer_can_keep_same_email(): void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $user->tenants()->attach($tenant->id,['role'=>'Admin','joined_at' => now()]);
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant->id,
            'created_by'=>$user->id
        ]);
         // Act
        $response = $this->put(route('customers.update',$customer), [
            'name' => 'ABCD Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543211',
            'company' => 'ABCD Company',

        ]);
        // Assert
        $response->assertRedirect(route('customers.index'));

        $this->assertDatabaseHas('customers', [
            'tenant_id' => $tenant->id,
            'name' => 'ABCD Pvt Ltd',
            'email' => 'abc@test.com',
        ]);
    }
    public function test_user_cannot_update_customer_from_another_tenant(): void
    {
        // Arrange
        $user = User::factory()->create();

        $tenant1 = Tenant::factory()->create();
        $tenant2 = Tenant::factory()->create();

        // User belongs only to Tenant 2
        $user->tenants()->attach($tenant2->id, [
            'role' => 'Admin',
            'joined_at' => now(),
        ]);

        $this->actingAs($user);

        // User has selected Tenant 2
        session([
            'tenant_id' => $tenant2->id,
        ]);

        // Customer belongs to Tenant 1
        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant1->id,
            'created_by' => $user->id,
        ]);

        // Act
        $response = $this->put(route('customers.update', $customer), [
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
            'phone' => '9999999999',
            'company' => 'Updated Company',
        ]);

        // Assert
        $response->assertForbidden();

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
        ]);
    }
    public function test_loggedin_user_with_tenant_can_delete_customer(): void
    {
        // Arrange
        $user = User::factory()->create();

        $tenant = Tenant::factory()->create();

        $user->tenants()->attach($tenant->id, [
            'role' => 'Admin',
            'joined_at' => now(),
        ]);

        $this->actingAs($user);

        session([
            'tenant_id' => $tenant->id,
        ]);

        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant->id,
            'created_by' => $user->id,
        ]);

        // Act
        $response = $this->delete(route('customers.destroy', $customer));

        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }
    public function test_guest_cannot_delete_customer():void
    {
        $response = $this->get('customers/destroy');
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
    public function test_sales_cannot_delete_customer():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $user->tenants()->attach($tenant->id,['role'=>'Sales','joined_at' => now()]);
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant->id,
            'created_by'=>$user->id
        ]);
         // Act
        $response = $this->delete(route('customers.destroy', $customer));

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
        ]);
    }
    public function test_support_cannot_delete_customer():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $user->tenants()->attach($tenant->id,['role'=>'Support','joined_at' => now()]);
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant->id,
            'created_by'=>$user->id
        ]);
         // Act
        $response = $this->delete(route('customers.destroy', $customer));

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
        ]);
    }
    public function test_loggedin_user_without_tenant_cannot_delete_customer():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $this->actingAs($user);// login
        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant->id,
            'created_by'=>$user->id
        ]);
         // Act
        $response = $this->delete(route('customers.destroy', $customer));

        $response->assertStatus(302);
        $response->assertRedirect(route('tenants.select'));
    }
    public function test_loggedin_user_with_tenant_can_view_customer(): void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $user->tenants()->attach($tenant->id,['role'=>'Admin','joined_at' => now()]);
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
         // Act
        $response = $this->get('customers');
        $response->assertStatus(200);
    }
    public function test_guest_cannot_view_customer():void
    {
        $response = $this->get('customers');
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
    public function test_sales_can_view_customer():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $user->tenants()->attach($tenant->id,['role'=>'Sales','joined_at' => now()]);
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
         // Act
        $response = $this->get('customers');
        $response->assertStatus(200);
    }
    public function test_support_can_view_customer():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $user->tenants()->attach($tenant->id,['role'=>'Support','joined_at' => now()]);
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
         // Act
        $response = $this->get('customers');
        $response->assertStatus(200);
    }
    public function test_loggedin_user_without_tenant_cannot_view_customer():void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('customers');
        $response->assertStatus(302);
        $response->assertRedirect(route('tenants.select'));
    }
    public function test_all_users_can_view_customer():void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create();
        $this->actingAs($user);// login
        session([
            'tenant_id' => $tenant->id,
        ]);//set session
        $customer = Customer::create([
            'name' => 'ABC Pvt Ltd',
            'email' => 'abc@test.com',
            'phone' => '9876543210',
            'company' => 'ABC Company',
            'tenant_id' => $tenant->id,
            'created_by'=>$user->id
        ]);
         // Act
        $response = $this->get('customers/'.$customer->id);
        $response->assertOk();
    }

}
