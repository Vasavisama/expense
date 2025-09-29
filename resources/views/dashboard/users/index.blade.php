@extends('dashboard.admin')

@section('content')
<div class="container">
    <h1 class="my-4">User Management</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            All Users
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form action="{{ route('users.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr id="user-row-{{ $user->id }}">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                @if ($user->role !== 'admin')
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                @else
                                    <td colspan="2"></td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function getCsrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) return meta.getAttribute('content');
        var input = document.querySelector('input[name="_token"]');
        return input ? input.value : '';
    }
    var csrf = getCsrfToken();

    document.querySelectorAll('form').forEach(function(form) {
        var methodInput = form.querySelector('input[name="_method"][value="DELETE"]');
        if (!methodInput) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to delete this user?')) return;

            var action = form.getAttribute('action');
            var row = form.closest('tr');

            fetch(action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf,
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({_method: 'DELETE', _token: csrf})
            })
            .then(res => res.json())
            .then(json => {
                if (json.success) {
                    if (row) row.remove();
                    var container = document.querySelector('.container');
                    var alert = document.createElement('div');
                    alert.className = 'alert alert-success';
                    alert.textContent = json.message || 'User deleted successfully.';
                    container.insertBefore(alert, container.firstChild);
                    setTimeout(() => alert.remove(), 2500);
                } else if (json.error) {
                    alert(json.error);
                }
            })
            .catch(err => console.error(err));
        });
    });
});
</script>
@endpush
@endsection
