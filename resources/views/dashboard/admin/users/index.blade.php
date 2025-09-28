@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>All Users</h4>
            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary ms-2">Search</button>
            </form>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-{{ $user->is_admin ? 'success' : 'secondary' }}">{{ $user->role }}</span></td>
                            <td>
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-{{ $user->is_active ? 'danger' : 'success' }}">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
@endsection