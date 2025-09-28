@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    <hr>

    <div class="card mb-4">
        <div class="card-header">
            Top Spenders
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Total Spent</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topSpenders as $spender)
                        <tr>
                            <td>{{ $spender->name }}</td>
                            <td>{{ $spender->email }}</td>
                            <td>${{ number_format($spender->total_spent, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No spending data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Export Expenses
        </div>
        <div class="card-body">
            <form action="{{ route('admin.expenses.export') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="report_type" class="form-label">Report Type</label>
                        <select name="report_type" id="report_type" class="form-select">
                            <option value="full_list">Full List</option>
                            <option value="monthly_summary">Monthly Summary</option>
                            <option value="yearly_summary">Yearly Summary</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" name="from_date" id="from_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" name="to_date" id="to_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="format" class="form-label">Format</label>
                        <select name="format" id="format" class="form-select">
                            <option value="csv">CSV</option>
                            <option value="xlsx">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection