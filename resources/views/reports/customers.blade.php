@extends('layouts.app')
@section('title', 'Leads')
@section('content')   
    <div class="container">
        <h1><p>Reports >> Customers</p></h1>
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
    <form action="{{ route('reports.customers') }}" method="get">
        <div class="row">
            <div class="col">
                <label class="visually-hidden" for="from_date">Start Date</label>
                <input type="date" value="{{request('from_date')}}" class="form-control" name="from_date" id="from_date" placeholder="Start Date">
            </div>
            <div class="col">
                <label class="visually-hidden" for="end_date">End Date</label>
                <input type="date" class="form-control" value="{{request('to_date')}}" name="to_date" id="to_date" placeholder="End Date">
            </div>
            
            <div class="col">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
            </form>
        <div class="col-12 ">
            <div class="row">
                    <div class="col-lg-6 col-md-12 col-6 mb-6">
                      <div class="card">
                        <div class="card-body">
                          
                          <span><strong>Summary</strong></span>
                          <div class="pt-2"><strong>Total:</strong><span class="ps-1">{{$stats['total']}}</span></div>
                          <div class="pt-1"><strong>Today:</strong><span class="ps-1">{{$stats['today']}}</span></div>
                          <div class="pt-1"><strong>This Month:</strong><span class="ps-1">{{$stats['this_month']}}</span></div>
                          <div class="pt-1"><strong>This Year:</strong><span class="ps-1">{{$stats['this_year']}}</span></div>
                          
                        </div>
                      </div>
                    </div>
            </div>
        </div>    
        @if ($customers->count())
            <div class="d-flex justify-content-end">
            <a href="{{ route('reports.customers.csv', request()->query()) }}" class="btn btn-secondary">
            Export CSV</a>
                &nbsp;<a class="btn btn-secondary" href="{{ route('reports.customers.pdf', request()->query()) }}">Export PDF</a>
            </div>
            <p><br></p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Company</th>        
                        <th>Creator</th>
                        <th>Created At</th>
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
                        <td>{{$customer->created_at->format('d-m-Y')}}</td>

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
@endsection
