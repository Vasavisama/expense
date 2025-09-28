<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ExpensesExport;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        $topSpenders = User::select('users.name', 'users.email', DB::raw('SUM(expenses.amount) as total_spent'))
            ->join('expenses', 'users.id', '=', 'expenses.user_id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('topSpenders'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:user,admin',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Toggle the active status of a user.
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $message = $user->is_active ? 'User activated successfully.' : 'User deactivated successfully.';

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string|in:full_list,monthly_summary,yearly_summary',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'format' => 'required|string|in:pdf,csv,xlsx',
        ]);

        $query = Expense::query();

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