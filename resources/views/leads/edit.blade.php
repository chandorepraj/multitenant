@extends('layouts.app')
@section('title', 'Customers')
@section('content')

    
    <div class="container">
        
        <h4>Update Customer</h4>
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
    <form action="{{ route('leads.update', $lead) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" value="{{$lead->name}}" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" value="{{$lead->email}}" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" value="{{$lead->phone}}" name="phone" id="phone" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="soure">Source</label>
            <input type="text" value="{{$lead->source}}" name="source" id="source" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="">Select Status</option>
                <option value="New" @if($lead->status == 'New') selected @endif>New</option>
                <option value="Contacted" @if($lead->status == 'Contacted') selected @endif>Contacted</option>
                <option value="Qualified" @if($lead->status == 'Qualified') selected @endif>Qualified</option>
                <option value="Won" @if($lead->status == 'Won') selected @endif>Won</option>
                <option value="Lost" @if($lead->status == 'Lost') selected @endif>Lost</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
                
@endsection