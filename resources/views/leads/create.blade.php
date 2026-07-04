@extends('layouts.app')
@section('title', 'Leads')
@section('content')

    
    <div class="container">
        
        <h4>Create Lead</h4>
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
    <form action="{{ route('leads.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" >
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" >
        </div>
        <div class="form-group">
            <label for="source">Source</label>
            <input type="text" name="source" id="source" class="form-control" >
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="">Select Status</option>
                <option value="New">New</option>
                <option value="Contacted">Contacted</option>
                <option value="Qualified">Qualified</option>
                <option value="Won">Won</option>
                <option value="Lost">Lost</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
    </div>
</div>
                
@endsection