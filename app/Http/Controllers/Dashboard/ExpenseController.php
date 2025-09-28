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

}
