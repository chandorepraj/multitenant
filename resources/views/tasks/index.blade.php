@extends('layouts.app')
@section('title', 'Tasks')
@section('content')   
@vite('resources/js/task.js')
    <div class="container">
        <h1><p>Tasks</p></h1>
        <br>
        <a href="{{ route($routePrefix.'tasks.create',$id) }}" class="btn btn-primary mb-3">Create Task</a>

        @if ($tasks->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Task</th>        
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th>Creator</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->status }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td>{{ $task->priority }}</td>
                        <td>{{ $task->owner->name }}</td>
                        <td>{{ $task->creator->name }}</td>
                        <td>
                            <a href="{{ route($routePrefix.'tasks.edit', [$id,$task->id,'from'=>'tasks']) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route($routePrefix.'tasks.destroy', [$id,$task->id,'from'=>'tasks']) }}" method="POST" style="display:inline;" id="{{'deleteTaskForm'.$task->id}}">
                                @csrf
                                @method('DELETE')
                                <button type="button"  class="btn btn-danger btn-sm delete-confirm" onclick="return delete_confirmation({{$task->id}})" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $tasks->links('pagination::bootstrap-4') }}
            </div>
        @else
            <p>No tasks available.</p>
        @endif
        
       
    </div>
    
    @include('components.delete-confirmation')    

@endsection
