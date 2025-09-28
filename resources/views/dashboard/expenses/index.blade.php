@extends('layouts.app')

@section('title', 'My Expenses')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Expenses</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($expenses->isEmpty())
        <div class="alert alert-info">
            No expenses added.
        </div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr>
                        <td>{{ $expense->amount }}</td>
                        <td>{{ $expense->category }}</td>
                        <td>{{ $expense->date }}</td>
                        <td>{{ $expense->notes }}</td>
                        <td>
                            <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection