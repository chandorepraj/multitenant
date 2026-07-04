@extends('layouts.app')
@section('title', 'Search')
@section('content')   
    <div class="container">
        <h1><p>Search Result</p></h1>
        <br>
        {{-- Customers --}}
        <div class="card mb-4">
            <div class="card-header">
                Customers ({{ $customers->count() }})
            </div>

            <div class="card-body p-0">

                @if($customers->count())

                    <table class="table table-bordered mb-0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($customers as $customer)

                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone }}</td>

                                <td>
                                    <a href="{{ route('customers.notes.index', $customer->id) }}" class="btn btn-primary btn-sm">Notes</a>
                                    <a href="{{ route('customers.tasks.index', $customer->id) }}" class="btn btn-primary btn-sm">Tasks</a>

                                </td>
                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                @else

                    <div class="p-3">
                        No customers found.
                    </div>

                @endif

            </div>
        </div>

        {{-- Leads --}}
        <div class="card">

            <div class="card-header">
                Leads ({{ $leads->count() }})
            </div>

            <div class="card-body p-0">

                @if($leads->count())

                    <table class="table table-bordered mb-0">

                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th >Action</th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($leads as $lead)

                            <tr>

                                <td>{{ $lead->name }}</td>
                                <td>{{ $lead->email }}</td>
                                <td>{{ $lead->phone }}</td>
                                <td>{{ $lead->status }}</td>

                                <td>
                                    <a href="{{ route('leads.notes.index', $lead->id) }}" class="btn btn-primary btn-sm">Notes</a>
                            <a href="{{ route('leads.tasks.index', $lead->id) }}" class="btn btn-primary btn-sm">Tasks</a>
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                @else

                    <div class="p-3">
                        No leads found.
                    </div>

                @endif

            </div>

        </div>
        
        
       
    </div>
@endsection
