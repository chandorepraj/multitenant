@extends('layouts.app')
@section('title', 'Notes')
@section('content')

    
    <div class="container">
        
        <h4>Create Note</h4>
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
    <div  class="py-12">   
    <form action="{{ route($routePrefix.'notes.update',[$record, $note]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Note</label>
            <textarea name="note" id="note" class="form-control" required>{{ old('note', $note->note) }}</textarea>
        </div>
        <br/>
        <input type="hidden" name="lead_id" id="lead_id" value={{$note->lead_id}}>
        <input type="hidden" name="customer_id" id="customer_id" value={{$note->customer_id}}>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
    </div>
</div>
                
@endsection