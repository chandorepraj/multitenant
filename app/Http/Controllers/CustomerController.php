<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\RedirectResponse;
use Auth;
use App\Models\ActivityLog;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $customers = Customer::with('creator')->where('tenant_id',session('tenant_id'))
        ->orderBy('id', 'desc')
        ->paginate(2);
        return view('customers.index',compact('customers'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $this->authorize('create', Customer::class);
        return view('customers.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $this->authorize('create', Customer::class);

        //
        $validatedData = $request->validated();
        $postData = array_merge($validatedData, [
            'created_by' => Auth::user()->id,
            'tenant_id' => session('tenant_id'),
        ]);

        $customer = Customer::create($postData);
        ActivityLog::record('customer_created',"Customer {$customer->name} created",$customer);

    
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
        $this->authorize('update', $customer);
        return view('customers.edit',compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        //
        $this->authorize('update', $customer);
        $request->validated();
        $customer->update($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
        $this->authorize('delete', $customer);
        $customer->delete();
        return response()->json([
            'message' => 'Customer deleted'
        ]);
    }
}
