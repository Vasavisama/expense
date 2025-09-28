<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Exports\ExpensesExport;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $expenses = $user->expenses()->orderBy('date', 'desc')->get();
        return view('dashboard.expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('dashboard.expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required|string|in:Food,Rent,Travel,Shopping',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->expenses()->create($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }

    public function edit(Expense $expense)
    {
        // Simple authorization check
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        return view('dashboard.expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        // Simple authorization check
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required|string|in:Food,Rent,Travel,Shopping',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        // Simple authorization check
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string|in:full_list,monthly_summary,yearly_summary',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'format' => 'required|string|in:pdf,csv,xlsx',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = $user->expenses();

        if ($request->filled('from_date')) {
            $query->where('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('date', '<=', $request->to_date);
        }

        $reportType = $request->report_type;
        $data = [];

        $dbConnection = $query->getConnection()->getDriverName();

        switch ($reportType) {
            case 'monthly_summary':
                $yearSelect = $dbConnection === 'sqlite' ? "strftime('%Y', date)" : "YEAR(date)";
                $monthSelect = $dbConnection === 'sqlite' ? "strftime('%m', date)" : "MONTHNAME(date)";

                $data = $query->select(
                    DB::raw("$yearSelect as year"),
                    DB::raw("$monthSelect as month"),
                    DB::raw('sum(amount) as total_amount')
                )
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderByRaw($dbConnection === 'sqlite' ? "strftime('%m', date) DESC" : "MONTH(date) DESC")
                ->get();
                break;

            case 'yearly_summary':
                $yearSelect = $dbConnection === 'sqlite' ? "strftime('%Y', date)" : "YEAR(date)";
                $data = $query->select(
                    DB::raw("$yearSelect as year"),
                    DB::raw('sum(amount) as total_amount')
                )
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->get();
                break;

            case 'full_list':
            default:
                $data = $query->orderBy('date', 'desc')->get();
                break;
        }

        $filename = "expenses-{$reportType}-" . now()->format('Y-m-d') . ".{$request->format}";

        if ($request->format === 'pdf') {
            $pdf = PDF::loadView('dashboard.expenses.pdf', ['data' => $data, 'report_type' => $reportType]);
            return $pdf->download($filename);
        }

        return Excel::download(new ExpensesExport($data, $reportType), $filename);
    }
}
