@extends('layouts.app')

@section('title', 'My Expenses')

@section('content')
<div class="container">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">My Expenses</h2>
        <a href="{{ route('password.reset') }}" class="btn btn-secondary">Reset Password</a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- No Data -->
    @if ($expenses->isEmpty())
        <div class="alert alert-info">
            No expenses added.
        </div>
    @else
        <!-- Expenses Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>Amount</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Notes</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            <tr>
                                <td>₹{{ number_format($expense->amount, 2) }}</td>
                                <td>{{ $expense->category }}</td>
                                <td>{{ $expense->date }}</td>
                                <td>{{ $expense->notes ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning me-2">
                                        Edit
                                    </a>
                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Extra CSS -->
<style>
    .card {
        border-radius: 10px;
    }
    h2 {
        font-size: 1.6rem;
    }
    .btn {
        border-radius: 6px;
    }
</style>
@endsection
