<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Expense;
use App\Exports\ExpensesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $topSpenders = User::select('users.name', 'users.email', DB::raw('SUM(expenses.amount) as total_spent'))
            ->join('expenses', 'users.id', '=', 'expenses.user_id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        return view('dashboard.admin.index', compact('topSpenders'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->latest()->paginate(10);

        return view('dashboard.admin.users.index', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('dashboard.admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:user,admin',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $message = $user->is_active ? 'User activated successfully.' : 'User deactivated successfully.';
        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function expenses(Request $request)
    {
        $query = Expense::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('category', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $expenses = $query->latest()->paginate(10);

        return view('dashboard.admin.expenses.index', compact('expenses'));
    }

    public function exportAll(Request $request)
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
                $data = $query->with('user')->orderBy('date', 'desc')->get();
                break;
        }

        $format = $request->input('format');
        $filename = "all-expenses-{$reportType}-" . now()->format('Y-m-d') . ".{$format}";

        if ($format === 'pdf') {
            $pdf = app('dompdf.wrapper')->loadView('dashboard.expenses.pdf', ['data' => $data, 'report_type' => $reportType, 'is_admin_export' => true]);
            return $pdf->download($filename);
        }

        return app('excel')->download(new ExpensesExport($data, $reportType, true), $filename);
    }
}