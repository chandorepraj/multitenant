@extends('layouts.app')
@section('title', 'Tasks')
@section('content')

    
    <div class="container">
        
        <h4>Create Task</h4>
        <br>
        <br>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    <div  class="py-12">   
    <form action="{{ route($routePrefix.'tasks.store',$id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" required class="form-control">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" required class="form-control">
                <option value="Pending">Pending</option>
                <option value="Completed">Completed</option>
            </select>
        </div>
        <div class="form-group">
            <label for="priority">Priority</label>
            <select name="priority" id="priority" required class="form-control">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
        </div>
        <div class="form-group">
            <label for="assigned_to">Assigned To</label>
            <select name="assigned_to" id="assigned_to" required class="form-control">
                <option value="">Select Member</option>
                @foreach($tenants->users as $user)
                     <option value="{{$user->id}}">{{$user->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" id="due_date" min="{{ now()->format('Y-m-d') }}"
    value="{{ old('due_date') }}">        
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        
        <br/>
        <input type="hidden" name="lead_id" id="lead_id" value={{$lead_id}}>
        <input type="hidden" name="customer_id" id="customer_id" value={{$customer_id}}>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
    </div>
</div>
                
@endsection