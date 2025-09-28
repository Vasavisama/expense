@extends('dashboard.admin')

@section('content')
<div class="container">
    <h1 class="my-4">Analytics</h1>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-bar mr-1"></i>
            Top 10 Spenders
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Total Spent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topSpenders as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>${{ number_format($user->total_spent, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No spending data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
