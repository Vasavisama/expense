<!DOCTYPE html>
<html>
<head>
    <title>Analytics Report</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Analytics Report</h1>
    <table>
        <thead>
            <tr>
                @if($reportType === 'full_list')
                    <th>Name</th>
                    <th>Email</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Category</th>
                @elseif($reportType === 'monthly_summary')
                    <th>Year</th>
                    <th>Month</th>
                    <th>Total Amount</th>
                @elseif($reportType === 'yearly_summary')
                    <th>Year</th>
                    <th>Total Amount</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    @if($reportType === 'full_list')
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->description }}</td>
                        <td>${{ number_format($row->amount, 2) }}</td>
                        <td>{{ $row->date }}</td>
                        <td>{{ $row->category }}</td>
                    @elseif($reportType === 'monthly_summary')
                        <td>{{ $row->year }}</td>
                        <td>{{ date('F', mktime(0, 0, 0, $row->month, 10)) }}</td>
                        <td>${{ number_format($row->total_amount, 2) }}</td>
                    @elseif($reportType === 'yearly_summary')
                        <td>{{ $row->year }}</td>
                        <td>${{ number_format($row->total_amount, 2) }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>