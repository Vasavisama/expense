@extends('layouts.app')

@section('title', 'My Expenses')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Expenses</h1>
        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exportModal">
            Export
        </button>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Export Expenses</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('expenses.export') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="report_type">Report Type</label>
                            <select class="form-control" id="report_type" name="report_type">
                                <option value="full_list">Full List (Filtered by Dates)</option>
                                <option value="monthly_summary">Monthly Summary Report</option>
                                <option value="yearly_summary">Yearly Summary Report</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="from_date">From Date</label>
                            <input type="date" class="form-control" id="from_date" name="from_date">
                        </div>
                        <div class="form-group">
                            <label for="to_date">To Date</label>
                            <input type="date" class="form-control" id="to_date" name="to_date">
                        </div>
                        <div class="form-group">
                            <label for="format">Format</label>
                            <select class="form-control" id="format" name="format">
                                <option value="pdf">PDF</option>
                                <option value="csv">CSV</option>
                                <option value="xlsx">Excel</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Export</button>
                    </div>
                </form>
            </div>
        </div>
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