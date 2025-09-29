@extends('dashboard.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1>Analytics</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exportModal">
            Export
        </button>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('analytics.export') }}" method="POST" id="exportForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Export Analytics</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="report_type">Report Type</label>
                        <select class="form-control" id="report_type" name="report_type" required>
                            <option value="filtered_by_dates">Filtered by Dates</option>
                            <option value="monthly_summary">Monthly Summary Reports</option>
                            <option value="yearly_summary">Yearly Summary Reports</option>
                        </select>
                    </div>

                    <div id="date_filters">
                        <div class="form-group">
                            <label for="from_date">From Date</label>
                            <input type="text" class="form-control" id="from_date" name="from_date" placeholder="dd-mm-yyyy">
                        </div>
                        <div class="form-group">
                            <label for="to_date">To Date</label>
                            <input type="text" class="form-control" id="to_date" name="to_date" placeholder="dd-mm-yyyy">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" id="category" name="category">
                            <option value="">All</option>
                            <option value="Trip">Trip</option>
                            <option value="Travel">Travel</option>
                            <option value="Food">Food</option>
                            <option value="Shopping">Shopping</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="format">Format</label>
                        <select class="form-control" id="format" name="format" required>
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
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

<script>
    // Using jQuery for simplicity and compatibility with Bootstrap 4
    $(document).ready(function() {
        const reportType = $('#report_type');
        const dateFilters = $('#date_filters');

        function toggleDateFilters() {
            if (reportType.val() === 'filtered_by_dates') {
                dateFilters.show();
            } else {
                dateFilters.hide();
            }
        }

        // Initial check
        toggleDateFilters();

        // Add event listener
        reportType.on('change', toggleDateFilters);

        // Show modal if there are validation errors from the backend
        @if($errors->any())
            $('#exportModal').modal('show');
        @endif
    });
</script>
@endsection