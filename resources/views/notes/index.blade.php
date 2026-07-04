@extends('layouts.app')
@section('title', 'Notes')
@section('content')   
@vite('resources/js/note.js')
    <div class="container">
        <h1><p>Notes</p></h1>
        <br>
        <a href="{{ route($routePrefix.'notes.create',$id) }}" class="btn btn-primary mb-3">Create Note</a>

        @if ($notes->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Note</th>
                        <th>Creator</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notes as $note)
                    <tr>
                        <td>{{ $note->note }}</td>
                        <td>{{ $note->creator->name }}</td>
                        <td>
                            <a href="{{ route($routePrefix.'notes.edit', [$id,$note->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route($routePrefix.'notes.destroy', [$id,$note->id]) }}" method="POST" style="display:inline;" id="{{'deleteNoteForm'.$note->id}}">
                                @csrf
                                @method('DELETE')
                                <button type="button"  class="btn btn-danger btn-sm delete-confirm" onclick="return delete_confirmation({{$note->id}})" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $notes->links('pagination::bootstrap-4') }}
            </div>
        @else
            <p>No notes available.</p>
        @endif
        
       
    </div>
    
    @include('components.delete-confirmation')    

@endsection
