@extends('layouts.app')
@section('title', 'Activity Logs')
@section('content')   
    <div class="container">
        <h1><p>Activity Logs</p></h1>
        <br>
        @if ($activities->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Activity Details</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $activity)
                    <tr>
                        <td>
                        {{ $activity->created_at->diffForHumans() }}
                            -
                            {{ $activity->user->name }}
                            -
                            {{ $activity->description }}
                      
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $activities->links('pagination::bootstrap-4') }}
            </div>
        @else
            <p>Activity logs not available.</p>
        @endif
        
       
    </div>
@endsection
