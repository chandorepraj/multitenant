<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Customer;
use App\Models\Lead;

use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if($request->route('customer'))
        {
            $customer = Customer::where('tenant_id', session('tenant_id'))
            ->findOrFail($request->route('customer'));
            $routePrefix = 'customer';
            $notes = $customer->notes()->paginate(2);
            $routePrefix = 'customers.';
            $id = $request->route('customer');
        }
        if($request->route('lead'))
        {
            $lead = Lead::where('tenant_id', session('tenant_id'))
            ->findOrFail($request->route('lead'));
            $notes = $lead->notes()->paginate(2);
            $routePrefix = 'leads.';
            $id = $request->route('lead');
        }
        return view('notes.index',compact('notes','routePrefix','id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        if($request->route('customer'))
        {
            $routePrefix = 'customers.';
            $customer_id = $id = $request->route('customer');
            $lead_id = null;
        }
        if($request->route('lead'))
        {
            $routePrefix = 'leads.';
            $customer_id = null;
            $lead_id = $id = $request->route('lead');
        }
        return view('notes.create',compact('routePrefix','id','customer_id','lead_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         $request->validate([
            'lead_id' => 'nullable|exists:leads,id|required_without:customer_id',
            'customer_id' => 'nullable|exists:customers,id|required_without:lead_id',
            'note' => 'required|string',
        ]);
        Note::create([
            'lead_id' => $request->lead_id,
            'customer_id' => $request->customer_id,
            'note' => $request->note,
            'tenant_id' => session('tenant_id'),
            'created_by' => Auth()->user()->id
        ]);
        if($request->route('customer'))
            return redirect()->route('customers.notes.index',$request->customer_id)->with('success', 'Note added successfully.');
        else
            return redirect()->route('leads.notes.index',$request->lead_id)->with('success', 'Note added successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit(Request $request, string $lead, Note $note)
{
   
        $routePrefix = '';

        if ($request->route('lead')) {
            $record = Lead::where('tenant_id', session('tenant_id'))
                ->findOrFail($request->route('lead'));

            $routePrefix = 'leads.';
        }

        if ($request->route('customer')) {
            $record = Customer::where('tenant_id', session('tenant_id'))
                ->findOrFail($request->route('customer'));

            $routePrefix = 'customers.';
        }

        return view('notes.edit', compact(
            'note',
            'record',
            'routePrefix'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $lead, Note $note)
    {
        $request->validate([
            'note' => 'required|string',
        ]);

        if ($request->route('lead')) {

            $record = Lead::where('tenant_id', session('tenant_id'))
                ->findOrFail($request->route('lead'));

            $routePrefix = 'leads';

        } else {

            $record = Customer::where('tenant_id', session('tenant_id'))
                ->findOrFail($request->route('customer'));

            $routePrefix = 'customers';
        }

        $note->update([
            'note' => $request->note,
        ]);

        return redirect()
            ->route($routePrefix.'.notes.index', $record)
            ->with('success', 'Note updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, String $lead, Note $note)
    {
        //
        $note->delete();
        if($request->route('customer'))
            return redirect()->route('customers.notes.index',$note->customer_id)->with('success', 'Note deleted successfully.');
        else
            return redirect()->route('leads.notes.index',$note->lead_id)->with('success', 'Note deleted successfully.');

    }
}
