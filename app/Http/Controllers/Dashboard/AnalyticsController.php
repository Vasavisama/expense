<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}