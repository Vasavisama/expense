@extends('layouts.admin')

@section('title', 'Expense Management')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>All Expenses</h4>
            <div class="d-flex">
                <form action="{{ route('admin.expenses.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control" placeholder="Search expenses..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary ms-2">Search</button>
                </form>
                <button type="button" class="btn btn-secondary ms-2" data-bs-toggle="modal" data-bs-target="#exportModal">
                    Export All
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>{{ $expense->id }}</td>
                            <td>{{ $expense->user->name }}</td>
                            <td>${{ number_format($expense->amount, 2) }}</td>
                            <td>{{ $expense->category }}</td>
                            <td>{{ $expense->date }}</td>
                            <td>{{ $expense->notes }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No expenses found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $expenses->links() }}
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Export All Expenses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.expenses.exportAll') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="report_type" class="form-label">Report Type</label>
                            <select class="form-select" id="report_type" name="report_type">
                                <option value="full_list">Full List (Filtered by Dates)</option>
                                <option value="monthly_summary">Monthly Summary Report</option>
                                <option value="yearly_summary">Yearly Summary Report</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="from_date" class="form-label">From Date (Optional)</label>
                            <input type="date" class="form-control" id="from_date" name="from_date">
                        </div>
                        <div class="mb-3">
                            <label for="to_date" class="form-label">To Date (Optional)</label>
                            <input type="date" class="form-control" id="to_date" name="to_date">
                        </div>
                        <div class="mb-3">
                            <label for="format" class="form-label">Format</label>
                            <select class="form-select" id="format" name="format">
                                <option value="pdf">PDF</option>
                                <option value="csv">CSV</option>
                                <option value="xlsx">Excel</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection