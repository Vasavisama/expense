@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Welcome to the Admin Dashboard</h4>
                </div>
                <div class="card-body">
                    <p>This is the central hub for managing your application. Use the sidebar to navigate to different sections.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Top 10 Spenders</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Total Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topSpenders as $index => $spender)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $spender->name }}</td>
                                    <td>{{ $spender->email }}</td>
                                    <td>${{ number_format($spender->total_spent, 2) }}</td>
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