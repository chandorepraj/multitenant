@extends('layouts.app')
@section('title', 'Organizations')
@section('content')   

    <div class="container">
        @if ($tenants->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tenants as $tenant)
                    <tr>
                        <td>{{ $tenant->name }}</td>
                        <td>{{ $tenant->slug }}</td>
                        <td></td>
                        <td>
                            <a href="{{ route('tenants.edit', $tenant->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button"  class="btn btn-danger btn-sm delete-confirm" onclick="return delete_confirmation({{$tenant->id}})" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $tenants->links('pagination::bootstrap-4') }}
            </div>
        @else
            <p>No tenants available.</p>
        @endif
    </div>
@endsection
