@extends('layouts.app')
@section('title', 'Customers')
@section('content')   
@vite('resources/js/customer.js')
    <div class="container">
        <h1><p>Customers</p></h1>
        <br>
        @can('create', App\Models\Customer::class)
            <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3">Create Customer</a>

        @endcan

        @if ($customers->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Company</th>
                        <th>Creator</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->company }}</td>
                        <td>{{ $customer->creator->name }}</td>
                        <td>
                            @can('update', $customer)
                            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            @endcan
                             @can('delete', $customer)
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button"  class="btn btn-danger btn-sm delete-confirm" onclick="return delete_confirmation({{$customer->id}})" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                            </form>
                            @endcan
                            <a href="{{ route('customers.notes.index', $customer->id) }}" class="btn btn-primary btn-sm">Notes</a>
                            <a href="{{ route('customers.tasks.index', $customer->id) }}" class="btn btn-primary btn-sm">Tasks</a>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $customers->links('pagination::bootstrap-4') }}
            </div>
        @else
            <p>No customers available.</p>
        @endif
        
       
    </div>
    
    @include('components.delete-confirmation')    

@endsection
