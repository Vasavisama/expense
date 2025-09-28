<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Auth::user()->expenses()->orderBy('date', 'desc')->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        Auth::user()->expenses()->create($request->all());

        return redirect()->route('expenses.index');
    }

    public function edit(Expense $expense)
    {
        // Ensure the user owns the expense
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        // Ensure the user owns the expense
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index');
    }

    public function destroy(Expense $expense)
    {
        // Ensure the user owns the expense
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        $expense->delete();

        return redirect()->route('expenses.index');
    }
}