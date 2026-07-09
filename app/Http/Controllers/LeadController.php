<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLeadRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\ActivityLog;
use App\Http\Requests\StoreCustomerRequest;
use Auth;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $leads = Lead::with('creator')->where('tenant_id',session('tenant_id'))
        ->orderBy('id', 'desc')
        ->paginate(2);
        return view('leads.index',compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $this->authorize('create', Lead::class);
        return view('leads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeadRequest $request): RedirectResponse
    {
        //
        $this->authorize('create', Lead::class);
        $validatedData = $request->validated();
        $postData = array_merge($validatedData, [
            'created_by' => Auth::user()->id,
            'tenant_id' => session('tenant_id'),
        ]);

        $lead = Lead::create($postData);
        //log activity
        ActivityLog::record('lead_created',"Lead {$lead->name} created",$lead);
    
        return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        //
        $this->authorize('update', $lead);
        return view('leads.edit',compact('lead'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLeadRequest $request, Lead $lead): RedirectResponse
    {
        //
        $this->authorize('update', $lead);
        $oldStatus = $lead->status;//to log status change activity
        $request->validated();
        $lead->update($request->all());
        if ($oldStatus !== $lead->status) 
        {

            ActivityLog::record(
                'lead_status_changed',
                "Lead '{$lead->name}' status changed from '{$oldStatus}' to '{$lead->status}'",
                $lead
            );
        }
        return redirect()->route('leads.index')->with('success', 'Lead updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        //
        $this->authorize('delete', $lead);

        $lead->delete();
        return response()->json([
            'message' => 'Lead deleted'
        ]);
    }
    public function convert(Request $request)
    {
        $lead_id = $request->id;
        $lead = Lead::where('tenant_id', session('tenant_id'))
                ->findOrFail($lead_id);

        return response()->json([
            'success' => true,
            'data' => $lead,
        ]);
    }
    public function conversion_save(StoreCustomerRequest $request): RedirectResponse
    {
        $lead_id = $request->lead_id;
        $lead = Lead::findOrFail($lead_id);
        $validatedData = $request->validated();
        unset($validatedData['lead_id']);
        unset($validatedData['lead_name']);
        $postData = array_merge($validatedData, [
            'created_by' => Auth::user()->id,
            'tenant_id' => session('tenant_id'),
        ]);

        $customer = Customer::create($postData);
        //update lead flag
        

        $lead->update([
            'is_converted' => 1,
        ]);
        ActivityLog::record('lead_converted',"Lead {$request->lead_name} converted to Customer {$request->name}",$customer);
        return redirect()->route('leads.index')->with('success', 'Lead converted successfully.');

    

    }
}
