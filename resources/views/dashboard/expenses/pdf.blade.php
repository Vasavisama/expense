<!DOCTYPE html>
<html>
<head>
    <title>Expenses Report</title>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Expenses Report</h1>
    <table>
        @if ($report_type === 'monthly_summary')
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td>{{ $row->year }}</td>
                        <td>{{ $row->month }}</td>
                        <td>{{ $row->total_amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        @elseif ($report_type === 'yearly_summary')
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td>{{ $row->year }}</td>
                        <td>{{ $row->total_amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        @else
            <thead>
                <tr>
                    @if (isset($is_admin_export) && $is_admin_export)
                        <th>User</th>
                    @endif
                    <th>Amount</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $expense)
                    <tr>
                        @if (isset($is_admin_export) && $is_admin_export)
                            <td>{{ $expense->user->name }}</td>
                        @endif
                        <td>{{ $expense->amount }}</td>
                        <td>{{ $expense->category }}</td>
                        <td>{{ $expense->date }}</td>
                        <td>{{ $expense->notes }}</td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
</body>
</html>