<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Tenant;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class TaskController extends Controller
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
            $tasks = $customer->tasks()->paginate(2);
            $routePrefix = 'customers.';
            $id = $request->route('customer');
        }
        if($request->route('lead'))
        {
            $lead = Lead::where('tenant_id', session('tenant_id'))
            ->findOrFail($request->route('lead'));
            $tasks = $lead->tasks()->paginate(2);
            $routePrefix = 'leads.';
            $id = $request->route('lead');
        }
        return view('tasks.index',compact('tasks','routePrefix','id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $tenants = Tenant::findOrFail(session('tenant_id'));

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
        return view('tasks.create',compact('routePrefix','id','customer_id','lead_id','tenants'));
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
            'title' => 'required|string',
            'status' => 'required|string',
            'priority' => 'required|string',
            'assigned_to' => 'required',
            'due_date' => 'required|date|after_or_equal:today',
        ]);
        Task::create([
            'lead_id' => $request->lead_id,
            'customer_id' => $request->customer_id,
            'title' => $request->title,
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to,
            'description' => $request->description,
            'tenant_id' => session('tenant_id'),
            'created_by' => Auth()->user()->id
        ]);
        if($request->route('customer'))
            return redirect()->route('customers.tasks.index',$request->customer_id)->with('success', 'Task added successfully.');
        else
            return redirect()->route('leads.tasks.index',$request->lead_id)->with('success', 'Task added successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit(Request $request, string $lead, Task $task)
{
   
        $routePrefix = '';
        $from = $request->query('from');
        $tenants = Tenant::findOrFail(session('tenant_id'));
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

        return view('tasks.edit', compact(
            'task',
            'record',
            'routePrefix','tenants','from'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $lead, Task $task)
    {
        $request->validate([
             'title' => 'required|string',
            'status' => 'required|string',
            'priority' => 'required|string',
            'assigned_to' => 'required',
            'due_date' =>  ['required','date',
                Rule::when(
                    $request->status !== 'Completed',
                    ['after_or_equal:today']
                ),
            ]
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

        $task->update([
            'title' => $request->title,
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to,
            'description' => $request->description,
        ]);
        if($request->from=="my_tasks")
        {
             return redirect()
            ->route('tasks.my')
            ->with('success', 'Task updated successfully.');
        } 
        else
        {
             return redirect()
            ->route($routePrefix.'.tasks.index', $record)
            ->with('success', 'Task updated successfully.');
        }
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, String $lead, Task $task)
    {
        //
        $from = $request->query('from');
        $task->delete();
        if($from=="my_tasks")
            return redirect()->route('tasks.my')->with('success', 'Task deleted successfully.');
        else
        {
            if($request->route('customer'))
                return redirect()->route('customers.tasks.index',$task->customer_id)->with('success', 'Task deleted successfully.');
            else
                return redirect()->route('leads.tasks.index',$task->lead_id)->with('success', 'Task deleted successfully.'); 
        }        

    }
    public function my_tasks()
    {
        $tenants = Tenant::findOrFail(session('tenant_id'));
        $tasks = Task::where('tenant_id', session('tenant_id'))
                    ->where(function ($query) {
                        $query->where('assigned_to', Auth()->user()->id)
                            ->orWhere('created_by', Auth()->user()->id);
                    })
            ->latest()
            ->paginate(10);

        return view('tasks.mytasks', compact('tasks'));
    }

}
