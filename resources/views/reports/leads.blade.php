@extends('layouts.app')
@section('title', 'Leads')
@section('content')   
    <div class="container">
        <h1><p>Reports >> Leads</p></h1>
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
    <form action="{{ route('reports.leads') }}" method="get">
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
                <label class="visually-hidden" for="status">Status</label>
                <select class="form-select" id="status" name="status">
                <option value="">Choose Status</option>
                <option value="New" @if(request('status') == 'New') selected @endif>New</option>
                <option value="Contacted" @if(request('status') == 'Contacted') selected @endif>Contacted</option>
                <option value="Qualified" @if(request('status') == 'Qualified') selected @endif>Qualified</option>
                <option value="Won" @if(request('status') == 'Won') selected @endif>Won</option>
                <option value="Lost" @if(request('status') == 'Lost') selected @endif>Lost</option>
                </select>
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
                          <div class="pt-2"><strong>Total:</strong><span class="ps-1">{{$stats['Total']}}</span></div>
                          <div class="pt-1"><strong>New:</strong><span class="ps-1">{{$stats['New']}}</span></div>
                          <div class="pt-1"><strong>Qualified:</strong><span class="ps-1">{{$stats['Qualified']}}</span></div>
                          <div class="pt-1"><strong>Lost:</strong><span class="ps-1">{{$stats['Lost']}}</span></div>
                          <div class="pt-1"><strong>Contacted:</strong><span class="ps-1">{{$stats['Contacted']}}</span></div>
                          <div class="pt-1"><strong>Won:</strong><span class="ps-1">{{$stats['Won']}}</span></div>
                          <div class="pt-1"><strong>Converted:</strong><span class="ps-1">{{$stats['Converted']}}</span></div>
                        </div>
                      </div>
                    </div>
            </div>
        </div>    
        @if ($leads->count())
            <div class="d-flex justify-content-end">
            <a href="{{ route('reports.leads.csv', request()->query()) }}" class="btn btn-secondary">
            Export CSV</a>
                &nbsp;<a class="btn btn-secondary" href="{{ route('reports.leads.pdf', request()->query()) }}">Export PDF</a>
            </div>
            <p><br></p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Creator</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                    <tr>
                        <td>{{ $lead->name }}</td>
                        <td>{{ $lead->email }}</td>
                        <td>{{ $lead->phone }}</td>
                        <td>{{ $lead->source }}</td>
                        <td>{{ $lead->status }}</td>
                        <td>{{ $lead->creator->name }}</td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $leads->links('pagination::bootstrap-4') }}
            </div>
        @else
            <p>No leads available.</p>
        @endif
        
       
    </div>

@endsection
