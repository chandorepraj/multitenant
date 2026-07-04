@extends('layouts.app')
@section('title', 'Leads')
@section('content')   
@vite('resources/js/lead.js')
    <div class="container">
        <h1><p>Leads</p></h1>
        <br>
        @can('create', App\Models\Lead::class)
        <a href="{{ route('leads.create') }}" class="btn btn-primary mb-3">Create Lead</a>
        @endcan
        @if ($leads->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Creator</th>
                        <th>Actions</th>
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
                        <td>
                            @can('update', $lead)
                            <a href="{{ route('leads.edit', $lead->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            @endcan
                            @can('delete',$lead)
                            <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button"  class="btn btn-danger btn-sm delete-confirm" onclick="return delete_confirmation({{$lead->id}})" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                            </form>
                            @endcan
                            <a href="{{ route('leads.notes.index', $lead->id) }}" class="btn btn-primary btn-sm">Notes</a>
                            <a href="{{ route('leads.tasks.index', $lead->id) }}" class="btn btn-primary btn-sm">Tasks</a>
                            @if(!$lead->is_converted)
                                <a href="#" class="btn btn-success btn-sm" title="Convert To Customer" onclick="conversion_modal({{$lead->id}})"  data-bs-toggle="modal" data-bs-target="#convertModal">Convert</a>

                            
                            @else
                                <a href="#" class="btn btn-success btn-sm " onclick="alert_message()" title="Convert To Customer">Convert</a>
                            @endif

                        </td>
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
    
    @include('components.delete-confirmation')    
    @include('leads.lead_convert') 
@endsection
