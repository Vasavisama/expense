<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Exports\ExpensesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AnalyticsController extends Controller
{
    public function index()
    {
        $topSpenders = User::select('users.name', 'users.email', DB::raw('SUM(expenses.amount) as total_spent'))
            ->join('expenses', 'users.id', '=', 'expenses.user_id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        return view('dashboard.analytics.index', compact('topSpenders'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:filtered_by_dates,monthly_summary,yearly_summary',
            'from_date' => 'nullable|date_format:d-m-Y',
            'to_date' => 'nullable|date_format:d-m-Y',
            'category' => 'nullable|in:Trip,Travel,Food,Shopping',
            'format' => 'required|in:csv,pdf,excel',
        ]);

        $query = Expense::with('user')->select('expenses.*');

        if ($request->report_type === 'filtered_by_dates') {
            if ($request->from_date) {
                $query->where('date', '>=', Carbon::createFromFormat('d-m-Y', $request->from_date)->startOfDay());
            }
            if ($request->to_date) {
                $query->where('date', '<=', Carbon::createFromFormat('d-m-Y', $request->to_date)->endOfDay());
            }
        } elseif ($request->report_type === 'monthly_summary') {
            $query->whereYear('date', now()->year)->whereMonth('date', now()->month);
        } elseif ($request->report_type === 'yearly_summary') {
            $query->whereYear('date', now()->year);
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $expenses = $query->get();

        if ($request->get('format') === 'csv') {
            $fileName = 'expenses.csv';
            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $columns = array('User', 'Email', 'Category', 'Amount', 'Date');

            $callback = function() use($expenses, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($expenses as $expense) {
                    $row['User']  = $expense->user->name;
                    $row['Email']    = $expense->user->email;
                    $row['Category']    = $expense->category;
                    $row['Amount']  = $expense->amount;
                    $row['Date']  = $expense->date;

                    fputcsv($file, array($row['User'], $row['Email'], $row['Category'], $row['Amount'], $row['Date']));
                }

                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }

        if ($request->get('format') === 'excel') {
            return Excel::download(new ExpensesExport($expenses), 'expenses.xlsx');
        }

        if ($request->get('format') === 'pdf') {
            $pdf = Pdf::loadView('pdf.expenses', compact('expenses'));
            return $pdf->download('expenses.pdf');
        }

        return redirect()->back()->with('error', 'Invalid format selected.');
    }
}
