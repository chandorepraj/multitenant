@extends('layouts.app')
@section('title', 'Users')
@section('content')   
@vite('resources/js/invitation.js')

    <div class="container">
        <h1><p>Team Members</p></h1>
        <br>
        @if ($users->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->pivot->role }}</td>
                        <td>
                        @can('update', $user)    
                            <button type="button"  class="btn btn-primary btn-sm " onclick="return update_role({{$user->id}})" data-bs-toggle="modal" data-bs-target="#roleModal">Update Role</button>
                        @endcan
                        @can('delete', $user)  
                            <button type="button"  class="btn btn-danger btn-sm " onclick="return delete_team_confirmation({{$user->id}})" data-bs-toggle="modal" data-bs-target="#deleteTeamModal">Remove from Team</button>
                        @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        @else
            <p>No users available.</p>
        @endif
        <h1><p>Pending invitations</p></h1>
        <br>
        @can('create',App\Models\User::class)
        <button type="button" data-bs-toggle="modal" data-bs-target="#inviteModal" class="btn btn-primary mb-3" >Invite Member</button>
       @endcan
        @if ($invitations->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invitations as $invitation)
                    <tr>
                        <td>{{ $invitation->email }}</td>
                        <td>{{ $invitation->role }}</td>

                        <td>
                            <a href="{{ route('invitations.edit', $invitation->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('invitations.destroy', $invitation->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button"  class="btn btn-danger btn-sm delete-confirm" onclick="return delete_confirmation({{$invitation->id}})" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $invitations->links('pagination::bootstrap-4') }}
            </div>
        @else
            <p>No pending invitations.</p>
        @endif
    </div>
    @include('users.invite-user')
    @include('users.role') 
    @include('users.delete-team-confirmation')         
    @include('components.delete-confirmation')         
@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = new bootstrap.Modal(
        document.getElementById('inviteModal')
    );

    modal.show();
});
</script>
@endif
@endsection
