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
    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" value="{{$customer->name}}" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" value="{{$customer->email}}" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" value="{{$customer->phone}}" name="phone" id="phone" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="company">Company</label>
            <input type="text" value="{{$customer->company}}" name="company" id="company" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
                
@endsection